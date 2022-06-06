<?php


namespace app\models\forms;

use yii\base\Exception;
use yii\base\Model;
use app\models\User;

/**
 * Форма для изменения пароля пользователя
 *
 * Class PasswordChangeForm
 * @package app\models\forms
 *
 * @property string $currentPassword                Текущий пароль
 * @property string $newPassword                    Новый пароль
 * @property string $newPasswordRepeat              Повторный ввод нового пароля
 * @property User $_user                            Объект текущего пользователя
 */
class PasswordChangeForm extends Model
{

    public $currentPassword;
    public $newPassword;
    public $newPasswordRepeat;


    /**
     * @var User
     */
    private $_user;


    /**
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, $config = [])
    {
        $this->setUser($user);
        parent::__construct($config);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'string', 'min' => 6, 'max' => 32],
            [['currentPassword', 'newPassword', 'newPasswordRepeat'], 'spaceInPassword'],
            ['currentPassword', 'validatePassword'],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'newPassword' => 'Новый пароль',
            'newPasswordRepeat' => 'Повторите новый пароль',
            'currentPassword' => 'Актуальный пароль',
        ];
    }


    /**
     * @param string $attribute
     * @param array $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser()->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Ошибка! Неверный текущий пароль.');
            }
        }
    }


    /**
     * @param $attr
     */
    public function spaceInPassword ($attr)
    {
        if (preg_match('/\s+/',$this->$attr)) {
            $this->addError($attr, 'Не допускается использование пробелов');
        }
    }


    /**
     * @return boolean
     * @throws Exception
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            return $user->save();
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     */
    public function setCurrentPassword($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }

    /**
     * @return string
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @return string
     */
    public function getNewPasswordRepeat()
    {
        return $this->newPasswordRepeat;
    }

    /**
     * @param string $newPasswordRepeat
     */
    public function setNewPasswordRepeat($newPasswordRepeat)
    {
        $this->newPasswordRepeat = $newPasswordRepeat;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

}