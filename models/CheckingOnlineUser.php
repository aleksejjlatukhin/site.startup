<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс хранит информацию в бд о том, когда пользователь последний раз заходил на сайт
 *
 * Class CheckingOnlineUser
 * @package app\models
 *
 * @property int $id                            Идентификатор записи
 * @property int $user_id                       Идентификатор пользователя
 * @property int $last_active_time              Дата последнего посещения
 */
class CheckingOnlineUser extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'checking_online_user';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'last_active_time'], 'required'],
            [['user_id', 'last_active_time'], 'integer'],
        ];
    }


    /**
     * Установить текущее время и сохранить запись в бд
     */
    public function setLastActiveTime()
    {
        $this->last_active_time = time();
        $this->save();
    }


    /**
     * Добавить новую запись
     *
     * @param int $id
     */
    public function addCheckingOnline($id)
    {
        $this->setUserId($id);
        $this->setLastActiveTime();
    }


    /**
     * @return bool|mixed
     */
    public function isOnline()
    {
        if ($this->last_active_time > time() - (5*60)) return true;
        else return $this->dateRusAndTime;
    }


    /**
     * Возвращает дату по русски + время
     *
     * @return string
     */
    public function getDateRusAndTime(){

        $monthes = array(
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        );

        if (date('d.n.Y', $this->last_active_time) == date('d.n.Y', time())) {
            return 'сегодня в ' . date('H:i', $this->last_active_time);
        }
        elseif (date('d', $this->last_active_time) == (date('d', time()) - 1)
            && date('n.Y', $this->last_active_time) == date('n.Y', time())) {
            return 'вчера в ' . date('H:i', $this->last_active_time);
        }
        else {

            if (date('Y', $this->last_active_time) == date('Y', time())) {

                return date('d', $this->last_active_time) . ' ' . $monthes[(date('n', $this->last_active_time))]
                    . ' в ' . date('H:i', $this->last_active_time);

            } else {

                return date('d', $this->last_active_time) . ' ' . $monthes[(date('n', $this->last_active_time))]
                        . ' ' . date(' Y', $this->last_active_time);
            }
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
    public function getLastActiveTime()
    {
        return $this->last_active_time;
    }
}