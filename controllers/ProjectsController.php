<?php

namespace app\controllers;

use app\models\Authors;
use app\models\Model;
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
                            /*$modelsConcept->creat_date = date('Y:m:d');
                            $modelsConcept->plan_gps = date('Y:m:d', (time() + 3600*24*30));
                            $modelsConcept->plan_ps = date('Y:m:d', (time() + 3600*24*60));
                            $modelsConcept->plan_dev_gcp = date('Y:m:d', (time() + 3600*24*90));
                            $modelsConcept->plan_gcp = date('Y:m:d', (time() + 3600*24*120));
                            $modelsConcept->plan_dev_gmvp = date('Y:m:d', (time() + 3600*24*150));
                            $modelsConcept->plan_gmvp = date('Y:m:d', (time() + 3600*24*180));*/

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

            $model->present_files = UploadedFile::getInstances($model, 'present_files');
            if ($model->present_files) {
                $model->uploadfiles();
            }


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
                            /*if (empty($modelsConcept->creat_date)){
                                $modelsConcept->creat_date = date('Y:m:d');
                                $modelsConcept->plan_gps = date('Y:m:d', (time() + 3600*24*30));
                                $modelsConcept->plan_ps = date('Y:m:d', (time() + 3600*24*60));
                                $modelsConcept->plan_dev_gcp = date('Y:m:d', (time() + 3600*24*90));
                                $modelsConcept->plan_gcp = date('Y:m:d', (time() + 3600*24*120));
                                $modelsConcept->plan_dev_gmvp = date('Y:m:d', (time() + 3600*24*150));
                                $modelsConcept->plan_gmvp = date('Y:m:d', (time() + 3600*24*180));
                            }*/
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
                        Yii::$app->session->setFlash('success', "Проект * {$model->project_name} * обновлен");
                        return $this->redirect(['view', 'id' => $model->id]);
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
        Segment::deleteAll(['project_id' => $model->id]);
        Authors::deleteAll(['project_id' => $model->id]);
        $model = $this->findModel($id)->delete();

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
