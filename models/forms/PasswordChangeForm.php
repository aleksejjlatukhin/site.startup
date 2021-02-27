<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\User;

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
        $this->_user = $user;
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
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Ошибка! Неверный текущий пароль.');
            }
        }
    }

    public function spaceInPassword ($attr)
    {
        if (preg_match('/\s+/',$this->$attr)) {
            $this->addError($attr, 'Не допускается использование пробелов');
        }
    }

    /**
     * @return boolean
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

}