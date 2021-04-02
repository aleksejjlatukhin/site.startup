<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ConversationAdmin extends ActiveRecord
{

    public static function tableName()
    {
        return 'conversation_admin';
    }

    public function rules()
    {
        return [
            [['id', 'admin_id', 'user_id', 'updated_at'], 'integer'],
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

    public function getAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }

    public function getUser ()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getMessages ()
    {
        return $this->hasMany(MessageAdmin::class, ['conversation_id' => 'id']);
    }

    public function getLastMessage ()
    {
        return $this->hasOne(MessageAdmin::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    public function getCountNewMessages ()
    {
        $count_new_messages = MessageAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
}