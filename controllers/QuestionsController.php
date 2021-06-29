<?php


namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\forms\FormCreateQuestion;
use app\models\forms\FormUpdateQuestion;
use app\models\ConfirmSegment;
use app\models\QuestionsConfirmGcp;
use app\models\QuestionsConfirmMvp;
use app\models\QuestionsConfirmProblem;
use app\models\QuestionsConfirmSegment;
use app\models\StageConfirm;
use app\models\User;
use Throwable;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;
use Yii;

class QuestionsController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['create'])){

            $confirm = self::getConfirm(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif (in_array($action->id, ['delete']) || in_array($action->id, ['update'])){

            $question = self::getModel(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $confirm = $question->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if (($project->user_id == Yii::$app->user->id)  || User::isUserDev(Yii::$app->user->identity['username'])){

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
     * @return array|bool
     */
    public function actionCreate($stage, $id)
    {
        $form = new FormCreateQuestion();
        $form->setConfirmId($id);
        $model = self::getCreateModel($stage);

        if ($form->load(Yii::$app->request->post())){

            if(Yii::$app->request->isAjax) {

                if ($result = $form->create($model)){

                    $response = [
                        'model' => $result['model'],
                        'questions' => $result['questions'],
                        'queryQuestions' => $result['queryQuestions'],
                        'ajax_questions_confirm' => $this->renderAjax('list_questions', ['questions' => $result['questions']]),
                    ];
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
    public function actionGetFormUpdate ($stage, $id)
    {
        $model = self::getModel($stage, $id);
        $form = new FormUpdateQuestion($model);
        $confirm = $form->confirm;
        $questions = $confirm->questions;

        if(Yii::$app->request->isAjax) {

            $response = [
                'ajax_questions_confirm' => $this->renderAjax('list_questions', ['questions' => $questions]),
                'renderAjax' => $this->renderAjax('form_update', ['model' => $form]),
            ];
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
     */
    public function actionUpdate($stage, $id)
    {
        $model = self::getModel($stage, $id);
        $form = new FormUpdateQuestion($model);

        if ($form->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($result = $form->update()){

                    $response = [
                        'model' => $result['model'],
                        'questions' => $result['questions'],
                        'queryQuestions' => $result['queryQuestions'],
                        'ajax_questions_confirm' => $this->renderAjax('list_questions', ['questions' => $result['questions']]),
                    ];
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
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionChangeStatus($stage, $id)
    {
        $model = self::getModel($stage, $id);
        $model->changeStatus();

        if (Yii::$app->request->isAjax) {
            if ($model->update()){
                $response = true;
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete ($stage, $id)
    {
        $model = self::getModel($stage, $id);

        if(Yii::$app->request->isAjax) {

            if ($data = $model->deleteAndGetData()){

                $response = [
                    'questions' => $data['questions'],
                    'queryQuestions' => $data['queryQuestions'],
                    'ajax_questions_confirm' => $this->renderAjax('list_questions', ['questions' => $data['questions']]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return array|bool
     */
    public function actionGetQueryQuestions ($stage, $id)
    {
        $confirm = self::getConfirm($stage, $id);
        $questions = $confirm->questions;

        if(Yii::$app->request->isAjax) {
            $response = ['ajax_questions_confirm' => $this->renderAjax('list_questions', ['questions' => $questions])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return QuestionsConfirmGcp|QuestionsConfirmMvp|QuestionsConfirmProblem|QuestionsConfirmSegment|bool|null
     */
    private static function getModel($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return QuestionsConfirmSegment::findOne($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return QuestionsConfirmProblem::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return QuestionsConfirmGcp::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return QuestionsConfirmMvp::findOne($id);
        }
        return false;
    }


    /**
     * @param $stage
     * @return QuestionsConfirmGcp|QuestionsConfirmMvp|QuestionsConfirmProblem|QuestionsConfirmSegment|bool
     */
    private static function getCreateModel($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new QuestionsConfirmSegment();
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new QuestionsConfirmProblem();
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new QuestionsConfirmGcp();
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new QuestionsConfirmMvp();
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return ConfirmGcp|ConfirmMvp|ConfirmProblem|ConfirmSegment|bool|null
     */
    private static function getConfirm($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return ConfirmSegment::findOne($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return ConfirmProblem::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return ConfirmGcp::findOne($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return ConfirmMvp::findOne($id);
        }
        return false;
    }
}