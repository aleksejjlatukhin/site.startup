<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interview".
 *
 * @property string $id
 * @property int $segment_id
 * @property int $count_respond
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
class Interview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['segment_id', 'count_respond', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['segment_id'], 'integer'],
            ['count_respond', 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8'], 'boolean'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => 255],
        ];
    }


    public function getSegment()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(Questions::class, ['interview_id' => 'id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpert::class, ['interview_id' => 'id']);
    }

    public function getProblems()
    {
        return $this->hasMany(GenerationProblem::class, ['interview_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'segment_id' => 'Segment ID',
            'count_respond' => 'Количество респондентов',
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
