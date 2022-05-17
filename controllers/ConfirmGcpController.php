<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateConfirmGcp;
use app\models\forms\FormCreateMvp;
use app\models\forms\FormCreateQuestion;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Projects;
use app\models\QuestionsConfirmGcp;
use app\models\RespondsProblem;
use app\models\RespondsGcp;
use app\models\Segments;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use app\models\ConfirmGcp;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер с методами для создания, редактирования
 * и получения информации по этапу подтверждения ценностного предложения
 *
 * Class ConfirmGcpController
 * @package app\controllers
 */
class ConfirmGcpController extends AppUserPartController
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

            $confirm = ConfirmGcp::findOne(Yii::$app->request->get('id'));
            /**
             * @var Gcps $hypothesis
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

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $confirm = ConfirmGcp::findOne(Yii::$app->request->get('id'));
            /**
             * @var Gcps $hypothesis
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

            $hypothesis = Gcps::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-gcp'])){

            $hypothesis = Gcps::findOne(Yii::$app->request->get('id'));
            /** @var Projects $project */
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/

            if ($project->getUserId() == $currentUser->getId()){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $confirm = ConfirmGcp::findOne(Yii::$app->request->get('id'));
            /**
             * @var Gcps $hypothesis
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

        } else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $id
     */
    public function actionSaveCacheCreationForm($id)
    {
        $gcp = Gcps::findOne($id);
        $cachePath = FormCreateConfirmGcp::getCachePath($gcp);
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
        $gcp = Gcps::findOne($id);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $model = new FormCreateConfirmGcp($gcp);

        //кол-во респондентов, подтвердивших текущую проблему
        $count_represent_problem = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $confirmProblem->id, 'interview_confirm_problem.status' => '1'])->count();

        $model->setCountRespond($count_represent_problem);


        if ($gcp->confirm){
            //Если у ГЦП создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $gcp->confirm->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
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
        $gcp = Gcps::findOne($id);
        $model = new FormCreateConfirmGcp($gcp);
        $model->setHypothesisId($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()) {

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
        $model = ConfirmGcp::findOne($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcps::findOne($model->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $questions = QuestionsConfirmGcp::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'questions' => $questions,
            'formUpdateConfirmGcp' => $formUpdateConfirmGcp,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'model' => $model,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
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
        $model = new FormUpdateConfirmGcp($id);
        $gcp = Gcps::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', [
                            'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($id),
                            'model' => $model,  'gcp' => $gcp
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
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcps::findOne($model->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $questions = QuestionsConfirmGcp::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmGcp' => $formUpdateConfirmGcp,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
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
     * Проверка данных подтверждения на этапе разработки MVP
     * @param $id
     * @return array|bool
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmGcp::findOne($id);
        $formCreateMvp = new FormCreateMvp($model->hypothesis);

        $count_descInterview = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->gcp->exist_confirm == 1) || (!empty($model->mvps)  && $model->count_positive <= $count_positive && $model->gcp->exist_confirm == 1)) {

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/mvps/create', [
                        'confirmGcp' => $model,
                        'model' => $formCreateMvp,
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
     * Завершение подтверждения ГЦП и переход на следующий этап
     * @param $id
     * @return array|bool
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmGcp::findOne($id);
        $gcp = $model->gcp;

        $count_descInterview = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->mvps)) {

                $response = ['not_completed_descInterviews' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->mvps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $gcp->exist_confirm,
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
        $gcp = Gcps::findOne($model->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        if ($gcp->exist_confirm === 0) { // ToDo:Создать класс StatusConfirm для обозначения статусов
            return $this->redirect(['/gcps/index', 'id' => $confirmProblem->id]);

        } else {

            $gcp->exist_confirm = 0;
            $gcp->time_confirm = time();
            $model->setEnableExpertise();

            if ($gcp->update() && $model->update()){

                $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
                $gcp->trigger(Gcps::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/gcps/index', 'id' => $confirmProblem->id]);
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
        $gcp = Gcps::findOne($model->gcpId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        $gcp->exist_confirm = 1;
        $gcp->time_confirm = time();
        $model->setEnableExpertise();

        if ($gcp->update() && $model->update()){

            $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
            $gcp->trigger(Gcps::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/mvps/index', 'id' => $model->id]);
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
        $content = $this->renderPartial('/confirm-gcp/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $gcp_desc = $model->gcp->description;
        if (mb_strlen($gcp_desc) > 25) {
            $gcp_desc = mb_substr($gcp_desc, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения ЦП: «'.$gcp_desc.'».';

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
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. ЦП: «'.$gcp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfDataResponds($id)
    {
        $model = $this->findModel($id);
        $responds = $model->responds;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('/confirm-gcp/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $gcp_desc = $model->gcp->description;
        if (mb_strlen($gcp_desc) > 25) {
            $gcp_desc = mb_substr($gcp_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение ЦП: «'.$gcp_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения гипотезы ЦП: «'.$gcp_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы ЦП: «'.$gcp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Finds the ConfirmGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
