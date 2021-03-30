<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


class ConversationMainAdmin extends ActiveRecord
{

    public static function tableName()
    {
        return 'conversation_main_admin';
    }

    public function rules()
    {
        return [

            [['id', 'main_admin_id', 'admin_id'], 'integer'],
            ['updated_at', 'default', 'value' => function () {
                return $this->getLastMessage()->created_at;
            }],

        ];
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function getAdmin ()
    {

        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }


    public function getMainAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'main_admin_id']);
    }


    public function getMessages ()
    {
        return $this->hasMany(MessageMainAdmin::class, ['conversation_id' => 'id']);
    }


    public function getLastMessage ()
    {
        return $this->hasOne(MessageMainAdmin::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    public function getCountNewMessages ()
    {
        $count_new_messages = MessageMainAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
 }