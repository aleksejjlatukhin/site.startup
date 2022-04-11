<?php


namespace app\modules\client\controllers;

use app\models\Client;
use app\models\ClientSettings;
use app\models\ClientUser;
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

class UsersController extends AppClientController
{

    public $layout = '@app/modules/client/views/layouts/users';

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if ($action->id == 'index' || $action->id == 'admins' || $action->id == 'experts') {

            if (User::isUserAdminCompany(Yii::$app->user->identity['username'])) {

                return parent::beforeAction($action);

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif ($action->id == 'status-update' || $action->id == 'add-admin') {

            if (User::isUserAdminCompany(Yii::$app->user->identity['username'])) {

                if ($action->id == 'status-update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get('id'));
            /** @var ClientUser $clientUser */
            $clientUser = $user->clientUser;
            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);

            if ($user->id == Yii::$app->user->id || (User::isUserAdminCompany(Yii::$app->user->identity['username']) && Yii::$app->user->getId() == $clientSettings->getAdminId())) {

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }else{
            return parent::beforeAction($action);
        }
    }


    /**
     * Список проектантов организации
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = User::findOne(Yii::$app->user->getId());
        /**
         * @var ClientUser $clientUser
         * @var Client $client
         */
        $clientUser = $user->clientUser;
        $client = $clientUser->client;
        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('index',[
            'users' => $users,
            'pages' => $pages,
        ]);
    }


    /**
     * Получить данные для модального окна назначения трекера проектанту
     *
     * @param $id
     * @return array|bool
     */
    public function actionGetModalAddAdminToUser ($id)
    {
        if (Yii::$app->request->isAjax){

            $user = User::findOne($id);
            $admins = User::find()->with('clientUser')
                ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
                ->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM, 'status' => User::STATUS_ACTIVE])
                ->andWhere(['client_user.client_id' => $user->clientUser->getClientId()])
                ->all();
            foreach ($admins as $admin) {
                $admin->username = $admin->second_name . ' ' . $admin->first_name . ' ' . $admin->middle_name;
            }

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
     * Получить данные для модального окна изменения статуса пользователя
     *
     * @param $id
     * @return array|bool
     */
    public function actionGetModalUpdateStatus ($id)
    {
        $model = User::findOne($id);
        if (Yii::$app->request->isAjax){
            $response = ['renderAjax' => $this->renderAjax('get_modal_update_status', ['model' => $model])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Список трекеров организации
     *
     * @return string
     */
    public function actionAdmins()
    {
        $user = User::findOne(Yii::$app->user->getId());
        /**
         * @var ClientUser $clientUser
         * @var Client $client
         */
        $clientUser = $user->clientUser;
        $client = $clientUser->client;
        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_ADMIN, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();
        $clientId = (ClientUser::findOne(['user_id' => Yii::$app->user->id])->getClientId());

        return $this->render('admins',[
            'users' => $users,
            'pages' => $pages,
            'clientId' => $clientId,
        ]);
    }


    /**
     * Список экспертов организации
     *
     * @return string
     */
    public function actionExperts()
    {
        $user = User::findOne(Yii::$app->user->getId());
        /**
         * @var ClientUser $clientUser
         * @var Client $client
         */
        $clientUser = $user->clientUser;
        $client = $clientUser->client;
        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_EXPERT, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('experts',[
            'users' => $users,
            'pages' => $pages,
        ]);
    }


    /**
     * Изменение статуса пользователя
     *
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
                        //Создание беседы между трекером и админом организации
                        $model->createConversationMainAdmin();

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_EXPERT)) {
                        //Создание беседы между экспертом и админом организации
                        User::createConversationExpert($model->mainAdmin, $model);

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_USER)) {
                        //Создание беседы между трекером и проектантом
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
     * Назначение трекера проектанту
     *
     * @param $id
     * @param $id_admin
     * @return array|bool
     */
    public function actionAddAdmin ($id, $id_admin)
    {
        $model = User::findOne($id);
        $admin = User::findOne($id_admin);
        $admin->username = $admin->second_name.' '.mb_substr($admin->first_name, 0, 1).'.'.mb_substr($admin->middle_name, 0, 1).'.';

        if (Yii::$app->request->isAjax) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
        return false;
    }


    /**
     * Список проектантов для трекера организации
     *
     * @param int $id
     * @param null $page
     * @return string
     */
    public function actionGroup($id, $page = null)
    {
        $admin = User::findOne($id);
        /** @var ClientUser $clientUser */
        $clientUser = $admin->clientUser;
        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM, 'id_admin' => $id])
            ->andWhere(['client_user.client_id' => $clientUser->getClientId()])
            ->orderBy(['updated_at' => SORT_DESC]);
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
     * Обновить на странице данных для проверки онлайн пользователь или нет
     *
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
     * Удаление пользователя
     *
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
                        $response = ['error' => true, 'message' => 'При удалении трекера произошла ошибка, обратитесь в техподдержку'];
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
                    $response = ['error' => true, 'message' => 'При удалении проектанта произошла ошибка, обратитесь в техподдержку'];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif ($user->role === User::ROLE_EXPERT) {

                if ($user->removeAllDataUser()) {

                    $response = ['success' => true];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                else {
                    $response = ['error' => true, 'message' => 'При удалении эксперта произошла ошибка, обратитесь в техподдержку'];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }
        return false;
    }

}