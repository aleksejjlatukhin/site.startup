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
use app\modules\expert\models\ConversationExpert;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MessageController extends AppAdminController
{

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($action)
    {

        if (in_array($action->id, ['view'])){

           $conversation = ConversationMainAdmin::findOne(Yii::$app->request->get());
           $admin = User::findOne(['id' => $conversation->admin_id]);
            // Ограничение доступа
            if (($admin->id == Yii::$app->user->id) || User::isUserMainAdmin(Yii::$app->user->identity['username'])){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }
        elseif (in_array($action->id, ['technical-support'])){

            $conversation = ConversationDevelopment::findOne(Yii::$app->request->get());
            $user = $conversation->user;
            $development = $conversation->development;

            // Ограничение доступа
            if (($user->id == Yii::$app->user->id) || ($development->id == Yii::$app->user->id)){

                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;

                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }
        elseif (in_array($action->id, ['index'])) {

            $admin = User::findOne(['id' => Yii::$app->request->get(), 'role' => User::ROLE_ADMIN]);
            $mainAdmin = User::findOne(['id' => Yii::$app->request->get(), 'role' => User::ROLE_MAIN_ADMIN]);
            $development = User::findOne(['id' => Yii::$app->request->get(), 'role' => User::ROLE_DEV]);

            // Ограничение доступа
            if ($admin->id == Yii::$app->user->id || $mainAdmin->id == Yii::$app->user->id || $development->id == Yii::$app->user->id){
                // ОТКЛЮЧАЕМ CSRF
                $this->enableCsrfValidation = false;
                return parent::beforeAction($action);

            }else{
                throw new HttpException(200, 'У Вас нет доступа по данному адресу.');
            }

        }
        else{
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
            // Беседы главного админа с экспертами
            $expertConversations = ConversationExpert::find()
                ->where(['role' => User::ROLE_MAIN_ADMIN])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы главного админа с трекерами
            $allConversations = ConversationMainAdmin::find()->joinWith('admin')
                ->andWhere(['main_admin_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            return $this->render('index', [
                'main_admin' => $main_admin,
                'searchForm' => $searchForm,
                'conversation_development' => $conversation_development,
                'expertConversations' => $expertConversations,
                'allConversations' => $allConversations,
            ]);
        }

        elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $admin = User::findOne($id);
            // Форма поиска
            $searchForm = new SearchForm();
            // Беседа трекера с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
            // Беседа трекера с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);
            // Все беседы трекера с проектантами
            $allConversations = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $id])
                ->andWhere(['admin_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы трекера с экспертами
            $conversationsExpert = ConversationExpert::find()->where(['user_id' => $id])
                ->orderBy(['updated_at' => SORT_DESC])->all();

            return $this->render('index-admin', [
                'admin' => $admin,
                'searchForm' => $searchForm,
                'conversationAdminMain' => $conversationAdminMain,
                'conversation_development' => $conversation_development,
                'allConversations' => $allConversations,
                'conversationsExpert' => $conversationsExpert,
            ]);
        }

        elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

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
     * @param $pathname
     * @return array|bool
     */
    public function actionGetListUpdateConversations ($id, $pathname)
    {
        if (Yii::$app->request->isAjax) {

            if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                if ($pathname === 'index') {

                    $admin = User::findOne($id);
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
                        'conversationsExpertForAdminAjax' => $this->renderAjax('update_conversations_expert_for_admin',[
                            'conversationsExpert' => ConversationExpert::find()->where(['user_id' => $admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'view') {

                    $conversationAdminMain = ConversationMainAdmin::findOne($id);
                    $admin = $conversationAdminMain->admin;
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
                        'conversationsExpertForAdminAjax' => $this->renderAjax('update_conversations_expert_for_admin',[
                            'conversationsExpert' => ConversationExpert::find()->where(['user_id' => $admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'technical-support') {

                    $conversation_development = ConversationDevelopment::findOne($id);
                    $admin = $conversation_development->user;
                    $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);

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
                        'conversationsExpertForAdminAjax' => $this->renderAjax('update_conversations_expert_for_admin',[
                            'conversationsExpert' => ConversationExpert::find()->where(['user_id' => $admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                if ($pathname === 'index') {

                    $main_admin = User::findOne($id);
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);

                    $response = [
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationDevelopmentForAdminMainAjax' => $this->renderAjax('update_conversation_development_for_main_admin', [
                            'conversation_development' => $conversation_development, 'main_admin' => $main_admin,
                        ]),
                        'conversationsAdminForAdminMainAjax' => $this->renderAjax('update_conversations_admin_for_main_admin', [
                            'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                        'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                            'expertConversations' => ConversationExpert::find()->andWhere(['role' => User::ROLE_MAIN_ADMIN])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'view') {

                    $conversation = ConversationMainAdmin::findOne($id);
                    $main_admin = $conversation->mainAdmin;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);

                    $response = [
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationDevelopmentForAdminMainAjax' => $this->renderAjax('update_conversation_development_for_main_admin', [
                            'conversation_development' => $conversation_development, 'main_admin' => $main_admin,
                        ]),
                        'conversationsAdminForAdminMainAjax' => $this->renderAjax('update_conversations_admin_for_main_admin', [
                            'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                        'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                            'expertConversations' => ConversationExpert::find()->where(['user_id' => $main_admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'technical-support') {

                    $conversation_development = ConversationDevelopment::findOne($id);
                    $main_admin = $conversation_development->user;

                    $response = [
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationDevelopmentForAdminMainAjax' => $this->renderAjax('update_conversation_development_for_main_admin', [
                            'conversation_development' => $conversation_development, 'main_admin' => $main_admin,
                        ]),
                        'conversationsAdminForAdminMainAjax' => $this->renderAjax('update_conversations_admin_for_main_admin', [
                            'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                        'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                            'expertConversations' => ConversationExpert::find()->where(['user_id' => $main_admin->id])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

                if ($pathname === 'index') {

                    $development = User::findOne($id);

                    $response = [
                        'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                            'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'technical-support') {

                    $conversation = ConversationDevelopment::findOne($id);
                    $development = $conversation->development;

                    $response = [
                        'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                            'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
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
     * @return array|bool
     */
    public function actionCheckingUnreadMessageMainAdmin ($id)
    {
        $message = MessageMainAdmin::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($message->status == MessageMainAdmin::READ_MESSAGE) {

                $response = ['checkRead' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkRead' => false];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
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
    public function actionCheckNewMessagesMainAdmin ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationMainAdmin::findOne($id);
        $main_admin = $conversation->mainAdmin;
        $admin = $conversation->admin;
        $lastMessageOnPage = MessageMainAdmin::findOne($idLastMessageOnPage);
        $messages = MessageMainAdmin::find()->andWhere(['conversation_id' => $conversation->id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

        if(Yii::$app->request->isAjax) {

            if ($messages) {

                $response = [
                    'checkNewMessages' => true,
                    'addNewMessagesAjax' => $this->renderAjax('check_new_messages_main_admin', [
                        'messages' => $messages, 'main_admin' => $main_admin, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                    ]),
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkNewMessages' => false];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
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
            // Все беседы главного админа с трекерами
            $allConversations = ConversationMainAdmin::find()
                ->andWhere(['main_admin_id' => $main_admin->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы главного админа с экспертами
            $expertConversations = ConversationExpert::find()
                ->andWhere(['user_id' => $main_admin->id])
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
                'expertConversations' => $expertConversations,
            ]);
        }

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Беседа трекера с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);
            // Беседа трекера с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
            // Все беседы трекера с экспертами
            $expertConversations = ConversationExpert::find()
                ->andWhere(['user_id' => $admin->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы трекера с проектантами
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
                'expertConversations' => $expertConversations,
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
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
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
     * @param $idLastMessageOnPage
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     * @throws Exception
     */
    public function actionSendMessage ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationMainAdmin::findOne($id);
        $main_admin = $conversation->mainAdmin;
        $admin = $conversation->admin;
        $formMessage = new FormCreateMessageMainAdmin();
        $lastMessageOnPage = MessageMainAdmin::findOne($idLastMessageOnPage);

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $admin->id;
                    $formMessage->adressee_id = $main_admin->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$admin->id.'/messages/category_main_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageMainAdmin::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'admin',
                            'countUnreadMessages' => $admin->countUnreadMessages,
                            'blockConversationAdminMain' => '#adminMainConversation-' . $id,
                            'conversationAdminMainForAdminAjax' => $this->renderAjax('update_conversation_main_admin_for_admin', [
                                'conversationAdminMain' => ConversationMainAdmin::findOne($id), 'admin' => $admin,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_main_admin', [
                                'messages' => $messages, 'main_admin' => $main_admin, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $main_admin->id;
                    $formMessage->adressee_id = $admin->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$main_admin->id.'/messages/category_main_admin/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageMainAdmin::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'main_admin',
                            'countUnreadMessages' => $main_admin->countUnreadMessages,
                            'conversationsAdminForAdminMainAjax' => $this->renderAjax('update_conversations_admin_for_main_admin', [
                                'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->id])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_main_admin', [
                                'messages' => $messages, 'main_admin' => $main_admin, 'admin' => $admin, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
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

            $expert_conversations_query = ConversationExpert::find()->joinWith('expert')
                ->andWhere(['user_id' => $id])
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

            $response = ['renderAjax' => $this->renderAjax('admin_conversations_query', [
                'conversations_query' => $conversations_query, 'expert_conversations_query' => $expert_conversations_query])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
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

            $expert_conversations_query = ConversationExpert::find()->joinWith('expert')
                ->andWhere(['user_id' => $id])
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

            $response = ['renderAjax' => $this->renderAjax('conversations_query', [
                'conversations_query' => $conversations_query, 'expert_conversations_query' => $expert_conversations_query])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
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

            $response = ['renderAjax' => $this->renderAjax('conversations_query_development', ['conversations_query' => $conversations_query])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
    }


    /**
     * @param $id
     * @return array|bool
     */
    public function actionReadMessageMainAdmin ($id)
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
                    'success' => true,
                    'message' => $model,
                    'countUnreadMessages' => $user->countUnreadMessages,
                    'blockConversation' => $blockConversation,
                    'countUnreadMessagesForConversation' => $countUnreadMessagesForConversation,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * @param $category
     * @param $id
     * @return \yii\console\Response|Response
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
            return Yii::$app->response->sendFile($file, $model->file_name);
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

            // Все беседы главного админа с трекерами
            $allConversations = ConversationMainAdmin::find()->joinWith('admin')
                ->andWhere(['main_admin_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Все беседы главного админа с экспертами
            $expertConversations = ConversationExpert::find()
                ->where(['user_id' => $user->id])
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
                'expertConversations' => $expertConversations,
            ]);
        }

        elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Беседа трекера с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $user->id]);
            $main_admin = $conversationAdminMain->mainAdmin;
            // Все беседы трекера с экспертами
            $expertConversations = ConversationExpert::find()
                ->where(['user_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы трекера с проектантами
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
                'expertConversations' => $expertConversations,
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
     * @return array|bool
     */
    public function actionCheckingUnreadMessageDevelopment ($id)
    {
        $message = MessageDevelopment::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($message->status == MessageDevelopment::READ_MESSAGE) {

                $response = ['checkRead' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkRead' => false];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
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

                if (User::isUserMainAdmin($user->username)) {

                    $response = [
                        'checkNewMessages' => true,
                        'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_main_admin', [
                            'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                        ]),
                    ];
                }
                elseif (User::isUserAdmin($user->username)) {

                    $response = [
                        'checkNewMessages' => true,
                        'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_admin', [
                            'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                        ]),
                    ];
                }

                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;

            } else {
                $response = ['checkNewMessages' => false];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
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

            if (User::isUserMainAdmin($user->username)) {

                $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_and_main_admin_pagination_ajax', [
                    'messages' => $messages, 'pagesMessages' => $pagesMessages,
                    'development' => $development, 'user' => $user,
                ]), 'lastPage' => $lastPage];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
            elseif (User::isUserAdmin($user->username)) {

                $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_and_admin_pagination_ajax', [
                    'messages' => $messages, 'pagesMessages' => $pagesMessages,
                    'development' => $development, 'user' => $user,
                ]), 'lastPage' => $lastPage];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
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
     * @param $idLastMessageOnPage
     * @return array|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     * @throws Exception
     */
    public function actionSendMessageDevelopment ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationDevelopment::findOne($id);
        $development = $conversation->development;
        $user = $conversation->user;
        $formMessage = new FormCreateMessageDevelopment();
        $lastMessageOnPage = MessageDevelopment::findOne($idLastMessageOnPage);

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

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
                            'sender' => 'admin',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $id,
                            'conversationDevelopmentForAdminAjax' => $this->renderAjax('update_conversation_development_for_admin', [
                                'conversation_development' => ConversationDevelopment::findOne($id), 'admin' => $user,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_admin', [
                                'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

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
                            'sender' => 'main_admin',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $id,
                            'conversationDevelopmentForAdminMainAjax' => $this->renderAjax('update_conversation_development_for_main_admin', [
                                'conversation_development' => ConversationDevelopment::findOne($id), 'main_admin' => $user,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_main_admin', [
                                'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserDev(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $development->id;
                    $formMessage->adressee_id = $user->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$development->id.'/messages/category_technical_support/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageDevelopment::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        if (User::isUserAdmin($user->username)) {

                            $response =  [
                                'sender' => 'development',
                                'countUnreadMessages' => $development->countUnreadMessages,
                                'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                    'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                                ]),
                                'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_admin', [
                                    'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                                ]),
                            ];

                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
                            return $response;
                        }

                        elseif (User::isUserMainAdmin($user->username)) {

                            $response =  [
                                'sender' => 'development',
                                'countUnreadMessages' => $development->countUnreadMessages,
                                'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                    'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                                ]),
                                'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_main_admin', [
                                    'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                                ]),
                            ];

                            Yii::$app->response->format = Response::FORMAT_JSON;
                            Yii::$app->response->data = $response;
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
                    'success' => true,
                    'message' => $model,
                    'countUnreadMessages' => $user->countUnreadMessages,
                    'blockConversation' => $blockConversation,
                    'countUnreadMessagesForConversation' => $countUnreadMessagesForConversation,
                ];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }


    /**
     * Создание новой беседы
     *
     * @param int $user_id
     * @param int $expert_id
     * @return array|bool
     */
    public function actionCreateExpertConversation($user_id, $expert_id)
    {
        if(Yii::$app->request->isAjax) {

            $user = User::findOne($user_id);
            $expert = User::findOne($expert_id);
            $conversation = User::createConversationExpert($user, $expert);

            if ($conversation) {
                $response = ['success' => true];
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = $response;
                return $response;
            }
        }
        return false;
    }

}