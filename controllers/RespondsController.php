<?php


namespace app\controllers;

use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\forms\CacheForm;
use app\models\forms\CreateRespondConfirmForm;
use app\models\forms\CreateRespondForm;
use app\models\forms\CreateRespondGcpForm;
use app\models\forms\CreateRespondMvpForm;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\forms\FormUpdateConfirmMvp;
use app\models\forms\FormUpdateConfirmProblem;
use app\models\forms\FormUpdateConfirmSegment;
use app\models\forms\UpdateRespondConfirmForm;
use app\models\forms\UpdateRespondForm;
use app\models\forms\UpdateRespondGcpForm;
use app\models\forms\UpdateRespondMvpForm;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\Projects;
use app\models\Respond;
use app\models\RespondsConfirm;
use app\models\RespondsGcp;
use app\models\RespondsMvp;
use app\models\Segment;
use app\models\StageConfirm;
use app\models\User;
use Throwable;
use yii\base\ErrorException;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class RespondsController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws HttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = self::findModel(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $confirm = $model->confirm;
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

            $confirm = self::getConfirm(Yii::$app->request->get('stage'), Yii::$app->request->get('id'));
            $hypothesis = $confirm->hypothesis;
            $project = $hypothesis->project;

            /*Ограничение доступа к проэктам пользователя*/
            if ($project->userId == Yii::$app->user->id || User::isUserAdmin(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

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

            $count_models = Respond::find()->where(['interview_id' => $id])->count();

            $count_exist_data_respond = Respond::find()->where(['interview_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = Respond::find()->with('descInterview')
                ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
                ->where(['interview_id' => $id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {

            $count_models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->count();

            $count_exist_data_respond = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsConfirm::find()->with('descInterview')
                ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
                ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {

            $count_models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->count();

            $count_exist_data_respond = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsGcp::find()->with('descInterview')
                ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
                ->where(['confirm_gcp_id' => $id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();

        } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {

            $count_models = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->count();

            $count_exist_data_respond = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->andWhere(['not', ['info_respond' => '']])
                ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

            $count_exist_data_descInterview = RespondsMvp::find()->with('descInterview')
                ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
                ->where(['confirm_mvp_id' => $id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();
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
                                $segment = Segment::findOne($confirm->segmentId);
                                $project = Projects::findOne($segment->projectId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'interview_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/interview/ajax_data_confirm', ['model' => Interview::findOne($id), 'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($id), 'project' => $project]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
                                $problem = GenerationProblem::findOne($confirm->problemId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_problem_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', ['model' => ConfirmProblem::findOne($id), 'problem' => $problem, 'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($id)]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {
                                $gcp = Gcp::findOne($confirm->gcpId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_gcp_id' => $id,
                                    'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', ['model' => ConfirmGcp::findOne($id), 'gcp' => $gcp, 'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($id)]),
                                ];

                            } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {
                                $mvp = Mvp::findOne($confirm->mvpId);
                                $response =  [
                                    'newRespond' => $newRespond,
                                    'responds' => $responds,
                                    'page' => $page,
                                    'confirm_mvp_id' => $id,
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
     * @return Respond|RespondsConfirm|RespondsGcp|RespondsMvp|bool|null
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
                            'success' => true, 'interview_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/interview/ajax_data_confirm', [
                                'model' => Interview::findOne($model->confirmId), 'project' => $project,
                                'formUpdateConfirmSegment' => new FormUpdateConfirmSegment($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
                        $response = [
                            'success' => true, 'confirm_problem_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', [
                                'model' => ConfirmProblem::findOne($model->confirmId), 'problem' => $hypothesis,
                                'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_GCP) {
                        $response = [
                            'success' => true, 'confirm_gcp_id' => $model->confirmId,
                            'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', [
                                'model' => ConfirmGcp::findOne($model->confirmId), 'gcp' => $hypothesis,
                                'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($model->confirmId)]),
                        ];

                    } elseif ($stage == StageConfirm::STAGE_CONFIRM_MVP) {
                        $response = [
                            'success' => true, 'confirm_mvp_id' => $model->confirmId,
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
    public static function getQueryModels($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return Respond::find()->where(['interview_id' => $id]);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsConfirm::find()->where(['confirm_problem_id' => $id]);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return RespondsGcp::find()->where(['confirm_gcp_id' => $id]);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return RespondsMvp::find()->where(['confirm_mvp_id' => $id]);
        }
        return false;
    }


    /**
     * @param $stage
     * @param $id
     * @return Respond|RespondsConfirm|RespondsGcp|RespondsMvp|null
     * @throws NotFoundHttpException
     */
    private static function findModel($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return Respond::findOne($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsConfirm::findOne($id);
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
            return Respond::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return RespondsConfirm::class;
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
     * @return ConfirmGcp|ConfirmMvp|ConfirmProblem|Interview|bool|null
     */
    private static function getConfirm($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return Interview::findOne($id);
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
     * @return CreateRespondConfirmForm|CreateRespondForm|CreateRespondGcpForm|CreateRespondMvpForm|bool
     */
    private static function getCreateModel($stage, $confirm)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new CreateRespondForm($confirm);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new CreateRespondConfirmForm($confirm);
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
            return CreateRespondForm::class;
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return CreateRespondConfirmForm::class;
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
     * @return UpdateRespondConfirmForm|UpdateRespondForm|UpdateRespondGcpForm|UpdateRespondMvpForm|bool
     */
    private static function getUpdateModel($stage, $id)
    {
        if ($stage == StageConfirm::STAGE_CONFIRM_SEGMENT) {
            return new UpdateRespondForm($id);
        } elseif($stage == StageConfirm::STAGE_CONFIRM_PROBLEM) {
            return new UpdateRespondConfirmForm($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_GCP) {
            return new UpdateRespondGcpForm($id);
        }elseif($stage == StageConfirm::STAGE_CONFIRM_MVP) {
            return new UpdateRespondMvpForm($id);
        }
        return false;
    }

}