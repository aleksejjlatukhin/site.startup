<?php

namespace app\controllers;

use app\models\ConfirmMvp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Mvp;
use app\models\Projects;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewMvp;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class DescInterviewMvpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update'])){

            $model = DescInterviewMvp::findOne(Yii::$app->request->get());
            $respond = RespondsMvp::findOne(['id' => $model->responds_mvp_id]);
            $confirmMvp = ConfirmMvp::findOne(['id' => $respond->confirm_mvp_id]);
            $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = RespondsMvp::findOne(Yii::$app->request->get());
            $confirmMvp = ConfirmMvp::findOne(['id' => $respond->confirm_mvp_id]);
            $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
            $project = Projects::findOne(['id' => $mvp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

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
        $model = DescInterviewMvp::findOne($id);
        $respond = RespondsMvp::findOne(['id' => $model->responds_mvp_id]);
        $confirmMvp = ConfirmMvp::findOne(['id' => $respond->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
            mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
            mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/' .
            mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/');

        $file = $path . $model->server_file;

        if (file_exists($file)) {

            return \Yii::$app->response->sendFile($file, $model->interview_file);
        }

    }


    public function actionDeleteFile($id)
    {
        $model = DescInterviewMvp::findOne($id);
        $respond = RespondsMvp::findOne(['id' => $model->responds_mvp_id]);
        $confirmMvp = ConfirmMvp::findOne(['id' => $respond->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $path = \Yii::getAlias(UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
            mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
            . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
            mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
            mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/' .
            mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/');

        unlink($path . $model->server_file);

        $model->interview_file = null;
        $model->server_file = null;

        $model->update();

        if (Yii::$app->request->isAjax)
        {
            return '';
        }
    }


    public function actionGetDataCreateForm($id)
    {
        $respond = RespondsMvp::findOne($id);
        $model = new DescInterviewMvp();

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('create', ['respond' => $respond, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewMvp();
        $model->responds_mvp_id = $id;
        $respond = RespondsMvp::findOne($id);
        $confirmMvp = ConfirmMvp::findOne(['id' => $respond->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $answers = $respond->answers;

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
                    mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
                    mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/' .
                    mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/';
                if (!file_exists($respond_dir)){
                    mkdir($respond_dir, 0777);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->validate() && $model->save()) {

                        $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                        if ($model->loadFile !== null){
                            if ($model->upload($respond_dir)){
                                $model->interview_file = $model->loadFile;
                                $model->save(false);
                            }
                        }

                        $response = ['confirm_mvp_id' => $confirmMvp->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetDataUpdateForm($id)
    {
        $model = $this->findModel($id);
        $respond = $model->respond;

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsMvp::find()->where(['id' => $model->responds_mvp_id])->one();
        $confirmMvp = ConfirmMvp::find()->where(['id' => $respond->confirm_mvp_id])->one();
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $answers = $respond->answers;

        if ($model->interview_file !== null){
            $model->loadFile = $model->interview_file;
        }

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) {
                    $answer->save(false);
                }

                $respond_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
                    mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/'.
                    mb_convert_encoding($this->translit($segment->name) , "windows-1251") .'/generation problems/'
                    . mb_convert_encoding($this->translit($problem->title) , "windows-1251") .'/gcps/'.
                    mb_convert_encoding($this->translit($gcp->title) , "windows-1251") .'/mvps/'.
                    mb_convert_encoding($this->translit($mvp->title) , "windows-1251") .'/interviews/' .
                    mb_convert_encoding($this->translit($respond->name) , "windows-1251") . '/';
                if (!file_exists($respond_dir)){
                    mkdir($respond_dir, 0777);
                }

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->validate() && $model->save()) {

                        $model->loadFile = UploadedFile::getInstance($model, 'loadFile');

                        if ($model->loadFile !== null){
                            if ($model->upload($respond_dir)){
                                $model->interview_file = $model->loadFile;
                                $model->save(false);
                            }
                        }

                        $response = ['confirm_mvp_id' => $confirmMvp->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }

    /**
     * Finds the DescInterviewMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
