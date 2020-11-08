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

    public function getGcps()
    {
        return $this->hasMany(Gcp::class, ['problem_id' => 'id']);
    }

    public function getMvps()
    {
        return $this->hasMany(Mvp::class, ['problem_id' => 'id']);
    }

    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['problem_id' => 'id']);
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
            [['interview_id', 'title'], 'required'],
            ['title', 'string', 'max' => 255],
            [['title', 'description'], 'trim'],
            [['time_confirm', 'interview_id', 'exist_confirm', 'segment_id', 'project_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
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


    public function deleteStage ()
    {

        if ($gcps = $this->gcps) {
            foreach ($gcps as $gcp) {
                $gcp->deleteStage();
            }
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                DescInterviewConfirm::deleteAll(['responds_confirm_id' => $respond->id]);
                AnswersQuestionsConfirmProblem::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmProblem::deleteAll(['confirm_problem_id' => $confirm->id]);
            RespondsConfirm::deleteAll(['confirm_problem_id' => $confirm->id]);
            $confirm->delete();
        }

        $this->delete();
    }
}
