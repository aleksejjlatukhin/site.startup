<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

class Gcp extends ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gcp';
    }


    /**
     * Gcp constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
    }


    /**
     * Получить объект подтверждения данного Gcp
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели данного Gcp
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить все объекты Mvp данного Gcp
     * @return ActiveQuery
     */
    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить объект текущей проблемы
     * @return ActiveQuery
     */
    public function getProblem()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'problem_id']);
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
     * Получить объект текущего проекта
     * @return ActiveQuery
     */
    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
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
     * Получить респондентов, которые
     * подтвердтлт текущую проблему
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->confirm_problem_id, 'desc_interview_confirm.status' => '1'])->all();
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'trim'],
            [['time_confirm', 'confirm_problem_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 1500],
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
     * Удаление Gcp и связанных данных
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
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

        // Удаление директории ГЦП
        $gcpPathDelete = UPLOAD.'/user-'.$this->project->user->id.'/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.
            '/problems/problem-'.$this->problem->id.'/gcps/gcp-'.$this->id;
        if (file_exists($gcpPathDelete)) FileHelper::removeDirectory($gcpPathDelete);

        // Удаление кэша для форм ГЦП
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->id.'/projects/project-'.$this->project->id.'/segments/segment-'.$this->segment->id.
            '/problems/problem-'.$this->problem->id.'/gcps/gcp-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление ГЦП
        $this->delete();
    }
}
