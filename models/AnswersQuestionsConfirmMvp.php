<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс хранит в бд ответы респондендов на вопросы
 * интервью на этапе подтверждения mvp-продукта
 *
 * Class AnswersQuestionsConfirmMvp
 * @package app\models
 *
 * @property int $id                    Идентификатор записи в таб. answers_questions_confirm_mvp
 * @property int $question_id           Идентификатор записи в таб. questions_confirm_mvp
 * @property int $respond_id            Идентификатор записи в таб. responds_mvp
 * @property string $answer             Ответ на вопрос
 */
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * @param int $question_id
     */
    public function setQuestionId($question_id)
    {
        $this->question_id = $question_id;
    }

    /**
     * @return int
     */
    public function getRespondId()
    {
        return $this->respond_id;
    }

    /**
     * @param int $respond_id
     */
    public function setRespondId($respond_id)
    {
        $this->respond_id = $respond_id;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}