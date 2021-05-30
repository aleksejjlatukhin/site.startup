<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

class Mvp extends ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

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


    /**
     * Получить объект подтверждения данного Mvp
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['mvp_id' => 'id']);
    }


    /**
     * Получить объект текущего проекта
     * @return ActiveQuery
     */
    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment ()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }


    /**
     * Получить объект текущей проблемы
     * @return ActiveQuery
     */
    public function getProblem ()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
    }


    /**
     * Получить объект текущего Gcp
     * @return ActiveQuery
     */
    public function getGcp ()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }


    /**
     * Получить объект бизнес-модели
     * @return ActiveQuery
     */
    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['mvp_id' => 'id']);
    }


    /**
     * Получить респондентов, которые
     * подтвердтлт текущее Gcp
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $this->confirm_gcp_id, 'desc_interview_gcp.status' => '1'])->all();
    }


    /**
     * @return mixed
     */
    public function getGcpId()
    {
        return $this->gcp_id;
    }


    /**
     * @return mixed
     */
    public function getProblemId()
    {
        return $this->problem_id;
    }


    /**
     * @return mixed
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }


    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
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


    /**
     * @return array
     */
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
     * Удаление Mvp и связанных данных
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
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

        // Удаление директории MVP
        $gcpPathDelete = UPLOAD.'/user-'.$this->project->user->id.'/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.
            '/problems/problem-'.$this->problem->id.'/gcps/gcp-'.$this->gcp->id.'/mvps/mvp-'.$this->id;
        if (file_exists($gcpPathDelete)) FileHelper::removeDirectory($gcpPathDelete);

        // Удаление кэша для форм MVP
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->id.'/projects/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.
            '/problems/problem-'.$this->problem->id.'/gcps/gcp-'.$this->gcp->id.'/mvps/mvp-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление MVP
        $this->delete();
    }
}
