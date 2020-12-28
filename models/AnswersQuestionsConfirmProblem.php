<?php


namespace app\models;


use yii\db\ActiveRecord;

class AnswersQuestionsConfirmProblem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answers_questions_confirm_problem';
    }

    public function getQuestion()
    {
        return $this->hasOne(QuestionsConfirmProblem::class, ['id' => 'question_id']);
    }

    public function getRespond ()
    {
        return $this->hasOne(RespondsConfirm::class, ['id' => 'respond_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'respond_id'], 'required'],
            [['answer'], 'string', 'max' => 1000],
            [['answer'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание ответа на вопрос',
        ];
    }
}