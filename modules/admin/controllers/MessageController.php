<?php

namespace app\modules\admin\controllers;

use app\models\ConversationDevelopment;
use app\models\forms\FormCreateMessageDevelopment;
use app\models\MessageAdmin;
use app\models\MessageDevelopment;
use app\models\MessageFiles;
use app\models\User;
use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\form\FormCreateMessageMainAdmin;
use app\modules\admin\models\form\SearchForm;
use app\modules\admin\models\MessageMainAdmin;
use app\models\ConversationAdmin;
use Yii;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class MessageController extends AppAdminController
{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

           $conversation = ConversationMainAdmin::findOne(Yii::$app->request->get());
           $admin = User::findOne(['id' => $conversation->admin_id]);
            /*Ограничение доступа к проэктам пользователя*/
            if (($admin->id == Yii::$app->user->id) || User::isUserMainAdmin(Yii::$app->user->identity['username'])){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }elseif (in_array($action->id, ['index'])) {

            $admin = User::findOne(['id' => \Yii::$app->request->get(), 'role' => User::ROLE_ADMIN]);
            $mainAdmin = User::findOne(['id' => \Yii::$app->request->get(), 'role' => User::ROLE_MAIN_ADMIN]);
            $development = User::findOne(['id' => \Yii::$app->request->get(), 'role' => User::ROLE_DEV]);

            /*Ограничение доступа к проэктам пользователя*/
            if ($admin->id == Yii::$app->user->id || $mainAdmin->id == Yii::$app->user->id || $development->id == Yii::$app->user->id){
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
     * @return bool|string
     */
    public function actionIndex ($id)
    {
        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $main_admin = User::findOne($id);
            // Форма поиска
            $searchForm = new SearchForm();
            // Беседа главного админа с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);
            // Все беседы главного админа с админами
            $allConversations = ConversationMainAdmin::find()->joinWith('admin')
                ->andWhere(['main_admin_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            return $this->render('index', [
                'main_admin' => $main_admin,
                'searchForm' => $searchForm,
                'conversation_development' => $conversation_development,
                'allConversations' => $allConversations,
            ]);
        }

        elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $admin = User::findOne($id);
            // Форма поиска
            $searchForm = new SearchForm();
            // Беседа админа с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
            // Беседа админа с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);
            // Все беседы админа с проектантами
            $allConversations = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $id])
                ->andWhere(['admin_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            return $this->render('index-admin', [
                'admin' => $admin,
                'searchForm' => $searchForm,
                'conversationAdminMain' => $conversationAdminMain,
                'conversation_development' => $conversation_development,
                'allConversations' => $allConversations,
            ]);
        }

        elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

            // Временный код - добавление бесед пользователей и техподдержки
            $users = User::find()->all();
            foreach ($users as $user){
                if ($user->id != $id) {
                    $user->createConversationDevelopment();
                }
            }

            $development = User::findOne($id);
            // Форма поиска
            $searchForm = new SearchForm();
            // Все беседы техподдержки
            $allConversations = ConversationDevelopment::find()->joinWith('user')
                ->andWhere(['dev_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            return $this->render('index-development', [
                'development' => $development,
                'searchForm' => $searchForm,
                'allConversations' => $allConversations,
            ]);
        }
        return false;
    }


    /**
     * @param $id
     * @return bool|string
     */
    public function actionView ($id)
    {
        $conversation = ConversationMainAdmin::findOne($id);
        $formMessage = new FormCreateMessageMainAdmin();
        $main_admin = User::findOne(['id' => $conversation->main_admin_id]);
        $admin = User::findOne(['id' => $conversation->admin_id]);
        $searchForm = new SearchForm(); // Форма поиска
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        // Вывод сообщений через пагинацию
        $query = MessageMainAdmin::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);
        $countMessages = MessageMainAdmin::find()->where(['conversation_id' => $id])->count();

        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            // Беседа админа с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);
            // Все беседы главного админа с админами
            $allConversations = ConversationMainAdmin::find()
                ->andWhere(['main_admin_id' => $main_admin->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$main_admin->id.'/messages/category_main_admin/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageMainAdminCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageMainAdmin']['description'];

            return $this->render('view', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $main_admin,
                'admin' => $admin,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversation_development' => $conversation_development,
                'allConversations' => $allConversations,
            ]);
        }

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Беседа админа с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
            // Беседа админа с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
            // Все беседы админа с проектантами
            $allConversations = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $admin->id])
                ->andWhere(['admin_id' => $admin->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_main_admin/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageMainAdminCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageMainAdmin']['description'];

            return $this->render('view-admin', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $main_admin,
                'admin' => $admin,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversationAdminMain' => $conversationAdminMain,
                'conversation_development' => $conversation_development,
                'allConversations' => $allConversations,
            ]);
        }

        return false;
    }


    /**
     * @param $id
     * @param $page
     * @param $final
     * @return array|bool
     */
    public function actionGetPageMessage ($id, $page, $final)
    {
        $conversation = ConversationMainAdmin::findOne($id);
        $main_admin = $conversation->mainAdmin;
        $admin = $conversation->admin;
        $query = MessageMainAdmin::find()->where(['conversation_id' => $id])->andWhere(['<', 'id', $final])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);

        // Проверяем является ли страница последней
        $lastPage = false; $lastMessage = MessageMainAdmin::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_ASC])->one();
        foreach ($messages as $message) {
            if ($message->id == $lastMessage->id) { $lastPage = true; }
        }

        if(Yii::$app->request->isAjax) {

            $response = ['nextPageMessageAjax' => $this->renderAjax('message_main_admin_pagination_ajax', [
                'messages' => $messages, 'pagesMessages' => $pagesMessages,
                'main_admin' => $main_admin, 'admin' => $admin,
            ]), 'lastPage' => $lastPage];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @return bool
     */
    public function actionSaveCacheMessageMainAdminForm ($id)
    {
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        $data = $_POST; //Массив, который будем записывать в кэш
        $conversation = ConversationMainAdmin::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax) {

            if ($conversation->mainAdmin->id == $user->id || $conversation->admin->id == $user->id) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_main_admin/conversation-'.$conversation->id.'/';
                $key = 'formCreateMessageMainAdminCache'; //Формируем ключ
                $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSendMessage ($id)
    {
        $conversation = ConversationMainAdmin::findOne($id);
        $main_admin = $conversation->mainAdmin;
        $admin = $conversation->admin;
        $formMessage = new FormCreateMessageMainAdmin();
        $last_message = $conversation->lastMessage;

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $admin->id;
                    $formMessage->adressee_id = $main_admin->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_main_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_admin_and_main_admin', [
                                'message' => $message, 'last_message' => $last_message,
                                'main_admin' => $main_admin, 'admin' => $admin
                            ]),
                            'conversationsForMainAdminAjax' => $this->renderAjax('update_conversations_for_main_admin', [
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $main_admin->id]), 'main_admin' => $main_admin,
                                'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne($id), 'development' => $admin->development, 'admin' => $admin,
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $admin->id]),
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'admin',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/admin/message/view',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $main_admin->id;
                    $formMessage->adressee_id = $admin->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$main_admin->id.'/messages/category_main_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_admin_and_main_admin', [
                                'message' => $message, 'last_message' => $last_message,
                                'main_admin' => $main_admin, 'admin' => $admin
                            ]),
                            'conversationsForMainAdminAjax' => $this->renderAjax('update_conversations_for_main_admin', [
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $main_admin->id]), 'main_admin' => $main_admin,
                                'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne($id), 'development' => $admin->development, 'admin' => $admin,
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $admin->id]),
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'main_admin',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/admin/message/view',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array
     */
    public function actionGetAdminConversationQuery ($id)
    {
        if (Yii::$app->request->isAjax){

            $query = trim($_POST['SearchForm']['search']);
            //Беседы с админами, которые попали в запрос
            $conversations_query = ConversationMainAdmin::find()->joinWith('admin')
                ->andWhere(['main_admin_id' => $id])
                ->andWhere(['or',
                    ['like', 'user.second_name', $query],
                    ['like', 'user.first_name', $query],
                    ['like', 'user.middle_name', $query],
                    ['like', "CONCAT( user.second_name, ' ', user.first_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.second_name, ' ', user.middle_name, ' ', user.first_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.middle_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.second_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.first_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.second_name, ' ', user.first_name)", $query],
                ])->all();

            $response = ['renderAjax' => $this->renderAjax('admin_conversations_query', ['conversations_query' => $conversations_query])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionGetConversationQuery ($id)
    {
        if (Yii::$app->request->isAjax){

            $query = trim($_POST['SearchForm']['search']);
            //Беседы с пользователями, которые попали в запрос
            $conversations_query = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $id])
                ->andWhere(['admin_id' => $id])
                ->andWhere(['or',
                    ['like', 'user.second_name', $query],
                    ['like', 'user.first_name', $query],
                    ['like', 'user.middle_name', $query],
                    ['like', "CONCAT( user.second_name, ' ', user.first_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.second_name, ' ', user.middle_name, ' ', user.first_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.middle_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.second_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.first_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.second_name, ' ', user.first_name)", $query],
                ])->all();

            $response = ['renderAjax' => $this->renderAjax('conversations_query', ['conversations_query' => $conversations_query])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function actionGetDevelopmentConversationQuery ($id)
    {
        if (Yii::$app->request->isAjax){

            $query = trim($_POST['SearchForm']['search']);
            //Беседы с админами, которые попали в запрос
            $conversations_query = ConversationDevelopment::find()->joinWith('user')
                ->andWhere(['dev_id' => $id])
                ->andWhere(['or',
                    ['like', 'user.second_name', $query],
                    ['like', 'user.first_name', $query],
                    ['like', 'user.middle_name', $query],
                    ['like', "CONCAT( user.second_name, ' ', user.first_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.second_name, ' ', user.middle_name, ' ', user.first_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.middle_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.first_name, ' ', user.second_name, ' ', user.middle_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.first_name, ' ', user.second_name)", $query],
                    ['like', "CONCAT( user.middle_name, ' ', user.second_name, ' ', user.first_name)", $query],
                ])->all();

            $response = ['renderAjax' => $this->renderAjax('conversations_query', ['conversations_query' => $conversations_query])];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionReadMessageAdmin ($id)
    {
        if (Yii::$app->request->isAjax){
            $model = MessageMainAdmin::findOne($id);
            $model->status = MessageMainAdmin::READ_MESSAGE;
            if ($model->save()) {

                $user = User::findOne($model->adressee_id);
                $countUnreadMessagesForConversation = MessageMainAdmin::find()->where(['adressee_id' => $model->adressee_id, 'sender_id' => $model->sender_id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();
                // Передаем id блока беседы
                if (User::isUserMainAdmin($user->username)) $blockConversation = '#adminConversation-' . $model->conversation_id;
                elseif (User::isUserAdmin($user->username)) $blockConversation = '#adminMainConversation-' . $model->conversation_id;

                $response = [
                    'action' => 'read-message',
                    'message' => $model,
                    'countUnreadMessages' => $user->countUnreadMessages,
                    'blockConversation' => $blockConversation,
                    'countUnreadMessagesForConversation' => $countUnreadMessagesForConversation,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $id
     * @param $pathname
     * @return array|bool
     */
    public function actionGetUsersIsOnline($id, $pathname)
    {
        if (Yii::$app->request->isAjax) {

            if ($pathname === 'index') {

                $user = User::findOne($id);

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $admin = $user;
                    $conversation_main_admin = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
                    $mainAdmin = ['conversation_id' => $conversation_main_admin->id, 'isOnline' => $admin->mainAdmin->checkOnline];

                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $admin->development->checkOnline];

                    $users = array();
                    $user_conversations = ConversationAdmin::findAll(['admin_id' => $admin->id]);
                    foreach ($user_conversations as $i => $conversation) {
                        $users[$i]['conversation_id'] = $conversation->id;
                        $users[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $response = ['mainAdmin' => $mainAdmin, 'development' => $development, 'users' => $users];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;

                } elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $main_admin = $user;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $main_admin->development->checkOnline];

                    $admins = array();
                    $admin_conversations = ConversationMainAdmin::findAll(['main_admin_id' => $main_admin->id]);
                    foreach ($admin_conversations as $i => $conversation) {
                        $admins[$i]['conversation_id'] = $conversation->id;
                        $admins[$i]['isOnline'] = $conversation->admin->checkOnline;
                    }

                    $response = ['development' => $development, 'admins' => $admins];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;

                } elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $development = $user;

                    $admins = array();
                    $admin_conversations = ConversationDevelopment::find()->joinWith('user')
                        ->andWhere(['>=', 'user.role',User::ROLE_ADMIN])
                        ->andWhere(['<=', 'user.role', User::ROLE_MAIN_ADMIN])
                        ->andWhere(['dev_id' => $development->id])->all();
                    foreach ($admin_conversations as $i => $conversation) {
                        $admins[$i]['conversation_id'] = $conversation->id;
                        $admins[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $users = array();
                    $user_conversations = ConversationDevelopment::find()->joinWith('user')
                        ->andWhere(['user.role' => User::ROLE_USER])
                        ->andWhere(['dev_id' => $development->id])->all();
                    foreach ($user_conversations as $i => $conversation) {
                        $users[$i]['conversation_id'] = $conversation->id;
                        $users[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $response = ['admins' => $admins, 'users' => $users];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }

            } elseif ($pathname === 'view') {

                $conversation = ConversationMainAdmin::findOne($id);
                $main_admin = $conversation->mainAdmin;
                $admin = $conversation->admin;

                if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $main_admin->development->checkOnline];

                    $admins = array();
                    $admin_conversations = ConversationMainAdmin::findAll(['main_admin_id' => $main_admin->id]);
                    foreach ($admin_conversations as $i => $conversation) {
                        $admins[$i]['conversation_id'] = $conversation->id;
                        $admins[$i]['isOnline'] = $conversation->admin->checkOnline;
                    }

                    $response = ['development' => $development, 'admins' => $admins];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
                elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $conversation_main_admin = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
                    $mainAdmin = ['conversation_id' => $conversation_main_admin->id, 'isOnline' => $admin->mainAdmin->checkOnline];

                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $admin->development->checkOnline];

                    $users = array();
                    $user_conversations = ConversationAdmin::findAll(['admin_id' => $admin->id]);
                    foreach ($user_conversations as $i => $conversation) {
                        $users[$i]['conversation_id'] = $conversation->id;
                        $users[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $response = ['mainAdmin' => $mainAdmin, 'development' => $development, 'users' => $users];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }

            elseif ($pathname === 'technical-support') {

                $conversation = ConversationDevelopment::findOne($id);
                $user = $conversation->user;
                $development = $conversation->development;

                if (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $admins = array();
                    $admin_conversations = ConversationDevelopment::find()->joinWith('user')
                        ->andWhere(['>=', 'user.role',User::ROLE_ADMIN])
                        ->andWhere(['<=', 'user.role', User::ROLE_MAIN_ADMIN])
                        ->andWhere(['dev_id' => $development->id])->all();
                    foreach ($admin_conversations as $i => $conversation) {
                        $admins[$i]['conversation_id'] = $conversation->id;
                        $admins[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $users = array();
                    $user_conversations = ConversationDevelopment::find()->joinWith('user')
                        ->andWhere(['user.role' => User::ROLE_USER])
                        ->andWhere(['dev_id' => $development->id])->all();
                    foreach ($user_conversations as $i => $conversation) {
                        $users[$i]['conversation_id'] = $conversation->id;
                        $users[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $response = ['admins' => $admins, 'users' => $users];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $main_admin = $user;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $development->checkOnline];

                    $admins = array();
                    $admin_conversations = ConversationMainAdmin::findAll(['main_admin_id' => $main_admin->id]);
                    foreach ($admin_conversations as $i => $conversation) {
                        $admins[$i]['conversation_id'] = $conversation->id;
                        $admins[$i]['isOnline'] = $conversation->admin->checkOnline;
                    }

                    $response = ['development' => $development, 'admins' => $admins];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
                elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $admin = $user;
                    $conversation_main_admin = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
                    $mainAdmin = ['conversation_id' => $conversation_main_admin->id, 'isOnline' => $admin->mainAdmin->checkOnline];

                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $development->checkOnline];

                    $users = array();
                    $user_conversations = ConversationAdmin::findAll(['admin_id' => $admin->id]);
                    foreach ($user_conversations as $i => $conversation) {
                        $users[$i]['conversation_id'] = $conversation->id;
                        $users[$i]['isOnline'] = $conversation->user->checkOnline;
                    }

                    $response = ['mainAdmin' => $mainAdmin, 'development' => $development, 'users' => $users];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }

        return false;
    }


    /**
     * @param $category
     * @param $id
     * @return \yii\console\Response|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDownload ($category, $id)
    {
        $model = MessageFiles::findOne(['category' => $category, 'id' => $id]);
        if ($category == MessageFiles::CATEGORY_ADMIN) $message = MessageAdmin::findOne($model->message_id);
        else if ($category == MessageFiles::CATEGORY_MAIN_ADMIN) $message = MessageMainAdmin::findOne($model->message_id);
        else if ($category == MessageFiles::CATEGORY_TECHNICAL_SUPPORT) $message = MessageDevelopment::findOne($model->message_id);

        $path = UPLOAD.'/user-'.$message->sender_id.'/messages/category-'.$category.'/message-'.$message->id.'/';
        $file = $path . $model->server_file;

        if (file_exists($file)) {
            return \Yii::$app->response->sendFile($file, $model->file_name);
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


    /**
     * @param $id
     * @return bool|string
     */
    public function actionTechnicalSupport ($id)
    {
        $conversation = ConversationDevelopment::findOne($id);
        $formMessage = new FormCreateMessageDevelopment();
        $development = $conversation->development;
        $user = $conversation->user;
        $searchForm = new SearchForm(); // Форма поиска
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        // Вывод сообщений через пагинацию
        $query = MessageDevelopment::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);
        $countMessages = MessageDevelopment::find()->where(['conversation_id' => $id])->count();

        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            // Все беседы главного админа с админами
            $allConversations = ConversationMainAdmin::find()->joinWith('admin')
                ->andWhere(['main_admin_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            return $this->render('technical-support-main-admin', [
                'conversation_development' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $user,
                'development' => $development,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'allConversations' => $allConversations,
            ]);
        }

        elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Беседа админа с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $user->id]);
            $main_admin = $conversationAdminMain->mainAdmin;
            // Все беседы админа с проектантами
            $allConversations = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $user->id])
                ->andWhere(['admin_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            return $this->render('technical-support-admin', [
                'conversation_development' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $main_admin,
                'admin' => $user,
                'development' => $development,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversationAdminMain' => $conversationAdminMain,
                'allConversations' => $allConversations,
            ]);
        }

        elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

            $allConversations = ConversationDevelopment::find()->joinWith('user')
                ->andWhere(['dev_id' => $development->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            if (User::isUserAdmin($user->username)) {

                return $this->render('technical-support-development-for-admin', [
                    'conversation' => $conversation,
                    'formMessage' => $formMessage,
                    'admin' => $user,
                    'searchForm' => $searchForm,
                    'messages' => $messages,
                    'countMessages' => $countMessages,
                    'pagesMessages' => $pagesMessages,
                    'development' => $development,
                    'allConversations' => $allConversations,
                ]);
            }

            elseif (User::isUserMainAdmin($user->username)) {

                return $this->render('technical-support-development-for-main-admin', [
                    'conversation' => $conversation,
                    'formMessage' => $formMessage,
                    'main_admin' => $user,
                    'searchForm' => $searchForm,
                    'messages' => $messages,
                    'countMessages' => $countMessages,
                    'pagesMessages' => $pagesMessages,
                    'development' => $development,
                    'allConversations' => $allConversations,
                ]);
            }
        }

        return false;
    }

    /**
     * @param $id
     * @param $page
     * @param $final
     * @return array|bool
     */
    public function actionGetPageMessageDevelopment ($id, $page, $final)
    {
        $conversation = ConversationDevelopment::findOne($id);
        $user = $conversation->user;
        $development = $conversation->development;
        $query = MessageDevelopment::find()->where(['conversation_id' => $id])->andWhere(['<', 'id', $final])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);

        // Проверяем является ли страница последней
        $lastPage = false; $lastMessage = MessageDevelopment::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_ASC])->one();
        foreach ($messages as $message) {
            if ($message->id == $lastMessage->id) { $lastPage = true; }
        }

        if(Yii::$app->request->isAjax) {

            if (User::isUserMainAdmin($user->username)) {

                $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_and_main_admin_pagination_ajax', [
                    'messages' => $messages, 'pagesMessages' => $pagesMessages,
                    'development' => $development, 'user' => $user,
                ]), 'lastPage' => $lastPage];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
            elseif (User::isUserAdmin($user->username)) {

                $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_and_admin_pagination_ajax', [
                    'messages' => $messages, 'pagesMessages' => $pagesMessages,
                    'development' => $development, 'user' => $user,
                ]), 'lastPage' => $lastPage];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionSaveCacheMessageDevelopmentForm ($id)
    {
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        $data = $_POST; //Массив, который будем записывать в кэш
        $conversation = ConversationDevelopment::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax) {

            if ($conversation->development->id == $user->id || $conversation->user->id == $user->id) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
                $key = 'formCreateMessageDevelopmentCache'; //Формируем ключ
                $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function actionSendMessageDevelopment ($id)
    {
        $conversation = ConversationDevelopment::findOne($id);
        $development = $conversation->development;
        $user = $conversation->user;
        $formMessage = new FormCreateMessageDevelopment();
        $last_message = $conversation->lastMessage;

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $development->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_development_and_admin', [
                                'message' => $message, 'last_message' => $last_message,
                                'development' => $development, 'user' => $user,
                            ]),
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                    ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne(['admin_id' => $user->id]),
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]),
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $user->id])->andWhere(['admin_id' => $user->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                                'development' => $development,
                                'admin' => $user,
                            ]),
                            'action' => 'send-message',
                            'sender' => 'admin',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/admin/message/technical-support',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $development->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_development_and_main_admin', [
                                'message' => $message, 'last_message' => $last_message,
                                'development' => $development, 'user' => $user,
                            ]),
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                    ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'conversationsForMainAdminAjax' => $this->renderAjax('update_conversations_for_main_admin', [
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]), 'main_admin' => $user,
                                'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $user->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'main_admin',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/admin/message/technical-support',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $development->id;
                    $formMessage->adressee_id = $user->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        if (User::isUserAdmin($user->username)) {

                            $response =  [
                                'messageAjax' => $this->renderAjax('new_message_development_and_admin', [
                                    'message' => $message, 'last_message' => $last_message,
                                    'development' => $development, 'user' => $user,
                                ]),
                                'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                    'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                        ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                                ]),
                                'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                    'conversationAdminMain' => ConversationMainAdmin::findOne(['admin_id' => $user->id]),
                                    'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]),
                                    'allConversations' => ConversationAdmin::find()->joinWith('user')
                                        ->andWhere(['user.id_admin' => $user->id])->andWhere(['admin_id' => $user->id])
                                        ->orderBy(['updated_at' => SORT_DESC])->all(),
                                    'development' => $development,
                                    'admin' => $user,
                                ]),
                                'action' => 'send-message',
                                'sender' => 'development',
                                'sender_id' => $message->sender_id,
                                'adressee_id' => $message->adressee_id,
                                'location_pathname' => '/admin/message/technical-support',
                                'conversation_id' => $id,
                            ];

                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }

                        elseif (User::isUserMainAdmin($user->username)) {

                            $response =  [
                                'messageAjax' => $this->renderAjax('new_message_development_and_main_admin', [
                                    'message' => $message, 'last_message' => $last_message,
                                    'development' => $development, 'user' => $user,
                                ]),
                                'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                    'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                        ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                                ]),
                                'conversationsForMainAdminAjax' => $this->renderAjax('update_conversations_for_main_admin', [
                                    'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]), 'main_admin' => $user,
                                    'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $user->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                                ]),
                                'action' => 'send-message',
                                'sender' => 'development',
                                'sender_id' => $message->sender_id,
                                'adressee_id' => $message->adressee_id,
                                'location_pathname' => '/admin/message/technical-support',
                                'conversation_id' => $id,
                            ];

                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionReadMessageDevelopment ($id)
    {
        if (Yii::$app->request->isAjax){
            $model = MessageDevelopment::findOne($id);
            $model->status = MessageDevelopment::READ_MESSAGE;
            if ($model->save()) {

                $user = User::findOne($model->adressee_id);
                $countUnreadMessagesForConversation = MessageDevelopment::find()->where(['adressee_id' => $model->adressee_id, 'sender_id' => $model->sender_id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
                // Передаем id блока беседы
                if (User::isUserMainAdmin($user->username)) $blockConversation = '#conversationTechnicalSupport-' . $model->conversation_id;
                elseif (User::isUserAdmin($user->username)) $blockConversation = '#conversationTechnicalSupport-' . $model->conversation_id;
                elseif (User::isUserDev($user->username)) $blockConversation = '#adminConversation-' . $model->conversation_id;

                $response = [
                    'action' => 'read-message',
                    'message' => $model,
                    'countUnreadMessages' => $user->countUnreadMessages,
                    'blockConversation' => $blockConversation,
                    'countUnreadMessagesForConversation' => $countUnreadMessagesForConversation,
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }

}