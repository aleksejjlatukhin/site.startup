<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "confirm_gcp".
 *
 * @property string $id
 * @property int $gcp_id
 * @property int $count_respond
 * @property int $count_positive
 */
class ConfirmGcp extends \yii\db\ActiveRecord
{

    public $exist_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_gcp';
    }

    public function getGcp()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertGcp::class, ['confirm_gcp_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsGcp::class, ['confirm_gcp_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id', 'exist_confirm'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gcp_id' => 'Gcp ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }
}
