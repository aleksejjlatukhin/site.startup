<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит информацию о активации клиентов (организациях)
 *
 * Class ClientActivation
 * @package app\models
 *
 * @property int $id                идентификатор записи
 * @property int $client_id         идентификатор клиента
 * @property int $status            состояние активации клиента
 * @property int $created_at        дата создания записи
 */
class ClientActivation extends ActiveRecord
{

    const ACTIVE = 789;
    const NO_ACTIVE = 987;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'client_activation';
    }


    /**
     * Получить объект клиента
     *
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }


    /**
     * Получить текущий статус клиента
     *
     * @param $clientId
     * @return int
     */
    public static function getCurrentStatus($clientId)
    {
        $obj = self::find()->where(['client_id' => $clientId])->orderBy(['id' => SORT_DESC])->one();
        return $obj->status;
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


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'status'], 'required'],
            [['created_at', 'client_id', 'status'], 'integer'],
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
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']],
            ],
        ];
    }


}