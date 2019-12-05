<?php

namespace app\controllers;

use app\models\FeedbackExpert;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Projects;
use app\models\Questions;
use app\models\Respond;
use Yii;
use app\models\Segment;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SegmentController implements the CRUD actions for Segment model.
 */
class SegmentController extends AppController
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
     * Lists all Segment models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $project = Projects::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Segment::find()->where(['project_id' => $id]),
        ]);

        $newModel = new Segment();
        $newModel->project_id = $id;

        if ($newModel->load(Yii::$app->request->post()) && $newModel->save()){
            if ($project->save()){
                $project->update_at = date('Y:m:d');
                return $this->redirect(['index', 'id' => $newModel->project_id]);
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'project' => $project,
            'newModel' => $newModel,
        ]);
    }

    public function actionRoadmap($id)
    {
        $project = Projects::findOne($id);

        $models = Segment::find()->where(['project_id' => $id])->all();

        return $this->render('roadmap', [
            'project' => $project,
            'models' => $models,
        ]);
    }

    public function actionOneRoadmap($id)
    {
        $model = Segment::findOne($id);
        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $interview = Interview::find()->where(['segment_id' => $model->id])->one();
        $problems = GenerationProblem::find()->where(['interview_id' => $interview->id])->all();

        if (!empty($problems)){
            foreach ($problems as $k => $problem){
                if (($k+1) == count($problems)){
                    if ($model->fact_gps !== $problem->date_gps){
                        $model->fact_gps = $problem->date_gps;
                        $model->save();
                    }
                }
            }
        }

        return $this->render('one-roadmap', [
            'model' => $model,
            'project' => $project,
        ]);

    }



    /**
     * Displays a single Segment model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $project = Projects::find()->where(['id' => $this->findModel($id)->project_id])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'project' => $project,
        ]);
    }

    /**
     * Creates a new Segment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
/*    public function actionCreate($id)
    {
        $model = new Segment();
        $model->project_id = $id;

        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($project->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'project' => $project,
        ]);
    }*/

    /**
     * Updates an existing Segment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $project = Projects::find()->where(['id' => $model->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($project->save()) {

                if ($_POST['Segment']['field_of_activity'] && $_POST['Segment']['sort_of_activity'] && $_POST['Segment']['age'] &&
                    $_POST['Segment']['income'] && $_POST['Segment']['quantity'] && $_POST['Segment']['market_volume'])
                {
                    if (empty($model->creat_date))
                    {
                        $model->creat_date = date('Y:m:d');
                        $model->plan_gps = date('Y:m:d', (time() + 3600*24*30));
                        $model->plan_ps = date('Y:m:d', (time() + 3600*24*60));
                        $model->plan_dev_gcp = date('Y:m:d', (time() + 3600*24*90));
                        $model->plan_gcp = date('Y:m:d', (time() + 3600*24*120));
                        $model->plan_dev_gmvp = date('Y:m:d', (time() + 3600*24*150));
                        $model->plan_gmvp = date('Y:m:d', (time() + 3600*24*180));
                        $model->save();
                    }
                }

                Yii::$app->session->setFlash('success', "Сегмент {$model->name} обновлен");
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing Segment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $project = Projects::find()->where(['id' => $this->findModel($id)->project_id])->one();
        $project->update_at = date('Y:m:d');
        $interview = Interview::find()->where(['segment_id' => $id])->one();
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();

        if ($project->save()) {

            Yii::$app->session->setFlash('error', "Сегмент {$this->findModel($id)->name} удален");

            foreach ($responds as $respond){
                $descInterview = $respond->descInterview;

                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews/' . $descInterview->interview_file);
                }

                if (!empty($descInterview)){
                    $descInterview->delete();
                }
            }

            if (!empty($interview->feedbacks)){
                foreach ($interview->feedbacks as $feedback) {
                    if ($feedback->feedback_file !== null){
                        unlink('upload/feedbacks/' . $feedback->feedback_file);
                    }
                }
            }


            Questions::deleteAll(['interview_id' => $interview->id]);
            Respond::deleteAll(['interview_id' => $interview->id]);
            FeedbackExpert::deleteAll(['interview_id' => $interview->id]);
            GenerationProblem::deleteAll(['interview_id' => $interview->id]);

            if ($interview){
                $interview->delete();
            }

            $this->findModel($id)->delete();

            return $this->redirect(['index', 'id' => $project->id]);

        }
    }

    /**
     * Finds the Segment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Segment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Segment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
