<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Форма для авторизации на сайте
 *
 * Class LoginForm
 * @package app\models
 *
 * @property string $identity                   Логин или email пользователя
 * @property string $password                   Пароль пользователя
 * @property bool $rememberMe                   Флаг "Запомнить меня"
 * @property User|false $_user                  Объект авторизованного пользователя
 */
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

            if (!$user || !$user->validatePassword($this->getPassword())) {
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
            $user = $this->getUser();
            if ($user) {
                return Yii::$app->user->login($user, $this->isRememberMe() ? 3600 * 24 * 30 : 0);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /**
     * @return bool|User|ActiveRecord
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

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return bool
     */
    public function isRememberMe()
    {
        return $this->rememberMe;
    }

    /**
     * @param bool $rememberMe
     */
    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = $rememberMe;
    }

    /**
     * @param User|false $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }
}
