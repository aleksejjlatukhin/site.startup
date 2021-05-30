<?php


namespace app\models;

use yii\db\ActiveQuery;
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


    /**
     * Получить объект вопроса
     * @return ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(QuestionsConfirmMvp::class, ['id' => 'question_id']);
    }


    /**
     * Получить объект респондента
     * @return ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasOne(RespondsMvp::class, ['id' => 'respond_id']);
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