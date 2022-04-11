<?php

namespace app\modules\expert\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Класс, который хранит беседы экспертов со всеми доступными пользователями, кроме техподдержки
 *
 * Class ConversationExpert
 * @package app\modules\expert\models
 *
 * @property int $id                        идентификатор беседы
 * @property int $expert_id                 идентификатор эксперта
 * @property int $user_id                   идентификатор пользователя
 * @property int $role                      роль пользователя
 * @property int $updated_at                дата обновления
 */
class ConversationExpert extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'conversation_expert';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [

            [['id', 'expert_id', 'user_id', 'role', 'updated_at'], 'integer'],
            ['role', 'in', 'range' => [
                User::ROLE_USER,
                User::ROLE_ADMIN,
                User::ROLE_MAIN_ADMIN,
                User::ROLE_ADMIN_COMPANY,
                User::ROLE_MANAGER
            ]],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            //Использование поведения TimestampBehavior ActiveRecord
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],

                ],
            ],
        ];
    }


    public function getRoleUser()
    {
        return $this->role;
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
     * Получить объект эксперта
     * @return ActiveQuery
     */
    public function getExpert ()
    {
        return $this->hasOne(User::class, ['id' => 'expert_id']);
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * @return mixed
     */
    public function getExpertId()
    {
        return $this->expert_id;
    }


    /**
     * Получить все сообщения беседы
     * @return ActiveQuery
     */
    public function getMessages ()
    {
        return $this->hasMany(MessageExpert::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageExpert::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных сообщений беседы
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageExpert::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageExpert::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }


    /**
     * Проверить существует ли беседа
     * между пользователем и экспертом
     *
     * @param int $expert_id
     * @param int $user_id
     * @return bool
     */
    public static function isExist($expert_id, $user_id)
    {
        $conversation = self::findOne([
            'expert_id' => $expert_id,
            'user_id' => $user_id
        ]);

        return $conversation ? true : false;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param int $expert_id
     */
    public function setExpertId($expert_id)
    {
        $this->expert_id = $expert_id;
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
    public function getRole()
    {
        return $this->role;
    }


    /**
     * @param int $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}