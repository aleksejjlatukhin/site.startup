<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс хранит сведения о том на какие тарифные планы подключены клиенты(организации),
 * в том числе и период в который будет действовать тариф у конкретного клиента.
 *
 * Class ClientRatesPlan
 * @package app\models
 *
 * @property int $id                    идентификатор в таблице client_rates_plan
 * @property int $client_id             идентификатор клиента(организации) подключенного на тариф
 * @property int $rates_plan_id         идентификатор тарифного плана
 * @property int $date_start            дата начала действия тарифа у клиента
 * @property int $date_end              дата окончания действия тарифа у клиента
 * @property int $created_at            дата создания записи в таблице client_rates_plan
 */
class ClientRatesPlan extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'client_rates_plan';
    }


    /**
     * @return ActiveQuery
     */
    public function getRatesPlan()
    {
        return $this->hasOne(RatesPlan::class, ['id' => 'rates_plan_id']);
    }


    /**
     * @return RatesPlan|null
     */
    public function findRatesPlan()
    {
        return RatesPlan::findOne($this->rates_plan_id);
    }


    /**
     * @return ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }


    /**
     * @return Client|null
     */
    public function findClient()
    {
        return Client::findOne($this->client_id);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'rates_plan_id', 'date_start', 'date_end'], 'required'],
            [['client_id', 'rates_plan_id', 'date_start', 'date_end'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date_start' => 'Начало действия тарифа',
            'date_end' => 'Окончание действия тарифа',
            'created_at' => 'Дата создания',
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
    public function getRatesPlanId()
    {
        return $this->rates_plan_id;
    }


    /**
     * @param int $rates_plan_id
     */
    public function setRatesPlanId($rates_plan_id)
    {
        $this->rates_plan_id = $rates_plan_id;
    }


    /**
     * @return int
     */
    public function getDateStart()
    {
        return $this->date_start;
    }


    /**
     * @param int $date_start
     */
    public function setDateStart($date_start)
    {
        $this->date_start = $date_start;
    }


    /**
     * @return int
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }


    /**
     * @param int $date_end
     */
    public function setDateEnd($date_end)
    {
        $this->date_end = $date_end;
    }


    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}