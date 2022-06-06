<?php

namespace app\models;

use Throwable;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

/**
 * Класс, который хранит объекты проблем сегментов в бд
 *
 * Class Problems
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. problems
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_segment
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $project_id                        Идентификатор записи в таб. projects
 * @property string $title                          Сформированное системой название проблемы
 * @property string $description                    Описание проблемы
 * @property int $indicator_positive_passage        Показатель положительного прохождения теста
 * @property int $created_at                        Дата создания проблемы
 * @property int $updated_at                        Дата обновления проблемы
 * @property int $time_confirm                      Дата подверждения проблемы
 * @property int $exist_confirm                     Параметр факта подтверждения проблемы
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class Problems extends ActiveRecord
{

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'problems';
    }


    /**
     * Problems constructor.
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
        return $this->hasMany(Gcps::class, ['problem_id' => 'id']);
    }


    /**
     * Получить все объекты Mvp данной проблемы
     * @return ActiveQuery
     */
    public function getMvps()
    {
        return $this->hasMany(Mvps::class, ['problem_id' => 'id']);
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
        return $this->hasOne(ConfirmProblem::class, ['problem_id' => 'id']);
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment()
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
        return Projects::findOne($this->getProjectId());
    }


    /**
     * Получить представителей сегмента
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents()
    {
        return RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->basic_confirm_id, 'interview_confirm_segment.status' => '1'])->all();
    }


    /**
     * Вопросы для проверки и ответы на них
     * создаются на этапе генерации проблем сегмента
     *
     * @return ActiveQuery
     */
    public function getExpectedResults()
    {
        return $this->hasMany(ExpectedResultsInterviewConfirmProblem::class, ['problem_id' => 'id']);
    }


    /**
     * Список вопросов для проверки и ответов на них
     *
     * @return string
     */
    public function getListExpectedResultsInterview()
    {
        $str = ''; $n = 1;
        foreach ($this->expectedResults as $expectedResult) {
            $str .= '<b>' . $n . '.</b> ' . $expectedResult->question . ' (' . $expectedResult->answer . ') </br>';
            $n++;
        }
        return $str;
    }


    /**
     * @return array
     */
    public static function getValuesForSelectIndicatorPositivePassage()
    {
        return [
            '5' => 'Показатель положительного прохождения теста - 5%',
            '10' => 'Показатель положительного прохождения теста - 10%',
            '15' => 'Показатель положительного прохождения теста - 15%',
            '20' => 'Показатель положительного прохождения теста - 20%',
            '25' => 'Показатель положительного прохождения теста - 25%',
            '30' => 'Показатель положительного прохождения теста - 30%',
            '35' => 'Показатель положительного прохождения теста - 35%',
            '40' => 'Показатель положительного прохождения теста - 40%',
            '45' => 'Показатель положительного прохождения теста - 45%',
            '50' => 'Показатель положительного прохождения теста - 50%',
            '55' => 'Показатель положительного прохождения теста - 55%',
            '60' => 'Показатель положительного прохождения теста - 60%',
            '65' => 'Показатель положительного прохождения теста - 65%',
            '70' => 'Показатель положительного прохождения теста - 70%',
            '75' => 'Показатель положительного прохождения теста - 75%',
            '80' => 'Показатель положительного прохождения теста - 80%',
            '85' => 'Показатель положительного прохождения теста - 85%',
            '90' => 'Показатель положительного прохождения теста - 90%',
            '95' => 'Показатель положительного прохождения теста - 95%',
            '100' => 'Показатель положительного прохождения теста - 100%',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['basic_confirm_id', 'title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2000],
            [['title', 'description'], 'trim'],
            [['indicator_positive_passage', 'time_confirm', 'basic_confirm_id', 'exist_confirm', 'segment_id', 'project_id', 'created_at', 'updated_at'], 'integer'],
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
            'title' => 'Название ГПС',
            'description' => 'Описание гипотезы проблемы сегмента',
            'indicator_positive_passage' => 'Показатель положительного прохождения теста',
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
            ExpectedResultsInterviewConfirmProblem::deleteAll(['problem_id' => $this->id]);
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

                InterviewConfirmProblem::deleteAll(['respond_id' => $respond->id]);
                AnswersQuestionsConfirmProblem::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmProblem::deleteAll(['confirm_id' => $confirm->id]);
            RespondsProblem::deleteAll(['confirm_id' => $confirm->id]);

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
     * @param int $id
     */
    public function setSegmentId($id)
    {
        $this->segment_id = $id;
    }

    /**
     * @return int
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }

    /**
     * @param int $id
     */
    public function setProjectId($id)
    {
        $this->project_id = $id;
    }

    /**
     * @return int
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
        return $this->basic_confirm_id;
    }

    /**
     * Параметр разрешения экспертизы
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
    public function getIndicatorPositivePassage()
    {
        return $this->indicator_positive_passage;
    }

    /**
     * @param int $indicator_positive_passage
     */
    public function setIndicatorPositivePassage($indicator_positive_passage)
    {
        $this->indicator_positive_passage = $indicator_positive_passage;
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
}
