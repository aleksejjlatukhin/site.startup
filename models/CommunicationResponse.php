<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию об ответе эксперта на коммуникацию по проекту от администратора
 *
 * Class CommunicationResponse
 * @package app\models
 *
 * @property int $id                                            Идентификатор записи
 * @property int $communication_id                              Идентификатор записи в таб. project_communication
 * @property int $answer                                        Результат ответа
 * @property string $expert_types                               Типы экспертной деятельности, по которым эксперт готов провести экспертизу
 * @property string $comment                                    Комментарий к ответу
 */
class CommunicationResponse extends ActiveRecord
{

    const POSITIVE_RESPONSE = 543;
    const NEGATIVE_RESPONSE = 678;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'communication_response';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer', 'communication_id'], 'integer'],
            [['answer', 'communication_id'], 'required'],
            [['comment', 'expert_types'], 'string', 'max' => 255],
            ['answer', 'in', 'range' => [
                self::POSITIVE_RESPONSE,
                self::NEGATIVE_RESPONSE
            ]],
        ];
    }


    /**
     * Установить параметры
     *
     * @param int $answer
     * @param array|null $expert_types
     * @param string $comment
     * @param int $communication_id
     */
    public function setParams($answer, $comment, $communication_id, $expert_types = null)
    {
        $this->setAnswer($answer);
        $this->setExpertTypes($expert_types);
        $this->setComment($comment);
        $this->setCommunicationId($communication_id);
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
    public function getCommunicationId()
    {
        return $this->communication_id;
    }

    /**
     * @param int $communication_id
     */
    public function setCommunicationId($communication_id)
    {
        $this->communication_id = $communication_id;
    }

    /**
     * @return int
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param int $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return string
     */
    public function getExpertTypes()
    {
        return $this->expert_types;
    }

    /**
     * @param array|null $expert_types
     */
    public function setExpertTypes($expert_types)
    {
        $this->expert_types = $expert_types ? implode('|', $expert_types) : '';
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

}