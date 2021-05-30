<?php


namespace app\modules\admin\controllers;

use app\models\ConversationAdmin;
use app\models\User;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

class UsersController extends AppAdminController
{

    public $layout = '@app/modules/admin/views/layouts/users';

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if ($action->id == 'index') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'admins') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'status-update') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'status-update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'add-admin') {

            if (User::isUserDev(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($action->id == 'add-admin') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get('id'));

            if ($user->id == Yii::$app->user->id || User::isUserDev(Yii::$app->user->identity['username'])
                || User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }


    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $countUsersOnPage = 20;
        $query = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM])->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('index',[
            'users' => $users,
            'pages' => $pages,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetModalAddAdminToUser ($id)
    {
        $user = User::findOne($id);
        $admins = User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM, 'status' => User::STATUS_ACTIVE])->all();
        foreach ($admins as $admin) {$admin->username = $admin->second_name . ' ' . $admin->first_name . ' ' . $admin->middle_name;}

        if (Yii::$app->request->isAjax){

            $response = [
                'renderAjax' => $this->renderAjax('get_modal_add_admin_to_user', ['user' => $user, 'admins' => $admins]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetModalUpdateStatus ($id)
    {
        $model = User::findOne($id);

        if (Yii::$app->request->isAjax){

            $response = [
                'renderAjax' => $this->renderAjax('get_modal_update_status', ['model' => $model]),
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @return string
     */
    public function actionAdmins()
    {
        $countUsersOnPage = 20;
        $query = User::find()->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM])->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('admins',[
            'users' => $users,
            'pages' => $pages,
        ]);
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionStatusUpdate ($id)
    {
        $model = User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->save()) {

                    if (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_ADMIN)) {
                        //Создание беседы между админом и главным админом
                        $model->createConversationMainAdmin();

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_USER)) {
                        //Создание беседы между админом и проектантом
                        $model->createConversationAdmin($model);
                    }

                    if (($model->status == User::STATUS_ACTIVE) && ($model->role != User::ROLE_DEV)) {
                        //Создание беседы между техподдержкой и пользователем
                        $model->createConversationDevelopment();
                    }

                    //Отправка письма на почту пользователю при изменении его статуса
                    $model->sendEmailUserStatus();

                    $response = ['model' => $model];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;

                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @param $id_admin
     * @return array|bool
     */
    public function actionAddAdmin ($id, $id_admin)
    {
        $model = User::findOne($id);
        $admin = User::findOne($id_admin);
        $admin->username = $admin->second_name.' '.mb_substr($admin->first_name, 0, 1).'.'.mb_substr($admin->middle_name, 0, 1).'.';

        if ($model->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax) {

                if ($model->save()) {

                    $conversation = ConversationAdmin::findOne(['user_id' => $model->id]);
                    if ($conversation) {
                        $conversation->admin_id = $model->id_admin;
                        $conversation->save();
                    }

                    $response = ['user' => $model, 'admin' => $admin];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;

                }
            }
        }
        return false;
    }


    /**
     * @param $id
     * @param null $page
     * @return string
     */
    public function actionGroup($id, $page = null)
    {
        $admin = User::findOne($id);
        $countUsersOnPage = 20;
        $query = User::find()->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => $id])->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('group',[
            'admin' => $admin,
            'users' => $users,
            'pages' => $pages,
        ]);

    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionUpdateDataColumnUser ($id)
    {
        $user = User::findOne($id);

        if (Yii::$app->request->isAjax){

            $response = ['renderAjax' => $this->renderAjax('update_column_user', ['user' => $user])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    public function actionUserDelete ($id)
    {
        $user = User::findOne($id);

        if (Yii::$app->request->isAjax) {

            if ($user->role === User::ROLE_ADMIN) {

                $subscribers = User::findAll(['id_admin' => $id]);

                if ($subscribers) {

                    $response = ['error' => true, 'message' => 'Запрещено удаление трекера, у которого есть пользователи!'];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                else {
                    if ($user->removeAllDataUser()) {

                        $response = ['success' => true];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                    else {
                        $response = ['error' => true, 'message' => 'При удалении пользователя произошла ошибка, обратитесь в техподдержку'];
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
            elseif ($user->role === User::ROLE_USER) {

                if ($user->removeAllDataUser()) {

                    $response = ['success' => true];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                else {
                    $response = ['error' => true, 'message' => 'При удалении пользователя произошла ошибка, обратитесь в техподдержку'];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }

}