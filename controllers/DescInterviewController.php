<?php

namespace app\controllers;

use app\models\Interview;
use app\models\Projects;
use app\models\Respond;
use app\models\Segment;
use app\models\User;
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

        if (in_array($action->id, ['view'])){

            $descInterview = DescInterview::findOne(Yii::$app->request->get());
            $respond = Respond::find()->where(['id' => $descInterview->respond_id])->one();
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['update'])){

            $descInterview = DescInterview::findOne(Yii::$app->request->get());
            $respond = Respond::find()->where(['id' => $descInterview->respond_id])->one();
            $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
            $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
            $project = Projects::find()->where(['id' => $segment->project_id])->one();

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

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
            if ($project->user_id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])){

                if ($action->id == 'create') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    public function actionDownload($id)
    {
        $model = DescInterview::findOne($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
            mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file, $model->interview_file);
        }

    }


    public function actionDeleteFile($id)
    {
        $model = DescInterview::findOne($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
            mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/');

        unlink($path . $model->server_file);

        $model->interview_file = null;
        $model->server_file = null;
        $project->updated_at = time();
        $project->save();

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return '';
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
        $model = new DescInterview();
        $model->respond_id = $id;
        $model->date_fact = date('Y:m:d');
        $respond = Respond::find()->where(['id' => $id])->one();

        //Если у респондента уже есть интервью, то отменить действие
        if ($respond->descInterview){
            return false;
        }

        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                    mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/';
                if (!file_exists($respond_dir)){
                    mkdir($respond_dir, 0777);
                }

                if ($model->validate() && $model->save()){

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->upload($respond_dir)){
                            $model->interview_file = $model->loadFile;
                            $model->save(false);
                        }
                    }

                    $project->updated_at = time();
                    if ($project->save()){

                        $response = $model;
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
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
        $model = $this->findModel($id);
        $respond = Respond::find()->where(['id' => $model->respond_id])->one();
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


        if ($model->interview_file !== null){
            $model->loadFile = $model->interview_file;
        }

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                    mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/';
                if (!file_exists($respond_dir)){
                    mkdir($respond_dir, 0777);
                }


                if ($model->validate() && $model->save()){

                    $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                    if ($model->loadFile !== null){
                        if ($model->upload($respond_dir)){
                            $model->interview_file = $model->loadFile;
                            $model->save(false);
                        }
                    }

                    $project->updated_at = time();
                    if ($project->save()){

                        $response = $model;
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
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
        $model = DescInterview::find()->where(['respond_id' => $id])->one();
        $respond = Respond::findOne($id);
        $interview = Interview::find()->where(['id' => $respond->interview_id])->one();
        $segment = Segment::find()->where(['id' => $interview->segment_id])->one();
        $project = Projects::find()->where(['id' => $segment->project_id])->one();
        $user = User::find()->where(['id' => $project->user_id])->one();


        $project->updated_at = time();

        if ($model->server_file !== null){
            unlink(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/interviews/' .
                mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/' . $model->server_file);
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
