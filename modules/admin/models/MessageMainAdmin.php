<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MessageMainAdmin extends ActiveRecord
{

    public static function tableName()
    {
        return 'message_main_admin';
    }

    const READ_MESSAGE = 20;
    const NO_READ_MESSAGE = 10;


    public function rules()
    {
        return [
            [['description'], 'filter', 'filter' => 'trim'],
            [['description'], 'string'],
            [['description'], 'required'],
            [['id', 'conversation_id','sender_id', 'adressee_id', 'status'], 'integer'],
            ['status', 'default', 'value' => function () {
                return MessageMainAdmin::NO_READ_MESSAGE;
            }],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Отправитель',
            'adressee_id' => 'Получатель',
            'status' => 'Статус прочтения',
            'description' => 'Сообщение',
            'created_at' => 'Время отправления',
        ];
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function getSender ()
    {
        $sender = User::findOne([
            'id' => $this->sender_id,
        ]);

        return $sender;
    }

    public function getAdressee ()
    {
        $adressee = User::findOne([
            'id' => $this->adressee_id,
        ]);

        return $adressee;
    }

}