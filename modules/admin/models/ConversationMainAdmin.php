<?php

namespace app\modules\admin\models;

use app\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;


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


    /**
     * Получить объект админа
     * @return ActiveQuery
     */
    public function getAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }


    /**
     * Получить объект главного админа
     * @return ActiveQuery
     */
    public function getMainAdmin ()
    {
        return $this->hasOne(User::class, ['id' => 'main_admin_id']);
    }


    /**
     * Получить все сообщения беседы
     * @return ActiveQuery
     */
    public function getMessages ()
    {
        return $this->hasMany(MessageMainAdmin::class, ['conversation_id' => 'id']);
    }


    /**
     * Получить последнее сообщение беседы
     * @return ActiveQuery
     */
    public function getLastMessage ()
    {
        return $this->hasOne(MessageMainAdmin::class, ['conversation_id' => 'id'])->orderBy('created_at DESC');
    }


    /**
     * Получить кол-во непрочитанных сообщений беседы
     * @return int|string
     */
    public function getCountNewMessages ()
    {
        $count_new_messages = MessageMainAdmin::find()
            ->where(['conversation_id' => $this->id, 'status' => MessageMainAdmin::NO_READ_MESSAGE])->count();

        return $count_new_messages;
    }
 }