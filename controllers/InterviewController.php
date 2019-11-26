<?php

namespace app\controllers;

use app\models\Projects;
use app\models\Questions;
use app\models\Respond;
use app\models\Segment;
use Yii;
use app\models\Interview;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InterviewController implements the CRUD actions for Interview model.
 */
class InterviewController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Interview models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Interview::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
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

        $newRespond = new Respond();
        $newRespond->interview_id = $id;
        if ($newRespond->load(Yii::$app->request->post()))
        {
            $newRespond->save();
            $model->count_respond = $model->count_respond + 1;
            $model->save();

            $project->update_at = date('Y:m:d');
            Yii::$app->session->setFlash('success', 'Создан новый респондент: "' . $newRespond->name . '"');
        }

        $responds = Respond::find()->where(['interview_id' => $id])->all();

        $dataProvider = new ActiveDataProvider([
            'query' => Respond::find(),
        ]);



        return $this->render('view', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'newRespond' => $newRespond,
            'responds' => $responds,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Interview model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $segment = Segment::findOne($id);

        if ($segment->field_of_activity == null || $segment->sort_of_activity == null || $segment->age == null ||
            $segment->income == null || $segment->quantity == null || $segment->market_volume == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить все данные о сегменте!");
            return $this->redirect(['segment/view', 'id' => $id]);
        }

        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $model = new Interview();
        $model->segment_id = $id;

        $newQuestions = new Questions();

        $modelInterview = Interview::find()->where(['segment_id' => $id])->one();
        if (!empty($modelInterview)){
            return $this->redirect(['view', 'id' => $modelInterview->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            for ($i = 1; $i <= $model->count_respond; $i++ )
            {
                $newRespond[$i] = new Respond();
                $newRespond[$i]->interview_id = $model->id;
                $newRespond[$i]->name = 'Респондент ' . $i;
                $newRespond[$i]->save();
            }

            if ($model->question_1 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Как и посредством какого инструмента / процесса вы справляетесь с задачей?';
                $question->save();
            }
            if ($model->question_2 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Что нравится / не нравится в текущем положении вещей?';
                $question->save();
            }
            if ($model->question_3 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Вас беспокоит данная ситуация?';
                $question->save();
            }
            if ($model->question_4 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Что вы пытались с этим сделать?';
                $question->save();
            }
            if ($model->question_5 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Что вы делали с этим в последний раз, какие шаги предпринимали?';
                $question->save();
            }
            if ($model->question_6 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Если ничего не делали, то почему?';
                $question->save();
            }
            if ($model->question_7 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Сколько денег / времени на это тратится сейчас?';
                $question->save();
            }
            if ($model->question_8 == 1){
                $question = new Questions();
                $question->interview_id = $model->id;
                $question->status = 1;
                $question->title = 'Есть ли деньги на решение сложившейся ситуации сейчас?';
                $question->save();
            }

            if ($newQuestions->load(Yii::$app->request->post())){
                if (!empty($newQuestions->title)){
                    $newQuestions->interview_id = $model->id;
                    $newQuestions->status = 1;
                    //debug($newQuestions);
                    $newQuestions->save();
                }
            }

            $project->update_at = date('Y:m:d');
            Yii::$app->session->setFlash('success', "Данные для интервью загружены");

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'newQuestions' => $newQuestions,
        ]);
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
        //$model = $this->findModel($id);
        $model = Interview::find()->with('questions')->where(['id' => $id])->one();

        $segment = Segment::find()->where(['id' => $model->segment_id])->one();

        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $questions = Questions::find()->where(['interview_id' => $id])->all();

        $newQuestions = new Questions();

        $responds = Respond::find()->where(['interview_id' => $id])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

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
                $respond = Respond::find()->orderBy(['id' => SORT_DESC])->limit($minus)->all();
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
            Yii::$app->session->setFlash('success', "Данные для интервью обновлены!");

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'newQuestions' => $newQuestions,
        ]);
    }

    /**
     * Deletes an existing Interview model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segment = Segment::find()->where(['id' => $model->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        Questions::deleteAll(['interview_id' => $id]);
        Respond::deleteAll(['interview_id' => $id]);

        Yii::$app->session->setFlash('error', "Ваше интервью удалено, создайте новое интервью!");

        $model->delete();

        return $this->redirect(['create', 'id' => $model->segment_id]);
    }

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
