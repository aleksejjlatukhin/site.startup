<?php

namespace app\controllers;

use app\models\ConfirmProblem;
use app\models\forms\CreateRespondConfirmForm;
use app\models\forms\FormUpdateConfirmProblem;
use app\models\GenerationProblem;
use app\models\Projects;
use app\models\forms\UpdateRespondConfirmForm;
use app\models\Segment;
use app\models\User;
use Yii;
use app\models\RespondsConfirm;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class RespondsConfirmController extends AppUserPartController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = RespondsConfirm::findOne(Yii::$app->request->get());
            $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
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

            $confirmProblem = ConfirmProblem::findOne(Yii::$app->request->get());
            $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
            $project = Projects::findOne(['id' => $problem->project->id]);

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

        $count_models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->count();

        //Кол-во респондентов, у кот-х заполнены данные
        $count_exist_data_respond = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        //Кол-во респондентов, у кот-х существует интервью
        $count_exist_data_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

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
        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $data = $_POST; //Массив, который будем записывать в кэш
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateRespond/';
            $key = 'formCreateRespondCache'; //Формируем ключ
            $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
        }
    }


    public function actionGetDataCreateForm($id)
    {
        $confirm_problem = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $confirm_problem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);
        $model = new CreateRespondConfirmForm();
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения

        if(Yii::$app->request->isAjax) {

            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
                '/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/formCreateRespond/';
            $cache_form_creation = $cache->get('formCreateRespondCache');

            if ($cache_form_creation) { //Если существует кэш, то добавляем его к полям модели CreateRespondConfirmForm
                foreach ($cache_form_creation['CreateRespondConfirmForm'] as $key => $value) {
                    $model[$key] = $value;
                }
            }

            $response = ['renderAjax' => $this->renderAjax('create', ['confirm_problem' => $confirm_problem, 'model' => $model])];
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
        $confirmProblem = ConfirmProblem::findOne($id);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $count_models = RespondsConfirm::find()->where(['confirm_problem_id' => $id])->count();
        $limit_count_respond = RespondsConfirm::LIMIT_COUNT;
        $newRespond = new CreateRespondConfirmForm();
        $newRespond->confirm_problem_id = $id;

        if ($newRespond->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($count_models < $limit_count_respond) {

                    if ($newRespond->validate(['name'])) {

                        if ($newRespond = $newRespond->create()) {

                            $responds = RespondsConfirm::findAll(['confirm_problem_id' => $id]);
                            $page = floor((count($responds) - 1) / 10) + 1;

                            $response =  [
                                'newRespond' => $newRespond,
                                'responds' => $responds,
                                'page' => $page,
                                'confirm_problem_id' => $id,
                                'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', ['model' => ConfirmProblem::findOne($id), 'problem' => $problem, 'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($id)]),
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
                }  else {
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
        $model = new UpdateRespondConfirmForm($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['model' => $model, 'problem' => $problem])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return RespondsConfirm|array
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = new UpdateRespondConfirmForm($id);
        $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->validate(['name'])){

                    if ($model->updateRespond()){

                        $response = ['confirm_problem_id' => $confirmProblem->id];
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
     * @return RespondsConfirm
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
        $model = ConfirmProblem::findOne($id);
        $queryResponds = RespondsConfirm::find()->where(['confirm_problem_id' => $id]);
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
        $confirmProblem = ConfirmProblem::findOne(['id' => $model->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $count_responds = RespondsConfirm::find()->where(['confirm_problem_id' => $confirmProblem->id])->count();

        if (Yii::$app->request->isAjax){

            if ($count_responds == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }


            elseif ($confirmProblem->count_respond == $confirmProblem->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            else {

                if ($model->delete()) {

                    $response = [
                        'success' => true,
                        'confirm_problem_id' => $model->confirm_problem_id,
                        'ajax_data_confirm' => $this->renderAjax('/confirm-problem/ajax_data_confirm', ['model' => ConfirmProblem::findOne($model->confirm_problem_id), 'problem' => $problem, 'formUpdateConfirmProblem' => new FormUpdateConfirmProblem($model->confirm_problem_id)]),
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
     * Finds the RespondsConfirm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RespondsConfirm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RespondsConfirm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
