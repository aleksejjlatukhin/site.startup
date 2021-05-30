<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
}
