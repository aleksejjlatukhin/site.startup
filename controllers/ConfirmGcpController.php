<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\forms\FormCreateConfirmGcp;
use app\models\forms\FormCreateMvp;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\forms\FormUpdateQuestionConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmGcp;
use app\models\RespondsConfirm;
use app\models\RespondsGcp;
use app\models\Segment;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmGcp;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;


class ConfirmGcpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::findOne(['id' => $model->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::findOne(['id' => $model->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $gcp = Gcp::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-gcp'])){

            $gcp = Gcp::findOne(Yii::$app->request->get());
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmGcp::findOne(Yii::$app->request->get());
            $gcp = Gcp::findOne(['id' => $model->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

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

            $question = QuestionsConfirmGcp::findOne(Yii::$app->request->get());
            $confirm_gcp = ConfirmGcp::findOne(['id' => $question->confirm_gcp_id]);
            $gcp = Gcp::findOne(['id' => $confirm_gcp->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

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
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $questions = QuestionsConfirmGcp::findAll(['confirm_gcp_id' => $id]);
        $newQuestion = new QuestionsConfirmGcp();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmGcp' => $formUpdateConfirmGcp,
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
     * Проверка данных подтверждения на этапе разработки MVP
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);
        $problem = GenerationProblem::find()->where(['id' => $gcp->problem_id])->one();
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $formCreateMvp = new FormCreateMvp();
        $cache = Yii::$app->cache;

        $count_descInterview = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $id, 'desc_interview_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->gcp->exist_confirm == 1) || (!empty($model->mvps)  && $model->count_positive <= $count_positive && $model->gcp->exist_confirm == 1)) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
                    '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/formCreate/';
                $cache_form_creation = $cache->get('formCreateMvpCache');

                if ($cache_form_creation) {
                    //Заполнение полей модели FormCreateMvp данными из кэша
                    foreach ($cache_form_creation['FormCreateMvp'] as $key => $value) {
                        $formCreateMvp[$key] = $value;
                    }
                }

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/mvp/create', [
                        'confirmGcp' => $model,
                        'model' => $formCreateMvp,
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
     * Завершение подтверждения ГЦП и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmGcp::findOne($id);
        $gcp = $model->gcp;

        $count_descInterview = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $id, 'desc_interview_gcp.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->mvps)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->mvps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $gcp->exist_confirm,
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
     * @throws \yii\base\ErrorException
     */
    public function actionNotExistConfirm($id)
    {
        $model = $this->findModel($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        if ($gcp->exist_confirm === 0) {

            return $this->redirect(['/gcp/index', 'id' => $confirmProblem->id]);
        } else {

            $gcp->exist_confirm = 0;
            $gcp->time_confirm = time();

            if ($gcp->save()){

                // Удаление дирректории для кэша подтверждения
                $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                    '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm';
                if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                $gcp->trigger(Gcp::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/gcp/index', 'id' => $confirmProblem->id]);
            }
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionExistConfirm($id)
    {
        $model = $this->findModel($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $gcp->exist_confirm = 1;
        $gcp->time_confirm = time();

        if ($gcp->save()){

            // Удаление дирректории для кэша подтверждения
            $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            $gcp->trigger(Gcp::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/mvp/index', 'id' => $model->id]);
        }
    }


    public function actionSaveCacheCreationForm($id)
    {
        $gcp = Gcp::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateConfirm/';
            $key = 'formCreateConfirmGcpCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new FormCreateConfirmGcp();
        $gcp = Gcp::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $generationProblem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache;

        //кол-во респондентов, подтвердивших текущую проблему
        $count_represent_problem = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $confirmProblem->id, 'desc_interview_confirm.status' => '1'])->count();

        $model->count_respond = $count_represent_problem;

        $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
            '/segments/segment-'.$segment->id.'/problems/problem-'.$generationProblem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateConfirm/';
        $cache_form_creation = $cache->get('formCreateConfirmGcpCache');

        if ($cache_form_creation) { //Если существует кэш, то добавляем его к полям модели FormCreateConfirmGcp
            foreach ($cache_form_creation['FormCreateConfirmGcp'] as $key => $value) {
                $model[$key] = $value;
            }
        }

        if ($gcp->confirm){
            //Если у ГЦП создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $gcp->confirm->id]);
        }

        return $this->render('create', [
            'model' => $model,
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
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     */
    public function actionSaveConfirmGcp($id)
    {
        $model = new FormCreateConfirmGcp();
        $model->gcp_id = $id;

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()) {

                    $response =  ['success' => true, 'id' => $model->id];
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
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', ['model' => $model, 'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($id), 'gcp' => $gcp]),
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
        $model = ConfirmGcp::findOne($id);
        $formUpdateConfirmGcp = new FormUpdateConfirmGcp($id);
        $gcp = Gcp::findOne(['id' => $model->gcp_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $gcp->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmGcp::findAll(['confirm_gcp_id' => $id]);
        $newQuestion = new QuestionsConfirmGcp();

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
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Метод для добавления новых вопросов
     * @param $id
     * @return array
     */
    public function actionAddQuestion($id)
    {
        $model = new QuestionsConfirmGcp();
        $model->confirm_gcp_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmGcpNew = ConfirmGcp::findOne($id);
                    $questions = $confirmGcpNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmGcpNew->addAnswerConfirmGcp($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmGcpNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmGcpNew->queryQuestionsGeneralList();

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
        $confirmGcp = $this->findModel($id);
        $questions = $confirmGcp->questions;

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
        $model = new FormUpdateQuestionConfirmGcp($id);
        $confirmGcp = $this->findModel($model->confirm_gcp_id);
        $questions = $confirmGcp->questions;

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
        $model = new FormUpdateQuestionConfirmGcp($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model = $model->update()) {

                    $confirmGcp = $this->findModel($model->confirm_gcp_id);
                    $questions = $confirmGcp->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $confirmGcp->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmGcp->queryQuestionsGeneralList();

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
        $model = QuestionsConfirmGcp::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $confirmGcpNew = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);
                $questions = $confirmGcpNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmGcpNew->deleteAnswerConfirmGcp($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmGcpNew->queryQuestionsGeneralList();

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
