<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SingupForm;
use app\models\User;
use app\models\ResetPasswordForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use app\models\SendEmailForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AccountActivation;

class SiteController extends AppController
{
    public $layout = 'base';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])|| User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        if (Yii::$app->user->isGuest) {

            $model_login = new LoginForm();
            $model_send_email = new SendEmailForm();
            $model_signup = new SingupForm();

            return $this->render('index', compact('user', 'model_login', 'model_send_email', 'model_signup'));

        }else {

            return $this->render('index', compact('user'));
        }
    }


    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function actionSingup()
    {

        if (Yii::$app->user->isGuest) {

            $emailActivation = Yii::$app->params['emailActivation'];
            $model = $emailActivation ? new SingupForm(['scenario' => 'emailActivation']) : new SingupForm();

            if ($model->load(Yii::$app->request->post())) {

                if (Yii::$app->request->isAjax) {

                    if ($model->validate()) {

                        if ($user = $model->singup()) {

                            if ($user->confirm == User::NOT_CONFIRM) {

                                /*try {

                                    $model->sendActivationEmail($user);
                                    Yii::$app->session->setFlash('success', '<div style="text-align: center">Письмо с подтверждением регистрации отправлено на указанный адрес: <strong>' .
                                        Html::encode($user->email) . '</strong></div>');

                                } catch (\Exception $e) {

                                    $user->delete();
                                    throw new \yii\web\HttpException('550', 'Ошибка. Не отправляются письма на указанный адрес эл.почты: ' . $model->email . '.');
                                    //Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                                    //Yii::error('Ошибка отправки письма.');
                                }
                                return $this->refresh();*/


                                if ($model->sendActivationEmail($user)) {

                                    //Письмо с подтверждение отправлено
                                    $response = [
                                        'success_singup' => true,
                                        'message' => 'Письмо с подтверждением регистрации отправлено на указанный email.',
                                    ];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;

                                } else {

                                    $user->delete();

                                    //Письмо с подтверждение не отправлено
                                    $response = [
                                        'error_singup_send_email' => true,
                                        'message' => 'Ошибка. Не отправляются письма на указанный email.',
                                    ];
                                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                    \Yii::$app->response->data = $response;
                                    return $response;
                                }

                            }
                        } else {

                            //Возникла ошибка при регистрации
                            $response = ['error_model_singup' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {

                        $response = [
                            'error_uniq_email' => false,
                            'error_uniq_username' => false,
                            'error_exist_agree' => false,
                        ];

                        if ($model->uniq_email === false) {
                            $response['error_uniq_email'] = true;
                        }

                        if ($model->uniq_username === false) {
                            $response['error_uniq_username'] = true;
                        }

                        if ($model->exist_agree != 1) {
                            $response['error_exist_agree'] = true;
                        }

                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;
                    }
                }
            }
        }
    }



    public function actionActivateAccount($key)
    {
        /*try {
            $user = new AccountActivation($key);
        }
        catch(InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }*/

        $user = new AccountActivation($key);

        //Если ключ существует и не просрочен
        if ($user->exist === true) {

            //Если подтверждение регистрации прошло успешно
            if($user->activateAccount()) {

                //Отправка письма админу
                $_user = $user->user;
                $_user->sendEmailAdmin($_user);

                return $this->redirect(Url::to(['/']));

            } else {

                //Ошибка подтверждения регистрации

                $user = Yii::$app->user->identity;
                $model_login = new LoginForm();
                $model_send_email = new SendEmailForm();

                return $this->render('/site/activate-account', [
                    'user' => $user,
                    'model_login' => $model_login,
                    'model_send_email' => $model_send_email,
                ]);
            }

        } else {

            //Если ключ не существует или просрочен

            $user = Yii::$app->user->identity;
            $model_login = new LoginForm();
            $model_send_email = new SendEmailForm();

            return $this->render('/site/activate-account', [
                'user' => $user,
                'model_login' => $model_login,
                'model_send_email' => $model_send_email,
            ]);
        }


        /*if($user->activateAccount()) {

            //Отправка письма админу после подтверждения регистрации
            $_user = $user->user;
            $_user->sendEmailAdmin($_user);

            Yii::$app->session->setFlash('success', '<div style="text-align: center">Подтверждение регистрации прошло успешно.</div>');

        } else {

            Yii::$app->session->setFlash('error', '<div style="text-align: center">Ошибка подтверждения регистрации.</div>');
            Yii::error('Ошибка при подтверждении регистрации.');
        }

        return $this->redirect(Url::to(['/']));*/
    }

    /**
     * Login action.
     *
     * @return array
     */
    public function actionLogin()
    {

        if (Yii::$app->user->isGuest) {

            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post())) {

                if (Yii::$app->request->isAjax) {

                    $user = $model->user;

                    //Если пользователь не подтвердил регистрацию и ввел верно пароль
                    if ($user->confirm == User::NOT_CONFIRM && $user->validatePassword($model->password) === true) {

                        //Если ключ активации регистрационной ссылки просрочен, то отправить новое письмо
                        if ($user::isSecretKeyExpire($user->secret_key) < time()) {

                            $user->generateSecretKey();
                            $user->save();
                            if ($model->sendActivationEmail($user)) {

                                $response = [
                                    'error_not_confirm_singup' => true,
                                    'message' => 'Проверьте email, Вам отправлено новое письмо для подтверждения регистрации.'
                                ];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }

                        } else {

                            $response = [
                                'error_not_confirm_singup' => true,
                                'message' => 'Проверьте email, Вам было отправлено письмо для подтверждения регистрации.'
                            ];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    } else {

                        if ($model->login()) {

                            if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
                                || User::isUserDev(Yii::$app->user->identity['username'])) {

                                $response = ['admin_success' => true];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }

                            if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                                $response = ['user_success' => true];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }

                            if (($user->confirm == User::CONFIRM) && ($user->status == User::STATUS_NOT_ACTIVE || $user->status == User::STATUS_DELETED)) {

                                $response = ['user_success' => true];
                                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                                \Yii::$app->response->data = $response;
                                return $response;
                            }

                        } else {

                            //Если пара логин-пароль не существует
                            $response = ['error_not_user' => true];
                            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                            \Yii::$app->response->data = $response;
                            return $response;
                        }
                    }
                }
            }
        }
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['/']);
    }


    public function actionSendEmail()
    {

        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {

            if(Yii::$app->request->isAjax) {

                if ($model->validate()) {
                    if ($model->sendEmail()):

                        //Если отправлено письмо
                        $response =  [
                            'success' => true,
                            'message' => [
                                'title' => 'Проверьте email',
                                'text' => 'На указанный адрес отправлено письмо со сслылкой для восстановления пароля.'
                            ],
                        ];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;

                    else:

                        //Если письмо не отправлено письмо
                        $response =  [
                            'error' => true,
                            'message' => [
                                'title' => 'Запрос отменен',
                                'text' => 'Письмо на email не отправлено, указанный адрес не зарегистрирован.'
                            ],
                        ];
                        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        \Yii::$app->response->data = $response;
                        return $response;

                    endif;
                }
            }
        }
    }


    public function actionResetPassword($key)
    {
        /*try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }*/

        $model = new ResetPasswordForm($key);

        //Если $key прошел проверку на валидность
        if ($model->exist === true) {

            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate() && $model->resetPassword()) {
                    //Если пароль изменен
                    return $this->redirect(['/']);
                }
            }

            return $this->render('reset-password', [
                'model' => $model,
            ]);

        }else {

            $model_send_email = new SendEmailForm();

            return $this->render('reset-password', [
                'model' => $model,
                'model_send_email' => $model_send_email,
            ]);
        }
    }



    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('about', compact('user'));
    }
}
