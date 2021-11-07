<?php

namespace app\models;

use yii\db\ActiveRecord;

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
        $this->answer = $answer;
        if ($expert_types) {
            $this->expert_types = implode('|', $expert_types);
        } else {
            $this->expert_types = $expert_types;
        }
        $this->comment = $comment;
        $this->communication_id = $communication_id;
    }

}