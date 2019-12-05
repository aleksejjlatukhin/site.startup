<?php

namespace app\controllers;

use app\models\DescInterview;
use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use Yii;
use app\models\GenerationProblem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GenerationProblemController implements the CRUD actions for GenerationProblem model.
 */
class GenerationProblemController extends Controller
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
    public function actionCreate($id)
    {
        $responds = Respond::find()->where(['interview_id' => $id])->all();

        $count = 0;
        foreach ($responds as $respond){

            $descInterview = DescInterview::find()->where(['respond_id' => $respond->id])->one();
            if(!empty($descInterview)){
                $count++;
            }
        }

        if ($count < 1){
            Yii::$app->session->setFlash('error', "Необходимо добавить материалы интервью хотя бы с одним респондентом!");
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $project->update_at = date('Y:m:d');

            if ($project->save()){

                Yii::$app->session->setFlash('success', "Данные по ". $model->title ." загружены");
                return $this->redirect(['interview/view', 'id' => $model->interview_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

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
                return $this->redirect(['interview/view', 'id' => $model->interview_id]);
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
        $project->update_at = date('Y:m:d');

        if ($project->save()) {
            Yii::$app->session->setFlash('error', '"' . $model->title . '" удалена!');
        }

        $model->delete();

        $j = 0;
        foreach ($interview->problems as $item){
            $j++;
            $item->title = 'ГПС ' . $j;
            $item->save();
        }

        return $this->redirect(['interview/view', 'id' => $interview->id]);
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
