<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mvp".
 *
 * @property string $id
 * @property int $confirm_gcp_id
 * @property string $title
 * @property string $description
 * @property string $date_create
 * @property string $date_confirm
 * @property int $exist_confirm
 */
class Mvp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mvp';
    }

    public function getGcp()
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_gcp_id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['mvp_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_gcp_id', 'title', 'description', 'date_create'], 'required'],
            [['title', 'description'], 'trim'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['confirm_gcp_id', 'exist_confirm'], 'integer'],
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
            'confirm_gcp_id' => 'Confirm Gcp ID',
            'title' => 'Наименование MVP',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_confirm' => 'Дата подтверждения',
        ];
    }
}
