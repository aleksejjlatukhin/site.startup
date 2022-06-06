<?php


namespace app\models;

use yii\base\Model;

/**
 * Подтверждение эл.почты при регистрации с помощью перехода
 * по ссылке отпрааленной в письме на эту почту
 *
 * Class AccountActivation
 * @package app\models
 *
 * @property User $_user            Текущий пользователь
 * @property bool $exist            Проверка ключа в ссылке
 */
class AccountActivation extends Model
{

    /* @var $_user User */
    private $_user;
    public $exist = true;


    /**
     * AccountActivation constructor.
     *
     * @param $key
     * @param array $config
     */
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            $this->setExist(false); // Ключ не может быть пустым
        $this->setUser(User::findBySecretKey($key));
        if(!$this->getUser())
            $this->setExist(false); // Не верный ключ! Возможно истекло время его действия
        parent::__construct($config);
    }


    /**
     * Подтвреждение регистрации по email
     *
     * @return bool
     */
    public function activateAccount()
    {
        $user = $this->getUser();
        $user->setConfirm(User::CONFIRM);
        $user->removeSecretKey();
        return $user->save();
    }


    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getUser()->getUsername();
    }


    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return bool
     */
    public function isExist()
    {
        return $this->exist;
    }

    /**
     * @param bool $exist
     */
    public function setExist($exist)
    {
        $this->exist = $exist;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

}