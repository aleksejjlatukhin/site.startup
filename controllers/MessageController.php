<?php


namespace app\controllers;

use app\models\ConversationDevelopment;
use app\models\forms\FormCreateMessageAdmin;
use app\models\forms\FormCreateMessageDevelopment;
use app\models\MessageDevelopment;
use app\models\MessageFiles;
use app\models\User;
use app\models\ConversationAdmin;
use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\form\SearchForm;
use app\modules\admin\models\MessageMainAdmin;
use Yii;
use app\models\MessageAdmin;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

class MessageController extends AppUserPartController
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

            $conversation = ConversationAdmin::findOne(Yii::$app->request->get());
            $user = User::findOne(['id' => $conversation->user_id]);
            $admin = User::findOne(['id' => $conversation->admin_id]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id) || ($admin->id == Yii::$app->user->id)){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }
        elseif (in_array($action->id, ['technical-support'])){

            $conversation = ConversationDevelopment::findOne(Yii::$app->request->get());
            $user = $conversation->user;
            $development = $conversation->development;

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id) || ($development->id == Yii::$app->user->id)){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new \yii\web\HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }
        elseif (in_array($action->id, ['index'])){

            $user = User::findOne(Yii::$app->request->get());

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id)){

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


    public function actionIndex ($id)
    {
        $user = User::findOne($id);
        $admin = User::findOne($user->id_admin);
        $development = $user->development;
        $conversation_admin = ConversationAdmin::findOne(['user_id' => $id]);
        $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);

        return $this->render('index', [
            'user' => $user,
            'admin' => $admin,
            'conversation_admin' => $conversation_admin,
            'development' => $development,
            'conversation_development' => $conversation_development,
        ]);
    }


    /**
     * @param $id
     * @return bool|string
     */
    public function actionView ($id)
    {
        $conversation = ConversationAdmin::findOne($id);
        $formMessage = new FormCreateMessageAdmin();
        $user = User::findOne(['id' => $conversation->user_id]);
        $admin = User::findOne(['id' => $conversation->admin_id]);
        $searchForm = new SearchForm(); // Форма поиска
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        // Вывод сообщений через пагинацию
        $query = MessageAdmin::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);
        $countMessages = MessageAdmin::find()->where(['conversation_id' => $id])->count();

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
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
            $cache->cachePath = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_admin/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageAdminCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageAdmin']['description'];

            return $this->render('view-admin', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'user' => $user,
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

        if (User::isUserSimple(Yii::$app->user->identity['username'])) {

            $development = $user->development;
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_admin/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageAdminCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageAdmin']['description'];

            return $this->render('view', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'user' => $user,
                'admin' => $admin,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'development' => $development,
                'conversation_development' => $conversation_development,
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
        $conversation = ConversationAdmin::findOne($id);
        $user = $conversation->user;
        $admin = $conversation->admin;
        $query = MessageAdmin::find()->where(['conversation_id' => $id])->andWhere(['<', 'id', $final])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);

        // Проверяем является ли страница последней
        $lastPage = false; $lastMessage = MessageAdmin::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_ASC])->one();
        foreach ($messages as $message) {
            if ($message->id == $lastMessage->id) { $lastPage = true; }
        }

        if(Yii::$app->request->isAjax) {

            $response = ['nextPageMessageAjax' => $this->renderAjax('message_pagination_ajax', [
                'messages' => $messages, 'pagesMessages' => $pagesMessages,
                'user' => $user, 'admin' => $admin,
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
    public function actionSaveCacheMessageAdminForm ($id)
    {
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        $data = $_POST; //Массив, который будем записывать в кэш
        $conversation = ConversationAdmin::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax) {

            if ($conversation->user->id == $user->id || $conversation->admin->id == $user->id) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_admin/conversation-'.$conversation->id.'/';
                $key = 'formCreateMessageAdminCache'; //Формируем ключ
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
    public function actionSendMessage ($id)
    {
        $conversation = ConversationAdmin::findOne($id);
        $user = User::findOne(['id' => $conversation->user_id]);
        $admin = User::findOne(['id' => $conversation->admin_id]);
        $formMessage = new FormCreateMessageAdmin();
        $last_message = $conversation->lastMessage;

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $admin->id;
                    $formMessage->adressee_id = $user->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message', [
                                'message' => $message, 'last_message' => $last_message,
                                'user' => $user, 'admin' => $admin
                            ]),
                            'conversationsForUserAjax' => $this->renderAjax('update_conversations_for_user', [
                                'conversation_admin' => ConversationAdmin::findOne($id), 'user' => $user, 'admin' => $admin,
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]),
                                'development' => $user->development,
                            ]),
                            'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne(['admin_id' => $admin->id]),
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $admin->id]),
                                'development' => $admin->development, 'admin' => $admin,
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'admin',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/message/view',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $admin->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message', [
                                'message' => $message, 'last_message' => $last_message,
                                'user' => $user, 'admin' => $admin
                            ]),
                            'conversationsForUserAjax' => $this->renderAjax('update_conversations_for_user', [
                                'conversation_admin' => ConversationAdmin::findOne($id), 'user' => $user, 'admin' => $admin,
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $user->id]),
                                'development' => $user->development,
                            ]),
                            'conversationsForAdminAjax' => $this->renderAjax('update_conversations_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne(['admin_id' => $admin->id]),
                                'conversation_development' => ConversationDevelopment::findOne(['user_id' => $admin->id]),
                                'development' => $admin->development, 'admin' => $admin,
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'user',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/message/view',
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
     * @return array|bool
     */
    public function actionReadMessageAdmin ($id)
    {
        if (Yii::$app->request->isAjax){
            $model = MessageAdmin::findOne($id);
            $model->status = MessageAdmin::READ_MESSAGE;
            if ($model->save()) {

                $user = User::findOne($model->adressee_id);
                $countUnreadMessagesForConversation = MessageAdmin::find()->where(['adressee_id' => $model->adressee_id, 'sender_id' => $model->sender_id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();
                // Передаем id блока беседы
                if (User::isUserSimple($user->username)) $blockConversation = '#adminConversation-' . $model->conversation_id;
                elseif (User::isUserAdmin($user->username)) $blockConversation = '#conversation-' . $model->conversation_id;

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
        if ($pathname === 'view') {

            $conversation = ConversationAdmin::findOne($id);
            $user = $conversation->user;
            $admin = $conversation->admin;

            if (Yii::$app->request->isAjax) {

                if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                    $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);
                    $admin = ['conversation_id' => $conversation_admin->id, 'isOnline' => $admin->checkOnline];

                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);
                    $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $user->development->checkOnline];

                    $response = ['development' => $development, 'admin' => $admin];
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
            return  false;
        }
        elseif ($pathname === 'index') {

            $user = User::findOne($id);

            if (Yii::$app->request->isAjax) {

                $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);
                $admin = ['conversation_id' => $conversation_admin->id, 'isOnline' => $conversation_admin->admin->checkOnline];

                $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);
                $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $user->development->checkOnline];

                $response = ['development' => $development, 'admin' => $admin];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
            return false;
        }
        elseif ($pathname === 'technical-support') {

            $conversation = ConversationDevelopment::findOne($id);
            $user = $conversation->user;
            $development = $conversation->development;

            if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);
                $admin = ['conversation_id' => $conversation_admin->id, 'isOnline' => $conversation_admin->admin->checkOnline];

                $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);
                $development = ['conversation_id' => $conversation_development->id, 'isOnline' => $development->checkOnline];

                $response = ['development' => $development, 'admin' => $admin];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }

            elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

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
        $user = $conversation->user;
        $development = $conversation->development;
        $searchForm = new SearchForm(); // Форма поиска
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        // Вывод сообщений через пагинацию
        $query = MessageDevelopment::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);
        $countMessages = MessageDevelopment::find()->where(['conversation_id' => $id])->count();

        if (User::isUserSimple(Yii::$app->user->identity['username'])) {

            $admin = User::findOne($user->id_admin);
            $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            return $this->render('technical-support', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'user' => $user,
                'admin' => $admin,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'development' => $development,
                'conversation_admin' => $conversation_admin,
            ]);
        }

        elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';
            // Все беседы техподдержки
            $allConversations = ConversationDevelopment::find()->joinWith('user')
                ->andWhere(['dev_id' => $development->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            return $this->render('technical-support-development', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'user' => $user,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'development' => $development,
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

            $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_pagination_ajax', [
                'messages' => $messages, 'pagesMessages' => $pagesMessages,
                'user' => $user, 'development' => $development,
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
    public function actionSaveCacheMessageDevelopmentForm ($id)
    {
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        $data = $_POST; //Массив, который будем записывать в кэш
        $conversation = ConversationDevelopment::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax) {

            if ($conversation->user->id == $user->id || $conversation->development->id == $user->id) {

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
        $user = $conversation->user;
        $development = $conversation->development;
        $formMessage = new FormCreateMessageDevelopment();
        $last_message = $conversation->lastMessage;

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $development->id;
                    $formMessage->adressee_id = $user->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_development', [
                                'message' => $message, 'last_message' => $last_message,
                                'user' => $user, 'development' => $development
                            ]),
                            'conversationsForUserAjax' => $this->renderAjax('update_conversations_for_user', [
                                'conversation_admin' => ConversationAdmin::findOne(['user_id' => $user->id]),
                                'user' => $user, 'admin' => User::findOne($user->id_admin), 'development' => $development,
                                'conversation_development' => ConversationDevelopment::findOne($id),
                            ]),
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                    ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'development',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/message/technical-support',
                            'conversation_id' => $id,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $development->id;
                    if ($message = $formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        $response =  [
                            'messageAjax' => $this->renderAjax('new_message_development', [
                                'message' => $message, 'last_message' => $last_message,
                                'user' => $user, 'development' => $development
                            ]),
                            'conversationsForUserAjax' => $this->renderAjax('update_conversations_for_user', [
                                'conversation_admin' => ConversationAdmin::findOne(['user_id' => $user->id]),
                                'user' => $user, 'admin' => User::findOne($user->id_admin), 'development' => $development,
                                'conversation_development' => ConversationDevelopment::findOne($id),
                            ]),
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'user' => $user, 'allConversations' => ConversationDevelopment::find()->joinWith('user')
                                    ->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'action' => 'send-message',
                            'sender' => 'user',
                            'sender_id' => $message->sender_id,
                            'adressee_id' => $message->adressee_id,
                            'location_pathname' => '/message/technical-support',
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
                if (User::isUserSimple($user->username)) $blockConversation = '#conversationTechnicalSupport-' . $model->conversation_id;
                elseif (User::isUserDev($user->username)) $blockConversation = '#conversation-' . $model->conversation_id;

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