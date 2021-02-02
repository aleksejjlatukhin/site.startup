<?php

namespace app\controllers;

use app\models\forms\FormCreateConfirmProblem;
use app\models\forms\FormCreateGcp;
use app\models\forms\FormUpdateConfirmProblem;
use app\models\forms\FormUpdateQuestionConfirmProblem;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmProblem;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use kartik\mpdf\Pdf;
use Yii;
use app\models\ConfirmProblem;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class ConfirmProblemController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::findOne(['id' => $problem->interview_id]);
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['save-confirm-problem'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::findOne(['id' => $problem->interview_id]);
            $segment = Segment::findOne(['id' => $interview->segment_id]);
            $project = Projects::findOne(['id' => $segment->project_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['add-questions'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $project = Projects::find()->where(['id' => $problem->project->id])->one();

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

            $question = QuestionsConfirmProblem::findOne(Yii::$app->request->get());
            $confirm_problem = ConfirmProblem::findOne(['id' => $question->confirm_problem_id]);
            $problem = GenerationProblem::findOne(['id' => $confirm_problem->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

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
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $questions = QuestionsConfirmProblem::findAll(['confirm_problem_id' => $id]);
        $newQuestion = new QuestionsConfirmProblem();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('view', [
            'model' => $model,
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
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
     * Проверка данных подтверждения на этапе разработки ГЦП
     * @param $id
     * @return array
     */
    public function actionDataAvailabilityForNextStep($id)
    {
        $model = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $formCreateGcp = new FormCreateGcp();
        $cache = Yii::$app->cache;

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id, 'desc_interview_confirm.status' => '1'])->count();


        if (Yii::$app->request->isAjax) {

            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive && $model->problem->exist_confirm == 1) || (!empty($model->gcps)  && $model->count_positive <= $count_positive && $model->problem->exist_confirm == 1)) {

                $cache->cachePath = '../runtime/cache/forms/'.mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251").
                    '/projects/'.mb_strtolower(mb_convert_encoding($this->translit($project->project_name), "windows-1251"),"windows-1251").
                    '/segments/'.mb_strtolower(mb_convert_encoding($this->translit($segment->name), "windows-1251"),"windows-1251").
                    '/problems/'.mb_strtolower(mb_convert_encoding($this->translit($problem->title), "windows-1251"),"windows-1251").'/gcps/formCreate/';
                $cache_form_creation = $cache->get('formCreateGcpCache');

                if ($cache_form_creation) {
                    //Заполнение полей модели FormCreateGcp данными из кэша
                    foreach ($cache_form_creation['FormCreateGcp'] as $key => $value) {
                        $formCreateGcp[$key] = $value;
                    }
                }

                $response =  [
                    'success' => true,
                    'renderAjax' => $this->renderAjax('/gcp/create', [
                        'confirmProblem' => $model,
                        'model' => $formCreateGcp,
                        'segment' => $model->problem->segment,
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
     * Завершение подтверждения ГПС и переход на следующий этап
     * @param $id
     * @return array
     */
    public function actionMovingNextStage($id)
    {
        $model = ConfirmProblem::findOne($id);
        $problem = $model->problem;

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id, 'desc_interview_confirm.status' => '1'])->count();

        if(Yii::$app->request->isAjax) {

            if (count($model->responds) > $count_descInterview && empty($model->gcps)) {

                $response = ['not_completed_descInterviews' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->gcps))) {

                $response =  [
                    'success' => true,
                    'exist_confirm' => $problem->exist_confirm,
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
        $generationProblem = GenerationProblem::findOne(['id' => $model->gps_id]);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);

        if ($generationProblem->exist_confirm === 0) {

            return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
        }else {

            $generationProblem->exist_confirm = 0;
            $generationProblem->time_confirm = time();

            if ($generationProblem->save()){
                $generationProblem->trigger(GenerationProblem::EVENT_CLICK_BUTTON_CONFIRM);
                return $this->redirect(['/generation-problem/index', 'id' => $interview->id]);
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
        $generationProblem = GenerationProblem::findOne(['id' => $model->gps_id]);

        $generationProblem->exist_confirm = 1;
        $generationProblem->time_confirm = time();

        if ($generationProblem->save()){
            $generationProblem->trigger(GenerationProblem::EVENT_CLICK_BUTTON_CONFIRM);
            return $this->redirect(['/gcp/index', 'id' => $model->id]);
        }
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new FormCreateConfirmProblem();
        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);

        //кол-во представителей сегмента
        $count_represent_segment = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $interview->id, 'desc_interview.status' => '1'])->count();

        $model->count_respond = $count_represent_segment;

        if ($generationProblem->confirm){
            //Если у проблемы создана программа подтверждения, то перейти на страницу подтверждения
            return $this->redirect(['view', 'id' => $generationProblem->confirm->id]);
        }


        return $this->render('create', [
            'model' => $model,
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
    public function actionSaveConfirmProblem($id)
    {
        $model = new FormCreateConfirmProblem();
        $model->gps_id = $id;
        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::findOne(['id' => $generationProblem->interview_id]);
        $responds = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $interview->id, 'desc_interview.status' => '1'])->all();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->create()){

                    //Создание респондентов для программы подтверждения ГПС из представителей сегмента
                    $model->createRespondConfirm($responds);

                    //Вопросы, которые будут добавлены по-умолчанию
                    $model->addQuestionDefault('Какими функциями должен обладать продукт вашей мечты?');
                    $model->addQuestionDefault('Расскажите поподробнее, каков алгоритм вашей работы?');
                    $model->addQuestionDefault('Почему вас это беспокоит?');
                    $model->addQuestionDefault('Каковы последствия этой ситуации?');
                    $model->addQuestionDefault('Расскажите поподробнее, что произошло в последний раз?');
                    $model->addQuestionDefault('Что еще пытались сделать?');
                    $model->addQuestionDefault('Кто будет финансировать покупку?');
                    $model->addQuestionDefault('С кем еще мне следует переговорить?');
                    $model->addQuestionDefault('Есть ли еще вопросы, которые мне следовало задать?');
                    $model->addQuestionDefault('Пытались ли найти решение?');
                    $model->addQuestionDefault('Эти решения оказались недостаточно эффективными?');
                    $model->addQuestionDefault('Как справляются с задачей сейчас и сколько денег тратят?');
                    $model->addQuestionDefault('Сколько времени это занимает?');
                    $model->addQuestionDefault('Продемонстрировать как они выполняют работу или другую деятельность?');
                    $model->addQuestionDefault('Что в этом нравится и что нет?');
                    $model->addQuestionDefault('Какие еще инструменты и процессы пробовали пока не остановились на этом?');
                    $model->addQuestionDefault('Ищут ли активно сейчас чем это можно заменить?');
                    $model->addQuestionDefault('Если да, то в чем проблема?');
                    $model->addQuestionDefault('Если не ищут, то почему?');
                    $model->addQuestionDefault('На чем теряют деньги, используя текущие инструменты?');


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
     */
    public function actionUpdate ($id)
    {
        $model = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne(['id' => $model->gps_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model = $model->update()){

                    $response = [
                        'success' => true,
                        'ajax_data_confirm' => $this->renderAjax('ajax_data_confirm', ['model' => $model, 'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($id), 'problem' => $problem]),
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
        $model = ConfirmProblem::findOne($id);
        $formUpdateConfirmProblem = new FormUpdateConfirmProblem($id);
        $problem = GenerationProblem::findOne(['id' => $model->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $questions = QuestionsConfirmProblem::findAll(['confirm_problem_id' => $id]);
        $newQuestion = new QuestionsConfirmProblem();

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();
        $queryQuestions = ArrayHelper::map($queryQuestions,'title','title');

        return $this->render('add-questions', [
            'formUpdateConfirmProblem' => $formUpdateConfirmProblem,
            'questions' => $questions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'model' => $model,
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
        $model = new QuestionsConfirmProblem();
        $model->confirm_problem_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmProblemNew = ConfirmProblem::findOne($id);
                    $questions = $confirmProblemNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmProblemNew->addAnswerConfirmProblem($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblemNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

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
        $confirmProblem = $this->findModel($id);
        $questions = $confirmProblem->questions;

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
        $model = new FormUpdateQuestionConfirmProblem($id);
        $confirmProblem = $this->findModel($model->confirm_problem_id);
        $questions = $confirmProblem->questions;

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
        $model = new FormUpdateQuestionConfirmProblem($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model = $model->update()) {

                    $confirmProblem = $this->findModel($model->confirm_problem_id);
                    $questions = $confirmProblem->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblem->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblem->queryQuestionsGeneralList();

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
        $model = QuestionsConfirmProblem::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $confirmProblemNew = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
                $questions = $confirmProblemNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmProblemNew->deleteAnswerConfirmProblem($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

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
        $content = $this->renderPartial('/confirm-problem/questions_and_answers_pdf', ['questions' => $questions]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $problem_desc = $model->problem->description;
        if (mb_strlen($problem_desc) > 25) {
            $problem_desc = mb_substr($problem_desc, 0, 25) . '...';
        }

        $filename = 'Ответы респондентов на вопросы интервью для подтверждения проблемы: «'.$problem_desc.'».';

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
                'SetHeader' => ['<div style="color: #3c3c3c;">Ответы респондентов на вопросы интервью. Проблема: «'.$problem_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
        $content = $this->renderPartial('/confirm-problem/viewpdf', ['model' => $model, 'responds' => $responds]);

        $destination = Pdf::DEST_BROWSER;
        //$destination = Pdf::DEST_DOWNLOAD;

        $problem_desc = $model->problem->description;
        if (mb_strlen($problem_desc) > 25) {
            $problem_desc = mb_substr($problem_desc, 0, 25) . '...';
        }

        $filename = 'Подтверждение проблемы «'.$problem_desc.'». Таблица респондентов.pdf';

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
                'SetTitle' => ['Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»'],
                'SetHeader' => ['<div style="color: #3c3c3c;">Респонденты для подтверждения гипотезы проблемы «'.$problem_desc.'»</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
     * Finds the ConfirmProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ConfirmProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConfirmProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
