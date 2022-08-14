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
use app\models\PatternHttpException;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\RespondsProblem;
use app\models\RespondsSegment;
use app\models\StageConfirm;
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

/**
 * Контроллер с методами для создания, редактирования и
 * получения информации по проведенным интервью с респондентами при подтверждении гипотезы
 *
 * Class InterviewsController
 * @package app\controllers
 */
class InterviewsController extends AppUserPartController
{


    /**
     * @param $action
     * @return bool
     * @throws HttpException
     */
    public function beforeAction($action): bool
    {

        if (in_array($action->id, ['update', 'delete'])){

            $interview = self::findModel((int)Yii::$app->request->get('stage'), (int)Yii::$app->request->get('id'));
            $respond = $interview->respond;
            $confirm = $respond->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;
            
            if (($project->getUserId() === Yii::$app->user->getId())){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }

            PatternHttpException::noAccess();

        }elseif ($action->id === 'create'){

            $respond = self::getRespond((int)Yii::$app->request->get('stage'), (int)Yii::$app->request->get('id'));
            $confirm = $respond->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;
            
            if ($project->getUserId() === Yii::$app->user->getId()){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }

            PatternHttpException::noAccess();

        }else{
            return parent::beforeAction($action);
        }

    }


    /**
     * @param int $stage
     * @param int $id
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload(int $stage, int $id)
    {
        $model = self::findModel($stage, $id);
        $file = $model->getPathFile() . $model->getServerFile();

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file, $model->getInterviewFile());
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


    /**
     * @param int $stage
     * @param int $id
     * @return bool|string
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDeleteFile(int $stage, int $id)
    {
        $model = self::findModel($stage, $id);
        $pathDirDelete = mb_substr($model->getPathFile(), 0, -1);

        if (file_exists($pathDirDelete)) {
            FileHelper::removeDirectory($pathDirDelete);
        }

        $model->setInterviewFile('');
        $model->setServerFile('');
        $model->update();

        if (Yii::$app->request->isAjax) {
            return '';
        }

        return true;
    }


    /**
     * Сохранение кэша из формы
     * создания интервью
     *
     * @param int $stage
     * @param int $id
     */
    public function actionSaveCacheCreationForm(int $stage, int $id): void
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
     * @param int $stage
     * @param int $id
     * @return array|bool
     */
    public function actionGetDataCreateForm(int $stage, int $id)
    {
        $respond = self::getRespond($stage, $id);
        $model = self::getCreateModel($stage);

        if(Yii::$app->request->isAjax) {

            $cachePath = $model::getCachePath($respond);
            $cacheName = 'formCreateInterviewCache';

            if ($cache = $model->_cacheManager->getCache($cachePath, $cacheName)) {

                $className = explode('\\', self::getClassModel($stage))[2];
                foreach ($cache[$className] as $key => $value) {
                    $model[$key] = $value;
                }

                $classQuestions = explode('\\', self::getClassAnswers($stage))[2];
                if ($cache[$classQuestions]) { //Если существует кэш для ответов на вопросы
                    foreach ($cache[$classQuestions] as $answerCache) {
                        foreach ($respond->answers as $answer) { // Добавляем ответы на вопросы интервью для полей модели AnswersQuestionsConfirmSegment
                            if ($answer->getQuestionId() === (int)$answerCache['question_id']) {
                                $answer->setAnswer((int)$answerCache['answer']);
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
     * @param int $stage
     * @param int $id
     * @return array|bool
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionCreate(int $stage, int $id)
    {
        if(Yii::$app->request->isAjax) {
            
            $respond = self::getRespond($stage, $id);
            $model = self::getCreateModel($stage);
            $model->setRespondId($id);
            $confirm = $respond->confirm;
            $answers = $respond->answers;
            
            if ($model->load(Yii::$app->request->post())) {
                if (Model::loadMultiple($answers, Yii::$app->request->post())) {
                    if (Model::validateMultiple($answers)) {
                        foreach ($answers as $answer) {
                            $answer->save(false);
                        }
                    }
                }

                if ($model->create()) {

                    // Удаление кэша формы создания
                    $cachePath = $model::getCachePath($respond);
                    $model->_cacheManager->deleteCache(mb_substr($cachePath, 0, -1));

                    $response = ['confirm_id' => $confirm->getId()];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param int $stage
     * @param int $id
     * @return array|bool
     */
    public function actionGetDataUpdateForm(int $stage, int $id)
    {
        if(Yii::$app->request->isAjax) {
            
            $model = self::findModel($stage, $id);
            $respond = $model->respond;
            $confirm = $respond->confirm;
            $hypothesis = $confirm->hypothesis;

            $response = ['renderAjax' => $this->renderAjax('update', ['respond' => $respond, 'model' => $model, 'confirm' => $confirm, 'hypothesis' => $hypothesis])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param int $stage
     * @param int $id
     * @return array|bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $stage, int $id)
    {
        if(Yii::$app->request->isAjax) {
            
            $model = self::findModel($stage, $id);
            $respond = $model->respond;
            $confirm = $respond->confirm;
            $answers = $respond->answers;
        
            if ($model->load(Yii::$app->request->post())) {
                if (Model::loadMultiple($answers, Yii::$app->request->post())) {
                    if (Model::validateMultiple($answers)) {
                        foreach ($answers as $answer) {
                            $answer->save(false);
                        }
                    }
                }
                if ($model->updateInterview()) {
                    $response = ['confirm_id' => $confirm->getId()];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }


    /**
     * @param int $stage
     * @param int $id
     * @return InterviewConfirmGcp|InterviewConfirmMvp|InterviewConfirmProblem|InterviewConfirmSegment|bool|null
     */
    private static function findModel(int $stage, int $id)
    {
        if ($stage === StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return InterviewConfirmSegment::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return InterviewConfirmProblem::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_GCP) {
            return InterviewConfirmGcp::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_MVP) {
            return InterviewConfirmMvp::findOne($id);
        }
        return false;
    }


    /**
     * @param int $stage
     * @return InterviewConfirmGcp|InterviewConfirmMvp|InterviewConfirmProblem|InterviewConfirmSegment|bool
     */
    private static function getCreateModel(int $stage)
    {
        if ($stage === StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new InterviewConfirmSegment();
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new InterviewConfirmProblem();
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_GCP) {
            return new InterviewConfirmGcp();
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_MVP) {
            return new InterviewConfirmMvp();
        }
        return false;
    }


    /**
     * @param int $stage
     * @param int $id
     * @return RespondsGcp|RespondsMvp|RespondsProblem|RespondsSegment|bool|null
     */
    private static function getRespond(int $stage, int $id)
    {
        if ($stage === StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return RespondsSegment::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsProblem::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_GCP) {
            return RespondsGcp::findOne($id);
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_MVP) {
            return RespondsMvp::findOne($id);
        }
        return false;
    }


    /**
     * @param int $stage
     * @return bool|string
     */
    private static function getClassModel(int $stage)
    {
        if ($stage === StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return InterviewConfirmSegment::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return InterviewConfirmProblem::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_GCP) {
            return InterviewConfirmGcp::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_MVP) {
            return InterviewConfirmMvp::class;
        }
        return false;
    }


    /**
     * @param int $stage
     * @return false|string
     */
    private static function getClassAnswers(int $stage)
    {
        if ($stage === StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return AnswersQuestionsConfirmSegment::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return AnswersQuestionsConfirmProblem::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_GCP) {
            return AnswersQuestionsConfirmGcp::class;
        }

        if ($stage === StageConfirm::STAGE_CONFIRM_MVP) {
            return AnswersQuestionsConfirmMvp::class;
        }
        return false;
    }
}