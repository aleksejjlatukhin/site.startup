<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "confirm_mvp".
 *
 * @property string $id
 * @property int $mvp_id
 * @property int $count_respond
 * @property int $count_positive
 */
class ConfirmMvp extends \yii\db\ActiveRecord
{

    public $exist_confirm;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_mvp';
    }

    public function getMvp()
    {
        return $this->hasOne(Mvp::class, ['id' => 'mvp_id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertMvp::class, ['confirm_mvp_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsMvp::class, ['confirm_mvp_id' => 'id']);
    }

    public function getBusiness()
    {
        return $this->hasOne(BusinessModel::class, ['confirm_mvp_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mvp_id', 'count_respond', 'count_positive'], 'required'],
            [['mvp_id', 'exist_confirm'], 'integer'],
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
            'mvp_id' => 'Mvp ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }
}
