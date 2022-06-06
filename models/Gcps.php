<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит объекты ценностных предложений в бд
 *
 * Class Gcps
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. gcps
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_problem
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $project_id                        Идентификатор записи в таб. projects
 * @property int $problem_id                        Идентификатор записи в таб. problems
 * @property string $title                          Сформированное системой название ценностного предложения
 * @property string $description                    Описание ценностного предложения
 * @property int $created_at                        Дата создания ЦП
 * @property int $updated_at                        Дата обновления ЦП
 * @property int $time_confirm                      Дата подверждения ЦП
 * @property int $exist_confirm                     Параметр факта подтверждения ЦП
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class Gcps extends ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gcps';
    }


    /**
     * Gcps constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
    }


    /**
     * Получить объект подтверждения данного Gcps
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['gcp_id' => 'id']);
    }


    /**
     * @return mixed
     */
    public function getConfirmProblemId()
    {
        return $this->basic_confirm_id;
    }


    /**
     * Получить все бизнес-модели данного Gcps
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить все объекты Mvps данного Gcps
     * @return ActiveQuery
     */
    public function getMvps ()
    {
        return $this->hasMany(Mvps::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить объект текущей проблемы
     * @return ActiveQuery
     */
    public function getProblem()
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * @return Problems|null
     */
    public function findProblem()
    {
        return Problems::findOne($this->getProblemId());
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment ()
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
    }


    /**
     * @return Segments|null
     */
    public function findSegment()
    {
        return Segments::findOne($this->getSegmentId());
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
     * @return Projects|null
     */
    public function findProject()
    {
        return Projects::findOne($this->getProblemId());
    }


    /**
     * Получить респондентов, которые
     * подтвердтлт текущую проблему
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->confirmProblemId, 'interview_confirm_problem.status' => '1'])->all();
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'trim'],
            [['time_confirm', 'basic_confirm_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 1500],
            [['title'], 'string', 'max' => 255],
            ['enable_expertise', 'default', 'value' => EnableExpertise::OFF],
            ['enable_expertise', 'in', 'range' => [
                EnableExpertise::OFF,
                EnableExpertise::ON,
            ]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
     * Удаление Gcps и связанных данных
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

                InterviewConfirmGcp::deleteAll(['respond_id' => $respond->id]);
                AnswersQuestionsConfirmGcp::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmGcp::deleteAll(['confirm_id' => $confirm->id]);
            RespondsGcp::deleteAll(['confirm_id' => $confirm->id]);
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBasicConfirmId()
    {
        return $this->basic_confirm_id;
    }

    /**
     * @param int $basic_confirm_id
     */
    public function setBasicConfirmId($basic_confirm_id)
    {
        $this->basic_confirm_id = $basic_confirm_id;
    }

    /**
     * @param int $segment_id
     */
    public function setSegmentId($segment_id)
    {
        $this->segment_id = $segment_id;
    }

    /**
     * @return int
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param int $problem_id
     */
    public function setProblemId($problem_id)
    {
        $this->problem_id = $problem_id;
    }

    /**
     * @return int
     */
    public function getProblemId()
    {
        return $this->problem_id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return int
     */
    public function getTimeConfirm()
    {
        return $this->time_confirm;
    }

    /**
     * @param int $time_confirm
     */
    public function setTimeConfirm($time_confirm)
    {
        $this->time_confirm = $time_confirm;
    }

    /**
     * @return int
     */
    public function getExistConfirm()
    {
        return $this->exist_confirm;
    }

    /**
     * @param int $exist_confirm
     */
    public function setExistConfirm($exist_confirm)
    {
        $this->exist_confirm = $exist_confirm;
    }

    /**
     * @return int
     */
    public function getEnableExpertise()
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise()
    {
        $this->enable_expertise = EnableExpertise::ON;
    }
}
