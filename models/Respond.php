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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'name'], 'required'],
            [['name', 'info_respond', 'add_info', 'place_interview'], 'trim'],
            [['interview_id'], 'integer'],
            [['date_interview'], 'safe'],
            [['name', 'info_respond', 'add_info', 'place_interview'], 'string', 'max' => 255],
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
            'add_info' => 'Доп. данные о респонденте',
            'date_interview' => 'Дата проведения',
            'place_interview' => 'Место проведения',
        ];
    }
}
