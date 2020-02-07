<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gcp".
 *
 * @property string $id
 * @property string $confirm_problem_id
 * @property string $title
 * @property string $good
 * @property string $benefit
 * @property string $contrast
 * @property string $description
 */
class Gcp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gcp';
    }

    public function getProblem()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['gcp_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good', 'benefit', 'contrast',], 'required'],
            [['title', 'good', 'benefit', 'contrast', 'description'], 'trim'],
            [['confirm_problem_id', 'exist_confirm'], 'integer'],
            [['description'], 'string'],
            [['title', 'good', 'benefit', 'contrast'], 'string', 'max' => 255],
            [['date_create', 'date_confirm', 'date_time_confirm', 'date_time_create'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_problem_id' => 'Confirm Problem ID',
            'title' => 'Наименование ГЦП',
            'good' => 'товар/услуга',
            'benefit' => 'выгода',
            'contrast' => 'с чем сравнивается',
            'description' => 'Формулировка ГЦП',
            'date_create' => 'Дата создания',
            'date_confirm' => 'Дата подтверждения'
        ];
    }
}
