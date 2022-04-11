<?php


namespace app\modules\admin\models\form;

use app\models\User;
use yii\base\Exception;
use yii\base\Model;

/**
 * Форма создания админа организации при создании организации
 *
 * Class FormCreateAdminCompany
 * @package app\modules\admin\models\form
 *
 * @property string $second_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $telephone
 * @property string $email
 * @property string $username
 * @property int $status
 * @property int $confirm
 * @property int $role
 */
class FormCreateAdminCompany extends Model
{

    //TODO: Необходимо здесь создать админа (User ClientUser),
    // кроме этого необходимо будет создать беседы с Тех поддержкой,
    // а в дальнейшем и с другими пользователями, при этом это должны
    // быть беседы conversation_main_admin (с трекерами) и т.д.
    // После этого так же создать объект ClientSettings.
    // После заполнения необходимых форм передать их в класс Создатель,
    // который вызовет все необходимые методы и запишет данные в бд.

    public $second_name;
    public $first_name;
    public $middle_name;
    public $telephone;
    public $email;
    public $username;
    public $status;
    public $confirm;
    public $role;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['second_name', 'first_name', 'middle_name', 'email', 'username'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone'], 'trim'],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone'], 'string', 'max' => 255],
            ['username', 'matchUsername'],
            ['username', 'uniqUsername'],
            ['email', 'uniqEmail'],

            ['confirm', 'default', 'value' => User::CONFIRM],
            ['confirm', 'in', 'range' => [User::CONFIRM]],

            ['status', 'default', 'value' => User::STATUS_ACTIVE],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE]],

            ['role', 'default', 'value' => User::ROLE_ADMIN_COMPANY],
            ['role', 'in', 'range' => [User::ROLE_ADMIN_COMPANY]],

        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'second_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'telephone' => 'Телефон',
            'email' => 'Email',
            'username' => 'Логин',
        ];
    }


    /**
     * Собственное правило для поля username
     * Переводим все логины в нижний регистр
     * и сравниваем их с тем, что в форме
     * @param $attr
     */
    public function uniqUsername($attr)
    {
        $users = User::find()->all();
        foreach ($users as $user){
            if (mb_strtolower($this->username) === mb_strtolower($user->username)){
                $this->addError($attr, 'Этот логин уже занят.');
            }
        }
    }


    /**
     * @param $attr
     */
    public function matchUsername($attr)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $this->username)) {
            $this->addError($attr, 'Логин должен содержать только латинские символы и цыфры.');
        }
        if (preg_match('/\s+/',$this->username)) {
            $this->addError($attr, 'Не допускается использование пробелов');
        }
    }


    /**
     * Собственное правило для поля email
     * Переводим все email в нижний регистр и сравниваем их с тем, что в форме
     * @param $attr
     */
    public function uniqEmail($attr)
    {
        $users = User::find()->all();
        foreach ($users as $user){
            if ($this->email === $user->email){
                $this->addError($attr, 'Эта почта уже зарегистрирована.');
            }
        }
    }


    /**
     * @return User|bool|null
     * @throws Exception
     */
    public function create()
    {
        $user = new User();
        $user->attributes = $this->attributes;
        $user->setPassword($this->username); // Пароль такой же как и логин, чтобы не забыть (в дальнейшем клиент должен его поменять самостоятельно)
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
}