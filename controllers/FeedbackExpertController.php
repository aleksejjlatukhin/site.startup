<?php

namespace app\controllers;

use app\models\Interview;
use app\models\Projects;
use app\models\Segment;
use Yii;
use app\models\FeedbackExpert;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FeedbackExpertController implements the CRUD actions for FeedbackExpert model.
 */
class FeedbackExpertController extends Controller
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
     * Lists all FeedbackExpert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedbackExpert::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDownload($filename)
    {
        $model = FeedbackExpert::find()->where(['feedback_file' => $filename])->one();

        $path = \Yii::getAlias('upload/feedbacks/');

        $file = $path . $model->feedback_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }

    }


    public function actionDeleteFile($filename)
    {
        $model = FeedbackExpert::find()->where(['feedback_file' => $filename])->one();

        $path = \Yii::getAlias('upload/feedbacks/');

        unlink($path . $model->feedback_file);

        $model->feedback_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single FeedbackExpert model.
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

        return $this->render('view', [
            'model' => $model,
            'interview' => $interview,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new FeedbackExpert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new FeedbackExpert();
        $model->interview_id = $id;
        $model->date_feedback = date('Y:m:d');
        $models = FeedbackExpert::find()->where(['interview_id' => $id])->all();
        $model->title = 'Отзыв ' . (count($models)+1);

        $interview = Interview::findOne($id);
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

            if ($model->loadFile !== null){
                if ($model->validate() && $model->upload()){
                    $model->feedback_file = $model->loadFile;
                    $model->save(false);
                }
            }

            $project->update_at = date('Y:m:d');

            if ($project->save()){

                Yii::$app->session->setFlash('success', $model->title ." добавлен");
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing FeedbackExpert model.
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

        if ($model->feedback_file !== null){
            $model->loadFile = $model->feedback_file;
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

            if ($model->loadFile !== null){
                if ($model->validate() && $model->upload()){
                    $model->feedback_file = $model->loadFile;
                    $model->save(false);
                }
            }

            $project->update_at = date('Y:m:d');
            if ($project->save()) {
                Yii::$app->session->setFlash('success', "Данные по " . $model->title . " обновлены!");
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing FeedbackExpert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->feedback_file !== null){
            unlink('upload/feedbacks/' . $model->feedback_file);
        }

        $interview = Interview::find()->where(['id' => $model->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($project->save()) {

            Yii::$app->session->setFlash('error', '"' . $model->title . '" удален!');

            $model->delete();

            $j = 0;
            foreach ($interview->feedbacks as $item){
                $j++;
                $item->title = 'Отзыв ' . $j;
                $item->save();
            }

            return $this->redirect(['interview/view', 'id' => $interview->id]);
        }
    }

    /**
     * Finds the FeedbackExpert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return FeedbackExpert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeedbackExpert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
