<?php

namespace app\controllers;

use app\models\FeedbackExpertConfirm;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\QuestionsConfirm;
use app\models\RespondsConfirm;
use app\models\Segment;
use Yii;
use app\models\ConfirmProblem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConfirmProblemController implements the CRUD actions for ConfirmProblem model.
 */
class ConfirmProblemController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ConfirmProblem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ConfirmProblem::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConfirmProblem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = ConfirmProblem::find()->with('questions')->where(['id' => $id])->one();
        $generationProblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationProblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        foreach ($responds as $respond){
            if (!empty($respond->name) && !empty($respond->info_respond) && !empty($respond->date_plan) && !empty($respond->place_interview)){
                $respond->exist_respond = 1;
            }else{
                $respond->exist_respond = 0;
            }
        }

        foreach ($responds as $respond){
            if (!empty($respond->descInterview->date_fact) && !empty($respond->descInterview->description)){
                $respond->descInterview->exist_desc = 1;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => RespondsConfirm::find()->where(['confirm_problem_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'generationProblem' => $generationProblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
        ]);
    }

    /**
     * Creates a new ConfirmProblem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new ConfirmProblem();
        $model->gps_id = $id;

        $generationPromblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationPromblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $newQuestions = new QuestionsConfirm();

        $modelConfirmProblem = ConfirmProblem::find()->where(['gps_id' => $id])->one();
        if (!empty($modelConfirmProblem)){
            return $this->redirect(['view', 'id' => $modelConfirmProblem->id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond > $model->count_positive){

                if ($model->save()){


                    $gps_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                        mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                        mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($generationPromblem->title , "windows-1251");

                    $gps_dir = mb_strtolower($gps_dir, "windows-1251");

                    if (!file_exists($gps_dir)){
                        mkdir($gps_dir, 0777);
                    }


                    $interviews_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                        mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                        mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($generationPromblem->title , "windows-1251") . '/interviews-confirm/';

                    $interviews_dir = mb_strtolower($interviews_dir, "windows-1251");

                    if (!file_exists($interviews_dir)){
                        mkdir($interviews_dir, 0777);
                    }


                    $feedbacks_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                        mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                        mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
                        . mb_convert_encoding($generationPromblem->title , "windows-1251") . '/feedbacks-confirm/';

                    $feedback_dirs = mb_strtolower($feedbacks_dir, "windows-1251");

                    if (!file_exists($feedbacks_dir)){
                        mkdir($feedbacks_dir, 0777);
                    }



                    for ($i = 1; $i <= $model->count_respond; $i++ )
                    {
                        $newRespond[$i] = new RespondsConfirm();
                        $newRespond[$i]->confirm_problem_id = $model->id;
                        $newRespond[$i]->name = 'Респондент ' . $i;
                        $newRespond[$i]->save();
                    }

                    if ($model->question_1 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Как и посредством какого инструмента / процесса вы справляетесь с задачей?';
                        $question->save();
                    }
                    if ($model->question_2 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Что нравится / не нравится в текущем положении вещей?';
                        $question->save();
                    }
                    if ($model->question_3 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Вас беспокоит данная ситуация?';
                        $question->save();
                    }
                    if ($model->question_4 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Что вы пытались с этим сделать?';
                        $question->save();
                    }
                    if ($model->question_5 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Что вы делали с этим в последний раз, какие шаги предпринимали?';
                        $question->save();
                    }
                    if ($model->question_6 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Если ничего не делали, то почему?';
                        $question->save();
                    }
                    if ($model->question_7 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Сколько денег / времени на это тратится сейчас?';
                        $question->save();
                    }
                    if ($model->question_8 == 1){
                        $question = new QuestionsConfirm();
                        $question->confirm_problem_id = $model->id;
                        $question->status = 1;
                        $question->title = 'Есть ли деньги на решение сложившейся ситуации сейчас?';
                        $question->save();
                    }

                    if ($newQuestions->load(Yii::$app->request->post())){
                        if (!empty($newQuestions->title)){
                            $newQuestions->confirm_problem_id = $model->id;
                            $newQuestions->status = 1;
                            //debug($newQuestions);
                            $newQuestions->save();
                        }
                    }

                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для интервью ППС загружены");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
            }
        }

        return $this->render('create', [
            'model' => $model,
            'generationPromblem' => $generationPromblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'newQuestions' => $newQuestions,
        ]);
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
        $model = ConfirmProblem::find()->with('questions')->where(['id' => $id])->one();
        $generationPromblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationPromblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $questions = QuestionsConfirm::find()->where(['confirm_problem_id' => $id])->all();
        $newQuestions = new QuestionsConfirm();

        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->count_respond > $model->count_positive){

                if ($model->save()){

                    if ((count($responds)+1) <= $model->count_respond){
                        for ($count = count($responds) + 1; $count <= $model->count_respond; $count++ )
                        {
                            $newRespond[$count] = new RespondsConfirm();
                            $newRespond[$count]->confirm_problem_id = $model->id;
                            $newRespond[$count]->name = 'Респондент ' . $count;
                            $newRespond[$count]->save();
                        }
                    }else{
                        $minus = count($responds) - $model->count_respond;
                        $respond = RespondsConfirm::find()->orderBy(['id' => SORT_DESC])->limit($minus)->all();
                        foreach ($respond as $item)
                        {
                            $item->delete();
                        }
                    }

                    if ($newQuestions->load(Yii::$app->request->post())){
                        if (!empty($newQuestions->title)){
                            $newQuestions->confirm_problem_id = $id;
                            $newQuestions->status = 1;
                            //debug($newQuestions);
                            $newQuestions->save();
                        }
                    }

                    $status = $_POST['ConfirmProblem']['questions'];

                    foreach ($model->questions as $key => $question){
                        $question->status = $status[$key];
                        $question->save();
                        if($question->status == 0){
                            $question->delete();
                        }
                    }


                    $project->update_at = date('Y:m:d');

                    if ($project->save()){

                        Yii::$app->session->setFlash('success', "Данные для интервью обновлены!");
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', "Количество респондентов не должно быть меньше количества позитивных интервью!");
            }
        }

        return $this->render('update', [
            'model' => $model,
            'generationPromblem' => $generationPromblem,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'newQuestions' => $newQuestions,
        ]);
    }

    /**
     * Deletes an existing ConfirmProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $generationPromblem = GenerationProblem::find()->where(['id' => $model->gps_id])->one();
        $interview = Interview::find()->where(['id' => $generationPromblem->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $model->id])->all();
        $project->update_at = date('Y:m:d');

        $gps_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($generationPromblem->title , "windows-1251");

        $gps_dir = mb_strtolower($gps_dir, "windows-1251");

        if (file_exists($gps_dir)){
            $this->delTree($gps_dir);
        }



        if ($project->save()){

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews-confirm/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($model->feedbacks)){
                foreach ($model->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks-confirm/' . $feedback->feedback_file);
                    }
                }
            }



            QuestionsConfirm::deleteAll(['confirm_problem_id' => $id]);
            RespondsConfirm::deleteAll(['confirm_problem_id' => $id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $id]);

            Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

            $model->delete();

            return $this->redirect(['create', 'id' => $model->gps_id]);
        }
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
