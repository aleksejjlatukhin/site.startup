<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "generation_problem".
 *
 * @property string $id
 * @property int $interview_id
 * @property string $description
 * @property string $date_gps
 */
class GenerationProblem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generation_problem';
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }

    public function getGcps()
    {
        return $this->hasMany(Gcp::class, ['problem_id' => 'id']);
    }

    public function getMvps()
    {
        return $this->hasMany(Mvp::class, ['problem_id' => 'id']);
    }

    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['problem_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmProblem::class, ['gps_id' => 'id']);
    }

    public function getSegment()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }

    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'description', 'date_gps', 'title'], 'required'],
            ['title', 'string', 'max' => 255],
            [['title', 'description'], 'trim'],
            [['interview_id', 'exist_confirm', 'segment_id', 'project_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['date_gps', 'date_confirm', 'date_time_confirm'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'interview_id' => 'Interview ID',
            'title' => 'Название ГПС',
            'description' => 'Описание',
            'date_gps' => 'Дата создания',
            'date_confirm' => 'Дата подтверждения'
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
