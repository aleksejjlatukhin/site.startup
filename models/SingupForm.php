<?php


namespace app\models;

use yii\base\Model;
use Yii;

class SingupForm extends Model
{

    public $fio;
    public $telephone;
    public $email;
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['fio', 'telephone', 'email', 'username', 'password'], 'required'],
            [['fio','username',], 'trim'],
            [['email'], 'email'],
            ['rememberMe', 'boolean'],
            [['fio', 'email', 'telephone', 'username', 'password'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'telephone' => 'Телефон',
            'email' => 'Эл.почта',
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить'
        ];
    }
}