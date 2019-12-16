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

    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirm::class, ['confirm_problem_id' => 'id']);
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
            [['gps_id', 'count_respond', 'count_positive', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['gps_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8'], 'boolean'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => 255],
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
            'count_positive' => 'Количество позитивных интервью',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Представление интервьюера',
            'reason_interview' => 'Почему мне интересно',
            'question_1' => '',
            'question_2' => '',
            'question_3' => '',
            'question_4' => '',
            'question_5' => '',
            'question_6' => '',
            'question_7' => '',
            'question_8' => '',
        ];
    }
}
