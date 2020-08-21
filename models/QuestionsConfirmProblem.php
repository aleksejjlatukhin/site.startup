<?php


namespace app\models;

use yii\db\ActiveRecord;

class QuestionsConfirmProblem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_problem';
    }

    public function getConfirmProblem ()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }

    public function getAnswer()
    {
        return $this->hasOne(AnswersQuestionsConfirmProblem::class, ['question_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_problem_id', 'title'], 'required'],
            [['confirm_problem_id'], 'integer'],
            [['status'], 'boolean'],
            [['status'], 'default', 'value' => '1'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Описание вопроса',
            'status' => 'Status',
        ];
    }
}