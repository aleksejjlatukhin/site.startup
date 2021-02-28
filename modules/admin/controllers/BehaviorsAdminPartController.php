<?php


namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\models\User;
use Yii;
use yii\filters\AccessControl;

class BehaviorsAdminPartController extends AppController
{
    public function behaviors()
    {
        return [
            'access' => [

                'class' => AccessControl::class,

                /*Вызов исключения в случае отсутствия доступа*/
                'denyCallback' => function ($rule, $action) {
                    return $this->goHome();
                },

                'rules' => [

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserMainAdmin(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserDev(Yii::$app->user->identity['username']);
                        }
                    ],
                ]

            ]

        ];
    }
}