<?php


namespace app\modules\admin\controllers;

use app\models\ConversationAdmin;
use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;

class UsersController extends AppAdminController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {
        if ($action->id == 'index') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'admins') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'status-update') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'status-update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'add-admin') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'add-admin') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get());

            if ($user->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }


    }


    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM]),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $users = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM])->all();
        $admins = User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM, 'status' => User::STATUS_ACTIVE])->all();

        foreach ($admins as $admin) {
            $admin->username = $admin->second_name . ' ' . $admin->first_name . ' ' . $admin->middle_name;
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'admins' => $admins,
            'users' => $users,
        ]);
    }


    public function actionAdmins()
    {
        $dataProvider = new ActiveDataProvider(['query' => User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM])]);

        return $this->render('admins',[
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionStatusUpdate ($id, $status)
    {

        $model = User::findOne($id);

        if ($status == 'active'){
            $model->status = User::STATUS_ACTIVE;

        }elseif ($status == 'delete'){
            $model->status = User::STATUS_DELETED;
        }

        if (Yii::$app->request->isAjax){

            if ($model->save()){

                if(($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_ADMIN)) {
                    //Создание беседы между админом и главным админом
                    $model->createConversationMainAdmin();

                } elseif(($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_USER)) {
                    //Создание беседы между админом и проектантом
                    $model->createConversationAdmin($model);
                }

                if(($model->status == User::STATUS_ACTIVE) && ($model->role != User::ROLE_DEV)) {
                    //Создание беседы между техподдержкой и пользователем
                    $model->createConversationDevelopment();
                }

                //Отправка письма на почту пользователю при изменении его статуса
                $model->sendEmailUserStatus();

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $model;
                return $model;

            }
        }
        return false;
    }



    public function actionAddAdmin ($id, $admin)
    {

        $model = User::findOne($id);

        $_admin_replace = User::findOne(['role' => User::ROLE_ADMIN, 'status' => User::STATUS_ACTIVE, 'confirm' => User::CONFIRM]);

        $_admin = User::findOne([
            'id' => $admin,
        ]);

        if ($_admin) {

            $model->id_admin = $admin;
        }else {
            $model->id_admin = $_admin_replace->id;
        }

        if (Yii::$app->request->isAjax){

            if ($model->save()){

                $conversation = ConversationAdmin::findOne([
                    'user_id' => $model->id,
                    ]);

                if ($conversation) {

                    $conversation->admin_id = $model->id_admin;
                    $conversation->save();
                }

                $response = [
                    'model' => $model,
                    'admin' => $_admin,
                    'admin_replace' => $_admin_replace,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            }
        }
        return false;
    }


    public function actionGroup($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => $id]),
            /*'pagination' => [
                'pageSize' => 5,
            ],*/
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $users = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => Yii::$app->user->id])->all();
        foreach ($users as $user){

            if (!empty($user->projects)){

                $projects_updated_at = [];

                foreach ($user->projects as $project) {

                    $projects_updated_at[] = $project->updated_at;
                }

                if (max($projects_updated_at) > $user->updated_at){

                    $user->updated_at = max($projects_updated_at);
                    $user->save();
                }

            }
        }

        return $this->render('group',[
            'dataProvider' => $dataProvider,

        ]);
    }

}