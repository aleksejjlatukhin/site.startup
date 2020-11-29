<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property string $id
 * @property int $project_id
 * @property string $fio
 * @property string $role
 * @property string $experience
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }


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
}
