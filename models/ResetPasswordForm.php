<?php


namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;

class ResetPasswordForm extends Model
{
    public $password;
    public $exist = true;
    private $_user;

    public function rules()
    {
        return [
            //['password', 'required'],
            ['password', 'string', /*'min' => 6, 'max' => 32*/],
            ['exist', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }

    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            //throw new InvalidArgumentException('Ключ не может быть пустым.');
            $this->exist = false;
        $this->_user = User::findBySecretKey($key);
        if(!$this->_user)
            //throw new InvalidArgumentException('Не верный ключ.');
            $this->exist = false;
        parent::__construct($config);
    }

    public function resetPassword()
    {
        /* @var $user User */
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }

}