<?php


namespace app\modules\expert\models\form;

use app\models\User;
use yii\base\Model;
use app\models\ExpertType;
use Yii;

class ProfileExpertForm extends  Model
{

    /**
     * id пользователя
     * @var int
     */
    public $id;

    /**
     * Фамилия
     * @var string
     */
    public $second_name;

    /**
     * Имя
     * @var string
     */
    public $first_name;

    /**
     * Отчество
     * @var string
     */
    public $middle_name;

    /**
     * Номер телефона
     * @var string
     */
    public $telephone;

    /**
     * Логин
     * @var string
     */
    public $username;

    /**
     * Эл. почта
     * @var string
     */
    public $email;

    /**
     * Образование
     * @var string
     */
    public $education;

    /**
     * Ученая степень
     * @var string
     */
    public $academic_degree;

    /**
     * Должность
     * @var string
     */
    public $position;

    /**
     * Тип эксперта
     * @var array
     */
    public $type;

    /**
     * Сфера профессиональной компетенции
     * @var string
     */
    public $scope_professional_competence;

    /**
     * Научные публикации
     * @var string
     */
    public $publications;

    /**
     * Реализованные проекты
     * @var string
     */
    public $implemented_projects;

    /**
     * Роль в реализованных проектах
     * @var string
     */
    public $role_in_implemented_projects;

    /**
     * Ключевые слова
     * @var string
     */
    public $keywords;

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
            [['second_name', 'first_name', 'middle_name', 'username', 'email',
                'education', 'academic_degree', 'position', 'type', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone',
                'education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'trim'],
            [['second_name', 'first_name', 'middle_name', 'telephone', 'email', 'education', 'academic_degree', 'position'], 'string', 'max' => 255],
            [['scope_professional_competence', 'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'string', 'max' => 2000],
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
        $user = User::find()->with(['expertInfo', 'keywords'])->where(['id' => $id])->one();
        $this->keywords = $user->keywords->description;
        foreach ($user as $key => $value) {
            if (property_exists($this, $key)) $this[$key] = $value;
        }
        foreach ($user->expertInfo as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key != 'id') {
                    if ($key == 'type') {
                        $this[$key] = ExpertType::getValue($value);
                    } else {
                        $this[$key] = $value;
                    }
                }
            }
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
            'education' => 'Образование',
            'academic_degree' => 'Ученая степень',
            'position' => 'Должность',
            'type' => 'Тип',
            'scope_professional_competence' => 'Сфера профессиональной компетенции',
            'publications' => 'Научные публикации',
            'implemented_projects' => 'Реализованные проекты',
            'role_in_implemented_projects' => 'Роль в реализованных проектах',
            'keywords' => 'Ключевые слова'
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

            if ($user->save()) {

                // Сохраняем ключевые слова
                $user->keywords->edit($this->keywords);
                // Сохраняем информацию о эксперте
                $expertInfo = $user->expertInfo;
                $expertInfo->education = $this->education;
                $expertInfo->academic_degree = $this->academic_degree;
                $expertInfo->position = $this->position;
                $expertInfo->type = implode('|', $this->type);
                $expertInfo->scope_professional_competence = $this->scope_professional_competence;
                $expertInfo->publications = $this->publications;
                $expertInfo->implemented_projects = $this->implemented_projects;
                $expertInfo->role_in_implemented_projects = $this->role_in_implemented_projects;

                if ($expertInfo->save()) {
                    return $user;
                }
            }

        } else {

            $this->checking_mail_sending = false;
            return  $this;
        }
    }
}