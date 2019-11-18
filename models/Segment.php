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

    public function getRoadmap()
    {
        return $this->hasOne(Roadmap::class, ['segment_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['project_id'], 'integer'],
            [['field_of_activity', 'sort_of_activity', 'add_info'], 'string'],
            [['name', 'age', 'income', 'quantity', 'market_volume'], 'string', 'max' => 255],
            [['creat_date', 'plan_gps'], 'required'],
            [['creat_date', 'plan_gps', 'fact_gps', 'plan_ps', 'fact_ps', 'plan_dev_gcp', 'fact_dev_gcp', 'plan_gcp', 'fact_gcp', 'plan_dev_gmvp', 'fact_dev_gmvp', 'plan_gmvp', 'fact_gmvp'], 'safe'],
        ];
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
            'age' => 'Возраст потребителя',
            'income' => 'Доход потребителя (руб / мес)',
            'quantity' => 'Потенциальное количество потребителей',
            'market_volume' => 'Объем рынка (млн / год)',
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
