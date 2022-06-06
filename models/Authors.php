<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит информацию об авторах проектов
 *
 * Class Authors
 * @package app\models
 *
 * @property int $id                            Идентификатор записи
 * @property int $project_id                    Идентификатор записи из таб.Projects
 * @property string $fio                        ФИО автора проекта
 * @property string $role                       Роль автора в проекте
 * @property string $experience                 Опыт работы автора проекта
 */
class Authors extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }


    /**
     * Получить объект проекта
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fio', 'role'], 'required'],
            [['project_id'], 'integer'],
            [['experience'], 'string', 'max' => 2000],
            [['fio', 'role'], 'string', 'max' => 255],
            [['fio', 'role', 'experience'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'fio' => 'Фамилия, имя, отчество',
            'role' => 'Роль в проекте',
            'experience' => 'Опыт работы',
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        parent::init();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * @param string $fio
     */
    public function setFio($fio)
    {
        $this->fio = $fio;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param string $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }
}
