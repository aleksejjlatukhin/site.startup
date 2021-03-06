<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use Yii;


class SendEmailForm extends Model
{

    public $email;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Эл.почта'
        ];
    }


    /**
     * @return bool
     */
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