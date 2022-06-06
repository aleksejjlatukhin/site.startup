<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс, который содержит информацию о эксперте
 *
 * Class ExpertInfo
 * @package app\models
 *
 * @property int $id                                    Идентификатор записи
 * @property int $user_id                               Идентификатор эксперта в таб.User
 * @property string $education                          Образование эксперта
 * @property string $academic_degree                    Ученая степень эксперта
 * @property string $position                           Должность эксперта
 * @property string $type                               Тип эксперта
 * @property string $scope_professional_competence      Сфера профессиональной компетенции эксперта
 * @property string $publications                       Научные публикации эксперта
 * @property string $implemented_projects               Реализованные проекты эксперта
 * @property string $role_in_implemented_projects       Роль эксперта в реализованных проектах
 */
class ExpertInfo extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expert_info';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['education', 'academic_degree', 'position', 'type', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'required'],
            [['education', 'academic_degree', 'position', 'scope_professional_competence',
                'publications', 'implemented_projects', 'role_in_implemented_projects'], 'trim'],
            [['education', 'academic_degree', 'position', 'type'], 'string', 'max' => 255],
            [['scope_professional_competence', 'publications', 'implemented_projects', 'role_in_implemented_projects'], 'string', 'max' => 2000],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
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
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param string $education
     */
    public function setEducation($education)
    {
        $this->education = $education;
    }

    /**
     * @return string
     */
    public function getAcademicDegree()
    {
        return $this->academic_degree;
    }

    /**
     * @param string $academic_degree
     */
    public function setAcademicDegree($academic_degree)
    {
        $this->academic_degree = $academic_degree;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getScopeProfessionalCompetence()
    {
        return $this->scope_professional_competence;
    }

    /**
     * @param string $scope_professional_competence
     */
    public function setScopeProfessionalCompetence($scope_professional_competence)
    {
        $this->scope_professional_competence = $scope_professional_competence;
    }

    /**
     * @return string
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * @param string $publications
     */
    public function setPublications($publications)
    {
        $this->publications = $publications;
    }

    /**
     * @return string
     */
    public function getImplementedProjects()
    {
        return $this->implemented_projects;
    }

    /**
     * @param string $implemented_projects
     */
    public function setImplementedProjects($implemented_projects)
    {
        $this->implemented_projects = $implemented_projects;
    }

    /**
     * @return string
     */
    public function getRoleInImplementedProjects()
    {
        return $this->role_in_implemented_projects;
    }

    /**
     * @param string $role_in_implemented_projects
     */
    public function setRoleInImplementedProjects($role_in_implemented_projects)
    {
        $this->role_in_implemented_projects = $role_in_implemented_projects;
    }
}