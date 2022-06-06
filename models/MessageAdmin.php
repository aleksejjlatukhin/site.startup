<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит сообщения между трекера и проектантами
 *
 * Class MessageAdmin
 * @package app\models
 *
 * @property int $id                            идентификатор сообщения
 * @property int $conversation_id               идентификатор беседы
 * @property int $sender_id                     идентификатор отправителя
 * @property int $adressee_id                   идентификатор получателя
 * @property string $description                текст сообщения
 * @property int $status                        статус сообщения
 * @property int $created_at                    дата создания
 * @property int $updated_at                    дата обновления
 */
class MessageAdmin extends ActiveRecord
{

    const READ_MESSAGE = 20;
    const NO_READ_MESSAGE = 10;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'message_admin';
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['description'], 'filter', 'filter' => 'trim'],
            [['description'], 'string', 'max' => 4000],
            [['id', 'conversation_id', 'sender_id', 'adressee_id', 'status'], 'integer'],
            ['status', 'default', 'value' => function () {
                return MessageAdmin::NO_READ_MESSAGE;
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


    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->conversation->touch('updated_at');
        });

        parent::init();
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /**
     * Получить объект беседы
     * @return ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(ConversationAdmin::class, ['id' => 'conversation_id']);
    }


    /**
     * Получить объект отправителя
     * @return ActiveQuery
     */
    public function getSender ()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }


    /**
     * Получить объект получателя
     * @return ActiveQuery
     */
    public function getAdressee ()
    {
        return $this->hasOne(User::class, ['id' => 'adressee_id']);
    }


    /**
     * Получить прикрепленные файлы
     * @return MessageFiles[]
     */
    public function getFiles ()
    {
        return MessageFiles::findAll(['category' => MessageFiles::CATEGORY_ADMIN, 'message_id' => $this->getId()]);
    }


    /**
     * Получить дату отправки сообщения
     * День и дата по-русски
     * @return string
     */
    public function getDayAndDateRus(){

        $days = array(
            'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
            'Четверг', 'Пятница', 'Суббота'
        );

        $monthes = array(
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        );

        if (date('d.n.Y', $this->getCreatedAt()) == date('d.n.Y', time())) {
            return 'Сегодня';
        }
        elseif (date('d', $this->getCreatedAt()) == (date('d', time()) - 1)
            && date('n.Y', $this->getCreatedAt()) == date('n.Y', time())) {
            return 'Вчера';
        }
        else {
            return ( $days[(date('w', $this->getCreatedAt()))] . ', ' . date('d', $this->getCreatedAt())
                . ' ' . $monthes[(date('n', $this->getCreatedAt()))] . ' ' . date(' Y', $this->getCreatedAt()));
        }
    }


    /**
     * Получить дату отправки сообщения
     * Дата по-русски
     * @return string
     */
    public function getDateRus(){

        $monthes = array(
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        );

        if (date('d.n.Y', $this->getCreatedAt()) == date('d.n.Y', time())) {
            return 'Сегодня';
        }
        elseif (date('d', $this->getCreatedAt()) == (date('d', time()) - 1)
            && date('n.Y', $this->getCreatedAt()) == date('n.Y', time())) {
            return 'Вчера';
        }
        else {
            return ( date('d', $this->getCreatedAt()) . ' ' . $monthes[(date('n', $this->getCreatedAt()))]
                . ' ' . date(' Y', $this->getCreatedAt()));
        }
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
    public function getConversationId()
    {
        return $this->conversation_id;
    }


    /**
     * @param int $conversation_id
     */
    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;
    }


    /**
     * @return int
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }


    /**
     * @param int $sender_id
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }


    /**
     * @return int
     */
    public function getAdresseeId()
    {
        return $this->adressee_id;
    }


    /**
     * @param int $adressee_id
     */
    public function setAdresseeId($adressee_id)
    {
        $this->adressee_id = $adressee_id;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }


    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}