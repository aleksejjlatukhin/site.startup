<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "desc_interview_gcp".
 *
 * @property string $id
 * @property int $responds_gcp_id
 * @property string $date_fact
 * @property string $status
 */
class DescInterviewGcp extends \yii\db\ActiveRecord
{

    public $exist_desc;
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
            [['responds_gcp_id'], 'required'],
            [['responds_gcp_id'], 'integer'],
            [['status'], 'string'],
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
}
