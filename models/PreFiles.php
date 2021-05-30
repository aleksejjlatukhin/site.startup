<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class PreFiles extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pre_files';
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
            [['project_id', 'file_name'], 'required'],
            [['project_id'], 'integer'],
            [['file_name', 'server_file'], 'string', 'max' => 255],
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
            'file_name' => 'File Name',
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
