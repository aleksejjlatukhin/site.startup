<?php


namespace app\models;

use yii\db\ActiveRecord;

class AnswersQuestionsConfirmGcp extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answers_questions_confirm_gcp';
    }

    public function getQuestion()
    {
        return $this->hasOne(QuestionsConfirmGcp::class, ['id' => 'question_id']);
    }

    public function getRespond()
    {
        return $this->hasOne(RespondsGcp::class, ['id' => 'respond_id']);
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