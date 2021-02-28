<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmGcp;
use app\models\ConfirmGcp;
use app\models\DescInterviewGcp;
use app\models\forms\CreateRespondGcpForm;
use app\models\forms\FormUpdateConfirmGcp;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\RespondsGcp;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use app\models\forms\UpdateRespondGcpForm;


class RespondsGcpController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = RespondsGcp::findOne(Yii::$app->request->get());
            $confirmGcp = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);
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

        $confirmGcp = ConfirmGcp::findOne(Yii::$app->request->get());
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $project = Projects::findOne(['id' => $gcp->project->id]);

        /*Ограничение доступа к проэктам пользователя*/
        if (($project->user_id == Yii::$app->user->id) || User::isUserAdmin(Yii::$app->user->identity['username'])
            || User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

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
     * @return array
     */
    public function actionDataAvailability($id)
    {

        $count_models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->count();

        //Кол-во респондентов, у кот-х заполнены данные
        $count_exist_data_respond = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        //Кол-во респондентов, у кот-х существует анкета
        $count_exist_data_descInterview = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();


        if(Yii::$app->request->isAjax) {

            if (($count_exist_data_respond == $count_models) || ($count_exist_data_descInterview > 0)) {

                $response =  ['success' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }else{

                $response = ['error' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
    }


    public function actionSaveCacheCreationForm($id)
    {
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateRespond/';
            $key = 'formCreateRespondCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    public function actionGetDataCreateForm($id)
    {
        $confirm_gcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirm_gcp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new CreateRespondGcpForm();
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
                '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/formCreateRespond/';
            $cache_form_creation = $cache->get('formCreateRespondCache');

            if ($cache_form_creation) { //Если существует кэш, то добавляем его к полям модели CreateRespondGcpForm
                foreach ($cache_form_creation['CreateRespondGcpForm'] as $key => $value) {
                    $model[$key] = $value;
                }
            }

            $response = ['renderAjax' => $this->renderAjax('create', ['confirm_gcp' => $confirm_gcp, 'model' => $model])];
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
     */
    public function actionCreate($id)
    {
        $confirmGcp = ConfirmGcp::findOne($id);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);
        $count_models = RespondsGcp::find()->where(['confirm_gcp_id' => $id])->count();
        $limit_count_respond = RespondsGcp::LIMIT_COUNT;
        $newRespond = new CreateRespondGcpForm();
        $newRespond->confirm_gcp_id = $id;

        if ($newRespond->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($count_models < $limit_count_respond) {

                    if ($newRespond->validate(['name'])) {

                        if ($newRespond = $newRespond->create()) {

                            $responds = RespondsGcp::findAll(['confirm_gcp_id' => $id]);
                            $page = floor((count($responds) - 1) / 10) + 1;

                            $response =  [
                                'newRespond' => $newRespond,
                                'responds' => $responds,
                                'page' => $page,
                                'confirm_gcp_id' => $id,
                                'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', ['model' => ConfirmGcp::findOne($id), 'gcp' => $gcp, 'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($id)]),
                            ];

                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {
                        $response = ['error' => true];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                } else {
                    $response = ['limit_count_respond' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }

    }


    public function actionGetDataUpdateForm($id)
    {
        $model = new UpdateRespondGcpForm($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);
        $gcp = Gcp::findOne(['id' => $confirmGcp->gcp_id]);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['model' => $model, 'gcp' => $gcp])];
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
        $model = new UpdateRespondGcpForm($id);
        $confirmGcp = ConfirmGcp::findOne(['id' => $model->confirm_gcp_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->validate(['name'])){

                    if ($model->updateRespond()){

                        $response = ['confirm_gcp_id' => $confirmGcp->id];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }else{

                    $response = ['error' => true];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
    }


    /**
     * @param $id
     * @return RespondsGcp
     * @throws NotFoundHttpException
     */
    public function actionGetDataModel($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isAjax) {

            $response = $model;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    public function actionGetQueryResponds($id, $page)
    {
        $model = ConfirmGcp::findOne($id);
        $queryResponds = RespondsGcp::find()->where(['confirm_gcp_id' => $id]);
        $pagesResponds = new Pagination(['totalCount' => $queryResponds->count(), 'page' => ($page - 1), 'pageSize' => 10]);
        $pagesResponds->pageSizeParam = false; //убираем параметр $per-page
        $responds = $queryResponds->offset($pagesResponds->offset)->limit(10)->all();

        if(Yii::$app->request->isAjax) {

            $response = ['ajax_data_responds' => $this->renderAjax('view_ajax', ['model' => $model, 'responds' => $responds, 'pagesResponds' => $pagesResponds])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete ($id) {

        $model = $this->findModel($id);
        $confirmGcp = ConfirmGcp::find()->where(['id' => $model->confirm_gcp_id])->one();
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $count_responds = RespondsGcp::find()->where(['confirm_gcp_id' => $confirmGcp->id])->count();

        if (Yii::$app->request->isAjax){

            if ($count_responds == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }


            elseif ($confirmGcp->count_respond == $confirmGcp->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            else {

                if ($model->delete()) {

                    $response = [
                        'success' => true,
                        'confirm_gcp_id' => $model->confirm_gcp_id,
                        'ajax_data_confirm' => $this->renderAjax('/confirm-gcp/ajax_data_confirm', ['model' => ConfirmGcp::findOne($model->confirm_gcp_id), 'gcp' => $gcp, 'formUpdateConfirmGcp' => new FormUpdateConfirmGcp($model->confirm_gcp_id)]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
                return false;
            }
        }
        return false;
    }

    /**
     * Finds the RespondsGcp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RespondsGcp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RespondsGcp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
