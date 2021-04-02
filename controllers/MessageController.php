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

        }
        else{
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
     * @return array|bool
     */
    public function actionGetListUpdateConversations ($id, $pathname)
    {
        if (Yii::$app->request->isAjax) {

            if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                if ($pathname === 'index') {

                    $user = User::findOne($id);
                    $admin = User::findOne(['id' => $user->id_admin]);
                    $development = $user->development;
                    $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);

                    $response = [
                        'conversationAdminForUserAjax' => $this->renderAjax('update_conversation_admin_for_user', [
                            'conversation_admin' => $conversation_admin, 'user' => $user, 'admin' => $admin,
                        ]),
                        'conversationDevelopmentForUserAjax' => $this->renderAjax('update_conversation_development_for_user', [
                            'conversation_development' => $conversation_development, 'development' => $development, 'user' => $user,
                        ]),
                        'blockConversationAdmin' => '#adminConversation-' . $conversation_admin->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;


                } elseif ($pathname === 'view') {

                    $conversation_admin = ConversationAdmin::findOne($id);
                    $user = $conversation_admin->user;
                    $admin = $conversation_admin->admin;
                    $development = $user->development;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);

                    $response = [
                        'conversationAdminForUserAjax' => $this->renderAjax('update_conversation_admin_for_user', [
                            'conversation_admin' => $conversation_admin, 'user' => $user, 'admin' => $admin,
                        ]),
                        'conversationDevelopmentForUserAjax' => $this->renderAjax('update_conversation_development_for_user', [
                            'conversation_development' => $conversation_development, 'development' => $development, 'user' => $user,
                        ]),
                        'blockConversationAdmin' => '#adminConversation-' . $conversation_admin->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;

                } elseif ($pathname === 'technical-support') {

                    $conversation_development = ConversationDevelopment::findOne($id);
                    $user = $conversation_development->user;
                    $development = $conversation_development->development;
                    $conversation_admin = ConversationAdmin::findOne(['user_id' => $user->id]);
                    $admin = $conversation_admin->admin;

                    $response = [
                        'conversationAdminForUserAjax' => $this->renderAjax('update_conversation_admin_for_user', [
                            'conversation_admin' => $conversation_admin, 'user' => $user, 'admin' => $admin,
                        ]),
                        'conversationDevelopmentForUserAjax' => $this->renderAjax('update_conversation_development_for_user', [
                            'conversation_development' => $conversation_development, 'development' => $development, 'user' => $user,
                        ]),
                        'blockConversationAdmin' => '#adminConversation-' . $conversation_admin->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                if ($pathname === 'view') {

                    $conversation = ConversationAdmin::findOne($id);
                    $admin = $conversation->admin;
                    $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);

                    $response = [
                        'blockConversationAdminMain' => '#adminMainConversation-' . $conversationAdminMain->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationAdminMainForAdminAjax' => $this->renderAjax('update_conversation_main_admin_for_admin', [
                            'conversationAdminMain' => $conversationAdminMain, 'admin' => $admin,
                        ]),
                        'conversationDevelopmentForAdminAjax' => $this->renderAjax('update_conversation_development_for_admin', [
                            'conversation_development' => $conversation_development, 'admin' => $admin,
                        ]),
                        'conversationsUserForAdminAjax' => $this->renderAjax('update_conversations_user_for_admin', [
                            'allConversations' => ConversationAdmin::find()->joinWith('user')
                                ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

                if ($pathname === 'technical-support') {

                    $conversation = ConversationDevelopment::findOne($id);
                    $development = $conversation->development;

                    $response = [
                        'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                            'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    \Yii::$app->response->data = $response;
                    return $response;
                }
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionCheckingUnreadMessageAdmin ($id)
    {
        $message = MessageAdmin::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($message->status == MessageAdmin::READ_MESSAGE) {

                $response = ['checkRead' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkRead' => false];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }

        return false;
    }


    /**
     * @param $id
     * @param $idLastMessageOnPage
     * @return array|bool
     */
    public function actionCheckNewMessagesAdmin ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationAdmin::findOne($id);
        $user = $conversation->user;
        $admin = $conversation->admin;
        $lastMessageOnPage = MessageAdmin::findOne($idLastMessageOnPage);
        $messages = MessageAdmin::find()->andWhere(['conversation_id' => $conversation->id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

        if(Yii::$app->request->isAjax) {

            if ($messages) {

                $response = [
                    'checkNewMessages' => true,
                    'addNewMessagesAjax' => $this->renderAjax('check_new_messages_admin', [
                        'messages' => $messages, 'user' => $user, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                    ]),
                ];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkNewMessages' => false];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }

        return false;
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionGetCountUnreadMessages ($id)
    {
        $user = User::findOne($id);
        $countUnreadMessages = $user->countUnreadMessages;

        if(Yii::$app->request->isAjax) {

            $response = ['countUnreadMessages' => $countUnreadMessages];
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $response;
            return $response;
        }

        return false;
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
    public function actionSendMessage ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationAdmin::findOne($id);
        $user = User::findOne(['id' => $conversation->user_id]);
        $admin = User::findOne(['id' => $conversation->admin_id]);
        $formMessage = new FormCreateMessageAdmin();
        $lastMessageOnPage = MessageAdmin::findOne($idLastMessageOnPage);

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $admin->id;
                    $formMessage->adressee_id = $user->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageAdmin::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'admin',
                            'countUnreadMessages' => $admin->countUnreadMessages,
                            'conversationsUserForAdminAjax'=> $this->renderAjax('update_conversations_user_for_admin', [
                                'allConversations' => ConversationAdmin::find()->joinWith('user')
                                    ->andWhere(['user.id_admin' => $admin->id])->andWhere(['admin_id' => $admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_admin', [
                                'messages' => $messages, 'user' => $user, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
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
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageAdmin::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'user',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'blockConversationAdmin' => '#adminConversation-' . $id,
                            'conversationAdminForUserAjax' => $this->renderAjax('update_conversation_admin_for_user', [
                                'conversation_admin' => ConversationAdmin::findOne($id), 'user' => $user, 'admin' => $admin,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_admin', [
                                'messages' => $messages, 'user' => $user, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
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
                    'success' => true,
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
     * @return array|bool
     */
    public function actionCheckingUnreadMessageDevelopment ($id)
    {
        $message = MessageDevelopment::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($message->status == MessageDevelopment::READ_MESSAGE) {

                $response = ['checkRead' => true];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkRead' => false];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
            }
        }

        return false;
    }


    /**
     * @param $id
     * @param $idLastMessageOnPage
     * @return array|bool
     */
    public function actionCheckNewMessagesDevelopment ($id, $idLastMessageOnPage)
    {

        $conversation = ConversationDevelopment::findOne($id);
        $development = $conversation->development;
        $user = $conversation->user;
        $lastMessageOnPage = MessageDevelopment::findOne($idLastMessageOnPage);
        $messages = MessageDevelopment::find()->andWhere(['conversation_id' => $conversation->id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

        if(Yii::$app->request->isAjax) {

            if ($messages) {

                $response = [
                    'checkNewMessages' => true,
                    'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development', [
                        'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                    ]),
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkNewMessages' => false];
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $response;
                return $response;
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
    public function actionSendMessageDevelopment ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationDevelopment::findOne($id);
        $user = $conversation->user;
        $development = $conversation->development;
        $formMessage = new FormCreateMessageDevelopment();
        $lastMessageOnPage = MessageDevelopment::findOne($idLastMessageOnPage);

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $development->id;
                    $formMessage->adressee_id = $user->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageDevelopment::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response = [
                            'sender' => 'development',
                            'countUnreadMessages' => $development->countUnreadMessages,
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development', [
                                'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
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
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageDevelopment::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'user',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $id,
                            'conversationDevelopmentForUserAjax' => $this->renderAjax('update_conversation_development_for_user', [
                                'conversation_development' => ConversationDevelopment::findOne($id), 'development' => $development, 'user' => $user,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development', [
                                'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
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
                    'success' => true,
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