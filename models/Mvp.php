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

    public $propertyContainer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mvp';
    }


    /**
     * Mvp constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
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

    public function getGcp ()
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
            [['confirm_gcp_id', 'title', 'description'], 'required'],
            [['title', 'description'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['time_confirm', 'confirm_gcp_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'created_at', 'updated_at'], 'integer'],

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
        if ($businessModel = $this->businessModel) {
            $businessModel->delete();
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                DescInterviewMvp::deleteAll(['responds_mvp_id' => $respond->id]);
                AnswersQuestionsConfirmMvp::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmMvp::deleteAll(['confirm_mvp_id' => $confirm->id]);
            RespondsMvp::deleteAll(['confirm_mvp_id' => $confirm->id]);
            $confirm->delete();
        }

        $this->delete();
    }
}
