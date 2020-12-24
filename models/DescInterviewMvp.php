<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class DescInterviewMvp extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview_mvp';
    }

    public function getRespond()
    {
        return $this->hasOne(RespondsMvp::class, ['id' => 'responds_mvp_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responds_mvp_id', 'status'], 'required'],
            [['responds_mvp_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responds_mvp_id' => 'Responds Mvp ID',
            'status' => 'Значимость MVP',
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
            $this->respond->confirm->mvp->project->touch('updated_at');
            $this->respond->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->mvp->project->touch('updated_at');
            $this->respond->confirm->mvp->project->user->touch('updated_at');
        });

        parent::init();
    }
}
