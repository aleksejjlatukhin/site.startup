<?php


namespace app\models;

use yii\base\Model;
use Yii;

class SingupForm extends Model
{

    public $second_name;
    public $first_name;
    public $middle_name;
    public $telephone;
    public $email;
    public $username;
    public $password;
    public $status;

    public function rules()
    {
        return [
            [['second_name', 'first_name', 'middle_name', 'email', 'username', 'password'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone', 'password'], 'trim'],

            ['username', 'string', 'min' => 3, 'max' => 32],
            ['password', 'string', 'min' => 6, 'max' => 32],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone'], 'string', 'max' => 255],

            ['username', 'match', 'pattern' => '/[a-z]+/i', 'message' => '{attribute} должен содержать только латиницу!'],
            ['username', 'uniqUsername'],
            ['username', 'unique',
                'targetClass' => User::class,
                'message' => 'Этот логин уже занят.'],

            [['email'], 'email'],
            ['email', 'unique',
                'targetClass' => User::class,
                'message' => 'Эта почта уже зарегистрирована.'],

            ['status', 'default', 'value' => User::STATUS_ACTIVE, 'on' => 'default'],
            ['status', 'in', 'range' =>[
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE
            ]],

        ];
    }

    public function attributeLabels()
    {
        return [
            'second_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'telephone' => 'Телефон',
            'email' => 'Эл.почта',
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить'
        ];
    }

    /*Собственное правило для поля username*/
    /*Переводим все логины в нижний регистр и сравниваем их с тем, что в форме*/
    public function uniqUsername($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){
            if (mb_strtolower($this->username) === mb_strtolower($user->username)){
                $this->addError($attr, 'Этот логин уже занят.');
            }
        }
    }



    public function singup()
    {
        $user = new User();
        $user->second_name = $this->second_name;
        $user->first_name = $this->first_name;
        $user->middle_name = $this->middle_name;
        $user->telephone = $this->telephone;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->role = 'user';
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }

}