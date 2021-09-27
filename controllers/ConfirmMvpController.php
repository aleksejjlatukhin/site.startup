<?php

namespace app\controllers;

use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateBusinessModel;
use app\models\forms\FormCreateConfirmMvp;
use app\models\forms\FormCreateQuestion;
use app\models\forms\FormUpdateConfirmMvp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\Projects;
use app\models\QuestionsConfirmMvp;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segments;
use app\models\User;
use kartik\mpdf\Pdf;
use Mpdf\MpdfException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use Throwable;
use Yii;
use app\models\ConfirmMvp;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class ConfirmMvpController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['mpdf-questions-and-answers']) || in_array($action->id, ['mpdf-data-responds'])){

            $confirm = ConfirmMvp::findOne(Yii::$app->request->get());
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            } elseif (User::isUserExpert(Yii::$app->user->identity['username'])) {

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

            $confirm = ConfirmMvp::findOne(Yii::$app->request->get());
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $hypothesis = Mvps::findOne(Yii::$app->request->get());
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-mvp'])){

            $hypothesis = Mvps::findOne(Yii::$app->request->get());
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $confirm = ConfirmMvp::findOne(Yii::$app->request->get());
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            } elseif (User::isUserExpert(Yii::$app->user->identity['username'])) {

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
        $mvp = Mvps::findOne($id);
        $cachePath = FormCreateConfirmMvp::getCachePath($mvp);
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
        $mvp = Mvps::findOne($id);
        $confirmGcp = ConfirmGcp::findOne($mvp->confirmGcpId);
        $gcp = Gcps::findOne($confirmGcp->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $model = new FormCreateConfirmMvp($mvp);

        //кол-во респондентов, подтвердивших текущую проблему
        $count_represent_gcp = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $confirmGcp->id, 'interview_confirm_gcp.status' => '1'])->count();

        $model->count_respond = $count_represent_gcp;

        if ($mvp->confirm){
            //Если у MVP создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $mvp->confirm->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
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
        $mvp = Mvps::findOne($id);
        $model = new FormCreateConfirmMvp($mvp);
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
        $model = ConfirmMvp::findOne($id);
        $formUpdateConfirmMvp = new FormUpdateConfirmMvp($id);
        $mvp = Mvps::findOne($model->mvpId);
        $confirmGcp = ConfirmGcp::findOne($mvp->confirmGcpId);
        $gcp = Gcps::findOne($confirmGcp->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($problem->segmentId);
        $project = Projects::findOne($problem->projectId);
        $questions = QuestionsConfirmMvp::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'formUpdateConfirmMvp' => $formUpdateConfirmMvp,
            'model' => $model,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
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
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionUpdate($id)
    {
        $model = new FormUpdateConfirmMvp($id);
        $mvp = Mvps::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', [
                            'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($id),
                            'model' => $model, 'mvp' => $mvp
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
        $formUpdateConfirmMvp = new FormUpdateConfirmMvp($id);
        $mvp = Mvps::findOne($model->mvpId);
        $confirmGcp = ConfirmGcp::findOne($mvp->confirmGcpId);
        $gcp = Gcps::findOne($confirmGcp->gcpId);
        $confirmProblem = ConfirmProblem::findOne($gcp->confirmProblemId);
        $problem = Problems::findOne($confirmProblem->problemId);
        $confirmSegment = ConfirmSegment::findOne($problem->confirmSegmentId);
        $segment = Segments::findOne($confirmSegment->segmentId);
        $project = Projects::findOne($segment->projectId);
        $questions = QuestionsConfirmMvp::findAll(['confirm_id' => $id]);
        $newQuestion = new FormCreateQuestion();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmMvp' => $formUpdateConfirmMvp,
            'mvp' => $mvp,
            'confirmGcp' => $confirmGcp,
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
     * Проверка данных подтверждения на этапе генерации бизнес-модели
     * @param $id
     * @return array|bool
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmMvp::findOne($id);
        $formCreateBusinessModel = new FormCreateBusinessModel($model->hypothesis);

        $count_descInterview = RespondsMvp::find()->with('interview')
            ->leftJoin('interview_confirm_mvp', '`interview_confirm_mvp`.`respond_id` = `responds_mvp`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_mvp.id' => null]])->count();

        $count_positive = RespondsMvp::find()->with('interview')
            ->leftJoin('interview_confirm_mvp', '`interview_confirm_mvp`.`respond_id` = `responds_mvp`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_mvp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->mvp->exist_confirm == 1) || (!empty($model->business)  && $model->count_positive <= $count_positive && $model->mvp->exist_confirm == 1)) {

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/business-model/create', [
                        'confirmMvp' => $model,
                        'model' => $formCreateBusinessModel,
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
     * Завершение подтверждения MVP и переход на следующий этап
     * @param $id
     * @return array|bool
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = $model->mvp;

        $count_descInterview = RespondsMvp::find()->with('interview')
            ->leftJoin('interview_confirm_mvp', '`interview_confirm_mvp`.`respond_id` = `responds_mvp`.`id`')
            ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_mvp.id' => null]])->count();

        $count_positive = RespondsMvp::find()->with('interview')
            ->leftJoin('interview_confirm_mvp', '`interview_confirm_mvp`.`respond_id` = `responds_mvp`.`id`')
            ->where(['confirm_id' => $id, 'interview_confirm_mvp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->business)) {

                $response = ['not_completed_descInterviews' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->business))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $mvp->exist_confirm,
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
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvps::findOne($model->mvpId);
        $confirmGcp = ConfirmGcp::findOne($mvp->confirmGcpId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        if ($mvp->exist_confirm === 0) {

            return $this->redirect(['mvps/index', 'id' => $confirmGcp->id]);
        }else {

            $mvp->exist_confirm = 0;
            $mvp->time_confirm = time();

            if ($mvp->save()){

                $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
                $mvp->trigger(Mvps::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['mvps/index', 'id' => $confirmGcp->id]);
            }
        }
        return false;
    }


    /**
     * @param $id
     * @return bool|Response
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvps::findOne($model->mvpId);
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        $mvp->exist_confirm = 1;
        $mvp->time_confirm = time();

        if ($mvp->save()){

            $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
            $mvp->trigger(Mvps::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['business-model/index', 'id' => $model->id]);
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
        $content = $this->renderPartial('/confirm-mvp/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $mvp_desc = $model->mvp->description;
        if (mb_strlen($mvp_desc) > 25) {
            $mvp_desc = mb_substr($mvp_desc, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения MVP: «'.$mvp_desc.'».';

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
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. MVP: «'.$mvp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
        $content = $this->renderPartial('/confirm-mvp/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $mvp_desc = $model->mvp->description;
        if (mb_strlen($mvp_desc) > 25) {
            $mvp_desc = mb_substr($mvp_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение MVP: «'.$mvp_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения MVP: «'.$mvp_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы MVP: «'.$mvp_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Finds the ConfirmMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
