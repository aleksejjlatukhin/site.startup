<?php


namespace app\controllers;

use app\models\AnswersQuestionsConfirmGcp;
use app\models\AnswersQuestionsConfirmMvp;
use app\models\AnswersQuestionsConfirmProblem;
use app\models\AnswersQuestionsConfirmSegment;
use app\models\forms\CacheForm;
use app\models\InterviewConfirmGcp;
use app\models\InterviewConfirmMvp;
use app\models\InterviewConfirmProblem;
use app\models\InterviewConfirmSegment;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\RespondsProblem;
use app\models\RespondsSegment;
use app\models\StageConfirm;
use app\models\User;
use Throwable;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\Model;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class InterviewsController extends AppUserPartController
{


    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $interview = self::findModel(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $respond = $interview->respond;
            $confirm = $respond->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->userId == Yii::$app->user->id) || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $respond = self::getRespond(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $confirm = $respond->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->userId == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param $stage
     * @param $id
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload($stage, $id)
    {
        $model = self::findModel($stage, $id);
        $file = $model->pathFile . $model->server_file;

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file, $model->interview_file);
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


    /**
     * @param $stage
     * @param $id
     * @return bool|string
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteFile($stage, $id)
    {
        $model = self::findModel($stage, $id);
        $pathDirDelete = mb_substr($model->pathFile, 0, -1);

        if (file_exists($pathDirDelete)) FileHelper::removeDirectory($pathDirDelete);

        $model->interview_file = null;
        $model->server_file = null;
        $model->update();

        if (Yii::$app->request->isAjax) return '';
        else return true;
    }


    /**
     * Сохранение кэша из формы
     * создания интервью
     * @param $stage
     * @param $id
     */
    public function actionSaveCacheCreationForm($stage, $id)
    {
        $respond = self::getRespond($stage, $id);
        $class = self::getClassModel($stage);
        $cachePath = $class::getCachePath($respond);
        $cacheName = 'formCreateInterviewCache';

        if(Yii::$app->request->isAjax) {

            $cache = new CacheForm();
            $cache->setCache($cachePath, $cacheName);
        }
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     */
    public function actionGetDataCreateForm($stage, $id)
    {
        $respond = self::getRespond($stage, $id);
        $model = self::getCreateModel($stage);

        if(Yii::$app->request->isAjax) {

            $cachePath = $model::getCachePath($respond);
            $cacheName = 'formCreateInterviewCache';

            if ($cache = $model->_cacheManager->getCache($cachePath, $cacheName)) {

                $className = explode('\\', self::getClassModel($stage))[2];
                foreach ($cache[$className] as $key => $value) $model[$key] = $value;

                $classQuestions = explode('\\', self::getClassAnswers($stage))[2];
                if ($cache[$classQuestions]) { //Если существует кэш для ответов на вопросы
                    foreach ($cache[$classQuestions] as $answerCache) {
                        foreach ($respond->answers as $answer) { // Добавляем ответы на вопросы интервью для полей модели AnswersQuestionsConfirmSegment
                            if ($answer['question_id'] == $answerCache['question_id']) {
                                $answer['answer'] = $answerCache['answer'];
                            }
                        }
                    }
                }
            }

            $response = ['renderAjax' => $this->renderAjax('create', ['respond' => $respond, 'model' => $model])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate($stage, $id)
    {
        $respond = self::getRespond($stage, $id);
        $model = self::getCreateModel($stage);
        $model->setRespondId($id);
        $confirm = $respond->confirm;
        $answers = $respond->answers;

        if(Yii::$app->request->isAjax) {

            if ($model->load(Yii::$app->request->post())) {

                if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {
                    foreach ($answers as $answer)
                        $answer->save(false);
                }

                if ($model->create()) {

                    // Удаление кэша формы создания
                    $cachePath = $model::getCachePath($respond);
                    $model->_cacheManager->deleteCache(mb_substr($cachePath, 0, -1));

                    $response = ['confirm_id' => $confirm->id];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     */
    public function actionGetDataUpdateForm($stage, $id)
    {
        $model = self::findModel($stage, $id);
        $respond = $model->respond;
        $confirm = $respond->confirm;
        $hypothesis = $confirm->hypothesis;

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model, 'confirm' => $confirm, 'hypothesis' => $hypothesis])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate($stage, $id)
    {
        $model = self::findModel($stage, $id);
        $respond = $model->respond;
        $confirm = $respond->confirm;
        $answers = $respond->answers;

        if(Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post())) {
                if (Model::loadMultiple($answers, Yii::$app->request->post()) && Model::validateMultiple($answers)) {
                    foreach ($answers as $answer)
                        $answer->save(false);
                }
                if ($model->updateInterview()) {
                    $response = ['confirm_id' => $confirm->id];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return InterviewConfirmGcp|InterviewConfirmMvp|InterviewConfirmProblem|InterviewConfirmSegment|bool|null
     */
    private static function findModel($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return InterviewConfirmSegment::findOne($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return InterviewConfirmProblem::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return InterviewConfirmGcp::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return InterviewConfirmMvp::findOne($id);
        }
        return false;
    }


    /**
     * @param $stage
     * @return InterviewConfirmGcp|InterviewConfirmMvp|InterviewConfirmProblem|InterviewConfirmSegment|bool
     */
    private static function getCreateModel($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new InterviewConfirmSegment();
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new InterviewConfirmProblem();
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new InterviewConfirmGcp();
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new InterviewConfirmMvp();
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return RespondsGcp|RespondsMvp|RespondsProblem|RespondsSegment|bool|null
     */
    private static function getRespond($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return RespondsSegment::findOne($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsProblem::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return RespondsGcp::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return RespondsMvp::findOne($id);
        }
        return false;
    }


    /**
     * @param $stage
     * @return bool|string
     */
    private static function getClassModel($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return InterviewConfirmSegment::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return InterviewConfirmProblem::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return InterviewConfirmGcp::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return InterviewConfirmMvp::class;
        }
        return false;
    }


    private static function getClassAnswers($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return AnswersQuestionsConfirmSegment::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return AnswersQuestionsConfirmProblem::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return AnswersQuestionsConfirmGcp::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return AnswersQuestionsConfirmMvp::class;
        }
        return false;
    }
}