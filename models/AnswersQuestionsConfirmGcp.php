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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['question_id', 'respond_id'], 'required'],
            [['answer'], 'string', 'max' => 255],
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