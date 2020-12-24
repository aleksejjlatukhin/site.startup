<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class DescInterviewGcp extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desc_interview_gcp';
    }

    public function getRespond()
    {
        return $this->hasOne(RespondsGcp::class, ['id' => 'responds_gcp_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responds_gcp_id', 'status'], 'required'],
            [['responds_gcp_id', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responds_gcp_id' => 'Responds Gcp ID',
            'status' => 'Значимость предложения'
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
            $this->respond->confirm->gcp->project->touch('updated_at');
            $this->respond->confirm->gcp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->respond->confirm->gcp->project->touch('updated_at');
            $this->respond->confirm->gcp->project->user->touch('updated_at');
        });

        parent::init();
    }
}
