<?php


namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\CacheForm;
use app\models\forms\CreateRespondGcpForm;
use app\models\forms\CreateRespondMvpForm;
use app\models\forms\CreateRespondProblemForm;
use app\models\forms\CreateRespondSegmentForm;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\forms\FormUpdateConfirmMvp;
use app\models\forms\FormUpdateConfirmProblem;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\forms\UpdateRespondProblemForm;
use app\models\forms\UpdateRespondGcpForm;
use app\models\forms\UpdateRespondMvpForm;
use app\models\forms\UpdateRespondSegmentForm;
use app\models\Gcps;
use app\models\Mvps;
use app\models\Problems;
use app\models\Projects;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\RespondsProblem;
use app\models\RespondsSegment;
use app\models\Segments;
use app\models\StageConfirm;
use Throwable;
use yii\base\ErrorException;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Контроллер с методами создания, редактирования и получения информации
 * о респондентах, которые проходят интервью при подтверждении гипотезы
 *
 * Class RespondsController
 * @package app\controllers
 */
class RespondsController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = self::findModel(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $confirm = $model->confirm;
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->userId == Yii::$app->user->id){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);
            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['create'])){

            $confirm = self::getConfirm(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->userId == Yii::$app->user->id){
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
     * Проверка доступа
     * к заполнению интервью
     * @param $stage
     * @param $id
     * @return array|bool
     */
    public function actionDataAvailability($stage, $id)
    {
        $count_models = 0; // Кол-во респондентов подтверждения
        $count_exist_data_respond = 0; // Кол-во респондентов, у кот-х заполнены данные
        $count_exist_data_descInterview = 0; // Кол-во респондентов, у кот-х существует интервью

        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {

            $count_models = RespondsSegment::find()->where(['confirm_id' => $id])->count();

            $count_exist_data_respond = RespondsSegment::find()->where(['confirm_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsSegment::find()->with('interview')
                ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
                ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_segment.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {

            $count_models = RespondsProblem::find()->where(['confirm_id' => $id])->count();

            $count_exist_data_respond = RespondsProblem::find()->where(['confirm_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsProblem::find()->with('interview')
                ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
                ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_problem.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {

            $count_models = RespondsGcp::find()->where(['confirm_id' => $id])->count();

            $count_exist_data_respond = RespondsGcp::find()->where(['confirm_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsGcp::find()->with('interview')
                ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
                ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {

            $count_models = RespondsMvp::find()->where(['confirm_id' => $id])->count();

            $count_exist_data_respond = RespondsMvp::find()->where(['confirm_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsMvp::find()->with('interview')
                ->leftJoin('interview_confirm_mvp', '`interview_confirm_mvp`.`respond_id` = `responds_mvp`.`id`')
                ->where(['confirm_id' => $id])->andWhere(['not', ['interview_confirm_mvp.id' => null]])->count();
        }


        if(Yii::$app->request->isAjax) {

            if (($count_exist_data_respond == $count_models) || ($count_exist_data_descInterview > 0)) {

                $response =  ['success' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * Сохранение кэша из формы
     * создания респондента
     * @param $stage
     * @param $id
     */
    public function actionSaveCacheCreationForm($stage, $id)
    {
        $confirm = self::getConfirm($stage, $id);
        $class = self::getClassCreateModel($stage);
        $cachePath = $class::getCachePath($confirm);
        $cacheName = 'formCreateRespondCache';

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

        $confirm = self::getConfirm($stage, $id);
        $model = self::getCreateModel($stage, $confirm);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('create', ['confirm' => $confirm, 'model' => $model])];
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
     * @throws NotFoundHttpException
     */
    public function actionCreate($stage, $id)
    {
        $confirm = self::getConfirm($stage, $id);
        $newRespond = $this::getCreateModel($stage, $confirm);
        $newRespond->setConfirmId($id);

        if ($newRespond->load(Yii::$app->request->post()))
        {
            if(Yii::$app->request->isAjax) {

                if ($confirm->checkingLimitCountRespond()) {

                    if ($newRespond->validate(['name'])) {

                        if ($newRespond = $newRespond->create()) {

                            // Обновление данных подтверждения
                            $confirm->setCountRespond(++$confirm->count_respond);
                            $confirm->save();

                            $responds = $confirm->responds;
                            $page = floor((count($responds) - 1) / 10) + 1;
                            $response = array();

                            if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
                                $segment = Segments::findOne($confirm->segmentId);
                                $project = Projects::findOne($segment->projectId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-segment/ajax_data_confirm', ['model' => ConfirmSegment::findOne($id), 'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($id), 'project' => $project]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
                                $problem = Problems::findOne($confirm->problemId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', ['model' => ConfirmProblem::findOne($id), 'problem' => $problem, 'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($id)]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {
                                $gcp = Gcps::findOne($confirm->gcpId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', ['model' => ConfirmGcp::findOne($id), 'gcp' => $gcp, 'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($id)]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {
                                $mvp = Mvps::findOne($confirm->mvpId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-mvp/ajax_data_confirm', ['model' => ConfirmMvp::findOne($id), 'mvp' => $mvp, 'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($id)]),
                                ];
                            }

                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {
                        $response = ['error' => true];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                } else {
                    $response = ['limit_count_respond' => true];
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
        $model = self::getUpdateModel($stage, $id);
        $confirm = $model->confirm;

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['model' => $model, 'confirm' => $confirm])];
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
     * @throws NotFoundHttpException
     */
    public function actionUpdate($stage, $id)
    {
        $model = self::getUpdateModel($stage, $id);
        $confirm = $model->confirm;

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->validate(['name'])){

                    if ($model->update()){

                        $response = ['confirm_id' => $confirm->id];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }

                }else{
                    $response = ['error' => true];
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
     * @return RespondsProblem|RespondsGcp|RespondsMvp|RespondsSegment|bool|null
     * @throws NotFoundHttpException
     */
    public function actionGetDataModel($stage, $id)
    {
        $model = self::findModel($stage, $id);

        if(Yii::$app->request->isAjax) {

            $response = $model;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @param $page
     * @return array|bool
     */
    public function actionGetQueryResponds($stage, $id, $page)
    {
        $confirm = self::getConfirm($stage, $id);
        $queryResponds = self::getQueryModels($stage, $id);
        $pagesResponds = new Pagination(['totalCount' => $queryResponds->count(), 'page' => ($page - 1), 'pageSize' => 10]);
        $pagesResponds->pageSizeParam = false; //убираем параметр $per-page
        $responds = $queryResponds->offset($pagesResponds->offset)->limit(10)->all();

        if(Yii::$app->request->isAjax) {

            $response = array();

            if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
                $response = ['ajax_data_responds' => $this->renderAjax('/responds/respondsForConfirmSegment', [
                    'confirm' => $confirm, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];

            } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
                $response = ['ajax_data_responds' => $this->renderAjax('/responds/respondsForConfirmProblem', [
                    'confirm' => $confirm, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];

            } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {
                $response = ['ajax_data_responds' => $this->renderAjax('/responds/respondsForConfirmGcp', [
                    'confirm' => $confirm, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];

            } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {
                $response = ['ajax_data_responds' => $this->renderAjax('/responds/respondsForConfirmMvp', [
                    'confirm' => $confirm, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];
            }

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
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete ($stage, $id)
    {
        $model = self::findModel($stage, $id);
        $confirm = $model->confirm;
        $hypothesis = $confirm->hypothesis;
        $project = $hypothesis->project;

        if (Yii::$app->request->isAjax){

            if ($confirm->count_respond == 1){

                $response = ['zero_value_responds' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }

            elseif ($confirm->count_respond == $confirm->count_positive){

                $response = ['number_less_than_allowed' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }

            else {

                if ($model->delete()) {

                    //Обновление данных подтверждения
                    $confirm->setCountRespond(--$confirm->count_respond);
                    $confirm->save();

                    $response = array();

                    if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
                        $response = [
                            'success' => true, 'confirm_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-segment/ajax_data_confirm', [
                                'model' => ConfirmSegment::findOne($model->confirmId), 'project' => $project,
                                'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
                        $response = [
                            'success' => true, 'confirm_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', [
                                'model' => ConfirmProblem::findOne($model->confirmId), 'problem' => $hypothesis,
                                'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {
                        $response = [
                            'success' => true, 'confirm_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', [
                                'model' => ConfirmGcp::findOne($model->confirmId), 'gcp' => $hypothesis,
                                'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {
                        $response = [
                            'success' => true, 'confirm_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-mvp/ajax_data_confirm', [
                                'model' => ConfirmMvp::findOne($model->confirmId), 'mvp' => $hypothesis,
                                'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($model->confirmId)]),
                        ];
                    }

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                return false;
            }
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return bool|ActiveQuery
     */
    private static function getQueryModels($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return RespondsSegment::find()->where(['confirm_id' => $id]);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsProblem::find()->where(['confirm_id' => $id]);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return RespondsGcp::find()->where(['confirm_id' => $id]);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return RespondsMvp::find()->where(['confirm_id' => $id]);
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return RespondsProblem|RespondsGcp|RespondsMvp|RespondsSegment|null
     * @throws NotFoundHttpException
     */
    private static function findModel($stage, $id)
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
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * @param $stage
     * @return bool|string
     */
    private static function getClassModel($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return RespondsSegment::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsProblem::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return RespondsGcp::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return RespondsMvp::class;
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


    /**
     * @param $stage
     * @param $confirm
     * @return CreateRespondSegmentForm|CreateRespondProblemForm|CreateRespondGcpForm|CreateRespondMvpForm|bool
     */
    private static function getCreateModel($stage, $confirm)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new CreateRespondSegmentForm($confirm);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new CreateRespondProblemForm($confirm);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new CreateRespondGcpForm($confirm);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new CreateRespondMvpForm($confirm);
        }
        return false;
    }


    /**
     * @param $stage
     * @return bool|string
     */
    private static function getClassCreateModel($stage)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return CreateRespondSegmentForm::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return CreateRespondProblemForm::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return CreateRespondGcpForm::class;
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return CreateRespondMvpForm::class;
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return UpdateRespondProblemForm|UpdateRespondGcpForm|UpdateRespondMvpForm|UpdateRespondSegmentForm|bool
     */
    private static function getUpdateModel($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new UpdateRespondSegmentForm($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new UpdateRespondProblemForm($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new UpdateRespondGcpForm($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new UpdateRespondMvpForm($id);
        }
        return false;
    }

}