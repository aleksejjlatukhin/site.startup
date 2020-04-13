<?php


namespace app\modules\admin\controllers;


use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;

class UsersController extends AppAdminController
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(['query' => User::find()->where(['role' => User::ROLE_USER])]);

        $users = User::find()->all();
        foreach ($users as $user){

            if (!empty($user->projects)){

                $project_update_at = [];

                foreach ($user->projects as $project) {

                    $project_update_at[] = strtotime($project->update_at);
                }

                if (max($project_update_at) > $user->updated_at){

                    $user->updated_at = max($project_update_at);
                    $user->save();
                }

            }
        }

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAdmins()
    {
        $dataProvider = new ActiveDataProvider(['query' => User::find()->where(['role' => User::ROLE_ADMIN])]);

        return $this->render('admins',[
            'dataProvider' => $dataProvider,
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id == 'status-update') {
            // ОТКЛЮЧАЕМ CSRF
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionStatusUpdate ($id, $status)
    {
        $model = User::findOne($id);

        if ($status == 'active'){
            $model->status = User::STATUS_ACTIVE;

        }elseif ($status == 'delete'){
            $model->status = User::STATUS_DELETED;
        }

        if (Yii::$app->request->isAjax){

            if ($model->save()){

                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                \Yii::$app->response->data = $model;
                return $model;

            }
        }
        return false;
    }

}