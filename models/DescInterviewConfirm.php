<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "desc_interview_confirm".
 *
 * @property string $id
 * @property int $responds_confirm_id
 * @property string $date_fact
 * @property string $description
 * @property string $interview_file
 */
class DescInterviewConfirm extends \yii\db\ActiveRecord
{

    public $exist_desc;

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
            [['responds_confirm_id', 'date_fact'], 'required'],
            [['responds_confirm_id'], 'integer'],
            [['date_fact'], 'safe'],
            ['exist_desc', 'boolean'],
            ['status', 'boolean'],
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
            'date_fact' => 'Дата Анкеты',
            'status' => 'Значимость проблемы'
        ];
    }
}
