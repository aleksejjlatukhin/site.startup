<?php


namespace app\models;

use yii\base\Model;

/**
 * Форма для сброса пароля
 *
 * Class ResetPasswordForm
 * @package app\models
 *
 * @property string $password
 * @property bool $exist
 * @property User $_user
 */
class ResetPasswordForm extends Model
{

    public $password;
    public $exist = true;
    private $_user;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['password', 'string'],
            ['exist', 'boolean'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль'
        ];
    }

    /**
     * ResetPasswordForm constructor.
     *
     * @param $key
     * @param array $config
     */
    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            $this->exist = false; // Ключ не может быть пустым
        $this->_user = User::findBySecretKey($key);
        if(!$this->_user)
            $this->exist = false; // Не верный ключ
        parent::__construct($config);
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function resetPassword()
    {
        /* @var $user User */
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }
}