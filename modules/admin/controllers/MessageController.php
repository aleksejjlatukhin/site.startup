<?php


namespace app\modules\admin\controllers;


use app\models\User;
use app\modules\admin\models\ConversationMainAdmin;
use app\modules\admin\models\MessageMainAdmin;
use app\models\ConversationAdmin;
use Yii;

class MessageController extends AppAdminController
{

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

            $user = User::findOne([
                'id' => \Yii::$app->request->get(),
                'role' => User::ROLE_ADMIN,
                ]);

            /*Ограничение доступа к проэктам пользователя*/
            if (($user->id == Yii::$app->user->id) || User::isUserMainAdmin(Yii::$app->user->identity['username'])){

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


        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $user = User::findOne($id);
            $query = trim(Yii::$app->request->get('query'));

            //Все беседы с главным админом
            $conversations = ConversationMainAdmin::findAll(['main_admin_id' => $id]);

            $conversation_e = ConversationMainAdmin::find()->where(['main_admin_id' => $id])->orderBy('updated_at DESC')->all();

            //Беседы, у которых есть сообщения
            $conversations_exist = [];

            foreach ($conversation_e as $conversation) {
                if (!empty($conversation->messages)) {
                    $conversations_exist[] = $conversation;
                }
            }

            //Пользователи, которые попали в запрос
            $admins = User::find()
                ->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_ADMIN])
                ->andFilterWhere(['or',
                    ['like', 'second_name', $query],
                    ['like', 'first_name', $query],
                    ['like', 'middle_name', $query],
                    ['like', "CONCAT( second_name, ' ', first_name, ' ', middle_name)", $query],
                    ['like', "CONCAT( second_name, ' ', middle_name, ' ', first_name)", $query],
                    ['like', "CONCAT( first_name, ' ', middle_name, ' ', second_name)", $query],
                    ['like', "CONCAT( first_name, ' ', second_name, ' ', middle_name)", $query],
                    ['like', "CONCAT( middle_name, ' ', first_name, ' ', second_name)", $query],
                    ['like', "CONCAT( middle_name, ' ', second_name, ' ', first_name)", $query],
                ])->all();


            //Беседы с пользователями, которые попали в запрос
            $conversations_query = [];

            foreach ($admins as $admin) {

                $conversations_query[] = ConversationMainAdmin::find()
                    ->where([
                    'main_admin_id' => $id,
                    'admin_id' => $admin->id,
                    ])->with('admin')->one();
            }


            if (Yii::$app->request->isAjax){

                $convers_query = [
                    'admins' => $admins,
                    'convers' => $conversations_query,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $convers_query;
                return $convers_query;
            }


            return $this->render('index', [
                'user' => $user,
                'admins' => $admins,
                'query' => $query,
                'conversations' => $conversations,
                'conversations_query' => $conversations_query,
                'conversations_exist' => $conversations_exist,
            ]);
        }


        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $admin = User::findOne($id);

            $convers_main = ConversationMainAdmin::findOne(['admin_id' => $admin->id]);

            $query = trim(Yii::$app->request->get('query'));

            //Все беседы проектантов с админом
            $conversations = ConversationAdmin::findAll(['admin_id' => $id]);

            $conversation_e = ConversationAdmin::find()->where(['admin_id' => $id])->orderBy('updated_at DESC')->all();

            //Беседы проектантов, у которых есть сообщения
            $conversations_exist = [];

            foreach ($conversation_e as $conversation) {
                if (!empty($conversation->messages)) {
                    $conversations_exist[] = $conversation;
                }
            }

            //Пользователи, которые попали в запрос
            $users = User::find()
                ->where(['status' => User::STATUS_ACTIVE, 'role' => User::ROLE_USER, 'id_admin' => $id])
                ->andFilterWhere(['or',
                    ['like', 'second_name', $query],
                    ['like', 'first_name', $query],
                    ['like', 'middle_name', $query],
                    ['like', "CONCAT( second_name, ' ', first_name, ' ', middle_name)", $query],
                    ['like', "CONCAT( second_name, ' ', middle_name, ' ', first_name)", $query],
                    ['like', "CONCAT( first_name, ' ', middle_name, ' ', second_name)", $query],
                    ['like', "CONCAT( first_name, ' ', second_name, ' ', middle_name)", $query],
                    ['like', "CONCAT( middle_name, ' ', first_name, ' ', second_name)", $query],
                    ['like', "CONCAT( middle_name, ' ', second_name, ' ', first_name)", $query],
                ])->all();


            //Беседы с пользователями, которые попали в запрос
            $conversations_query = [];

            foreach ($users as $user) {

                $conversations_query[] = ConversationAdmin::find()
                    ->where([
                        'admin_id' => $id,
                        'user_id' => $user->id,
                    ])->with('user')->one();
            }


            if (Yii::$app->request->isAjax){

                $convers_query = [
                    'users' => $users,
                    'convers' => $conversations_query,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $convers_query;
                return $convers_query;
            }


            return $this->render('index-admin', [
                'admin' => $admin,
                'users' => $users,
                'query' => $query,
                'conversations' => $conversations,
                'conversations_query' => $conversations_query,
                'conversations_exist' => $conversations_exist,
                'convers_main' => $convers_main,
            ]);
        }



    }



    public function actionView ($id)
    {

        $conversation = ConversationMainAdmin::findOne($id);

        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $admin = User::findOne(['id' => $conversation->admin_id]);
            $main_admin = User::findOne(['id' => $conversation->main_admin_id]);
            $messages = $conversation->messages;

            $conversations = ConversationMainAdmin::findAll(['main_admin_id' => $conversation->main_admin_id]);

            $model = new MessageMainAdmin();
            $model->conversation_id = $id;
            $model->sender_id = $main_admin->id;
            $model->adressee_id = $admin->id;

            if (Yii::$app->request->isAjax){

                if ($model->load(Yii::$app->request->post())) {

                    $model->description = $_POST['MessageMainAdmin']['description'];

                    if ($model->save()){

                        $conversation->updated_at = $model->updated_at;
                        $conversation->save();

                        $data = [
                            'message' => $model,
                            'admin' => $admin,
                            'main_admin' => $main_admin,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $data;
                        return $data;
                    }
                }
            }

            return $this->render('view', [
                'conversation' => $conversation,
                'conversations' => $conversations,
                'admin' => $admin,
                'model' => $model,
                'messages' => $messages,
            ]);
        }

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $admin = User::findOne(['id' => $conversation->admin_id]);
            $main_admin = User::findOne(['id' => $conversation->main_admin_id]);
            $messages = $conversation->messages;

            $model = new MessageMainAdmin();
            $model->conversation_id = $id;
            $model->sender_id = $admin->id;
            $model->adressee_id = $main_admin->id;

            if (Yii::$app->request->isAjax){

                if ($model->load(Yii::$app->request->post())) {

                    $model->description = $_POST['MessageMainAdmin']['description'];

                    if ($model->save()){

                        $conversation->updated_at = $model->updated_at;
                        $conversation->save();

                        $data = [
                            'message' => $model,
                            'admin' => $admin,
                            'main_admin' => $main_admin,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $data;
                        return $data;
                    }
                }
            }

            return $this->render('view-admin', [
                'conversation' => $conversation,
                'admin' => $admin,
                'main_admin' => $main_admin,
                'model' => $model,
                'messages' => $messages,
            ]);
        }
    }



    public function actionUpdate ($id)
    {

        $conversation = ConversationMainAdmin::findOne($id);
        $admin = User::findOne(['id' => $conversation->admin_id]);
        $main_admin = User::findOne(['id' => $conversation->main_admin_id]);

        $messages = $conversation->messages;

        $times = [];
        $dates = [];

        foreach ($messages as $message) {
            $times[] = date('H:i', $message->updated_at);
            $dates[] = date('d.m.Y', $message->updated_at);
        }



        if (Yii::$app->request->isAjax){

            $data = [
                'messages' => $messages,
                'times' => $times,
                'dates' => $dates,
                'admin' => $admin,
                'main_admin' => $main_admin,
            ];

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $data;
            return $data;

        }

    }



    public function actionUpdateConversations ($id)
    {

        if (User::isUserMainAdmin(Yii::$app->user->identity['username'])) {

            $conversation_e = ConversationMainAdmin::find()->where(['main_admin_id' => $id])->orderBy('updated_at DESC')->all();

            //Беседы, у которых есть сообщения
            $conversations_exist = [];
            $last_messages = [];
            $admins = [];
            $times = [];
            $dates = [];

            foreach ($conversation_e as $conversation) {
                if (!empty($conversation->messages)) {
                    $conversations_exist[] = $conversation;
                    $last_messages[] = $conversation->lastMessage;
                    $admins[] = $conversation->admin;
                    $times[] = date('H:i',$conversation->updated_at);
                    $dates[] = date('d.m.Y',$conversation->updated_at);
                }
            }


            if (Yii::$app->request->isAjax){

                $data = [
                    'convers' => $conversations_exist,
                    'last' => $last_messages,
                    'main' => User::findOne($id),
                    'admins' => $admins,
                    'times' => $times,
                    'dates' => $dates,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $data;
                return $data;

            }
        }


        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $conversation_e = ConversationAdmin::find()->where(['admin_id' => $id])->orderBy('updated_at DESC')->all();

            //Беседы, у которых есть сообщения
            $conversations_exist = [];
            $last_messages = [];
            $users = [];
            $times = [];
            $dates = [];

            foreach ($conversation_e as $conversation) {
                if (!empty($conversation->messages)) {
                    $conversations_exist[] = $conversation;
                    $last_messages[] = $conversation->lastMessage;
                    $users[] = $conversation->user;
                    $times[] = date('H:i',$conversation->updated_at);
                    $dates[] = date('d.m.Y',$conversation->updated_at);
                }
            }


            if (Yii::$app->request->isAjax){

                $data = [
                    'convers' => $conversations_exist,
                    'last' => $last_messages,
                    'main' => User::findOne($id),
                    'users' => $users,
                    'times' => $times,
                    'dates' => $dates,
                ];

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $data;
                return $data;

            }
        }



    }

}