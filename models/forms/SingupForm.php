<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;
use Yii;

class SingupForm extends Model
{

    public $second_name;
    public $first_name;
    public $middle_name;
    public $telephone;
    public $email;
    public $username;
    public $uniq_username = true;
    public $match_username = true;
    public $uniq_email = true;
    public $password;
    public $status;
    public $confirm;
    public $role;
    public $exist_agree = true;

    public function rules()
    {
        return [
            [['exist_agree', 'uniq_username', 'match_username', 'uniq_email'],'boolean'],
            ['exist_agree', 'existAgree'],
            [['second_name', 'first_name', 'middle_name', 'email', 'username', 'password'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone', 'password'], 'trim'],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone'], 'string', 'max' => 255],
            ['username', 'matchUsername'],
            ['username', 'uniqUsername'],
            ['email', 'uniqEmail'],

            ['confirm', 'default', 'value' => User::NOT_CONFIRM, 'on' => 'emailActivation'],
            ['confirm', 'in', 'range' =>[
                User::CONFIRM,
                User::NOT_CONFIRM,
            ]],

            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE,],
            ['status', 'in', 'range' =>[
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE,
                User::STATUS_DELETED,
            ]],

            ['role', 'default', 'value' => User::ROLE_USER],
            ['role', 'in', 'range' =>[
                User::ROLE_USER,
                User::ROLE_ADMIN,
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
            'email' => 'Email',
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
            'role' => 'Проектная роль пользователя',
            'exist_agree' => 'Я принимаю пользовательское соглашение'
        ];
    }

    //Согласие на обработку данных
    public function existAgree($attr)
    {
        if ($this->exist_agree != 1){
            $this->addError($attr, 'Необходимо принять пользовательское соглашение');
        }
    }

    //Собственное правило для поля username
    //Переводим все логины в нижний регистр и сравниваем их с тем, что в форме
    public function uniqUsername($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){
            if (mb_strtolower($this->username) === mb_strtolower($user->username)){
                $this->uniq_username = false;
                $this->addError($attr, 'Этот логин уже занят.');
            }
        }
    }

    public function matchUsername($attr)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $this->username)) {
            $this->match_username = false;
            $this->addError($attr, 'Логин должен содержать только латинские символы и цыфры.');
        }

        if (preg_match('/\s+/',$this->username)) {
            $this->match_username = false;
            $this->addError($attr, 'Не допускается использование пробелов');
        }
    }

    //Собственное правило для поля email
    //Переводим все логины в нижний регистр и сравниваем их с тем, что в форме
    public function uniqEmail($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){
            if ($this->email === $user->email){
                $this->uniq_email = false;
                $this->addError($attr, 'Эта почта уже зарегистрирована.');
            }
        }
    }

    public function singup()
    {
        if ($this->exist_agree == 1){

            $user = new User();
            $user->second_name = $this->second_name;
            $user->first_name = $this->first_name;
            $user->middle_name = $this->middle_name;
            $user->telephone = $this->telephone;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;
            $user->confirm = $this->confirm;
            $user->role = $this->role;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if($this->scenario === 'emailActivation') {
                $user->generateSecretKey();
            }

            return $user->save() ? $user : null;
        }
    }

    /*Подтвреждение регистрации по email*/
    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
            ->setTo($this->email)
            ->setSubject('Регистрация на сайте Spaccel.ru')
            ->send();
    }

}