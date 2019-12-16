<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generation_problem".
 *
 * @property string $id
 * @property int $interview_id
 * @property string $description
 * @property string $date_gps
 */
class GenerationProblem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generation_problem';
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmProblem::class, ['gps_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'description', 'date_gps', 'title'], 'required'],
            ['title', 'string', 'max' => 255],
            [['title', 'description'], 'trim'],
            [['interview_id'], 'integer'],
            [['description'], 'string'],
            [['date_gps'], 'safe'],
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
            'title' => 'Название ГПС',
            'description' => 'Описание',
            'date_gps' => 'Дата',
        ];
    }
}
