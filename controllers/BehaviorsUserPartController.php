<?php


namespace app\controllers;

use app\models\ClientSettings;
use app\models\ClientUser;
use app\models\User;
use Yii;
use yii\filters\AccessControl;

class BehaviorsUserPartController extends AppController
{

    public function beforeAction($action)
    {
        // Подключение шаблона для техподдержки, админа Spaccel и менеджера в пользовательской части
        if (User::isUserDev(Yii::$app->user->identity['username'])
            || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserManager(Yii::$app->user->identity['username'])) {
            $this->layout = '@app/modules/admin/views/layouts/main-user';
        }

        // Подключение шаблона трекера организации в пользовательской части
        if (User::isUserAdmin(Yii::$app->user->identity['username'])) {
            $user = User::findOne(Yii::$app->user->id);
            /** @var ClientUser $clientUser */
            $clientUser = $user->clientUser;
            $clientSettings = ClientSettings::findOne(['client_id' => $clientUser->getClientId()]);
            $admin = User::findOne($clientSettings->getAdminId());

            if (User::isUserMainAdmin($admin->getUsername())) {
                $this->layout = '@app/modules/admin/views/layouts/main-user';
            } else {
                $this->layout = '@app/modules/client/views/layouts/main-user';
            }
        }

        // Подключение шаблона админа организации в пользовательской части
        if (User::isUserAdminCompany(Yii::$app->user->identity['username'])) {
            $this->layout = '@app/modules/client/views/layouts/main-user';
        }

        // Подключение шаблона эксперта в пользовательской части
        if (User::isUserExpert(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/expert/views/layouts/main-user';
        }

        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [

                'class' => AccessControl::class,

                /*Вызов исключения в случае отсутствия доступа*/
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/

                'denyCallback' => function ($rule, $action) {
                    return $this->goHome();
                },

                'rules' => [

                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['get-form-registration', 'registration', 'singup', 'error', 'login', 'index', 'about', 'send-email', 'reset-password', 'activate-account', 'confidentiality-policy', 'download-presentation'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['?']
                    ],

                    [
                        'allow' => true,
                        'controllers' => ['site', 'profile'],
                        'actions' => ['singup', 'login', 'index', 'about', 'send-email', 'reset-password', 'update-profile', 'change-password',
                            'logout', 'project', 'roadmap', 'prefiles', 'not-found'],
                        'verbs' => ['GET', 'POST'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !User::isActiveStatus(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserSimple(Yii::$app->user->identity['username']);
                        }
                    ],

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

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserExpert(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserManager(Yii::$app->user->identity['username']);
                        }
                    ],

                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return User::isUserAdminCompany(Yii::$app->user->identity['username']);
                        }
                    ],
                ]

            ]

        ];
    }
}