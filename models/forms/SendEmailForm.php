<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use Yii;


class SendEmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            //['email', 'required'],
            //['email', 'email'],
            //['email', 'exist',
                //'targetClass' => User::class,
                //'filter' => [
                    //'status' => User::STATUS_ACTIVE
                //],
                //'message' => 'Данный адрес эл.почты не зарегистрирован.'
            //],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Эл.почта'
        ];
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne(['email' => $this->email]);

        if($user){

            $user->generateSecretKey();

            if($user->save()){

                return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                    ->setTo($this->email)
                    ->setSubject('Изменение пароля на сайте Spaccel.ru для пользователя ' . $user->username)
                    ->send();
            }
        }

        return false;
    }
}