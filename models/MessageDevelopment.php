<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class MessageDevelopment extends ActiveRecord
{

    public static function tableName()
    {
        return 'message_development';
    }


    const READ_MESSAGE = 20;
    const NO_READ_MESSAGE = 10;


    public function rules()
    {
        return [
            [['description'], 'filter', 'filter' => 'trim'],
            [['description'], 'string'],
            [['id', 'conversation_id','sender_id', 'adressee_id', 'status'], 'integer'],
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

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    public function getConversation()
    {
        return $this->hasOne(ConversationDevelopment::class, ['id' => 'conversation_id']);
    }

    public function getSender ()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    public function getAdressee ()
    {
        return $this->hasOne(User::class, ['id' => 'adressee_id']);
    }

    public function getFiles ()
    {
        return MessageFiles::findAll(['category' => MessageFiles::CATEGORY_TECHNICAL_SUPPORT, 'message_id' => $this->id]);
    }

    // День и дата по-русски
    function getDayAndDateRus(){

        $days = array(
            'Воскресенье', 'Понедельник', 'Вторник', 'Среда',
            'Четверг', 'Пятница', 'Суббота'
        );

        $monthes = array(
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        );

        if (date('d.n.Y', $this->created_at) == date('d.n.Y', time())) {
            return 'Сегодня';
        }
        elseif (date('d', $this->created_at) == (date('d', time()) - 1)
            && date('n.Y', $this->created_at) == date('n.Y', time())) {
            return 'Вчера';
        }
        else {
            return ( $days[(date('w', $this->created_at))] . ', ' . date('d', $this->created_at)
                . ' ' . $monthes[(date('n', $this->created_at))] . ' ' . date(' Y', $this->created_at));
        }
    }

    // Дата по-русски
    function getDateRus(){

        $monthes = array(
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        );

        if (date('d.n.Y', $this->created_at) == date('d.n.Y', time())) {
            return 'Сегодня';
        }
        elseif (date('d', $this->created_at) == (date('d', time()) - 1)
            && date('n.Y', $this->created_at) == date('n.Y', time())) {
            return 'Вчера';
        }
        else {
            return ( date('d', $this->created_at) . ' ' . $monthes[(date('n', $this->created_at))]
                . ' ' . date(' Y', $this->created_at));
        }
    }
}