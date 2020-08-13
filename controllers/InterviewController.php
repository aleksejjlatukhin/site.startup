<?php

namespace app\controllers;

use app\models\AllQuestions;
use app\models\DescInterview;
use app\models\FeedbackExpert;
use app\models\FeedbackExpertConfirm;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Questions;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\Interview;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InterviewController implements the CRUD actions for Interview model.
 */
class InterviewController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $segment = Segment::findOne(Yii::$app->request->get());
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['add-questions'])){

            $model = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $model->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete-question'])){

            $question = Questions::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $question->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'delete-question') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['data-availability-for-next-step'])){

            $interview = Interview::findOne(Yii::$app->request->get());
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'data-availability-for-next-step') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }



    /**
     * Displays a single Interview model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Interview::find()->with('questions')->where(['id' => $id])->one();
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();


        //Выбор респондентов, которые являются представителями сегмента
        $dataProviderRespondsPositive = new ActiveDataProvider([
            'query' => Respond::find()->with('descInterview')
                ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
                ->where(['interview_id' => $id, 'desc_interview.status' => '1']),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);



        $dataProviderProblems = new ActiveDataProvider([
            'query' => GenerationProblem::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);


        $dataProviderResponds = new ActiveDataProvider([
            'query' => Respond::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);



        $dataProviderQuestions = new ActiveDataProvider([
            'query' => Questions::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        $newQuestion = new Questions();
        $newQuestion->interview_id = $id;

        $newProblem = new GenerationProblem();
        $newProblem->interview_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();

        return $this->render('view', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'dataProviderRespondsPositive' => $dataProviderRespondsPositive,
            'dataProviderProblems' => $dataProviderProblems,
            'dataProviderResponds' => $dataProviderResponds,
            'dataProviderQuestions' => $dataProviderQuestions,
            'newQuestion' => $newQuestion,
            'newProblem' => $newProblem,
            'queryQuestions' => $queryQuestions
        ]);
    }


    public function actionDataAvailabilityForNextStep($id)
    {
        $model = Interview::findOne($id);

        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($model->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if(Yii::$app->request->isAjax) {
            if ((count($model->responds) == $count_descInterview && $model->count_positive <= $count_positive) || (!empty($model->problems)  && $model->count_positive <= $count_positive)) {

                $response =  ['success' => true];
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
     * Creates a new Interview model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $segment = Segment::findOne($id);

        if (empty($segment->creat_date)){
            Yii::$app->session->setFlash('error', "Необходимо заполнить все данные о сегменте!");
            return $this->redirect(['segment/view', 'id' => $id]);
        }

        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $model = new Interview();
        $model->segment_id = $id;
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['segment/view', 'id' => $segment->id]);
            }
        }

        $modelInterview = Interview::find()->where(['segment_id' => $id])->one();
        if (!empty($modelInterview)){
            return $this->redirect(['view', 'id' => $modelInterview->id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){

                    $interviews_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                        mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/';
                    if (!file_exists($interviews_dir)) {
                        mkdir($interviews_dir, 0777);
                    }

                    $feedbacks_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                        mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/feedbacks/';
                    if (!file_exists($feedbacks_dir)) {
                        mkdir($feedbacks_dir, 0777);
                    }

                    $generation_problems_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                        mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/generation problems/';
                    if (!file_exists($generation_problems_dir)) {
                        mkdir($generation_problems_dir, 0777);
                    }

                    //Создание респондентов по заданному значению count_respond
                    $model->createRespond();

                    //Вопросы, которые будут добавлены по-умолчанию
                    $model->addQuestionDefault('Как и посредством какого инструмента / процесса вы справляетесь с задачей?');
                    $model->addQuestionDefault('Что нравится / не нравится в текущем положении вещей?');
                    $model->addQuestionDefault('Вас беспокоит данная ситуация?');
                    $model->addQuestionDefault('Что вы пытались с этим сделать?');
                    $model->addQuestionDefault('Что вы делали с этим в последний раз, какие шаги предпринимали?');
                    $model->addQuestionDefault('Если ничего не делали, то почему?');
                    $model->addQuestionDefault('Сколько денег / времени на это тратится сейчас?');
                    $model->addQuestionDefault('Есть ли деньги на решение сложившейся ситуации сейчас?');
                    $model->addQuestionDefault('Что влияет на решение о покупке продукта?');
                    $model->addQuestionDefault('Как принимается решение о покупке?');

                    if ($project->save()) {

                        return $this->redirect(['add-questions', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества респондентов соответствующих сенгменту!");
            }
        }

        return $this->render('create', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    //Страница со списком вопросов
    public function actionAddQuestions($id)
    {
        $interview = Interview::find()->with('questions')->where(['id' => $id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $dataProviderQuestions = new ActiveDataProvider([
            'query' => Questions::find()->where(['interview_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        $newQuestion = new Questions();
        $newQuestion->interview_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $interview->queryQuestionsGeneralList();

        return $this->render('add-questions', [
            'dataProviderQuestions' => $dataProviderQuestions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    public function actionUpdateDataInterview ($id)
    {
        $model = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;


        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $responds = Respond::find()->where(['interview_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive){

                    if ($model->save()){

                        if ((count($responds)+1) <= $model->count_respond){
                            for ($count = count($responds) + 1; $count <= $model->count_respond; $count++ )
                            {
                                $newRespond[$count] = new Respond();
                                $newRespond[$count]->interview_id = $model->id;
                                $newRespond[$count]->name = 'Респондент ' . $count;
                                $newRespond[$count]->save();
                            }
                        }else{
                            $minus = count($responds) - $model->count_respond;
                            $respond = Respond::find()->where(['interview_id' => $id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                            foreach ($respond as $item)
                            {
                                $descInterview = DescInterview::find()->where(['respond_id' => $item->id])->one();

                                if ($descInterview) {
                                    $descInterview->delete();
                                }

                                $del_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                                    mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                                    mb_convert_encoding($this->translit($segment->name), "windows-1251") . '/interviews/' .
                                    mb_convert_encoding($this->translit($item->name), "windows-1251") . '/';

                                if (file_exists($del_dir)) {
                                    $this->delTree($del_dir);
                                }

                                $item->delete();
                            }
                        }

                        $project->update_at = date('Y:m:d');

                        if ($project->save()){

                            $descInterviews = [];
                            foreach ($model->responds as $respond) {
                                if($respond->descInterview) {
                                    $descInterviews[] = $respond->descInterview;
                                }
                            }

                            $response = [
                                'model' => $model,
                                'responds' => $model->responds,
                                'descInterviews' => $descInterviews,
                                'problems' => $model->problems,
                                ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }

    /**
     * Updates an existing Interview model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Interview::find()->with('questions')->where(['id' => $id])->one();
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;


        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $questions = Questions::find()->where(['interview_id' => $id])->all();
        $newQuestions = new Questions();
        $responds = Respond::find()->where(['interview_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){

                    if ((count($responds)+1) <= $model->count_respond){
                        for ($count = count($responds) + 1; $count <= $model->count_respond; $count++ )
                        {
                            $newRespond[$count] = new Respond();
                            $newRespond[$count]->interview_id = $model->id;
                            $newRespond[$count]->name = 'Респондент ' . $count;
                            $newRespond[$count]->save();
                        }
                    }else{
                        $minus = count($responds) - $model->count_respond;
                        $respond = Respond::find()->where(['interview_id' => $id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                        foreach ($respond as $item)
                        {
                            $item->delete();
                        }
                    }


                    if ($newQuestions->load(Yii::$app->request->post())){
                        if (!empty($newQuestions->title)){
                            $newQuestions->interview_id = $id;
                            $newQuestions->status = 1;
                            //debug($newQuestions);
                            $newQuestions->save();
                        }
                    }

                    $status = $_POST['Interview']['questions'];

                    foreach ($model->questions as $key => $question){
                        $question->status = $status[$key];
                        $question->save();
                        if($question->status == 0){
                            $question->delete();
                        }
                    }

                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для программы генерации ГПС обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества респондентов соответствующих сенгменту!");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'newQuestions' => $newQuestions,
        ]);
    }


    //Метод для добавления новых вопросов
    public function actionAddQuestion($id)
    {
        $model = new Questions();
        $model->interview_id = $id;

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $interviewNew = Interview::findOne($id);
                    $showListQuestions = $interviewNew->showListQuestions;
                    $questions = $interviewNew->questions;

                    //Добавляем вопрос в общую базу вопросов
                    $interviewNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                    $response = [
                        'model' => $model,
                        'questions' => $questions,
                        'queryQuestions' => $queryQuestions,
                        'showListQuestions' => $showListQuestions,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    public function actionDeleteQuestion ($id)
    {
        $model = Questions::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->update_at = date('Y:m:d');
                $project->save();

                $interviewNew = Interview::find()->where(['id' => $model->interview_id])->one();
                $showListQuestions = $interviewNew->showListQuestions;
                $questions = $interviewNew->questions;

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $interviewNew->queryQuestionsGeneralList();

                $response = [
                    'showListQuestions' => $showListQuestions,
                    'questions' => $questions,
                    'queryQuestions' => $queryQuestions,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }

    /**
     * Deletes an existing Interview model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        //Удаление доступно только проектанту, который создал данную модель
        if ($user->id != $_user['id']){
            Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $responds = Respond::find()->where(['interview_id' => $model->id])->all();
        $generationProblems = GenerationProblem::find()->where(['interview_id' => $model->id])->all();
        $project->update_at = date('Y:m:d');

        if ($project->save()){

            $pathDeleteInt = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/interviews';
            $this->delTree($pathDeleteInt);

            $pathDeleteGps = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/generation problems';
            $this->delTree($pathDeleteGps);

            $pathDeleteFeedbacks = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") .
                    '/segments/' . mb_convert_encoding($this->translit($segment->name), "windows-1251")) . '/feedbacks';
            $this->delTree($pathDeleteFeedbacks);

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }


            if (!empty($generationProblems)){
                foreach ($generationProblems as $generationProblem){
                    if (!empty($generationProblem->confirm)){
                        $confirmProblem = $generationProblem->confirm;


                        if (!empty($confirmProblem->feedbacks)){
                            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }


                        if (!empty($confirmProblem->responds)){
                            $respondsConfirm = $confirmProblem->responds;
                            foreach ($respondsConfirm as $respondConfirm){
                                if (!empty($respondConfirm->descInterview)){
                                    $descInterviewConfirm = $respondConfirm->descInterview;
                                    $descInterviewConfirm->delete();
                                }
                            }
                            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
                        }

                        $confirmProblem->delete();
                    }
                }
            }

            Questions::deleteAll(['interview_id' => $id]);
            Respond::deleteAll(['interview_id' => $id]);
            FeedbackExpert::deleteAll(['interview_id' => $id]);
            GenerationProblem::deleteAll(['interview_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->segment_id]);
        }
    }*/

    /**
     * Finds the Interview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Interview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
