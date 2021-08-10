<?php


namespace app\models\forms;


use app\models\ExpertInfo;
use app\models\User;
use yii\base\Exception;

class SingupExpertForm extends SingupForm
{

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
     * @return array
     */
    public function rules()
    {
        return [
            [['exist_agree', 'uniq_username', 'match_username', 'uniq_email'],'boolean'],
            ['exist_agree', 'existAgree'],
            [['second_name', 'first_name', 'middle_name', 'email', 'username', 'password',
                'education', 'academic_degree', 'position', 'type', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'required'],
            [['second_name', 'first_name', 'middle_name', 'username', 'email', 'telephone', 'password',
                'education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'trim'],
            [['second_name', 'first_name', 'middle_name', 'email', 'telephone',
                'education', 'academic_degree', 'position'], 'string', 'max' => 255],
            [['scope_professional_competence', 'publications', 'implemented_projects', 'role_in_implemented_projects'], 'string', 'max' => 2000],
            ['username', 'matchUsername'],
            ['username', 'uniqUsername'],
            ['email', 'uniqEmail'],

            ['confirm', 'default', 'value' => User::NOT_CONFIRM, 'on' => 'emailActivation'],
            ['confirm', 'in', 'range' => [
                User::CONFIRM,
                User::NOT_CONFIRM,
            ]],

            ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE,],
            ['status', 'in', 'range' => [
                User::STATUS_NOT_ACTIVE,
                User::STATUS_ACTIVE,
                User::STATUS_DELETED,
            ]],

            ['role', 'default', 'value' => User::ROLE_EXPERT],
            ['role', 'in', 'range' => [User::ROLE_EXPERT]],

        ];
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
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
            'role' => 'Проектная роль пользователя',
            'exist_agree' => '',
            'education' => 'Образование',
            'academic_degree' => 'Ученая степень',
            'position' => 'Должность',
            'type' => 'Тип',
            'scope_professional_competence' => 'Сфера профессиональной компетенции',
            'publications' => 'Научные публикации',
            'implemented_projects' => 'Реализованные проекты',
            'role_in_implemented_projects' => 'Роль в реализованных проектах'
        ];
    }


    /**
     * @return User|bool|null
     * @throws Exception
     */
    public function singup()
    {
        if ($this->exist_agree == 1){

            $user = new User();
            $user->second_name = $this->second_name;
            $user->first_name = $this->first_name;
            $user->middle_name = $this->middle_name;
            $user->telephone = $this->telephone;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;
            $user->confirm = $this->confirm;
            $user->role = $this->role;
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if($this->scenario === 'emailActivation') {
                $user->generateSecretKey();
            }

            if ($user->save()) {

                $expertInfo = new ExpertInfo();
                $expertInfo->user_id = $user->id;
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
        }
        return false;
    }
}