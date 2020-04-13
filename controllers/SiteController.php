<?php

namespace app\controllers;

use Yii;
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
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('index', compact('user'));
    }


    public function actionTargetSegment()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('target-segment', compact('user'));
    }


    public function actionSegmentProblems()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('segment-problems', compact('user'));
    }


    public function actionProblemConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('problem-confirmation', compact('user'));
    }


    public function actionValueProposition()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('value-proposition', compact('user'));
    }


    public function actionOfferConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('offer-confirmation', compact('user'));
    }


    public function actionDevelopmentMvp()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('development-mvp', compact('user'));
    }


    public function actionMvpConfirmation()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
            $this->layout = '@app/modules/admin/views/layouts/base';
        }

        return $this->render('mvp-confirmation', compact('user'));
    }

    public function actionBusinessModel()
    {
        $user = Yii::$app->user->identity;

        /*Подключение шаблона администратора в пользовательской части*/
        if (User::isUserAdmin(Yii::$app->user->identity['username'])){
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

        $model = new SingupForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            if ($user = $model->singup()){

                if (Yii::$app->getUser()->login($user)){

                    if ($user->status === User::STATUS_NOT_ACTIVE) {
                        Yii::$app->session->setFlash('success', 'Поздравляем Вы успешно прошли регистрацию! Ожидайте активации вашего профиля администратором.');
                    }

                    return $this->goHome();
                }
            }else{
                Yii::$app->session->setFlash('error', 'Возникла ошибка при регистрации.');
                Yii::error('Ошибка при регистрации');
                return $this->refresh();
            }
        }

        return $this->render('singup', [
            'model' => $model,
        ]);
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

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if (User::isUserAdmin(Yii::$app->user->identity['username'])){
                return $this->redirect('/admin');
            }

            return $this->goBack();
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
