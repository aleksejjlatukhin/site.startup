<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Класс, хранит беседы между техподдержкой и всеми остальными пользователями
 *
 * Class ConversationDevelopment
 * @package app\models
 *
 * @property int $id                        идентификатор беседы
 * @property int $dev_id                    идентификатор техподдержки
 * @property int $user_id                   идентификатор пользователя
 * @property int $updated_at                дата обновления
 */
class ConversationDevelopment extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'conversation_development';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'dev_id', 'user_id', 'updated_at'], 'integer'],
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
     * Получить объект техподдержки
     * @return ActiveQuery
     */
    public function getDevelopment ()
    {
        return $this->hasOne(User::class, ['id' => 'dev_id']);
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
        return $this->hasMany(MessageDevelopment::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageDevelopment::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных
     * сообщений беседы
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageDevelopment::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageDevelopment::NO_READ_MESSAGE])->count();

        return $count_new_messages;
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
    public function getDevId()
    {
        return $this->dev_id;
    }


    /**
     * @param int $dev_id
     */
    public function setDevId($dev_id)
    {
        $this->dev_id = $dev_id;
    }


    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }


    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}