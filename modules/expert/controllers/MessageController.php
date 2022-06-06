<?php


namespace app\modules\expert\controllers;

use app\models\ConversationAdmin;
use app\models\ConversationDevelopment;
use app\models\forms\FormCreateMessageDevelopment;
use app\models\MessageDevelopment;
use app\models\MessageFiles;
use app\models\User;
use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\ConversationManager;
use app\modules\expert\models\form\SearchForm;
use app\modules\expert\models\ConversationExpert;
use app\modules\expert\models\form\FormCreateMessageExpert;
use app\modules\expert\models\MessageExpert;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MessageController extends AppExpertController
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

            $conversation = ConversationExpert::findOne(Yii::$app->request->get());
            $expert = $conversation->expert;
            $user = $conversation->user;

            // Ограничение доступа
            if (($expert->id == Yii::$app->user->id) || ($user->id == Yii::$app->user->id)){
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

            $expert = User::findOne(['id' => Yii::$app->request->get(), 'role' => User::ROLE_EXPERT]);

            // Ограничение доступа
            if ($expert->id == Yii::$app->user->id){
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
     * @return string
     */
    public function actionIndex ($id)
    {
        $expert = User::findOne($id);
        // Форма поиска
        $searchForm = new SearchForm();
        // Беседа эксерта с техподдержкой
        $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);
        // Беседа эксперта с главным админом
        $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $expert->getId(), 'user_id' => $expert->mainAdmin->getId()]);
        // Беседы эксперта и трекеров
        $adminConversations = ConversationExpert::find()
            ->where(['expert_id' => $id, 'role' => User::ROLE_ADMIN])
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();
        // Беседы эксперта и проектантов
        $userConversations = ConversationExpert::find()
            ->where(['expert_id' => $id, 'role' => User::ROLE_USER])
            ->orderBy(['updated_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'expert' => $expert,
            'searchForm' => $searchForm,
            'conversation_development' => $conversation_development,
            'conversationAdminMain' => $conversationAdminMain,
            'adminConversations' => $adminConversations,
            'userConversations' => $userConversations,
        ]);
    }


    /**
     * Страница просмотра беседы
     *
     * @param $id
     * @return string|false
     */
    public function actionView($id)
    {
        $conversation = ConversationExpert::findOne($id);
        $formMessage = new FormCreateMessageExpert();
        $expert = User::findOne(['id' => $conversation->expertId]);
        $user = User::findOne(['id' => $conversation->userId]);
        $searchForm = new SearchForm();
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        // Вывод сообщений через пагинацию
        $query = MessageExpert::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);
        $countMessages = MessageExpert::find()->where(['conversation_id' => $id])->count();

        if (User::isUserExpert(Yii::$app->user->identity['username'])) {

            // Беседа эксперта с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $expert->id]);
            // Беседа эксперта с гл. админом
            $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $expert->getId(), 'user_id' => $expert->mainAdmin->getId()]);
            // Все беседы эксперта с трекерами
            $adminConversations = ConversationExpert::find()
                ->andWhere(['expert_id' => $expert->id])
                ->andWhere(['role' => User::ROLE_ADMIN])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы эксперта с проектантами
            $userConversations = ConversationExpert::find()
                ->andWhere(['expert_id' => $expert->id])
                ->andWhere(['role' => User::ROLE_USER])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$expert->id.'/messages/category_expert/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageExpertCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageExpert']['description'];

            if (User::isUserMainAdmin($user->username) || User::isUserAdminCompany($user->username)) {

                return $this->render('view', [
                    'conversation' => $conversation,
                    'formMessage' => $formMessage,
                    'expert' => $expert,
                    'main_admin' => $user,
                    'searchForm' => $searchForm,
                    'messages' => $messages,
                    'countMessages' => $countMessages,
                    'pagesMessages' => $pagesMessages,
                    'conversation_development' => $conversation_development,
                    'conversationAdminMain' => $conversationAdminMain,
                    'adminConversations' => $adminConversations,
                    'userConversations' => $userConversations,
                ]);

            } elseif (User::isUserAdmin($user->username)) {

                return $this->render('view-expert-admin', [
                    'conversation' => $conversation,
                    'formMessage' => $formMessage,
                    'expert' => $expert,
                    'admin' => $user,
                    'searchForm' => $searchForm,
                    'messages' => $messages,
                    'countMessages' => $countMessages,
                    'pagesMessages' => $pagesMessages,
                    'conversation_development' => $conversation_development,
                    'conversationAdminMain' => $conversationAdminMain,
                    'adminConversations' => $adminConversations,
                    'userConversations' => $userConversations,
                ]);
            }

        } elseif (User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserAdminCompany(Yii::$app->user->identity['username'])) {

            // Беседа админа с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);
            // Все беседы главного админа с менеджерами
            $managerConversations = ConversationManager::find()
                ->andWhere(['user_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы главного админа с трекерами
            $allConversations = ConversationMainAdmin::find()
                ->andWhere(['main_admin_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы главного админа с экспертами
            $expertConversations = ConversationExpert::find()
                ->andWhere(['user_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            User::isUserMainAdmin(Yii::$app->user->identity['username']) ? $module = 'admin' : $module = 'client';

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_expert/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageExpertCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageExpert']['description'];

            return $this->render('view-main-admin', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $user,
                'expert' => $expert,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversation_development' => $conversation_development,
                'managerConversations' => $managerConversations,
                'allConversations' => $allConversations,
                'expertConversations' => $expertConversations,
                'module' => $module,
            ]);
        }

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            // Беседа трекера с главным админом
            $conversationAdminMain = ConversationMainAdmin::findOne(['admin_id' => $user->id]);
            // Беседа трекера с техподдержкой
            $conversation_development = ConversationDevelopment::findOne(['user_id' => $user->id]);
            // Все беседы трекера с экспертами
            $expertConversations = ConversationExpert::find()
                ->andWhere(['user_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы трекера с проектантами
            $allConversations = ConversationAdmin::find()->joinWith('user')
                ->andWhere(['user.id_admin' => $user->id])
                ->andWhere(['admin_id' => $user->id])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            User::isUserMainAdmin($user->mainAdmin->getUsername()) ? $module = 'admin' : $module = 'client';

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_expert/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageExpertCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageExpert']['description'];

            return $this->render('view-admin', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'expert' => $expert,
                'admin' => $user,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversationAdminMain' => $conversationAdminMain,
                'conversation_development' => $conversation_development,
                'expertConversations' => $expertConversations,
                'allConversations' => $allConversations,
                'module' => $module,
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

            if (User::isUserExpert(Yii::$app->user->identity['username'])) {

                if ($pathname === 'index') {

                    $expert = User::findOne($id);
                    // Беседа эксерта с техподдержкой
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $id]);
                    // Беседа эксперта с главным админом
                    $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $id, 'user_id' => $expert->mainAdmin->getId()]);
                    // Беседы эксперта и трекеров
                    $adminConversations = ConversationExpert::find()
                        ->where(['expert_id' => $id, 'role' => User::ROLE_ADMIN])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();
                    // Беседы эксперта и проектантов
                    $userConversations = ConversationExpert::find()
                        ->where(['expert_id' => $id, 'role' => User::ROLE_USER])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();

                    $response = [
                        'blockConversationAdminMain' => '#adminMainConversation-' . $conversationAdminMain->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationAdminMainForExpertAjax' => $this->renderAjax('update_conversation_main_admin_for_expert', [
                            'conversationAdminMain' => $conversationAdminMain, 'expert' => $expert,
                        ]),
                        'conversationDevelopmentForExpertAjax' => $this->renderAjax('update_conversation_development_for_expert', [
                            'conversation_development' => $conversation_development, 'expert' => $expert,
                        ]),
                        'conversationsAdminForExpertAjax' => $this->renderAjax('update_conversations_admin_for_expert', [
                            'adminConversations' => $adminConversations, 'expert' => $expert,
                        ]),
                        'conversationsUserForExpertAjax' => $this->renderAjax('update_conversations_user_for_expert', [
                            'userConversations' => $userConversations, 'expert' => $expert,
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'view') {

                    $conversation = ConversationExpert::findOne($id);
                    $expert = $conversation->expert;
                    // Беседа эксерта с техподдержкой
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $expert->id]);
                    // Беседа эксперта с главным админом
                    $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $expert->id, 'user_id' => $expert->mainAdmin->getId()]);
                    // Беседы эксперта и трекеров
                    $adminConversations = ConversationExpert::find()
                        ->where(['expert_id' => $expert->id, 'role' => User::ROLE_ADMIN])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();
                    // Беседы эксперта и проектантов
                    $userConversations = ConversationExpert::find()
                        ->where(['expert_id' => $expert->id, 'role' => User::ROLE_USER])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();


                    $response = [
                        'blockConversationAdminMain' => '#adminMainConversation-' . $conversationAdminMain->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationAdminMainForExpertAjax' => $this->renderAjax('update_conversation_main_admin_for_expert', [
                            'conversationAdminMain' => $conversationAdminMain, 'expert' => $expert,
                        ]),
                        'conversationDevelopmentForExpertAjax' => $this->renderAjax('update_conversation_development_for_expert', [
                            'conversation_development' => $conversation_development, 'expert' => $expert,
                        ]),
                        'conversationsAdminForExpertAjax' => $this->renderAjax('update_conversations_admin_for_expert', [
                            'adminConversations' => $adminConversations, 'expert' => $expert,
                        ]),
                        'conversationsUserForExpertAjax' => $this->renderAjax('update_conversations_user_for_expert', [
                            'userConversations' => $userConversations, 'expert' => $expert,
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
                elseif ($pathname === 'technical-support') {

                    $conversation_development = ConversationDevelopment::findOne($id);
                    $expert = $conversation_development->user;
                    // Беседа эксперта с главным админом
                    $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $expert->id, 'user_id' => $expert->mainAdmin->getId()]);
                    // Беседы эксперта и трекеров
                    $adminConversations = ConversationExpert::find()
                        ->where(['expert_id' => $expert->id, 'role' => User::ROLE_ADMIN])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();
                    // Беседы эксперта и проектантов
                    $userConversations = ConversationExpert::find()
                        ->where(['expert_id' => $expert->id, 'role' => User::ROLE_USER])
                        ->orderBy(['updated_at' => SORT_DESC])
                        ->all();

                    $response = [
                        'blockConversationAdminMain' => '#adminMainConversation-' . $conversationAdminMain->id,
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationAdminMainForExpertAjax' => $this->renderAjax('update_conversation_main_admin_for_expert', [
                            'conversationAdminMain' => $conversationAdminMain, 'expert' => $expert,
                        ]),
                        'conversationDevelopmentForExpertAjax' => $this->renderAjax('update_conversation_development_for_expert', [
                            'conversation_development' => $conversation_development, 'expert' => $expert,
                        ]),
                        'conversationsAdminForExpertAjax' => $this->renderAjax('update_conversations_admin_for_expert', [
                            'adminConversations' => $adminConversations, 'expert' => $expert,
                        ]),
                        'conversationsUserForExpertAjax' => $this->renderAjax('update_conversations_user_for_expert', [
                            'userConversations' => $userConversations, 'expert' => $expert,
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
                    return $response;
                }
            }
            elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                if ($pathname === 'view') {

                    $conversation = ConversationExpert::findOne($id);
                    $admin = $conversation->user;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $admin->id]);
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
            elseif (User::isUserMainAdmin(Yii::$app->user->identity['username']) || User::isUserAdminCompany(Yii::$app->user->identity['username'])) {

                if ($pathname === 'view') {

                    $conversation = ConversationExpert::findOne($id);
                    $main_admin = $conversation->user;
                    $conversation_development = ConversationDevelopment::findOne(['user_id' => $main_admin->id]);

                    $response = [
                        'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $conversation_development->id,
                        'conversationDevelopmentForAdminMainAjax' => $this->renderAjax('update_conversation_development_for_main_admin', [
                            'conversation_development' => $conversation_development, 'main_admin' => $main_admin,
                        ]),
                        'conversationsAdminForAdminMainAjax' => $this->renderAjax('update_conversations_admin_for_main_admin', [
                            'allConversations' => ConversationMainAdmin::find()->andWhere(['main_admin_id' => $main_admin->getId()])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                        'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                            'expertConversations' => ConversationExpert::find()->andWhere(['user_id' => $main_admin->getId()])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                        'conversationsManagerForAdminMainAjax' => $this->renderAjax('update_conversations_manager_for_main_admin', [
                            'managerConversations' => ConversationManager::find()->where(['user_id' => $main_admin->getId()])
                                ->orderBy(['updated_at' => SORT_DESC])->all(),
                        ]),
                    ];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = $response;
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
    public function actionGetConversationQuery ($id)
    {
        if (Yii::$app->request->isAjax){

            $query = trim($_POST['SearchForm']['search']);
            //Беседы с трекерами и проектантами, которые попали в запрос
            $conversations_query = ConversationExpert::find()->joinWith('user')
                ->andWhere(['expert_id' => $id])
                ->andWhere(['like', 'user.username', $query])
                ->all();

            $response = ['renderAjax' => $this->renderAjax('conversations_query', ['conversations_query' => $conversations_query])];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }

        return false;
    }


    /**
     * @param $id
     * @param $idLastMessageOnPage
     * @return array|bool
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionSendMessage ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationExpert::findOne($id);
        $user = $conversation->user;
        $expert = $conversation->expert;
        $formMessage = new FormCreateMessageExpert();
        $lastMessageOnPage = MessageExpert::findOne($idLastMessageOnPage);

        if ($formMessage->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax){

                if (User::isUserExpert(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $expert->id;
                    $formMessage->adressee_id = $user->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$expert->id.'/messages/category_expert/conversation-'.$conversation->id.'/';
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageExpert::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response = array();

                        if (User::isUserMainAdmin($user->username)) {
                            $response =  [
                                'sender' => 'expert',
                                'countUnreadMessages' => $expert->countUnreadMessages,
                                'blockConversationAdminMain' => '#adminMainConversation-' . $id,
                                'conversationAdminMainForExpertAjax' => $this->renderAjax('update_conversation_main_admin_for_expert', [
                                    'conversationAdminMain' => ConversationExpert::findOne($id), 'expert' => $expert,
                                ]),
                                'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_main_admin', [
                                    'messages' => $messages, 'main_admin' => $user, 'expert' => $expert, 'lastMessageOnPage' => $lastMessageOnPage,
                                ]),
                            ];

                        } elseif (User::isUserAdminCompany($user->username)) {
                            $response =  [
                                'sender' => 'expert',
                                'countUnreadMessages' => $expert->countUnreadMessages,
                                'blockConversationAdminMain' => '#adminMainConversation-' . $id,
                                'conversationAdminMainForExpertAjax' => $this->renderAjax('update_conversation_main_admin_for_expert', [
                                    'conversationAdminMain' => ConversationExpert::findOne($id), 'expert' => $expert,
                                ]),
                                'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_main_admin', [
                                    'messages' => $messages, 'main_admin' => $user, 'expert' => $expert, 'lastMessageOnPage' => $lastMessageOnPage,
                                ]),
                            ];

                        } elseif (User::isUserAdmin($user->username)) {
                            $response =  [
                                'sender' => 'expert',
                                'countUnreadMessages' => $expert->countUnreadMessages,
                                'conversationsAdminForExpertAjax' => $this->renderAjax('update_conversations_admin_for_expert', [
                                    'adminConversations' => ConversationExpert::find()->andWhere(['expert_id' => $expert->id, 'role' => User::ROLE_ADMIN])
                                        ->orderBy(['updated_at' => SORT_DESC])->all(), 'expert' => $expert,
                                ]),
                                'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_admin', [
                                    'messages' => $messages, 'expert' => $expert, 'admin' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
                                ]),
                            ];
                        }

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $expert->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_expert/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageExpert::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'main_admin',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                                'expertConversations' => ConversationExpert::find()->andWhere(['role' => User::ROLE_MAIN_ADMIN])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_main_admin', [
                                'messages' => $messages, 'main_admin' => $user, 'expert' => $expert, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }

                elseif (User::isUserAdminCompany(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $expert->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/messages/category_expert/conversation-'.$conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageExpert::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response =  [
                            'sender' => 'main_admin',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'conversationsExpertForAdminMainAjax' => $this->renderAjax('update_conversations_expert_for_main_admin', [
                                'expertConversations' => ConversationExpert::find()->andWhere(['role' => User::ROLE_ADMIN_COMPANY])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_main_admin', [
                                'messages' => $messages, 'main_admin' => $user, 'expert' => $expert, 'lastMessageOnPage' => $lastMessageOnPage,
                            ]),
                        ];

                        Yii::$app->response->format = Response::FORMAT_JSON;
                        Yii::$app->response->data = $response;
                        return $response;
                    }
                }
                elseif (User::isUserAdmin(Yii::$app->user->identity['username'])) {

                    $formMessage->conversation_id = $id;
                    $formMessage->sender_id = $user->id;
                    $formMessage->adressee_id = $expert->id;
                    if ($formMessage->create()) {

                        //Удаление кэша формы создания сообщения
                        $cachePathDelete = '../runtime/cache/forms/user-' . $user->id . '/messages/category_expert/conversation-' . $conversation->id;
                        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

                        // Сообщения, которых ещё нет на странице
                        $messages = MessageExpert::find()->andWhere(['conversation_id' => $id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

                        $response = [
                            'sender' => 'admin',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'conversationsExpertForAdminAjax' => $this->renderAjax('update_conversations_expert_for_admin', [
                                'conversationsExpert' => ConversationExpert::find()->andWhere(['user_id' => $user->id, 'role' => User::ROLE_ADMIN])
                                    ->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert_admin', [
                                'messages' => $messages, 'admin' => $user, 'expert' => $expert, 'lastMessageOnPage' => $lastMessageOnPage,
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
     * @return bool
     */
    public function actionSaveCacheMessageExpertForm ($id)
    {
        $cache = Yii::$app->cache; //Обращаемся к кэшу приложения
        $data = $_POST; //Массив, который будем записывать в кэш
        $conversation = ConversationExpert::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax) {

            if ($conversation->expert->id == $user->id || $conversation->user->id == $user->id) {

                $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_expert/conversation-'.$conversation->id.'/';
                $key = 'formCreateMessageExpertCache'; //Формируем ключ
                $cache->set($key, $data, 3600*24*30); //Создаем файл кэша на 30дней
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
    public function actionGetPageMessage ($id, $page, $final)
    {
        $conversation = ConversationExpert::findOne($id);
        $expert = $conversation->expert;
        $user = $conversation->user;
        $query = MessageExpert::find()->where(['conversation_id' => $id])->andWhere(['<', 'id', $final])->orderBy(['id' => SORT_DESC]);
        $pagesMessages = new Pagination(['totalCount' => $query->count(), 'page' => ($page - 1), 'pageSize' => 20]);
        $messages = $query->offset($pagesMessages->offset)->limit($pagesMessages->pageSize)->all();
        $messages = array_reverse($messages);

        // Проверяем является ли страница последней
        $lastPage = false; $lastMessage = MessageExpert::find()->where(['conversation_id' => $id])->orderBy(['id' => SORT_ASC])->one();
        foreach ($messages as $message) {
            if ($message->id == $lastMessage->id) { $lastPage = true; }
        }

        if(Yii::$app->request->isAjax) {

            $response = ['nextPageMessageAjax' => $this->renderAjax('message_expert_pagination_ajax', [
                'messages' => $messages, 'pagesMessages' => $pagesMessages,
                'expert' => $expert, 'user' => $user,
            ]), 'lastPage' => $lastPage];
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = $response;
            return $response;
        }
        return false;
    }


    /**
     * @param $id
     * @param $idLastMessageOnPage
     * @return array|bool
     */
    public function actionCheckNewMessagesExpert ($id, $idLastMessageOnPage)
    {
        $conversation = ConversationExpert::findOne($id);
        $expert = $conversation->expert;
        $user = $conversation->user;
        $lastMessageOnPage = MessageExpert::findOne($idLastMessageOnPage);
        $messages = MessageExpert::find()->andWhere(['conversation_id' => $conversation->id])->andWhere(['>', 'id', $idLastMessageOnPage])->all();

        if(Yii::$app->request->isAjax) {

            if ($messages) {

                $response = [
                    'checkNewMessages' => true,
                    'addNewMessagesAjax' => $this->renderAjax('check_new_messages_expert', [
                        'messages' => $messages, 'expert' => $expert, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
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
     * @return array|bool
     */
    public function actionReadMessageExpert ($id)
    {
        if (Yii::$app->request->isAjax){
            $model = MessageExpert::findOne($id);
            $model->status = MessageExpert::READ_MESSAGE;
            if ($model->save()) {

                $user = User::findOne($model->adressee_id);
                $countUnreadMessagesForConversation = MessageExpert::find()->where(['adressee_id' => $model->adressee_id, 'sender_id' => $model->sender_id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();
                // Передаем id блока беседы
                if (User::isUserMainAdmin($user->username) || User::isUserAdminCompany($user->username)) $blockConversation = '#expertConversation-' . $model->conversation_id;
                elseif (User::isUserAdmin($user->username)) $blockConversation = '#expertConversation-' . $model->conversation_id;
                elseif (User::isUserExpert($user->username)) {
                    if (User::isUserMainAdmin($model->sender->username) || User::isUserAdminCompany($model->sender->username))
                        $blockConversation = '#adminMainConversation-' . $model->conversation_id;
                    if (User::isUserAdmin($model->sender->username))
                        $blockConversation = '#adminConversation-' . $model->conversation_id;
                }

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
     * @param $id
     * @return array|bool
     */
    public function actionCheckingUnreadMessageExpert ($id)
    {
        $message = MessageExpert::findOne($id);

        if(Yii::$app->request->isAjax) {

            if ($message->status == MessageExpert::READ_MESSAGE) {

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
     * @param $category
     * @param $id
     * @return \yii\console\Response|Response
     * @throws NotFoundHttpException
     */
    public function actionDownload ($category, $id)
    {
        $model = MessageFiles::findOne(['category' => $category, 'id' => $id]);
        if ($category == MessageFiles::CATEGORY_EXPERT) $message = MessageExpert::findOne($model->message_id);
        else if ($category == MessageFiles::CATEGORY_TECHNICAL_SUPPORT) $message = MessageDevelopment::findOne($model->message_id);

        $path = UPLOAD.'/user-'.$message->sender_id.'/messages/category-'.$category.'/message-'.$message->id.'/';
        $file = $path . $model->server_file;

        if (file_exists($file)) {
            return Yii::$app->response->sendFile($file, $model->file_name);
        }
        throw new NotFoundHttpException('Данный файл не найден');
    }


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

        if (User::isUserExpert(Yii::$app->user->identity['username'])) {

            // Беседа эксперта с главным админом
            $conversationAdminMain = ConversationExpert::findOne(['expert_id' => $user->getId(), 'user_id' => $user->mainAdmin->id]);
            $main_admin = $conversationAdminMain->user;
            // Все беседы эксперта с трекерами
            $adminConversations = ConversationExpert::find()
                ->where(['expert_id' => $user->id])
                ->andWhere(['role' => User::ROLE_ADMIN])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();
            // Все беседы эксперта с проектантами
            $userConversations = ConversationExpert::find()
                ->andWhere(['expert_id' => $user->id])
                ->andWhere(['role' => User::ROLE_USER])
                ->orderBy(['updated_at' => SORT_DESC])
                ->all();

            // Если есть кэш, добавляем его в форму сообщения
            $cache->cachePath = '../runtime/cache/forms/user-'.$user->id.'/messages/category_technical_support/conversation-'.$conversation->id.'/';
            $cache_form_message = $cache->get('formCreateMessageDevelopmentCache');
            if ($cache_form_message) $formMessage->description = $cache_form_message['FormCreateMessageDevelopment']['description'];

            return $this->render('technical-support-expert', [
                'conversation_development' => $conversation,
                'formMessage' => $formMessage,
                'main_admin' => $main_admin,
                'expert' => $user,
                'development' => $development,
                'searchForm' => $searchForm,
                'messages' => $messages,
                'countMessages' => $countMessages,
                'pagesMessages' => $pagesMessages,
                'conversationAdminMain' => $conversationAdminMain,
                'adminConversations' => $adminConversations,
                'userConversations' => $userConversations,
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

            return $this->render('technical-support-development', [
                'conversation' => $conversation,
                'formMessage' => $formMessage,
                'expert' => $user,
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
    public function actionReadMessageDevelopment ($id)
    {
        if (Yii::$app->request->isAjax){
            $model = MessageDevelopment::findOne($id);
            $model->status = MessageDevelopment::READ_MESSAGE;
            if ($model->save()) {

                $user = User::findOne($model->adressee_id);
                $countUnreadMessagesForConversation = MessageDevelopment::find()->where(['adressee_id' => $model->adressee_id, 'sender_id' => $model->sender_id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();
                // Передаем id блока беседы
                if (User::isUserExpert($user->username)) $blockConversation = '#conversationTechnicalSupport-' . $model->conversation_id;
                elseif (User::isUserDev($user->username)) $blockConversation = '#expertConversation-' . $model->conversation_id;

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
     * @throws ErrorException
     * @throws Exception
     * @throws NotFoundHttpException
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

                if (User::isUserExpert(Yii::$app->user->identity['username'])) {

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
                            'sender' => 'expert',
                            'countUnreadMessages' => $user->countUnreadMessages,
                            'blockConversationDevelopment' => '#conversationTechnicalSupport-' . $id,
                            'conversationDevelopmentForExpertAjax' => $this->renderAjax('update_conversation_development_for_expert', [
                                'conversation_development' => ConversationDevelopment::findOne($id), 'expert' => $user,
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_expert', [
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

                        $response =  [
                            'sender' => 'development',
                            'countUnreadMessages' => $development->countUnreadMessages,
                            'conversationsForDevelopmentAjax' => $this->renderAjax('update_conversations_for_development', [
                                'allConversations' => ConversationDevelopment::find()->joinWith('user')->where(['dev_id' => $development->id])->orderBy(['updated_at' => SORT_DESC])->all(),
                            ]),
                            'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_expert', [
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

            $response = ['nextPageMessageAjax' => $this->renderAjax('message_development_and_expert_pagination_ajax', [
                'messages' => $messages, 'pagesMessages' => $pagesMessages,
                'development' => $development, 'user' => $user,
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
                    'addNewMessagesAjax' => $this->renderAjax('check_new_messages_development_and_expert', [
                        'messages' => $messages, 'development' => $development, 'user' => $user, 'lastMessageOnPage' => $lastMessageOnPage,
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