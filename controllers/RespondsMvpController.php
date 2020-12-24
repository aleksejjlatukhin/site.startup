<?php

namespace app\controllers;

use app\models\AnswersQuestionsConfirmMvp;
use app\models\ConfirmMvp;
use app\models\DescInterviewMvp;
use app\models\forms\CreateRespondMvpForm;
use app\models\forms\FormUpdateConfirmMvp;
use app\models\Mvp;
use app\models\Projects;
use app\models\forms\UpdateRespondMvpForm;
use app\models\User;
use Yii;
use app\models\RespondsMvp;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;


class RespondsMvpController extends AppController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['update']) || in_array($action->id, ['delete'])){

            $model = RespondsMvp::findOne(Yii::$app->request->get());
            $confirmMvp = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);
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

            $confirmMvp = ConfirmMvp::findOne(Yii::$app->request->get());
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



    /**
     * @param $id
     * @return array
     */
    public function actionDataAvailability($id)
    {

        $count_models = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->count();

        //Кол-во респондентов, у кот-х заполнены данные
        $count_exist_data_respond = RespondsMvp::find()->where(['confirm_mvp_id' => $id])
            ->andWhere(['not', ['info_respond' => '']])->count();

        //Кол-во респондентов, у кот-х существует анкета
        $count_exist_data_descInterview = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

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


    public function actionGetDataCreateForm($id)
    {
        $confirm_mvp = ConfirmMvp::findOne($id);
        $model = new CreateRespondMvpForm();

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('create', ['confirm_mvp' => $confirm_mvp, 'model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionCreate($id)
    {
        $confirmMvp = ConfirmMvp::findOne($id);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $count_models = RespondsMvp::find()->where(['confirm_mvp_id' => $id])->count();
        $limit_count_respond = RespondsMvp::LIMIT_COUNT;
        $newRespond = new CreateRespondMvpForm();
        $newRespond->confirm_mvp_id = $id;

        if ($newRespond->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($count_models < $limit_count_respond) {

                    if ($newRespond->validate(['name'])) {

                        if ($newRespond = $newRespond->create()) {

                            $newRespond->addAnswersForNewRespond();

                            $confirmMvp->count_respond = $confirmMvp->count_respond + 1;
                            $confirmMvp->save();

                            $responds = RespondsMvp::findAll(['confirm_mvp_id' => $id]);
                            $page = floor((count($responds) - 1) / 10) + 1;

                            $response =  [
                                'newRespond' => $newRespond,
                                'responds' => $responds,
                                'page' => $page,
                                'confirm_mvp_id' => $id,
                                'ajax_data_confirm' => $this->renderAjax('/confirm-mvp/ajax_data_confirm', ['model' => ConfirmMvp::findOne($id), 'mvp' => $mvp, 'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($id)]),
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
        $model = new UpdateRespondMvpForm($id);

        if(Yii::$app->request->isAjax) {

            $response = ['renderAjax' => $this->renderAjax('update', ['model' => $model])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = new UpdateRespondMvpForm($id);
        $confirmMvp = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->validate(['name'])){

                    if ($model->updateRespond()){

                        $response = ['confirm_mvp_id' => $confirmMvp->id];
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
     * @return RespondsMvp
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
        $model = ConfirmMvp::findOne($id);
        $queryResponds = RespondsMvp::find()->where(['confirm_mvp_id' => $id]);
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
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $descInterview = DescInterviewMvp::findOne(['responds_mvp_id' => $model->id]);
        $answers = AnswersQuestionsConfirmMvp::findAll(['respond_id' => $id]);
        $confirmMvp = ConfirmMvp::findOne(['id' => $model->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $count_responds = RespondsMvp::find()->where(['confirm_mvp_id' => $confirmMvp->id])->count();

        if (Yii::$app->request->isAjax){

            if ($count_responds == 1){

                $response = ['zero_value_responds' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            elseif ($confirmMvp->count_respond == $confirmMvp->count_positive){

                $response = ['number_less_than_allowed' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            else {

                if ($descInterview) {
                    $descInterview->delete();
                }

                foreach ($answers as $answer){
                    $answer->delete();
                }

                if ($model->delete()) {

                    $confirmMvp->count_respond = $confirmMvp->count_respond - 1;
                    $confirmMvp->save();
                }

                $response = [
                    'success' => true,
                    'confirm_mvp_id' => $model->confirm_mvp_id,
                    'ajax_data_confirm' => $this->renderAjax('/confirm-mvp/ajax_data_confirm', ['model' => ConfirmMvp::findOne($model->confirm_mvp_id), 'mvp' => $mvp, 'formUpdateConfirmMvp' => new FormUpdateConfirmMvp($model->confirm_mvp_id)]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

        }
        return false;

    }

    /**
     * Finds the RespondsMvp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RespondsMvp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RespondsMvp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
