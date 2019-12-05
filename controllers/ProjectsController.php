<?php

namespace app\controllers;

use app\models\Authors;
use app\models\FeedbackExpert;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Model;
use app\models\PreFiles;
use app\models\Questions;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\Projects;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends AppController
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
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        $dataProvider = new ActiveDataProvider([
            'query' => Projects::find()->where(['user_id' => $user['id']]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDownload($filename)
    {
        $model = PreFiles::find()->where(['file_name' => $filename])->one();

        $path = \Yii::getAlias('upload/files/');

        $file = $path . $model->file_name;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }
    }


    public function actionDeleteFile($filename)
    {
        $model = PreFiles::find()->where(['file_name' => $filename])->one();

        $project = Projects::find()->where(['id' => $model->project_id])->one();

        $path = \Yii::getAlias('upload/files/');

        unlink($path . $model->file_name);

        $model->delete();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $project->id]);
        }
    }

    /**
     * Displays a single Projects model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projects();
        $modelsConcept = [new Segment];
        $modelsAuthors = [new Authors];

        $user = Yii::$app->user->identity;
        $model->user_id = $user['id'];

        $model->created_at = date('Y:m:d');
        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $modelsConcept = Model::createMultiple(Segment::class);
            Model::loadMultiple($modelsConcept, Yii::$app->request->post());

            $modelsAuthors = Model::createMultiple(Authors::class);
            Model::loadMultiple($modelsAuthors, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsConcept) && $valid;
            $valid = Model::validateMultiple($modelsAuthors) && $valid;


            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsConcept as $modelsConcept) {
                            $modelsConcept->project_id = $model->id;
                            if (! ($flag = $modelsConcept->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }


                        foreach ($modelsAuthors as $modelsAuthors) {
                            $modelsAuthors->project_id = $model->id;
                            if (! ($flag = $modelsAuthors->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();

                        $model->present_files = UploadedFile::getInstances($model, 'present_files');

                        if ($model->validate() && $model->upload()){
                            foreach ($model->present_files as $file){
                                $preFiles = new PreFiles();
                                $preFiles->file_name = $file;
                                $preFiles->project_id = $model->id;
                                $preFiles->save(false);
                            }
                        }

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

        }
        return $this->render('create', [
            'model' => $model,
            'modelsConcept' => (empty($modelsConcept)) ? [new Segment] : $modelsConcept,
            'modelsAuthors' => (empty($modelsAuthors)) ? [new Authors] : $modelsAuthors,
        ]);
    }

    /**
     * Updates an existing Projects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsConcept = Segment::find()->where(['project_id'=>$id])->all();
        $modelsAuthors = Authors::find()->where(['project_id'=>$id])->all();

        $model->update_at = date('Y:m:d');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $oldIDs = ArrayHelper::map($modelsConcept, 'id', 'id');
            $modelsConcept = Model::createMultiple(Segment::class, $modelsConcept);
            Model::loadMultiple($modelsConcept, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsConcept, 'id', 'id')));

            $oldIDs = ArrayHelper::map($modelsAuthors, 'id', 'id');
            $modelsAuthors = Model::createMultiple(Authors::class, $modelsAuthors);
            Model::loadMultiple($modelsAuthors, Yii::$app->request->post());
            $deletedIDs1 = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAuthors, 'id', 'id')));

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsConcept) && $valid;
            $valid = Model::validateMultiple($modelsAuthors) && $valid;


            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            Segment::deleteAll(['id' => $deletedIDs]);
                        }
                        if (! empty($deletedIDs1)) {
                            Authors::deleteAll(['id' => $deletedIDs1]);
                        }
                        foreach ($modelsConcept as $modelsConcept) {
                            $modelsConcept->project_id = $model->id;
                            if (! ($flag = $modelsConcept->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        foreach ($modelsAuthors as $modelsAuthors) {
                            $modelsAuthors->project_id = $model->id;
                            if (! ($flag = $modelsAuthors->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                    }
                    if ($flag) {
                        $transaction->commit();

                        $model->present_files = UploadedFile::getInstances($model, 'present_files');

                        if ($model->validate() && $model->upload()){
                            foreach ($model->present_files as $file){
                                $preFiles = new PreFiles();
                                $preFiles->file_name = $file;
                                $preFiles->project_id = $model->id;
                                $preFiles->save(false);
                            }


                            Yii::$app->session->setFlash('success', "Проект * {$model->project_name} * обновлен");
                            return $this->redirect(['view', 'id' => $model->id]);
                        }

                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsConcept' => (empty($modelsConcept)) ? [new Segment] : $modelsConcept,
            'modelsAuthors' => (empty($modelsAuthors)) ? [new Authors] : $modelsAuthors,
        ]);
    }

    /**
     * Deletes an existing Projects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $segments = Segment::find()->where(['project_id' => $model->id])->all();
        $preFiles = PreFiles::find()->where(['project_id' => $model->id])->all();

        if ($segments){

            foreach ($segments as $segment){
                $interview = Interview::find()->where(['segment_id' => $segment->id])->one();
                if (!empty($interview->feedbacks)){
                    foreach ($interview->feedbacks as $feedback) {
                        if ($feedback->feedback_file !== null){
                            unlink('upload/feedbacks/' . $feedback->feedback_file);
                        }
                    }
                }


                $responds = Respond::find()->where(['interview_id' => $interview->id])->all();
                foreach ($responds as $respond){
                    $descInterview = $respond->descInterview;

                    if ($descInterview->interview_file !== null){
                        unlink('upload/interviews/' . $descInterview->interview_file);
                    }

                    if (!empty($descInterview)){
                        $descInterview->delete();
                    }
                }
            }

            foreach ($segments as $segment){
                $interview = Interview::find()->where(['segment_id' => $segment->id])->one();
                Questions::deleteAll(['interview_id' => $interview->id]);
                Respond::deleteAll(['interview_id' => $interview->id]);
                FeedbackExpert::deleteAll(['interview_id' => $interview->id]);
                GenerationProblem::deleteAll(['interview_id' => $interview->id]);
                Interview::deleteAll(['segment_id' => $segment->id]);
            }
        }

        foreach ($preFiles as $file){
            if (!empty($file)){
                unlink('upload/files/' . $file->file_name);
            }
        }

        PreFiles::deleteAll(['project_id' => $model->id]);
        Authors::deleteAll(['project_id' => $model->id]);
        Segment::deleteAll(['project_id' => $model->id]);

        Yii::$app->session->setFlash('error', 'Прооект "' . $this->findModel($id)->project_name . '" удален');

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
