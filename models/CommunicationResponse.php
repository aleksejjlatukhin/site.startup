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
            [['comment'], 'string', 'max' => 255],
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
     * @param string $comment
     * @param int $communication_id
     */
    public function setParams($answer, $comment, $communication_id)
    {
        $this->answer = $answer;
        $this->comment = $comment;
        $this->communication_id = $communication_id;
    }

}