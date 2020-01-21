<?php

namespace app\models;

use Yii;

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
            [['responds_gcp_id', 'date_fact'], 'required'],
            [['responds_gcp_id'], 'integer'],
            [['date_fact'], 'safe'],
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
            'date_fact' => 'Дата Анкеты',
            'status' => 'Значимость предложения'
        ];
    }
}
