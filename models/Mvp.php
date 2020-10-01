<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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

    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getSegment ()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }

    public function getProblem ()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
    }

    public function getValueProposition ()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }

    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['mvp_id' => 'id']);
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
            [['confirm_gcp_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'created_at', 'updated_at'], 'integer'],
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
            'title' => 'Наименование ГMVP',
            'description' => 'Описание',
            'date_create' => 'Дата создания',
            'date_confirm' => 'Дата подтверждения',
        ];
    }

    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }
}
