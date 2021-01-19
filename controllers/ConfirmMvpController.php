<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmProblem;
use app\models\forms\FormCreateBusinessModel;
use app\models\forms\FormCreateConfirmMvp;
use app\models\forms\FormUpdateConfirmMvp;
use app\models\forms\FormUpdateQuestionConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\QuestionsConfirmMvp;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmMvp;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ConfirmMvpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::findOne(['id' => $model->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::findOne(['id' => $model->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $mvp = Mvp::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-mvp'])){

            $mvp = Mvp::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmMvp::findOne(Yii::$app->request->get());
            $mvp = Mvp::findOne(['id' => $model->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete-question'])){

            $question = QuestionsConfirmMvp::findOne(Yii::$app->request->get());
            $confirm_mvp = ConfirmMvp::findOne(['id' => $question->confirm_mvp_id]);
            $mvp = Mvp::findOne(['id' => $confirm_mvp->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

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
        $mvp = Mvp::findOne(['id' => $model->mvp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $mvp->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $questions = QuestionsConfirmMvp::findAll(['confirm_mvp_id' => $id]);
        $newQuestion = new QuestionsConfirmMvp();

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
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
        ]);
    }

    /**
     * Проверка данных подтверждения на этапе генерации бизнес-модели
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmMvp::findOne($id);

        $count_descInterview = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

        $count_positive = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $id, 'desc_interview_mvp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->mvp->exist_confirm == 1) || (!empty($model->business)  && $model->count_positive <= $count_positive && $model->mvp->exist_confirm == 1)) {

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/business-model/create', [
                        'confirmMvp' => $model,
                        'model' => new FormCreateBusinessModel(),
                    ]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * Завершение подтверждения MVP и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmMvp::findOne($id);
        $mvp = $model->mvp;

        $count_descInterview = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

        $count_positive = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $id, 'desc_interview_mvp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->business)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->business))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $mvp->exist_confirm,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvp::findOne(['id' => $model->mvp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $mvp->confirm_gcp_id]);

        if ($mvp->exist_confirm === 0) {

            return $this->redirect(['mvp/index', 'id' => $confirmGcp->id]);
        }else {

            $mvp->exist_confirm = 0;
            $mvp->time_confirm = time();

            if ($mvp->save()){
                $mvp->trigger(Mvp::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['mvp/index', 'id' => $confirmGcp->id]);
            }
        }
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $mvp = Mvp::findOne(['id' => $model->mvp_id]);

        $mvp->exist_confirm = 1;
        $mvp->time_confirm = time();

        if ($mvp->save()){
            $mvp->trigger(Mvp::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['business-model/index', 'id' => $model->id]);
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new FormCreateConfirmMvp();
        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $mvp->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        //кол-во респондентов, подтвердивших текущую проблему
        $count_represent_gcp = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $confirmGcp->id, 'desc_interview_gcp.status' => '1'])->count();

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
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    /**
     * @param $id
     * @return array|\yii\web\Response
     */
    public function actionSaveConfirmMvp($id)
    {
        $model = new FormCreateConfirmMvp();
        $model->mvp_id = $id;
        $mvp = Mvp::findOne($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $mvp->confirm_gcp_id]);
        $responds = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $confirmGcp->id, 'desc_interview_gcp.status' => '1'])->all();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()) {

                    //Создание респондентов для программы подтверждения MVP из респондентов подтвердивших ЦП
                    $model->createRespondConfirm($responds);

                    //Вопросы, которые будут добавлены по-умолчанию
                    $model->addQuestionDefault('Что нравится в представленном MVP?');
                    $model->addQuestionDefault('Что не нравится в представленном MVP?');
                    $model->addQuestionDefault('Чем отличается ожидаемое решение от представленного?');
                    $model->addQuestionDefault('Что бы Вы хотели сделать по другому?');
                    $model->addQuestionDefault('Что показалось неудобным?');
                    $model->addQuestionDefault('Вы готовы заплатить за такой продукт?');


                    $response =  [
                        'success' => true,
                        'id' => $model->id,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = new FormUpdateConfirmMvp($id);
        $mvp = Mvp::findOne(['id' => $model->mvp_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', ['model' => $model, 'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($id), 'mvp' => $mvp]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
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
        $mvp = Mvp::findOne(['id' => $model->mvp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $mvp->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmMvp::findAll(['confirm_mvp_id' => $id]);
        $newQuestion = new QuestionsConfirmMvp();

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
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
        ]);
    }

    /**
     * Метод для добавления новых вопросов
     * @param $id
     * @return array
     */
    public function actionAddQuestion($id)
    {
        $model = new QuestionsConfirmMvp();
        $model->confirm_mvp_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmMvpNew = ConfirmMvp::findOne($id);
                    $questions = $confirmMvpNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmMvpNew->addAnswerConfirmMvp($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmMvpNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmMvpNew->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetQueryQuestions ($id)
    {
        $confirmMvp = $this->findModel($id);
        $questions = $confirmMvp->questions;

        if(Yii::$app->request->isAjax) {
            $response = ['ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetQuestionUpdateForm ($id)
    {
        $model = new FormUpdateQuestionConfirmMvp($id);
        $confirmMvp = $this->findModel($model->confirm_mvp_id);
        $questions = $confirmMvp->questions;

        if(Yii::$app->request->isAjax) {

            $response = [
                'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                'renderAjax' => $this->renderAjax('ajax_form_update_question', ['model' => $model]),
            ];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateQuestion ($id)
    {
        $model = new FormUpdateQuestionConfirmMvp($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model = $model->update()) {

                    $confirmMvp = $this->findModel($model->confirm_mvp_id);
                    $questions = $confirmMvp->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $confirmMvp->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmMvp->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $model = QuestionsConfirmMvp::findOne($id);
        $confirmMvp = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $confirmMvpNew = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);
                $questions = $confirmMvp->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmMvpNew->deleteAnswerConfirmMvp($id);
                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmMvpNew->queryQuestionsGeneralList();

                $response = [
                    'model' => $model,
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                    'ajax_questions_confirm' => $this->renderAjax('ajax_questions_confirm', ['questions' => $questions]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
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
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        \Yii::$app->response->data = $response;
        return $response;

    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
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
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
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
