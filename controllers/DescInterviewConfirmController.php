<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\RespondsConfirm;
use app\models\Segment;
use app\models\User;
use yii\base\Model;
use Yii;
use app\models\DescInterviewConfirm;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class DescInterviewConfirmController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = DescInterviewConfirm::findOne(Yii::$app->request->get());
            $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
            $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
            $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
            $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

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
        $model = DescInterviewConfirm::findOne($id);
        $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $path = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/interviews/respond-'.$respond->id.'/';
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
        $model = DescInterviewConfirm::findOne($id);
        $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $pathDirDelete = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/interviews/respond-'.$respond->id;

        if (file_exists($pathDirDelete)) FileHelper::removeDirectory($pathDirDelete);

        $model->interview_file = null;
        $model->server_file = null;
        $model->update();

        if (Yii::$app->request->isAjax) return '';
        else return true;
    }


    public function actionSaveCacheCreationForm($id)
    {
        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/interviews/respond-'.$respond->id.'/';
            $key = 'formCreateInterviewCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    public function actionGetDataCreateForm($id)
    {
        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new DescInterviewConfirm();
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/interviews/respond-'.$respond->id.'/';
            $cache_form_creation = $cache->get('formCreateInterviewCache');

            if ($cache_form_creation) { //Если существует кэш

                foreach ($cache_form_creation['AnswersQuestionsConfirmProblem'] as $answerCache) {
                    foreach ($respond->answers as $answer) { // Добавляем ответы на вопросы интервью для полей модели AnswersQuestionsConfirmProblem
                        if ($answer['question_id'] == $answerCache['question_id']) {
                            $answer['answer'] = $answerCache['answer'];
                        }
                    }
                }
                foreach ($cache_form_creation['DescInterviewConfirm'] as $key => $value) { //Добавляем данные для полей модели DescInterviewConfirm
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
        $model = new DescInterviewConfirm();
        $model->responds_confirm_id = $id;
        $respond = RespondsConfirm::findOne($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $answers = $respond->answers;

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) $answer->save(false);

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->create()) {

                        $response = ['confirm_problem_id' => $confirmProblem->id];
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
        $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);


        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model, 'problem' => $problem])];
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
        $respond = RespondsConfirm::findOne(['id' => $model->responds_confirm_id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $respond->confirm_problem_id]);
        $answers = $respond->answers;

        //Если ранее был загружен файл
        if ($model->interview_file !== null) $model->loadFile = $model->interview_file;

        if(Yii::$app->request->isAjax) {

            if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {

                foreach ($answers as $answer) $answer->save(false);

                if ($model->load(Yii::$app->request->post())) {

                    if ($model->updateInterview()) {

                        $response = ['confirm_problem_id' => $confirmProblem->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }

    /**
     * Finds the DescInterviewConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return DescInterviewConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DescInterviewConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
