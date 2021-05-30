<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class LoginForm extends Model
{

    public $identity;
    public $password;
    public $rememberMe = true;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['identity', 'filter', 'filter' => 'trim'],
            ['identity', 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'identity' => 'Логин или адрес эл.почты',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }


    /**
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Логин/пароль введены не верно!');
            }
        }
    }


    /**
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            //$this->status = ($user = $this->getUser()) ? $user->status : User::STATUS_NOT_ACTIVE;
            $user = $this->getUser();
            //if ($this->status === User::STATUS_ACTIVE) {
            if ($user) {
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /**
     * @return bool|mixed|ActiveRecord
     */
    public function getUser()
    {
        if ($this->_user === false)
            $this->_user = User::findIdentityByUsernameOrEmail($this->identity);
        return $this->_user;
    }


    /**
     * Подтвреждение регистрации по email
     * @param $user
     * @return bool
     */
    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => 'StartPool - Акселератор стартап-проектов'])
            ->setTo($user->email)
            ->setSubject('Регистрация на сайте StartPool')
            ->send();

    }
}
