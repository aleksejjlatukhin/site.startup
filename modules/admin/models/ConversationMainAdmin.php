<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * Класс, который хранит записи о беседах админов организаций с трекерами этих организаций
 *
 * Class ConversationMainAdmin
 * @package app\modules\admin\models
 *
 * @property int $id                    идентификатор беседы
 * @property int $main_admin_id         идентификатор админа организации
 * @property int $admin_id              идентификатор трекера организации
 * @property int $updated_at            дата обновления
 */
class ConversationMainAdmin extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'conversation_main_admin';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [

            [['id', 'main_admin_id', 'admin_id', 'updated_at'], 'integer'],
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
     * Получить объект трекера
     *
     * @return ActiveQuery
     */
    public function getAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }


    /**
     * Получить объект админа организации
     *
     * @return ActiveQuery
     */
    public function getMainAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'main_admin_id']);
    }


    /**
     * Получить все сообщения беседы
     *
     * @return ActiveQuery
     */
    public function getMessages ()
    {
        return $this->hasMany(MessageMainAdmin::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     *
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageMainAdmin::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных сообщений беседы
     *
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageMainAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();

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
    public function getMainAdminId()
    {
        return $this->main_admin_id;
    }


    /**
     * @param int $main_admin_id
     */
    public function setMainAdminId($main_admin_id)
    {
        $this->main_admin_id = $main_admin_id;
    }


    /**
     * @return int
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }


    /**
     * @param int $admin_id
     */
    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
    }


    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}