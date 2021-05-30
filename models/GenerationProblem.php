<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

class GenerationProblem extends ActiveRecord
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


    /**
     * Получить все объекты Gcp данной проблемы
     * @return ActiveQuery
     */
    public function getGcps()
    {
        return $this->hasMany(Gcp::class, ['problem_id' => 'id']);
    }


    /**
     * Получить все объекты Mvp данной проблемы
     * @return ActiveQuery
     */
    public function getMvps()
    {
        return $this->hasMany(Mvp::class, ['problem_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели данной проблемы
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['problem_id' => 'id']);
    }


    /**
     * Получить объект подтверждения данной проблемы
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmProblem::class, ['gps_id' => 'id']);
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment()
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
     * @param $id
     * @return mixed
     */
    public function setSegmentId($id)
    {
        return $this->segment_id = $id;
    }


    /**
     * @return mixed
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function setProjectId($id)
    {
        return $this->project_id = $id;
    }


    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }


    /**
     * @return mixed
     */
    public function getConfirmSegmentId()
    {
        return $this->interview_id;
    }


    /**
     * Получить представителей сегмента
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $this->interview_id, 'desc_interview.status' => '1'])->all();
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
     * Удаление проблемы и связанных данных
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
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
