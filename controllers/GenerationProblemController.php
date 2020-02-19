<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\DescInterview;
use app\models\FeedbackExpertConfirm;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\Segment;
use Yii;
use app\models\GenerationProblem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GenerationProblemController implements the CRUD actions for GenerationProblem model.
 */
class GenerationProblemController extends AppController
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
     * Lists all GenerationProblem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => GenerationProblem::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GenerationProblem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = GenerationProblem::findOne($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('view', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new GenerationProblem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    // ОТКЛЮЧАЕМ CSRF
    public function beforeAction($action)
    {
        if ($action->id == 'create') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }


    public function actionCreate($id)
    {
        $responds = Respond::find()->where(['interview_id' => $id])->all();

        $count = 0;
        foreach ($responds as $respond){

            $descInterview = DescInterview::find()->where(['respond_id' => $respond->id])->one();
            if($descInterview->status == 1){
                $count++;
            }
        }

        if ($count < 1){
            Yii::$app->session->setFlash('error', "Необходимо добавить материалы интервью хотя бы с одним представителем сегмента!");
            return $this->redirect(['interview/view', 'id' => $id]);
        }

        $model = new GenerationProblem();
        $model->interview_id = $id;
        $model->date_gps = date('Y:m:d');
        $models = GenerationProblem::find()->where(['interview_id' => $id])->all();
        $model->title = 'ГПС ' . (count($models)+1);

        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (Yii::$app->request->isAjax){

            if ($model->load(Yii::$app->request->post())) {
                $model->description = $_POST['GenerationProblem']['description'];

                if ($model->save()){

                    $project->update_at = date('Y:m:d');
                    $project->save();

                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $model;
                    return $model;

                }
            }
        }


        return $this->render('create', [
            'model' => $model,
            'models' => $models,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
            'responds' => $responds,
        ]);
    }


    /*public function actionTest($id){


        $model = new GenerationProblem();
        $model->interview_id = $id;
        $model->date_gps = date('Y:m:d');
        $models = GenerationProblem::find()->where(['interview_id' => $id])->all();
        $model->title = 'ГПС ' . (count($models)+1);


        if (Yii::$app->request->isAjax){

            if($model->load(\Yii::$app->request->post())){
                $model->description = $_POST['GenerationProblem']['description'];

                if ($model->save()) {

                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $model;
                    return $model;
                }
            }
        }

        return $this->render('test', compact('model', 'models'));
    }*/

    /**
     * Updates an existing GenerationProblem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $project->update_at = date('Y:m:d');
            if ($project->save()) {
                Yii::$app->session->setFlash('success', "Данные по " . $model->title . " обновлены!");
                return $this->redirect(['generation-problem/view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing GenerationProblem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $confirmProblem = ConfirmProblem::find()->where(['gps_id' => $model->id])->one();
        $responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->all();
        $project->update_at = date('Y:m:d');

        if ($project->save()) {

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews-confirm/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($confirmProblem->feedbacks)){
                foreach ($confirmProblem->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks-confirm/' . $feedback->feedback_file);
                    }
                }
            }

            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);
            FeedbackExpertConfirm::deleteAll(['confirm_problem_id' => $confirmProblem->id]);

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');

            $confirmProblem->delete();

            $model->delete();

            $j = 0;
            foreach ($interview->problems as $item){
                $j++;
                $item->title = 'ГПС ' . $j;
                $item->save();
            }

            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }


    }

    /**
     * Finds the GenerationProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GenerationProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GenerationProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}