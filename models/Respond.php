<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "responds".
 *
 * @property string $id
 * @property int $interview_id
 * @property string $name
 * @property string $info_respond
 * @property string $add_info
 * @property string $date_interview
 * @property string $place_interview
 */
class Respond extends \yii\db\ActiveRecord
{
    public $exist_respond;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    public function getDescInterview()
    {
        return $this->hasOne(DescInterview::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'name'], 'required'],
            [['name', 'info_respond', 'place_interview', 'email'], 'trim'],
            [['interview_id'], 'integer'],
            [['date_plan'], 'safe'],
            [['name', 'info_respond', 'place_interview', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
            ['exist_respond', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'interview_id' => 'Interview ID',
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'Адрес электронной почты',
            'date_plan' => 'План',
            'place_interview' => 'Место проведения',
        ];
    }
}
