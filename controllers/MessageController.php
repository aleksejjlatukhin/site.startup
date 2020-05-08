<?php


namespace app\controllers;


use app\models\User;
use app\models\ConversationAdmin;
use Yii;
use app\models\MessageAdmin;

class MessageController extends AppController
{

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

        }else{
            return parent::beforeAction($action);
        }

    }



    public function actionView ($id)
    {

        $conversation = ConversationAdmin::findOne($id);

        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {

            $this->layout = '@app/modules/admin/views/layouts/main';

            $user = User::findOne(['id' => $conversation->user_id]);
            $admin = User::findOne(['id' => $conversation->admin_id]);
            $messages = $conversation->messages;

            $conversations = ConversationAdmin::findAll(['admin_id' => $conversation->admin_id]);

            $model = new MessageAdmin();
            $model->conversation_id = $id;
            $model->sender_id = $admin->id;
            $model->adressee_id = $user->id;

            if (Yii::$app->request->isAjax){

                if ($model->load(Yii::$app->request->post())) {

                    $model->description = $_POST['MessageAdmin']['description'];

                    if ($model->save()){

                        $conversation->updated_at = $model->updated_at;
                        $conversation->save();

                        $data = [
                            'message' => $model,
                            'user' => $user,
                            'admin' => $admin,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $data;
                        return $data;
                    }
                }
            }

            return $this->render('view-admin', [
                'conversation' => $conversation,
                'conversations' => $conversations,
                'user' => $user,
                'admin' => $admin,
                'model' => $model,
                'messages' => $messages,
            ]);
        }

        if (User::isUserSimple(Yii::$app->user->identity['username'])) {

            $this->layout = 'profile';

            $user = User::findOne(['id' => $conversation->user_id]);
            $admin = User::findOne(['id' => $conversation->admin_id]);
            $messages = $conversation->messages;

            $model = new MessageAdmin();
            $model->conversation_id = $id;
            $model->sender_id = $user->id;
            $model->adressee_id = $admin->id;

            if (Yii::$app->request->isAjax){

                if ($model->load(Yii::$app->request->post())) {

                    $model->description = $_POST['MessageAdmin']['description'];

                    if ($model->save()){

                        $conversation->updated_at = $model->updated_at;
                        $conversation->save();

                        $data = [
                            'message' => $model,
                            'user' => $user,
                            'admin' => $admin,
                        ];

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $data;
                        return $data;
                    }
                }
            }

            return $this->render('view', [
                'conversation' => $conversation,
                'user' => $user,
                'admin' => $admin,
                'model' => $model,
                'messages' => $messages,
            ]);
        }
    }



    public function actionUpdate ($id)
    {

        $conversation = ConversationAdmin::findOne($id);
        $user = User::findOne(['id' => $conversation->user_id]);
        $admin = User::findOne(['id' => $conversation->admin_id]);

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
                'user' => $user,
                'admin' => $admin,
            ];

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            \Yii::$app->response->data = $data;
            return $data;

        }

    }
}