<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class DescInterviewConfirm extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview_confirm';
    }

    public function getRespond()
    {
        return $this->hasOne(RespondsConfirm::class, ['id' => 'responds_confirm_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responds_confirm_id', 'status'], 'required'],
            [['responds_confirm_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responds_confirm_id' => 'Responds Confirm ID',
            'status' => 'Значимость проблемы'
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->respond->confirm->problem->project->touch('updated_at');
            $this->respond->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->problem->project->touch('updated_at');
            $this->respond->confirm->problem->project->user->touch('updated_at');
        });

        parent::init();
    }
}
