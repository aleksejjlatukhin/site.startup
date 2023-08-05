<?php

namespace app\controllers;

use app\models\ClientSettings;
use app\models\CommunicationResponse;
use app\models\CommunicationTypes;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\EnableExpertise;
use app\models\forms\CacheForm;
use app\models\forms\FormCreateConfirmGcp;
use app\models\forms\FormCreateMvp;
use app\models\forms\FormCreateQuestion;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\forms\SearchForm;
use app\models\Gcps;
use app\models\PatternHttpException;
use app\models\Problems;
use app\models\Projects;
use app\models\QuestionsConfirmGcp;
use app\models\RespondsProblem;
use app\models\RespondsGcp;
use app\models\Segments;
use app\models\StatusConfirmHypothesis;
use app\models\User;
use app\models\UserAccessToProjects;
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
    public function beforeAction($action): bool
    {
        $currentUser = User::findOne(Yii::$app->user->getId());
        $currentClientUser = $currentUser->clientUser;

        // Закомментировал потому, что блокируются методы для гипотез которые находятся в корзине, исправить это.
        if ($action->id === 'view'/*, 'mpdf-questions-and-answers', 'mpdf-data-responds'*/){

            $confirm = $this->findModel((int)Yii::$app->request->get('id'), false);

            if ($confirm->getDeletedAt()) {
                return parent::beforeAction($action);
            }

            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;
            
            if (($project->getUserId() === $currentUser->getId())){
                return parent::beforeAction($action);
            } 
            
            if (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() === $currentUser->getId()) {
                return parent::beforeAction($action);
            } 
            
            if (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() === $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                }

                if ($modelClientUser->client->settings->getAccessAdmin() === ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                }

                PatternHttpException::noAccess();
            } 
            
            if (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->getId());
                $userAccessToProject = $expert->findUserAccessToProject($project->getId());

                /** @var UserAccessToProjects $userAccessToProject */
                if ($userAccessToProject) {

                    if ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->getAnswer() === CommunicationResponse::POSITIVE_RESPONSE) {

                                return parent::beforeAction($action);
                            }

                        } elseif (time() < $userAccessToProject->getDateStop()) {

                            return parent::beforeAction($action);
                        }
                        PatternHttpException::noAccess();

                    } elseif ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {

                        return parent::beforeAction($action);

                    } else {
                        PatternHttpException::noAccess();
                    }
                } else{
                    PatternHttpException::noAccess();
                }

            } else{
                PatternHttpException::noAccess();
            }

        }elseif (in_array($action->id, ['update', 'delete'])){

            $confirm = ConfirmGcp::findOne((int)Yii::$app->request->get('id'));
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;
            
            if ($project->getUserId() === $currentUser->getId()){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }

            PatternHttpException::noAccess();

        }elseif ($action->id === 'create'){

            $hypothesis = Gcps::findOne((int)Yii::$app->request->get('id'));
            if (!$hypothesis) {
                PatternHttpException::noData();
            }
            $project = $hypothesis->project;
            
            if ($project->getUserId() === $currentUser->getId()){
                return parent::beforeAction($action);
            }

            PatternHttpException::noAccess();

        }elseif ($action->id === 'save-confirm-gcp'){

            $hypothesis = Gcps::findOne((int)Yii::$app->request->get('id'));
            $project = $hypothesis->project;
            
            if ($project->getUserId() === $currentUser->getId()){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }

            PatternHttpException::noAccess();

        }elseif ($action->id === 'add-questions'){

            $confirm = ConfirmGcp::findOne((int)Yii::$app->request->get('id'));
            if (!$confirm) {
                PatternHttpException::noData();
            }

            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;
            
            if (($project->getUserId() === $currentUser->getId())){
                return parent::beforeAction($action);
            } 
            
            if (User::isUserAdmin($currentUser->getUsername()) && $project->user->getIdAdmin() === $currentUser->getId()) {
                return parent::beforeAction($action);
            }
            
            if (User::isUserMainAdmin($currentUser->getUsername()) || User::isUserDev($currentUser->getUsername()) || User::isUserAdminCompany($currentUser->getUsername())) {

                $modelClientUser = $project->user->clientUser;

                if ($currentClientUser->getClientId() === $modelClientUser->getClientId()) {
                    return parent::beforeAction($action);
                }

                if ($modelClientUser->client->settings->getAccessAdmin() === ClientSettings::ACCESS_ADMIN_TRUE) {
                    return parent::beforeAction($action);
                }

                PatternHttpException::noAccess();
            } 
            
            if (User::isUserExpert($currentUser->getUsername())) {

                $expert = User::findOne(Yii::$app->user->getId());
                $userAccessToProject = $expert->findUserAccessToProject($project->getId());

                /** @var UserAccessToProjects $userAccessToProject */
                if ($userAccessToProject) {

                    if ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {

                        $responsiveCommunication = $userAccessToProject->communication->responsiveCommunication;

                        if ($responsiveCommunication) {

                            if ($responsiveCommunication->communicationResponse->getAnswer() === CommunicationResponse::POSITIVE_RESPONSE) {
                                return parent::beforeAction($action);
                            }

                        } elseif (time() < $userAccessToProject->getDateStop()) {
                            return parent::beforeAction($action);
                        }
                        PatternHttpException::noAccess();

                    } elseif ($userAccessToProject->getCommunicationType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
                        return parent::beforeAction($action);

                    } else {
                        PatternHttpException::noAccess();
                    }
                } else{
                    PatternHttpException::noAccess();
                }

            } else{
                PatternHttpException::noAccess();
            }

        } else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param int $id
     */
    public function actionSaveCacheCreationForm(int $id): void
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
     * @param int $id
     * @return string|Response
     */
    public function actionCreate(int $id)
    {
        $gcp = Gcps::findOne($id);
        $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());
        $problem = Problems::findOne($confirmProblem->getProblemId());
        $confirmSegment = ConfirmSegment::findOne($problem->getConfirmSegmentId());
        $segment = Segments::findOne($confirmSegment->getSegmentId());
        $project = Projects::findOne($segment->getProjectId());
        $model = new FormCreateConfirmGcp($gcp);

        //кол-во респондентов, подтвердивших текущую проблему
        $count_represent_problem = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->andWhere(['confirm_id' => $confirmProblem->getId(), 'interview_confirm_problem.status' => '1'])->count();

        $model->setCountRespond($count_represent_problem);

        if ($gcp->getEnableExpertise() === EnableExpertise::OFF) {
            return $this->redirect(['/gcps/index', 'id' => $confirmProblem->getId()]);
        }

        if ($confirm = $gcp->confirm){
            //Если у ГЦП создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $confirm->getId()]);
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
     * @param int $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function actionSaveConfirm(int $id)
    {
        if(Yii::$app->request->isAjax) {

            $gcp = Gcps::findOne($id);
            $model = new FormCreateConfirmGcp($gcp);
            $model->setHypothesisId($id);

            if ($model->load(Yii::$app->request->post())) {
                if ($model = $model->create()) {

                    $response =  ['success' => true, 'id' => $model->getId()];
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
     *
     * @param int $id
     * @return string
     */
    public function actionAddQuestions(int $id): string
    {
        $model = ConfirmGcp::findOne($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcps::findOne($model->getGcpId());
        $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());
        $problem = Problems::findOne($confirmProblem->getProblemId());
        $confirmSegment = ConfirmSegment::findOne($problem->getConfirmSegmentId());
        $segment = Segments::findOne($problem->getSegmentId());
        $project = Projects::findOne($problem->getProjectId());
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
     * @param int $id
     * @return array|false
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionUpdate (int $id)
    {
        if(Yii::$app->request->isAjax) {

            $model = new FormUpdateConfirmGcp($id);
            $confirm = ConfirmGcp::findOne($id);
            $gcp = Gcps::findOne($confirm->getGcpId());

            if ($model->load(Yii::$app->request->post())) {
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
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        $model = $this->findModel($id, false);
        if ($model->getDeletedAt()) {
            return $this->redirect(['/confirm-gcp/view-trash', 'id' => $id]);
        }
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcps::findOne($model->getGcpId());
        $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());
        $problem = Problems::findOne($confirmProblem->getProblemId());
        $confirmSegment = ConfirmSegment::findOne($problem->getConfirmSegmentId());
        $segment = Segments::findOne($confirmSegment->getSegmentId());
        $project = Projects::findOne($segment->getProjectId());
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
            'searchForm' => new SearchForm()
        ]);
    }


    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionViewTrash(int $id): string
    {
        /**
         * @var $model ConfirmGcp
         * @var $gcp Gcps
         * @var $confirmProblem ConfirmProblem
         * @var $problem Problems
         * @var $confirmSegment ConfirmSegment
         * @var $segment Segments
         * @var $project Projects
         * @var $questions QuestionsConfirmGcp[]
         */
        $model = $this->findModel($id, false);

        $gcp = Gcps::find(false)
            ->andWhere(['id' => $model->getGcpId()])
            ->one();

        $confirmProblem = ConfirmProblem::find(false)
            ->andWhere(['id' => $gcp->getConfirmProblemId()])
            ->one();

        $problem = Problems::find(false)
            ->andWhere(['id' => $confirmProblem->getProblemId()])
            ->one();

        $confirmSegment = ConfirmSegment::find(false)
            ->andWhere(['id' => $problem->getConfirmSegmentId()])
            ->one();

        $segment = Segments::find(false)
            ->andWhere(['id' => $confirmSegment->getSegmentId()])
            ->one();

        $project = Projects::find(false)
            ->andWhere(['id' => $segment->getProjectId()])
            ->one();

        $questions = QuestionsConfirmGcp::find(false)
            ->andWhere(['confirm_id' => $id])
            ->all();

        return $this->render('view_trash', [
            'model' => $model,
            'gcp' => $gcp,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'confirmSegment' => $confirmSegment,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'searchForm' => new SearchForm()
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
     *
     * @param int $id
     * @return array|bool
     */
    public function actionDataAvailabilityForNextStep(int $id)
    {
        $model = ConfirmGcp::findOne($id);
        $formCreateMvp = new FormCreateMvp($model->hypothesis);

        $count_descInterview = (int)RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->andWhere(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = (int)RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->andWhere(['confirm_id' => $id, 'interview_confirm_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (($model->mvps && $model->getCountPositive() <= $count_positive && $model->gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) || (count($model->responds) === $count_descInterview && $model->getCountPositive() <= $count_positive && $model->gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED)) {

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
            }

            $response = ['error' => true];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Завершение подтверждения ГЦП и переход на следующий этап
     *
     * @param int $id
     * @return array|bool
     */
    public function actionMovingNextStage(int $id)
    {
        $model = ConfirmGcp::findOne($id);
        $gcp = $model->gcp;

        $count_descInterview = (int)RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->andWhere(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = (int)RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->andWhere(['confirm_id' => $id, 'interview_confirm_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (!$model->mvps && (count($model->responds) > $count_descInterview)) {

                $response = ['not_completed_descInterviews' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }
            if (!$model->mvps || (count($model->responds) === $count_descInterview && $model->getCountPositive() <= $count_positive)) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $gcp->getExistConfirm(),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }

            $response = ['error' => true];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool|Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionNotExistConfirm(int $id)
    {
        $model = $this->findModel($id);
        $gcp = Gcps::findOne($model->getGcpId());
        $confirmProblem = ConfirmProblem::findOne($gcp->getConfirmProblemId());
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        if ($gcp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) {
            return $this->redirect(['/gcps/index', 'id' => $confirmProblem->getId()]);
        }

        $gcp->setExistConfirm(StatusConfirmHypothesis::NOT_COMPLETED);
        $gcp->setTimeConfirm();

        if ($model->allowExpertise($gcp)){

            $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
            $gcp->trigger(Gcps::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/gcps/index', 'id' => $confirmProblem->getId()]);
        }
        return false;
    }


    /**
     * @param int $id
     * @return bool|Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionExistConfirm(int $id)
    {
        $model = $this->findModel($id);
        $gcp = Gcps::findOne($model->getGcpId());
        $cacheManager = new CacheForm();
        $cachePath = $model->getCachePath();

        $gcp->setExistConfirm(StatusConfirmHypothesis::COMPLETED);
        $gcp->setTimeConfirm();

        if ($model->allowExpertise($gcp)){

            $cacheManager->deleteCache($cachePath); // Удаление дирректории для кэша подтверждения
            $gcp->trigger(Gcps::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/mvps/index', 'id' => $model->getId()]);
        }
        return false;
    }


    /**
     * @param int $id
     * @param bool $isOnlyNotDelete
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataQuestionsAndAnswers(int $id, bool $isOnlyNotDelete = true): array
    {
        $model = $this->findModel($id, $isOnlyNotDelete);

        /** @var $questions QuestionsConfirmGcp[] */
        $questions = $isOnlyNotDelete ?
            $model->questions :
            QuestionsConfirmGcp::find(false)
                ->andWhere(['confirm_id' => $model->getId()])
                ->all();

        $response = ['ajax_questions_and_answers' => $this->renderAjax('ajax_questions_and_answers', [
            'questions' => $questions, 'isOnlyNotDelete' => $isOnlyNotDelete
        ])];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $response;
        return $response;

    }


    /**
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfQuestionsAndAnswers(int $id)
    {
        $model = $this->findModel($id, false);
        /** @var $questions QuestionsConfirmGcp[] */
        $questions = QuestionsConfirmGcp::find(false)
            ->andWhere(['confirm_id' => $model->getId()])
            ->all();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        /** @var $gcp Gcps */
        $gcp = Gcps::find(false)
            ->andWhere(['id' => $model->getGcpId()])
            ->one();

        $gcp_desc = $gcp->getDescription();
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
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InvalidConfigException
     */
    public function actionMpdfDataResponds(int $id)
    {
        $model = $this->findModel($id, false);

        /** @var $responds RespondsGcp[] */
        $responds = RespondsGcp::find(false)
            ->andWhere(['confirm_id' => $model->getId()])
            ->all();

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        /** @var $gcp Gcps */
        $gcp = Gcps::find(false)
            ->andWhere(['id' => $model->getGcpId()])
            ->one();

        $gcp_desc = $gcp->getDescription();
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
     * @param int $id
     * @param bool $isOnlyNotDelete
     * @return ConfirmGcp|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id, bool $isOnlyNotDelete = true): ?ConfirmGcp
    {
        if (!$isOnlyNotDelete) {
            $model = ConfirmGcp::find(false)
                ->andWhere(['id' => $id])
                ->one();

        } else {
            $model = ConfirmGcp::findOne($id);
        }

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
