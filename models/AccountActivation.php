<?php


namespace app\models;


use yii\base\InvalidArgumentException;
use yii\base\Model;
use Yii;

/* @property string $username */
/*Подтвреждение регистрации по email*/

class AccountActivation extends Model
{
    /* @var $user \app\models\User */
    private $_user;
    public $exist = true;

    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            //throw new InvalidArgumentException('Ключ не может быть пустым!');
            $this->exist = false;
        $this->_user = User::findBySecretKey($key);
        if(!$this->_user)
            //throw new InvalidArgumentException('Не верный ключ! Возможно истекло время его действия...');
            $this->exist = false;
        parent::__construct($config);
    }

    public function activateAccount()
    {
        $user = $this->_user;
        $user->confirm = User::CONFIRM;
        $user->removeSecretKey();
        return $user->save();
    }

    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }

    public function getUser()
    {
        $user = $this->_user;
        return $user;
    }

}