<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

class ConversationAdmin extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'conversation_admin';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'admin_id', 'user_id', 'updated_at'], 'integer'],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
            ],
        ];
    }


    /**
     * Получить объект Админа
     * @return ActiveQuery
     */
    public function getAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }


    /**
     * Получить объект пользователя
     * @return ActiveQuery
     */
    public function getUser ()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * Получить все сообщения беседы
     * @return ActiveQuery
     */
    public function getMessages ()
    {
        return $this->hasMany(MessageAdmin::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageAdmin::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных
     * сообщений беседы
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
}