<?php


namespace app\modules\admin\controllers;


use app\models\ConfirmMvp;
use app\models\ConversationAdmin;
use app\models\Gcp;
use app\models\GenerationProblem;
use app\models\Interview;
use app\models\Mvp;
use app\models\PasswordChangeForm;
use app\models\ProfileForm;
use app\models\Projects;
use app\models\Segment;
use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;

class UsersController extends AppAdminController
{

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

        }elseif ($action->id == 'profile') {

            $user = User::findOne(Yii::$app->request->get());
            $admin = User::findOne(['id' => $user->id_admin]);

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'profile-admin') {

            $admin = User::findOne(Yii::$app->request->get());

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (($action->id == 'update-profile') || ($action->id == 'change-password')) {

            $admin = User::findOne(Yii::$app->request->get());

            if ($admin->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])) {

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
        foreach ($users as $user){

            if (!empty($user->projects)){

                $project_update_at = [];

                foreach ($user->projects as $project) {

                    $project_updated_at[] = $project->updated_at;
                }

                if (max($project_updated_at) > $user->updated_at){

                    $user->updated_at = max($project_updated_at);
                    $user->save();
                }

            }
        }


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



    public function actionProfile($id)
    {
        $user = User::findOne($id);

        return $this->render('profile',[

            'user' => $user,
        ]);
    }



    public function actionProfileAdmin ($id)
    {
        $admin = User::findOne($id);

        if (!(($admin->role == User::ROLE_ADMIN) || ($admin->role == User::ROLE_MAIN_ADMIN)  || ($admin->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $users = User::find()->where(['id_admin' => $id])->all();

        $countProjects = 0;

        foreach ($users as $user) {

            $countProjects += count($user->projects);
        }


        return $this->render('profile-admin',[

            'admin' => $admin,
            'users' => $users,
            'countProjects' => $countProjects,
        ]);
    }



    public function actionUpdateProfile($id)
    {

        $user = User::findOne($id);

        if (!(($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $model = new ProfileForm();

        $model->second_name = $user->second_name;
        $model->first_name = $user->first_name;
        $model->middle_name = $user->middle_name;
        $model->telephone = $user->telephone;
        $model->username = $user->username;
        $model->email = $user->email;

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->update()){

                return $this->redirect(['/admin/users/profile-admin', 'id' => $id]);
            }
        }

        return $this->render('update-profile', [
            'user' => $user,
            'model' => $model,
        ]);

    }

    public function actionChangePassword($id)
    {

        $user = User::findOne($id);

        if (!(($user->role == User::ROLE_ADMIN) || ($user->role == User::ROLE_MAIN_ADMIN)  || ($user->role == User::ROLE_DEV))) {

            throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
        }

        $model = new PasswordChangeForm($user, []);

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($model->changePassword()){

                return $this->redirect(['/admin/users/profile-admin', 'id' => $id]);
            }
        }

        return $this->render('change-password', [
            'user' => $user,
            'model' => $model,
        ]);
    }

}