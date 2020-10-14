<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "desc_interview_mvp".
 *
 * @property string $id
 * @property int $responds_mvp_id
 * @property string $date_fact
 * @property string $status
 */
class DescInterviewMvp extends \yii\db\ActiveRecord
{

    public $exist_desc;
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
            ['exist_desc', 'boolean'],
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
}
