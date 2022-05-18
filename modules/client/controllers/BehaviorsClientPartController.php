<?php


namespace app\modules\client\controllers;

use app\controllers\AppController;
use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\User;
use Yii;
use yii\filters\AccessControl;

class BehaviorsClientPartController extends AppController
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
                            $user = User::findOne(Yii::$app->user->id);
                            /** @var ClientUser $clientUser */
                            $clientUser = $user->clientUser;
                            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);
                            $isActiveClient = $clientUser->findClient()->isActive();
                            $admin = User::findOne($clientSettings->getAdminId());
                            return User::isUserAdmin($user->getUsername()) && !User::isUserMainAdmin($admin->getUsername()) && $isActiveClient;
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = User::findOne(Yii::$app->user->id);
                            /** @var ClientUser $clientUser */
                            $clientUser = $user->clientUser;
                            $isActiveClient = $clientUser->findClient()->isActive();
                            return User::isUserAdminCompany($user->getUsername()) && $isActiveClient;
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserDev(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserManager(Yii::$app->user->identity['username']);
                        }
                    ],
                ]

            ]

        ];
    }
}