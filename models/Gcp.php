<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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

    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['gcp_id' => 'id']);
    }

    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['gcp_id' => 'id']);
    }

    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['gcp_id' => 'id']);
    }

    public function getProblem()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
    }

    public function getSegment ()
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
            [['title', 'description'], 'trim'],
            [['time_confirm', 'confirm_problem_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
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
            'description' => 'Формулировка ГЦП',
            'date_create' => 'Дата создания',
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
        if ($mvps = $this->mvps) {
            foreach ($mvps as $mvp) {
                $mvp->deleteStage();
            }
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                DescInterviewGcp::deleteAll(['responds_gcp_id' => $respond->id]);
                AnswersQuestionsConfirmGcp::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmGcp::deleteAll(['confirm_gcp_id' => $confirm->id]);
            RespondsGcp::deleteAll(['confirm_gcp_id' => $confirm->id]);
            $confirm->delete();
        }

        $this->delete();
    }
}
