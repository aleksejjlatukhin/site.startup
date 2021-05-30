<?php


namespace app\models\forms;

use app\models\User;
use yii\base\Model;
use Yii;

class ProfileForm extends Model
{

    public $id;
    public $second_name;
    public $first_name;
    public $middle_name;
    public $telephone;
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
            [['second_name', 'first_name', 'middle_name', 'username', 'email'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone'], 'trim'],
            [['second_name', 'first_name', 'middle_name', 'telephone', 'email'], 'string', 'max' => 255],
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
            $user->second_name = $this->second_name;
            $user->first_name = $this->first_name;
            $user->middle_name = $this->middle_name;
            $user->telephone = $this->telephone;
            $user->email = $this->email;
            $user->username = $this->username;

            return $user->save() ? $user : null;

        } else {

            $this->checking_mail_sending = false;
            return  $this;
        }
    }
}


