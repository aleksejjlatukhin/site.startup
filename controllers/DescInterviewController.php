<?php

namespace app\controllers;

use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use Yii;
use app\models\DescInterview;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DescInterviewController implements the CRUD actions for DescInterview model.
 */
class DescInterviewController extends AppController
{

    public function beforeAction($action)
    {

        if (in_array($action->id, ['view']) || in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $descInterview = DescInterview::findOne(Yii::$app->request->get());
            $respond = Respond::find()->where(['id' => $descInterview->respond_id])->one();
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = Respond::findOne(Yii::$app->request->get());
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->user_id == Yii::$app->user->id){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }

    /**
     * Lists all DescInterview models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DescInterview::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/


    public function actionDownload($id)
    {
        $user = Yii::$app->user->identity;
        $model = DescInterview::findOne($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/interviews/' .
            mb_convert_encoding($respond->name , "windows-1251") . '/');

        $file = $path . $model->interview_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file);
        }

    }


    public function actionDeleteFile($id)
    {
        $user = Yii::$app->user->identity;
        $model = DescInterview::findOne($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
            mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
            mb_convert_encoding($segment->name , "windows-1251") .'/interviews/' .
            mb_convert_encoding($respond->name , "windows-1251") . '/');

        unlink($path . $model->interview_file);

        $model->interview_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return 'Delete';
        }else{
            return $this->redirect(['update', 'id' => $model->id]);
        }
    }

    /**
     * Displays a single DescInterview model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        return $this->render('view', [
            'model' => $model,
            'respond' => $respond,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Creates a new DescInterview model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $user = Yii::$app->user->identity;
        $model = new DescInterview();
        $model->respond_id = $id;
        $model->date_fact = date('Y:m:d');

        $respond = Respond::find()->where(['id' => $id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if (!empty($respond->descInterview)){
            return $this->redirect(['view', 'id' => $respond->descInterview->id]);
        }

        if ($respond->name == null || $respond->info_respond == null || $respond->place_interview == null ||
            $respond->date_plan == null){
            Yii::$app->session->setFlash('error', "Необходимо заполнить все данные о респонденте!");
            return $this->redirect(['respond/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post())) {

            $respond_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                mb_convert_encoding($segment->name , "windows-1251") .'/interviews/' .
                mb_convert_encoding($respond->name , "windows-1251") . '/';
            if (!file_exists($respond_dir)){
                mkdir($respond_dir, 0777);
            }

            if ($model->save()){

                $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                if ($model->loadFile !== null){
                    if ($model->validate() && $model->upload($respond_dir)){
                        $model->interview_file = $model->loadFile;
                        $model->save(false);
                    }
                }

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Материалы полученные во время интервью добавлены!");
                    return $this->redirect(['respond/view', 'id' => $model->respond_id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'respond' => $respond,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Updates an existing DescInterview model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = Yii::$app->user->identity;
        $model = $this->findModel($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();

        if ($model->interview_file !== null){
            $model->loadFile = $model->interview_file;
        }

        if ($model->load(Yii::$app->request->post())) {

            $respond_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                mb_convert_encoding($segment->name , "windows-1251") .'/interviews/' .
                mb_convert_encoding($respond->name , "windows-1251") . '/';
            if (!file_exists($respond_dir)){
                mkdir($respond_dir, 0777);
            }

            if ($model->save()){

                $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                if ($model->loadFile !== null){
                    if ($model->validate() && $model->upload($respond_dir)){
                        $model->interview_file = $model->loadFile;
                        $model->save(false);
                    }
                }

                $project->update_at = date('Y:m:d');
                if ($project->save()){
                    Yii::$app->session->setFlash('success', "Материалы полученные во время интервью обновлены");
                    return $this->redirect(['respond/view', 'id' => $model->respond_id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'respond' => $respond,
            'segment' => $segment,
            'project' => $project,
        ]);
    }

    /**
     * Deletes an existing DescInterview model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = Yii::$app->user->identity;
        $model = DescInterview::find()->where(['respond_id' => $id])->one();
        $respond = Respond::findOne($id);
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $project->update_at = date('Y:m:d');

        if ($model->interview_file !== null){
            unlink(UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/' .
                mb_convert_encoding($project->project_name , "windows-1251") . '/segments/'.
                mb_convert_encoding($segment->name , "windows-1251") .'/interviews/' .
                mb_convert_encoding($respond->name , "windows-1251") . '/' . $model->interview_file);
        }

        if ($project->save()) {

            Yii::$app->session->setFlash('error', 'Материалы полученные во время интервью ' . date("d.m.Y", strtotime($model->date_fact)) . ' удалены!');

            $model->delete();

            return $this->redirect(['respond/view', 'id' => $respond->id]);
        }
    }

    /**
     * Finds the DescInterview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
