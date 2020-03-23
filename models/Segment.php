<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "segments".
 *
 * @property string $id
 * @property int $project_id
 * @property string $name
 * @property string $field_of_activity
 * @property string $sort_of_activity
 * @property string $age
 * @property string $income
 * @property string $quantity
 * @property string $market_volume
 * @property string $add_info
 */
class Segment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'segments';
    }

    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['segment_id' => 'id']);
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info'], 'trim'],
            [['project_id'], 'integer'],
            [['age_from', 'age_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100'],
            ['age_from', 'uniqueAge'],
            [['income_from', 'income_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '10000'],
            ['income_from', 'uniqueIncome'],
            [['quantity_from', 'quantity_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '1000000'],
            ['quantity_from', 'uniqueQuantity'],
            [['market_volume_from', 'market_volume_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100000'],
            ['market_volume_from', 'uniqueMarketVolume'],
            [['field_of_activity', 'sort_of_activity', 'add_info'], 'string'],
            [['name',], 'string', 'min' => 6, 'max' => 48],
            [['creat_date', 'plan_gps', 'fact_gps', 'plan_ps', 'fact_ps', 'plan_dev_gcp', 'fact_dev_gcp', 'plan_gcp', 'fact_gcp', 'plan_dev_gmvp', 'fact_dev_gmvp', 'plan_gmvp', 'fact_gmvp'], 'safe'],
        ];
    }


    public function uniqueAge($attr){
        if ($this->age_from == $this->age_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueIncome($attr){
        if ($this->income_from == $this->income_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueQuantity($attr){
        if ($this->quantity_from == $this->quantity_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueMarketVolume($attr){
        if ($this->market_volume_from == $this->market_volume_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Наименование сегмента',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Род деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity_from' => 'Потенциальное количество потребителей (тыс. чел.)',
            'market_volume_from' => 'Объем рынка (млн. руб./год)',
            'add_info' => 'Дополнительная информация',
            'creat_date' => 'Дата создания',
            'plan_gps' => 'План',
            'fact_gps' => 'Факт',
            'plan_ps' => 'План',
            'fact_ps' => 'Факт',
            'plan_dev_gcp' => 'План',
            'fact_dev_gcp' => 'Факт',
            'plan_gcp' => 'План',
            'fact_gcp' => 'Факт',
            'plan_dev_gmvp' => 'План',
            'fact_dev_gmvp' => 'Факт',
            'plan_gmvp' => 'План',
            'fact_gmvp' => 'Факт',
        ];
    }
}
