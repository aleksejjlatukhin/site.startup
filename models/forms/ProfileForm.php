<?php


namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use Yii;

/**
 * Форма для редактирования профиля пользователя
 *
 * Class ProfileForm
 * @package app\models\forms
 *
 * @property int $id                    Идентификатор пользователя
 * @property string $username           Логин пользователя
 * @property string $email              Эл.почта пользователя
 */
class ProfileForm extends Model
{

    public $id;
    public $username;
    public $email;
    public $uniq_username = true;
    public $match_username = true;
    public $uniq_email = true;
    public $checking_mail_sending = true;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['uniq_username', 'match_username', 'uniq_email', 'checking_mail_sending'], 'boolean'],
            [['username', 'email'], 'required'],
            [['username', 'email'], 'trim'],
            [['email'], 'string', 'max' => 255],
            ['username', 'matchUsername'],
            ['username', 'uniqUsername'],
            ['email', 'uniqEmail'],
        ];
    }


    /**
     * ProfileForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $user = User::findOne($id);
        foreach ($user as $key => $value) {
            if (property_exists($this, $key)) $this[$key] = $value;
        }
        parent::__construct($config);
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
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
            if ($user->id != $this->id && mb_strtolower($this->username) === mb_strtolower($user->username)){
                $this->uniq_username = false;
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
            $this->match_username = false;
            $this->addError($attr, 'Логин должен содержать только латинские символы и цыфры.');
        }

        if (preg_match('/\s+/',$this->username)) {
            $this->match_username = false;
            $this->addError($attr, 'Не допускается использование пробелов');
        }
    }


    /**
     * @param $attr
     */
    public function uniqEmail($attr)
    {
        $users = User::find()->all();

        foreach ($users as $user){
            if ($user->id != $this->id && $this->email === $user->email){
                $this->uniq_email = false;
                $this->addError($attr, 'Эта почта уже зарегистрирована.');
            }
        }
    }


    /**
     * Отправка уведомления на email
     * @return bool
     */
    public function sendEmail()
    {
        try {

            $mail = Yii::$app->mailer->compose('changeProfile', ['user' => $this])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo($this->email)
                ->setSubject('Изменение профиля на сайте Spaccel.ru');

            $mail->send();
            return true;

        } catch (\Swift_TransportException  $e) {

            return  false;
        }
    }


    /**
     * @return $this|User|null
     */
    public function update()
    {
        if ($this->sendEmail()) {

            $user = User::findOne($this->id);
            $user->setEmail($this->email);
            $user->setUsername($this->username);

            return $user->save() ? $user : null;

        } else {

            $this->checking_mail_sending = false;
            return  $this;
        }
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}


