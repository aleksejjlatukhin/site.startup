<?php


namespace app\modules\admin\controllers;

use app\models\Client;
use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\ConversationAdmin;
use app\models\User;
use app\modules\admin\models\ConversationManager;
use Yii;
use yii\data\Pagination;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Контроллер с методами для редактирования и получения информации по пользователям системы
 *
 * Class UsersController
 * @package app\modules\admin\controllers
 */
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
        $currentUser = User::findOne(Yii::$app->user->getId());
        /** @var ClientUser $currentClientUser */
        $currentClientUser = $currentUser->clientUser;

        if (in_array($action->id, ['index', 'admins', 'experts'])) {

            if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                if (Yii::$app->request->get('id')) {

                    $client = Client::findOne(Yii::$app->request->get('id'));

                    if ($currentClientUser->getClientId() == $client->getId()) {

                        return parent::beforeAction($action);

                    } elseif ($client->findSettings()->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {

                        return parent::beforeAction($action);

                    } else {

                        throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                    }
                }

                return parent::beforeAction($action);

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif ($action->id == 'status-update') {

            if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                if ($action->id == 'status-update') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif ($action->id == 'add-admin') {

            if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {

                if ($action->id == 'add-admin') {
                    // ОТКЛЮЧАЕМ CSRF
                    $this->enableCsrfValidation = false;
                }

                return parent::beforeAction($action);

            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } elseif ($action->id == 'group') {

            $user = User::findOne(Yii::$app->request->get('id'));
            /** @var ClientUser $clientUser */
            $clientUser = $user->clientUser;
            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);
            $admin = User::findOne($clientSettings->getAdminId());

            if (User::isUserMainAdmin($admin->getUsername())) {
                if ($user->getId() == $currentUser->getId() || User::isUserDev($currentUser->getUsername())
                    || User::isUserMainAdmin($currentUser->getUsername())) {
                    return parent::beforeAction($action);
                } else{
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            } elseif (User::isUserAdminCompany($admin->getUsername()) && $clientSettings->getAccessAdmin() == ClientSettings::ACCESS_ADMIN_TRUE) {
                if (User::isUserDev($currentUser->getUsername()) || User::isUserMainAdmin($currentUser->getUsername())) {
                    return parent::beforeAction($action);
                } else {
                    throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
                }
            } else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        } else{
            return parent::beforeAction($action);
        }


    }


    /**
     * Список проектантов организации
     *
     * @param null|int $id
     * @return string
     */
    public function actionIndex($id = null)
    {
        if ($id) {
            $client = Client::findOne($id);
        } else {
            $user = User::findOne(Yii::$app->user->getId());
            /**
             * @var ClientUser $clientUser
             * @var Client $client
             */
            $clientUser = $user->clientUser;
            $client = $clientUser->client;
        }

        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_USER, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        if ($id) {
            return $this->render('index_company',[
                'client' => $client,
                'users' => $users,
                'pages' => $pages,
            ]);
        } else {
            return $this->render('index',[
                'users' => $users,
                'pages' => $pages,
            ]);
        }
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
            $response = ['renderAjax' => $this->renderAjax('get_modal_update_status', ['model' => $model]),];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * Список трекеров организации
     *
     * @param null|int $id
     * @return string
     */
    public function actionAdmins($id = null)
    {
        if ($id) {
            $client = Client::findOne($id);
        } else {
            $user = User::findOne(Yii::$app->user->getId());
            /**
             * @var ClientUser $clientUser
             * @var Client $client
             */
            $clientUser = $user->clientUser;
            $client = $clientUser->client;
        }

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

        if ($id) {
            return $this->render('admins_company',[
                'client' => $client,
                'users' => $users,
                'pages' => $pages,
                'clientId' => $clientId,
            ]);
        } else {
            return $this->render('admins',[
                'users' => $users,
                'pages' => $pages,
                'clientId' => $clientId,
            ]);
        }
    }


    /**
     * Список экспертов организации
     *
     * @param null|int $id
     * @return string
     */
    public function actionExperts($id = null)
    {
        if ($id) {
            $client = Client::findOne($id);
        } else {
            $user = User::findOne(Yii::$app->user->getId());
            /**
             * @var ClientUser $clientUser
             * @var Client $client
             */
            $clientUser = $user->clientUser;
            $client = $clientUser->client;
        }

        $countUsersOnPage = 20;
        $query = User::find()->with('clientUser')
            ->leftJoin('client_user', '`client_user`.`user_id` = `user`.`id`')
            ->where(['role' => User::ROLE_EXPERT, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        if ($id) {
            return $this->render('experts_company',[
                'client' => $client,
                'users' => $users,
                'pages' => $pages,
            ]);
        } else {
            return $this->render('experts',[
                'users' => $users,
                'pages' => $pages,
            ]);
        }
    }


    /**
     * Список экспертов организации
     *
     * @return string
     */
    public function actionManagers()
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
            ->where(['role' => User::ROLE_MANAGER, 'confirm' => User::CONFIRM])
            ->andWhere(['client_user.client_id' => $client->getId()])
            ->orderBy(['updated_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $countUsersOnPage, ]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();

        return $this->render('managers',[
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
                        //Создание беседы между трекером и главным админом
                        $model->createConversationMainAdmin();

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_EXPERT)) {
                        //Создание беседы между экспертом и главным админом
                        User::createConversationExpert($model->mainAdmin, $model);

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_USER)) {
                        //Создание беседы между трекером и проектантом
                        $model->createConversationAdmin($model);

                    } elseif (($model->status == User::STATUS_ACTIVE) && ($model->role == User::ROLE_MANAGER)) {
                        //Создание беседы между главным алмином и менедром по клиентам
                        ConversationManager::createRecordWithMainAdmin($model->getId(), $model->mainAdmin);
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
     * Список проектантов для трекера организации
     *
     * @param $id
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
        $pages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => $countUsersOnPage]);
        $pages->pageSizeParam = false; //убираем параметр $per-page
        $users = $query->offset($pages->offset)->limit($countUsersOnPage)->all();
        $currentUser = User::findOne(Yii::$app->user->getId());
        $checkCurrentUserToClient = $clientUser->getClientId() == $currentUser->clientUser->getClientId();

        return $this->render('group',[
            'admin' => $admin,
            'users' => $users,
            'pages' => $pages,
            'checkCurrentUserToClient' => $checkCurrentUserToClient,
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

}