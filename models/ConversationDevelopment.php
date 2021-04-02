<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ConversationDevelopment extends ActiveRecord
{
    public static function tableName()
    {
        return 'conversation_development';
    }

    public function rules()
    {
        return [
            [['id', 'dev_id', 'user_id', 'updated_at'], 'integer'],
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            //Использование поведения TimestampBehavior ActiveRecord
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    \yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    \yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
            ],
        ];
    }

    public function getDevelopment ()
    {
        return $this->hasOne(User::class, ['id' => 'dev_id']);
    }

    public function getUser ()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getMessages ()
    {
        return $this->hasMany(MessageDevelopment::class, ['conversation_id' => 'id']);
    }

    public function getLastMessage ()
    {
        return $this->hasOne(MessageDevelopment::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }

    public function getCountNewMessages ()
    {
        $count_new_messages = MessageDevelopment::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
}