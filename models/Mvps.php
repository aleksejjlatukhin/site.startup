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
 * Класс, который хранит объекты mvp-продуктов в бд
 *
 * Class Mvps
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. mvps
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_gcp
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $project_id                        Идентификатор записи в таб. projects
 * @property int $problem_id                        Идентификатор записи в таб. problems
 * @property int $gcp_id                            Идентификатор записи в таб. gcps
 * @property string $title                          Сформированное системой название mvp-продукта
 * @property string $description                    Описание mvp-продукта
 * @property int $created_at                        Дата создания mvp-продукта
 * @property int $updated_at                        Дата обновления mvp-продукта
 * @property int $time_confirm                      Дата подверждения mvp-продукта
 * @property int $exist_confirm                     Параметр факта подтверждения mvp-продукта
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class Mvps extends ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mvps';
    }


    /**
     * Mvps constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
    }


    /**
     * Получить объект подтверждения данного Mvps
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['mvp_id' => 'id']);
    }


    /**
     * @return mixed
     */
    public function getConfirmGcpId()
    {
        return $this->basic_confirm_id;
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
        return Projects::findOne($this->getProjectId());
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
     * Получить объект текущей проблемы
     * @return ActiveQuery
     */
    public function getProblem ()
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
     * Получить объект текущего Gcps
     * @return ActiveQuery
     */
    public function getGcp ()
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * @return Gcps|null
     */
    public function findGcp()
    {
        return Gcps::findOne($this->getGcpId());
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
     * подтвердтлт текущее Gcps
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getConfirmGcpId(), 'interview_confirm_gcp.status' => '1'])->all();
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['basic_confirm_id', 'title', 'description'], 'required'],
            [['title', 'description'], 'trim'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['time_confirm', 'basic_confirm_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'created_at', 'updated_at'], 'integer'],
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
     * Удаление Mvps и связанных данных
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

                InterviewConfirmMvp::deleteAll(['responds_mvp_id' => $respond->id]);
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
     * @param int $gcp_id
     */
    public function setGcpId($gcp_id)
    {
        $this->gcp_id = $gcp_id;
    }

    /**
     * @return int
     */
    public function getGcpId()
    {
        return $this->gcp_id;
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
