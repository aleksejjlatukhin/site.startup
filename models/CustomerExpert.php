<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о том, к какому клиенту (организации) какой привязан эксперт от платформы spaccel.ru
 *
 * Class CustomerExpert
 * @package app\models
 *
 * @property int $id                        идентификатор записи
 * @property int $user_id                   идентификатор эксперта из таблицы User
 * @property int $client_id                 идентификатор клиента (организации)
 * @property int $status                    статус эксперта по данному клиенту
 * @property int $created_at                дата привязки эксперта по клиентам к организации
 * @property int $updated_at                дата изменения статуса эксперта по клиентам к организации
 */
class CustomerExpert extends ActiveRecord
{

    const ACTIVE = 9378;
    const NO_ACTIVE = 8351;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'customer_expert';
    }


    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'user_id'], 'required'],
            [['client_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => function () {
                return self::NO_ACTIVE;
            }],
            ['status', 'in', 'range' => [
                self::NO_ACTIVE,
                self::ACTIVE
            ]],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
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
    public function getClientId()
    {
        return $this->client_id;
    }


    /**
     * @param int $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
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
}