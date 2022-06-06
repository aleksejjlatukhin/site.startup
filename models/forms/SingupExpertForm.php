<?php


namespace app\models\forms;


use app\models\ExpertInfo;
use app\models\KeywordsExpert;
use app\models\User;
use yii\base\Exception;

/**
 * Форма регистрации эксперта
 *
 * Class SingupExpertForm
 * @package app\models\forms
 *
 * @property string $email
 * @property string $username
 * @property string $password
 * @property int $status
 * @property int $confirm
 * @property int $role
 * @property string $education
 * @property string $academic_degree
 * @property string $position
 * @property string $type
 * @property string $scope_professional_competence
 * @property string $publications
 * @property string $implemented_projects
 * @property string $role_in_implemented_projects
 * @property string $keywords
 */
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
     * Ключевые слова
     * @var string
     */
    public $keywords;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['exist_agree', 'uniq_username', 'match_username', 'uniq_email'],'boolean'],
            ['exist_agree', 'existAgree'],
            [['email', 'username', 'password',
                'education', 'academic_degree', 'position', 'type', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'required'],
            [['username', 'email', 'password',
                'education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'trim'],
            [['email', 'education', 'academic_degree', 'position'], 'string', 'max' => 255],
            [['scope_professional_competence', 'publications', 'implemented_projects', 'role_in_implemented_projects', 'keywords'], 'string', 'max' => 2000],
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
            'role_in_implemented_projects' => 'Роль в реализованных проектах',
            'keywords' => 'Ключевые слова'
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
            $user->setUsername($this->username);
            $user->setEmail($this->email);
            $user->setStatus($this->status);
            $user->setConfirm($this->confirm);
            $user->setRole($this->role);
            $user->setPassword($this->password);
            $user->generateAuthKey();

            if($this->scenario === 'emailActivation') {
                $user->generateSecretKey();
            }

            if ($user->save()) {

                // Сохраняем ключевые слова
                KeywordsExpert::create($user->id, $this->keywords);

                // Сохраняем информацию о эксперте
                $expertInfo = new ExpertInfo();
                $expertInfo->setUserId($user->getId());
                $expertInfo->setEducation($this->education);
                $expertInfo->setAcademicDegree($this->academic_degree);
                $expertInfo->setPosition($this->position);
                $expertInfo->setType(implode('|', $this->type));
                $expertInfo->setScopeProfessionalCompetence($this->scope_professional_competence);
                $expertInfo->setPublications($this->publications);
                $expertInfo->setImplementedProjects($this->implemented_projects);
                $expertInfo->setRoleInImplementedProjects($this->role_in_implemented_projects);

                if ($expertInfo->save()) {
                    return $user;
                }
            }
        }
        return false;
    }
}