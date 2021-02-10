<?php

namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\DescInterviewGcp;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;


class DescInterviewGcpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = DescInterviewGcp::findOne(Yii::$app->request->get());
            $respond = RespondsGcp::findOne(['id' => $model->responds_gcp_id]);
            $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
            $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = RespondsGcp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
            $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
            $project = Projects::findOne(['id' => $gcp->project->id]);

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


    /**
     * @param $id
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = DescInterviewGcp::findOne($id);
        $respond = RespondsGcp::findOne(['id' => $model->responds_gcp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/interviews/respond-'.$respond->id.'/';
        $file = $path . $model->server_file;

        if (file_exists($file)) {
            return \Yii::$app->response->sendFile($file, $model->interview_file);
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }

    /**
     * @param $id
     * @return bool|string
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteFile($id)
    {
        $model = DescInterviewGcp::findOne($id);
        $respond = RespondsGcp::findOne(['id' => $model->responds_gcp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $pathDirDelete = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/interviews/respond-'.$respond->id;

        if (file_exists($pathDirDelete)) FileHelper::removeDirectory($pathDirDelete);

        $model->interview_file = null;
        $model->server_file = null;
        $model->update();

        if (Yii::$app->request->isAjax) return '';
        else return true;
    }


    public function actionSaveCacheCreationForm($id)
    {
        $respond = RespondsGcp::findOne($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/interviews/respond-'.$respond->id.'/';
            $key = 'formCreateInterviewCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    public function actionGetDataCreateForm($id)
    {
        $respond = RespondsGcp::findOne($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new DescInterviewGcp();
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/interviews/respond-'.$respond->id.'/';
            $cache_form_creation = $cache->get('formCreateInterviewCache');

            if ($cache_form_creation) { //Если существует кэш

                foreach ($cache_form_creation['AnswersQuestionsConfirmGcp'] as $answerCache) {
                    foreach ($respond->answers as $answer) { // Добавляем ответы на вопросы интервью для полей модели AnswersQuestionsConfirmGcp
                        if ($answer['question_id'] == $answerCache['question_id']) {
                            $answer['answer'] = $answerCache['answer'];
                        }
                    }
                }
                foreach ($cache_form_creation['DescInterviewGcp'] as $key => $value) { //Добавляем данные для полей модели DescInterviewGcp
                    $model[$key] = $value;
                }
            }

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
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function actionCreate($id)
    {
        $model = new DescInterviewGcp();
        $model->responds_gcp_id = $id;
        $respond = RespondsGcp::findOne($id);
        $confirmGcp = ConfirmGcp::findOne($respond->confirm_gcp_id);
        $answers = $respond->answers;

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) $answer->save(false);

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->create()) {

                        $response = ['confirm_gcp_id' => $confirmGcp->id];
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
        $respond = RespondsGcp::findOne(['id' => $model->responds_gcp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model, 'gcp' => $gcp])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $respond = RespondsGcp::findOne(['id' => $model->responds_gcp_id]);
        $confirmGcp = ConfirmGcp::findOne(['id' => $respond->confirm_gcp_id]);
        $answers = $respond->answers;

        //Если ранее был загружен файл
        if ($model->interview_file !== null) $model->loadFile = $model->interview_file;

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) $answer->save(false);

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->updateInterview()) {

                        $response = ['confirm_gcp_id' => $confirmGcp->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }

    /**
     * Finds the DescInterviewGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
