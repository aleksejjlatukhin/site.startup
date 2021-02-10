<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

class GenerationProblem extends \yii\db\ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generation_problem';
    }

    /**
     * GenerationProblem constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
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
            [['title'], 'string', 'max' => 255],
            [['description', 'action_to_check', 'result_metric'], 'string', 'max' => 2000],
            [['title', 'description', 'action_to_check', 'result_metric'], 'trim'],
            [['time_confirm', 'interview_id', 'exist_confirm', 'segment_id', 'project_id', 'created_at', 'updated_at'], 'integer'],
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
            'description' => 'Описание гипотезы проблемы сегмента',
            'action_to_check' => 'Действие для проверки',
            'result_metric' => 'Метрика результата',
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


    public function init()
    {

        $this->on(self::EVENT_CLICK_BUTTON_CONFIRM, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        parent::init();
    }


    /**
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
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

        // Удаление директории проблемы
        $problemPathDelete = UPLOAD.'/user-'.$this->project->user->id.'/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.'/problems/problem-'.$this->id;
        if (file_exists($problemPathDelete)) FileHelper::removeDirectory($problemPathDelete);

        // Удаление кэша для форм проблемы
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->id.'/projects/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.'/problems/problem-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление проблемы
        $this->delete();
    }
}
