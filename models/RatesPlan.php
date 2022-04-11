<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс хранит общие сведения о тарифных планах
 *
 * Class RatesPlan
 * @package app\models
 *
 * @property int $id                            индентификатор тарифного плана
 * @property string $name                       наименование тарифного плана
 * @property string $description                описание тарифного плана
 * @property int $max_count_project_user        максимальное количество проектантов по тарифному плану
 * @property int $max_count_tracker             максимальное количество трекеров по тарифному плану
 * @property int $created_at                    дата создания тарифного плана
 */
class RatesPlan extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'rates_plan';
    }


    /**
     * @return ActiveQuery
     */
    public function getClientRatesPlans()
    {
        return $this->hasMany(ClientRatesPlan::class, ['rates_plan_id' => 'id']);
    }


    /**
     * @return ClientRatesPlan[]
     */
    public function findClientRatesPlans()
    {
        return ClientRatesPlan::findAll(['rates_plan_id' => $this->id]);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'max_count_project_user', 'max_count_tracker'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['max_count_project_user', 'max_count_tracker', 'created_at'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'description' => 'Описание',
            'max_count_project_user' => 'Максимальное количество проектантов',
            'max_count_tracker' => 'Максимальное количество трекеров',
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getMaxCountProjectUser()
    {
        return $this->max_count_project_user;
    }


    /**
     * @param int $max_count_project_user
     */
    public function setMaxCountProjectUser($max_count_project_user)
    {
        $this->max_count_project_user = $max_count_project_user;
    }


    /**
     * @return int
     */
    public function getMaxCountTracker()
    {
        return $this->max_count_tracker;
    }


    /**
     * @param int $max_count_tracker
     */
    public function setMaxCountTracker($max_count_tracker)
    {
        $this->max_count_tracker = $max_count_tracker;
    }


    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }


}