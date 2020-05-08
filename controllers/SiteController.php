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

        return $this->render('index', compact('user'));
    }


    public function actionTargetSegment()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('target-segment', compact('user'));
    }


    public function actionSegmentProblems()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('segment-problems', compact('user'));
    }


    public function actionProblemConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('problem-confirmation', compact('user'));
    }


    public function actionValueProposition()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('value-proposition', compact('user'));
    }


    public function actionOfferConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('offer-confirmation', compact('user'));
    }


    public function actionDevelopmentMvp()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('development-mvp', compact('user'));
    }


    public function actionMvpConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('mvp-confirmation', compact('user'));
    }

    public function actionBusinessModel()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
            || User::isUserDev(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('business-model', compact('user'));
    }


    /**
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionSingup()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $emailActivation = Yii::$app->params['emailActivation'];
        $model = $emailActivation ? new SingupForm(['scenario' => 'emailActivation']) : new SingupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($user = $model->singup()){

                if ($user->confirm == User::CONFIRM) {

                    if (Yii::$app->getUser()->login($user)){

                        return $this->goHome();
                    }
                }else {

                    try {

                        $model->sendActivationEmail($user);
                        Yii::$app->session->setFlash('success', '<div style="text-align: center">Письмо с подтверждением регистрации отправлено на указанный адрес: <strong>' .
                            Html::encode($user->email).'</strong></div>');

                    }catch (\Exception $e) {

                        $user->delete();
                        throw new \yii\web\HttpException('550','Ошибка. Не отправляются письма на указанный адрес эл.почты: '. $model->email .'.');
                        //Yii::$app->session->setFlash('error', 'Ошибка. Письмо не отправлено.');
                        //Yii::error('Ошибка отправки письма.');
                    }
                    return $this->refresh();
                }
            }else{
                Yii::$app->session->setFlash('error', '<div style="text-align: center">Возникла ошибка при регистрации.</div>');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            }
        }

        return $this->render('singup', [
            'model' => $model,
        ]);
    }



    public function actionActivateAccount($key)
    {
        try {
            $user = new AccountActivation($key);
        }
        catch(InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user->activateAccount()) {

            //Отправка письма админу после подтверждения регистрации
            $_user = $user->user;
            $_user->sendEmailAdmin($_user);

            Yii::$app->session->setFlash('success', '<div style="text-align: center">Подтверждение регистрации прошло успешно.</div>');

        } else {

            Yii::$app->session->setFlash('error', '<div style="text-align: center">Ошибка подтверждения регистрации.</div>');
            Yii::error('Ошибка при подтверждении регистрации.');
        }

        return $this->redirect(Url::to(['/site/login']));
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {

            $user = $model->user;

            if ($user->confirm == User::NOT_CONFIRM) {

                $user->generateSecretKey();
                $user->save();
                if ($model->sendActivationEmail($user)) {

                    Yii::$app->session->setFlash('success', '<div style="text-align: center">Письмо с подтверждением регистрации отправлено на указанный адрес: <strong>' .
                        Html::encode($user->email).'</strong></div>');
                    return $this->refresh();
                }
            }else {

                if ($model->login()) {

                    if (User::isUserAdmin(Yii::$app->user->identity['username']) || User::isUserMainAdmin(Yii::$app->user->identity['username'])
                        || User::isUserDev(Yii::$app->user->identity['username'])){
                        return $this->redirect('/admin');
                    }

                    if (User::isUserSimple(Yii::$app->user->identity['username'])){
                        return $this->goBack();
                    }

                    if (($user->confirm == User::CONFIRM) && ($user->status == User::STATUS_NOT_ACTIVE)){
                        Yii::$app->session->setFlash('success', '<p>Скоро Вы сможете приступить к работе на нашем сайте. Ожидайте активации вашего стутуса.</p><p>Мы отправим Вам письмо на электронную почту, когда будет принято данное решение</p>');
                        return $this->goBack();
                    }
                }
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
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
            if ($model->validate()) {
                if($model->sendEmail()):
                    Yii::$app->getSession()->setFlash('warning', 'Проверьте емайл.');
                    return $this->goHome();
                else:
                    Yii::$app->getSession()->setFlash('error', 'Нельзя сбросить пароль.');
                endif;
            }
        }

        return $this->render('send-email', [
            'model' => $model,
        ]);
    }


    public function actionResetPassword($key)
    {
        try {
            $model = new ResetPasswordForm($key);
        }
        catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->resetPassword()) {
                Yii::$app->getSession()->setFlash('warning', 'Пароль изменен.');
                return $this->redirect(['/site/login']);
            }
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }*/

    /**
     * Displays about page.
     *
     * @return string
     */
    /*public function actionAbout()
    {
        return $this->render('about');
    }*/
}
