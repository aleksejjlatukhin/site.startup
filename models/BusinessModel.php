<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business_model".
 *
 * @property string $id
 * @property int $confirm_mvp_id
 * @property string $quantity
 * @property string $sort_of_activity
 * @property string $relations
 * @property string $partners
 * @property string $distribution_of_sales
 * @property string $resources
 * @property string $cost
 * @property string $revenue
 */
class BusinessModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_model';
    }

    public function getMvp()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'required'],
            [['confirm_mvp_id'], 'integer'],
            [['relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_mvp_id' => 'Confirm Mvp ID',
            'relations' => 'Взаимоотношения с клиентами',
            'partners' => 'Ключевые партнеры',
            'distribution_of_sales' => 'Каналы коммуникации и сбыта',
            'resources' => 'Ключевые ресурсы',
            'cost' => 'Структура издержек',
            'revenue' => 'Потоки поступления доходов',
        ];
    }
}
