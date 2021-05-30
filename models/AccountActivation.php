<?php


namespace app\models;

use yii\base\Model;

class AccountActivation extends Model
{

    /* @var $_user User */
    private $_user;
    public $exist = true;


    /**
     * AccountActivation constructor.
     * @param $key
     * @param array $config
     */
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            $this->exist = false; // Ключ не может быть пустым
        $this->_user = User::findBySecretKey($key);
        if(!$this->_user)
            $this->exist = false; // Не верный ключ! Возможно истекло время его действия
        parent::__construct($config);
    }


    /**
     * Подтвреждение регистрации по email
     * @return bool
     */
    public function activateAccount()
    {
        $user = $this->_user;
        $user->confirm = User::CONFIRM;
        $user->removeSecretKey();
        return $user->save();
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }


    /**
     * @return User|null
     */
    public function getUser()
    {
        $user = $this->_user;
        return $user;
    }

}