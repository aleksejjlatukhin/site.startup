<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateConfirmSegment;
use app\models\forms\FormCreateProblem;
use app\models\forms\FormCreateQuestion;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\Projects;
use app\models\QuestionsConfirmSegment;
use app\models\RespondsSegment;
use app\models\Segments;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Контроллер с методами для создания,
 * редактирования и получения информации по этапу подтверждения сегмента
 *
 * Class ConfirmSegmentController
 * @package app\controllers
 */
class ConfirmSegmentController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        /** @var ClientUser $currentClientUser */
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['view']) || in_array($action->id, ['mpdf-questions-and-answers']) || in_array($action->id, ['mpdf-data-responds'])){

            $confirm = ConfirmSegment::findOne(Yii::$app->request->get('id'));
            /**
             * @var Segments $hypothesis
             * @var Projects $project
            */
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if (($project->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } elseif ($modelClientUser->findClient()->findSettings()->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->id);

                $userAccessToProject = $expert->findUserAccessToProject($project->id);

                if ($userAccessToProject) {

                    if ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }

                        } else {

                            if (time() < $userAccessToProject->date_stop) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }
                        }

                    } elseif ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                        return parent::beforeAction($action);

                    } else {
                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                } else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $confirm = ConfirmSegment::findOne(Yii::$app->request->get('id'));
            /**
             * @var Segments $hypothesis
             * @var Projects $project
             */
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $hypothesis = Segments::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm'])){

            $hypothesis = Segments::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()) {

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['add-questions'])){

            $confirm = ConfirmSegment::findOne(Yii::$app->request->get('id'));
            /**
             * @var Segments $hypothesis
             * @var Projects $project
             */
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if (($project->getUserId() == $currentUser->getId())){

                return parent::beforeAction($action);

            } elseif (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() == $currentUser->getId()) {

                return parent::beforeAction($action);

            } elseif (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                /** @var ClientUser $modelClientUser */
                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() == $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                } elseif ($modelClientUser->findClient()->findSettings()->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } elseif (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->id);

                $userAccessToProject = $expert->findUserAccessToProject($project->id);

                if ($userAccessToProject) {

                    if ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->answer == CommunicationResponse::POSITIVE_RESPONSE) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }

                        } else {

                            if (time() < $userAccessToProject->date_stop) {

                                return parent::beforeAction($action);

                            } else {
                                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                            }
                        }

                    } elseif ($userAccessToProject->communication_type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                        return parent::beforeAction($action);

                    } else {
                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                } else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     */
    public function actionSaveCacheCreationForm($id)
    {
        $segment = Segments::findOne($id);
        $cachePath = FormCreateConfirmSegment::getCachePath($segment);
        $cacheName = 'formCreateConfirmCache';

        if(Yii::$app->request->isAjax) {

            $cache = new CacheForm();
            $cache->setCache($cachePath, $cacheName);
        }
    }


    /**
     * @param $id
     * @return string|Response
     */
    public function actionCreate($id)
    {
        $segment = Segments::findOne($id);
        $project = Projects::findOne($segment->projectId);
        $model = new FormCreateConfirmSegment($segment);

        if ($segment->confirm){ //Если у сегмента создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $segment->confirm->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionSaveConfirm($id)
    {
        $segment = Segments::findOne($id);
        $model = new FormCreateConfirmSegment($segment);
        $model->setHypothesisId($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()){

                    $response =  ['success' => true, 'id' => $model->id];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }

    /**
     * Страница со списком вопросов
     * @param $id
     * @return string
     */
    public function actionAddQuestions($id)
    {
        $model = ConfirmSegment::findOne($id);
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segments::findOne($model->segmentId);
        $project = Projects::findOne($segment->projectId);
        $questions = QuestionsConfirmSegment::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'model' => $model,
            'formUpdateConfirmSegment' => $formUpdateConfirmSegment,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmSegment($id);
        $confirm = ConfirmSegment::findOne($id);
        $segment = Segments::findOne($confirm->segmentId);
        $project = Projects::findOne($segment->projectId);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($confirm = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', [
                            'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($id),
                            'model' => $confirm, 'project' => $project
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = ConfirmSegment::findOne($id);
        $formUpdateConfirmSegment = new FormUpdateConfirmSegment($id);
        $segment = Segments::findOne($model->segmentId);
        $project = Projects::findOne($segment->projectId);
        $questions = QuestionsConfirmSegment::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');


        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmSegment' => $formUpdateConfirmSegment,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
        ]);
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepOne ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_one');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepTwo ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_two');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @return bool|string
     */
    public function actionGetInstructionStepThree ()
    {
        if(Yii::$app->request->isAjax) {
            $response = $this->renderAjax('instruction_step_three');
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Проверка данных подтверждения на этапе генерации ГПС
     * @param $id
     * @return array|bool
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmSegment::findOne($id);
        $formCreateProblem = new FormCreateProblem($model->hypothesis);

        $count_descInterview = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_segment.id' => null]])->count();

        $count_positive = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_segment.status' => '1'])->count();

        if (Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->hypothesis->exist_confirm == 1) || (!empty($model->problems)  && $model->count_positive <= $count_positive && $model->hypothesis->exist_confirm == 1)) {

                $response =  [
                    'success' => true,
                    'cacheExpectedResultsInterview' => $formCreateProblem->_cacheManager->getCache($formCreateProblem->cachePath, 'formCreateHypothesisCache')['FormCreateProblem']['_expectedResultsInterview'],
                    'renderAjax' => $this->renderAjax('/problems/create', [
                        'confirmSegment' => $model,
                        'model' => $formCreateProblem,
                        'responds' => RespondsSegment::find()->with('interview')
                            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
                            ->where(['confirm_id' => $id, 'interview_confirm_segment.status' => '1'])->all(),
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }

    /**
     * Завершение подтверждения сегмента и переход на следующий этап
     * @param $id
     * @return array|bool
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmSegment::findOne($id);
        $segment = $model->segment;

        $count_descInterview = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_segment.id' => null]])->count();

        $count_positive = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_segment.status' => '1'])->count();


        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->problems)) {

                $response = ['not_completed_descInterviews' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } elseif ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->problems))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $segment->exist_confirm,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return bool|Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $segment = Segments::findOne($model->segmentId);
        $project = Projects::findOne($segment->projectId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        if ($segment->exist_confirm === 0) { // TODO:Создать класс StatusConfirm для обозначения статусов
            return $this->redirect(['/segments/index', 'id' => $project->id]);

        }else {

            $segment->exist_confirm = 0;
            $segment->time_confirm = time();
            $model->setEnableExpertise();

            if ($segment->update() && $model->update()){

                $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
                $segment->trigger(Segments::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/segments/index', 'id' => $project->id]);
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $segment = Segments::findOne($model->segmentId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        $segment->exist_confirm = 1;
        $segment->time_confirm = time();
        $model->setEnableExpertise();

        if ($segment->update() && $model->update()){

            $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
            $segment->trigger(Segments::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/problems/index', 'id' => $id]);
        }
        return false;
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        $response = ['ajax_questions_and_answers' => $this->renderAjax('ajax_questions_and_answers', ['questions' => $questions])];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $response;
        return $response;

    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfQuestionsAndAnswers($id)
    {
        $model = $this->findModel($id);
        $questions = $model->questions;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-segment/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $segment_name = $model->segment->name;
        if (mb_strlen($segment_name) > 25) {
            $segment_name = mb_substr($segment_name, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения сегмента: «'.$segment_name.'».';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            //'orientation' => Pdf::ORIENT_LANDSCAPE,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            //'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginTop' => 20,
            'marginBottom' => 20,
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => $filename,
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. Сегмент: «'.$segment_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @return mixed
     * @throws CrossReferenceException
     * @throws InvalidConfigException
     * @throws MpdfException
     * @throws NotFoundHttpException
     * @throws PdfParserException
     * @throws PdfTypeException
     */
    public function actionMpdfDataResponds($id)
    {
        $model = $this->findModel($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-segment/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $segment_name = $model->segment->name;
        if (mb_strlen($segment_name) > 25) {
            $segment_name = mb_substr($segment_name, 0, 25) . '...';
        }

        $filename = 'Подтверждение сегмента «'.$segment_name.'». Таблица респондентов.pdf';

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            //'format' => Pdf::FORMAT_TABLOID,
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            //'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            'filename' => $filename,
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssFile' => '@app/web/css/style.css',
            // any css to be embedded if required
            //'cssInline' => '.business-model-view-export {color: #3c3c3c;};',
            'marginFooter' => 5,
            'defaultFont' => 'RobotoCondensed-Light',
            // call mPDF methods on the fly
            'methods' => [
                'SetTitle' => ['Респонденты для подтверждения гипотезы сегмента «'.$model->segment->name.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы сегмента «'.$segment_name.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                //'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                //'SetAuthor' => 'Kartik Visweswaran',
                //'SetCreator' => 'Kartik Visweswaran',
                //'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    /**
     * @param $id
     * @return ConfirmSegment|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = ConfirmSegment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
