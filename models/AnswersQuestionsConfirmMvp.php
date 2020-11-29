<?php


namespace app\models;

use yii\db\ActiveRecord;

class AnswersQuestionsConfirmMvp extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answers_questions_confirm_mvp';
    }

    public function getQuestion()
    {
        return $this->hasOne(QuestionsConfirmMvp::class, ['id' => 'question_id']);
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