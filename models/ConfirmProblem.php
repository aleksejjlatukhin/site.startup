<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "confirm_problem".
 *
 * @property string $id
 * @property int $gps_id
 * @property int $count_respond
 * @property int $count_positive
 * @property string $greeting_interview
 * @property string $view_interview
 * @property string $reason_interview
 * @property string $question_1
 * @property string $question_2
 * @property string $question_3
 * @property string $question_4
 * @property string $question_5
 * @property string $question_6
 * @property string $question_7
 * @property string $question_8
 */
class ConfirmProblem extends \yii\db\ActiveRecord
{

    public $exist_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_problem';
    }

    public function getProblem()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'gps_id']);
    }


    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertConfirm::class, ['confirm_problem_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsConfirm::class, ['confirm_problem_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gps_id', 'count_respond', 'count_positive'], 'required'],
            [['gps_id', 'exist_confirm'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gps_id' => 'Gps ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }
}
