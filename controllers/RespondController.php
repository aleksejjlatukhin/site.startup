<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\Respond;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RespondController implements the CRUD actions for Respond model.
 */
class RespondController extends Controller
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
     * Lists all Respond models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $newRespond = new Respond();
        $newRespond->interview_id = $id;
        if ($newRespond->load(Yii::$app->request->post()))
        {
            $newRespond->save();
            $interview->count_respond = $interview->count_respond + 1;
            $interview->save();

            $project->update_at = date('Y:m:d');
            if ($project->save()){
                Yii::$app->session->setFlash('success', 'Создан новый респондент: "' . $newRespond->name . '"');
                return $this->redirect(['index', 'id' => $id]);
            }
        }

        return $this->render('index', [
            'models' => $models,
            'newRespond' => $newRespond,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    public function actionExist($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Respond::find()->where(['interview_id' => $id]),
        ]);
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('exist', [
            'dataProvider' => $dataProvider,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    public function actionByDateInterview($id)
    {
        $models = Respond::find()->where(['interview_id' => $id])->all();
        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        return $this->render('by-date-interview', [
            'models' => $models,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Displays a single Respond model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $desc_interview = DescInterview::find()->where(['respond_id' => $model->id])->one();

        return $this->render('view', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
            'desc_interview' => $desc_interview,
        ]);
    }


    /**
     * Creates a new Respond model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Respond();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Respond model.
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
            if ($project->save()){
                Yii::$app->session->setFlash('success', 'Данные обновлены!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing Respond model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $descInterview = DescInterview::find()->where(['respond_id' => $this->findModel($id)])->one();
        $interview = Interview::find()->where(['id' => $this->findModel($id)->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');
        $responds = Respond::find()->where(['interview_id' => $interview->id])->all();

        if (count($responds) == 1){
            Yii::$app->session->setFlash('error', 'Удаление последнего респондента запрещено!');
            return $this->redirect(['view', 'id' => $this->findModel($id)->id]);
        }

        if ($project->save()){
            Yii::$app->session->setFlash('error', 'Респондент: "' . $this->findModel($id)->name . '" удален!');

            if ($descInterview){
                if ($descInterview->interview_file !== null){
                    unlink('upload/interviews/' . $descInterview->interview_file);
                }

                $descInterview->delete();
            }


            if ($this->findModel($id)->delete()){
                $interview->count_respond = $interview->count_respond -1;
                $interview->save();
            }
            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }
    }

    /**
     * Finds the Respond model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Respond the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Respond::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
