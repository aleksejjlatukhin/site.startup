<?php

namespace app\controllers;

use app\models\DescInterviewConfirm;
use app\models\FeedbackExpertConfirm;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirmProblem;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\ConfirmProblem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfirmProblemController implements the CRUD actions for ConfirmProblem model.
 */
class ConfirmProblemController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $model = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $problem = GenerationProblem::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $problem->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * Displays a single ConfirmProblem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        $data_responds = 0;
        $data_desc = 0;
        foreach ($responds as $respond){
            if (!empty($respond->name) && !empty($respond->info_respond)){
                $respond->exist_respond = 1;
                $data_responds++;
                if (!empty($respond->descInterview)){
                    $data_desc++;
                }
            }else{
                $respond->exist_respond = 0;
            }
        }



        $dataProviderResponds = new ActiveDataProvider([
            'query' => RespondsConfirm::find()->where(['confirm_problem_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'name' => SORT_ASC,
                ]
            ],
        ]);


        $dataProviderQuestions = new ActiveDataProvider([
            'query' => QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);


        $newQuestion = new QuestionsConfirmProblem();
        $newQuestion->confirm_problem_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $model->queryQuestionsGeneralList();



        return $this->render('view', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
            'data_responds' => $data_responds,
            'data_desc' => $data_desc,
            'dataProviderResponds' => $dataProviderResponds,
            'dataProviderQuestions' => $dataProviderQuestions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
        ]);
    }


    public function actionNotExistConfirm($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $generationProblem->exist_confirm = 0;
        $generationProblem->date_confirm = date('Y:m:d');

        if ($generationProblem->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['interview/view', 'id' => $interview->id]);
            }
        }
    }


    public function actionExistConfirm($id)
    {
        $model = ConfirmProblem::findOne($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $generationProblem->exist_confirm = 1;
        $generationProblem->date_confirm = date('Y:m:d');
        $generationProblem->date_time_confirm = date('Y-m-d H:i:s');

        if ($generationProblem->save()){

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                return $this->redirect(['gcp/create', 'id' => $model->id]);
            }
        }
    }

    /**
     * Creates a new ConfirmProblem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ConfirmProblem();
        $model->gps_id = $id;

        $generationProblem = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Действие доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['generation-problem/view', 'id' => $generationProblem->id]);
            }
        }


        if (!empty($generationProblem->confirm)){
            return $this->redirect(['view', 'id' => $generationProblem->confirm->id]);
        }


        foreach ($responds as $respond){
            if ($respond->descInterview->status == 1){
                $respondsPre[] = $respond; // представители сегмента
            }
        }

        $model->count_respond = count($respondsPre);


        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){


                    $gps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251");

                    $gps_dir = mb_strtolower($gps_dir, "windows-1251");

                    if (!file_exists($gps_dir)){
                        mkdir($gps_dir, 0777);
                    }


                    $feedbacks_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                        mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                        mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251") . '/feedbacks-confirm/';

                    $feedbacks_dir = mb_strtolower($feedbacks_dir, "windows-1251");

                    if (!file_exists($feedbacks_dir)){
                        mkdir($feedbacks_dir, 0777);
                    }


                    //Создание респондентов для программы подтверждения ГПС из представителей сегмента
                    $model->createRespondConfirm($responds);

                    //Вопросы, которые будут добавлены по-умолчанию
                    $model->addQuestionDefault('Что влияет на решение о покупке продукта?');
                    $model->addQuestionDefault('Как принимается решение о покупке?');


                    if ($project->save()){

                        return $this->redirect(['/confirm-problem/add-questions', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов, подтверждающих проблему не может быть больше общего числа респондентов");
            }
        }

        return $this->render('create', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    //Страница со списком вопросов
    public function actionAddQuestions($id)
    {
        $confirmProblem = ConfirmProblem::find()->with('questions')->where(['id' => $id])->one();
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $interview = Interview::findOne(['id' => $problem->interview_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);

        $dataProviderQuestions = new ActiveDataProvider([
            'query' => QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        $newQuestion = new QuestionsConfirmProblem();
        $newQuestion->confirm_problem_id = $id;

        //Список вопросов для добавления к списку программы
        $queryQuestions = $confirmProblem->queryQuestionsGeneralList();

        return $this->render('add-questions', [
            'dataProviderQuestions' => $dataProviderQuestions,
            'newQuestion' => $newQuestion,
            'queryQuestions' => $queryQuestions,
            'confirmProblem' => $confirmProblem,
            'problem' => $problem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }


    //Метод для добавления новых вопросов
    public function actionAddQuestion($id)
    {
        $model = new QuestionsConfirmProblem();
        $model->confirm_problem_id = $id;
        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);

        if ($model->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($model->save()){

                    $confirmProblemNew = ConfirmProblem::findOne($id);
                    $showListQuestions = $confirmProblemNew->showListQuestions;
                    $questions = $confirmProblemNew->questions;

                    //Создание пустого ответа для нового вопроса для каждого респондента
                    $confirmProblemNew->addAnswerConfirmProblem($model->id);
                    //Добавляем вопрос в общую базу вопросов
                    $confirmProblemNew->addQuestionToGeneralList($model->title);
                    //Передаем обновленный список вопросов для добавления в программу
                    $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

                    $project->update_at = date('Y:m:d');
                    $project->save();

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


    public function actionDeleteQuestion($id)
    {
        $model = QuestionsConfirmProblem::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);

        if(Yii::$app->request->isAjax) {

            if ($model->delete()){

                $project->update_at = date('Y:m:d');
                $project->save();

                $confirmProblemNew = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
                $showListQuestions = $confirmProblemNew->showListQuestions;
                $questions = $confirmProblemNew->questions;

                //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
                $confirmProblemNew->deleteAnswerConfirmProblem($id);

                //Передаем обновленный список вопросов для добавления в программу
                $queryQuestions = $confirmProblemNew->queryQuestionsGeneralList();

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


    public function actionUpdateDataInterview ($id)
    {
        $model = ConfirmProblem::find()->where(['id' => $id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
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

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->count_respond >= $model->count_positive){

                    if ($model->save()){

                        /*if ((count($responds)+1) <= $model->count_respond){
                            for ($count = count($responds) + 1; $count <= $model->count_respond; $count++ )
                            {
                                $newRespond[$count] = new RespondsConfirm();
                                $newRespond[$count]->confirm_problem_id = $id;
                                $newRespond[$count]->name = 'Респондент ' . $count;
                                $newRespond[$count]->save();
                            }
                        }else{
                            $minus = count($responds) - $model->count_respond;
                            $respond = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                            foreach ($respond as $item)
                            {
                                $descInterview = DescInterviewConfirm::find()->where(['responds_confirm_id' => $item->id])->one();

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
                        }*/

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
                                'gcps' => $model->gcps,
                                'problem' => $generationProblem,
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
     * Updates an existing ConfirmProblem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = ConfirmProblem::find()->where(['id' => $id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
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


        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond >= $model->count_positive){

                if ($model->save()){

                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для подтверждения обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество позитивных ответов не может быть больше количества респондентов");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing ConfirmProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $model->id])->all();
        $project->update_at = date('Y:m:d');
        $user = User::find()->where(['id' => $project->user_id])->one();
        $_user = Yii::$app->user->identity;

        if (!User::isUserDev(Yii::$app->user->identity['username'])) {

            //Удаление доступно только проектанту, который создал данную модель
            if ($user->id != $_user['id']){
                Yii::$app->session->setFlash('error', 'У Вас нет прав на данное действие!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $gps_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($generationProblem->title) , "windows-1251");

        $gps_dir = mb_strtolower($gps_dir, "windows-1251");

        if (file_exists($gps_dir)){
            $this->delTree($gps_dir);
        }

        if ($project->save()){

            foreach ($responds as $respond){

                $descInterview = $respond->descInterview;
                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }


            RespondsConfirm::deleteAll(['confirm_problem_id' => $id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->gps_id]);
        }
    }*/

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
