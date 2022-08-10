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
 * @property int $id                                                        Идентификатор записи в таб. problems
 * @property int $basic_confirm_id                                          Идентификатор записи в таб. confirm_segment
 * @property int $segment_id                                                Идентификатор записи в таб. segments
 * @property int $project_id                                                Идентификатор записи в таб. projects
 * @property string $title                                                  Сформированное системой название проблемы
 * @property string $description                                            Описание проблемы
 * @property int $indicator_positive_passage                                Показатель положительного прохождения теста
 * @property int $created_at                                                Дата создания проблемы
 * @property int $updated_at                                                Дата обновления проблемы
 * @property int $time_confirm                                              Дата подверждения проблемы
 * @property int $exist_confirm                                             Параметр факта подтверждения проблемы
 * @property string $enable_expertise                                       Параметр разрешения на экспертизу по даному этапу
 * @property PropertyContainer $propertyContainer                           Свойство для реализации шаблона 'контейнер свойств'
 *
 * @property Gcps[] $gcps                                                   Ценностные предложения
 * @property Mvps[] $mvps                                                   Mvp-продукты
 * @property BusinessModel[] $businessModels                                Бизнес-модели
 * @property ConfirmProblem $confirm                                        Подтверждение проблемы
 * @property Segments $segment                                              Сегмент
 * @property Projects $project                                              Проект
 * @property RespondsSegment[] $respondsAgents                              Представители сегмента
 * @property ExpectedResultsInterviewConfirmProblem[] $expectedResults      Вопросы для проверки и ответы на них создаются на этапе генерации проблем сегмента
 */
class Problems extends ActiveRecord
{

    public const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'problems';
    }


    /**
     * Problems constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setPropertyContainer();
        parent::__construct($config);
    }


    /**
     * Получить все объекты Gcp данной проблемы
     *
     * @return ActiveQuery
     */
    public function getGcps(): ActiveQuery
    {
        return $this->hasMany(Gcps::class, ['problem_id' => 'id']);
    }


    /**
     * Получить все объекты Mvp данной проблемы
     *
     * @return ActiveQuery
     */
    public function getMvps(): ActiveQuery
    {
        return $this->hasMany(Mvps::class, ['problem_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели данной проблемы
     *
     * @return ActiveQuery
     */
    public function getBusinessModels(): ActiveQuery
    {
        return $this->hasMany(BusinessModel::class, ['problem_id' => 'id']);
    }


    /**
     * Получить объект подтверждения данной проблемы
     *
     * @return ActiveQuery
     */
    public function getConfirm(): ActiveQuery
    {
        return $this->hasOne(ConfirmProblem::class, ['problem_id' => 'id']);
    }


    /**
     * Получить объект текущего сегмента
     *
     * @return ActiveQuery
     */
    public function getSegment(): ActiveQuery
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
    }


    /**
     * Получить объект текущего проекта
     *
     * @return ActiveQuery
     */
    public function getProject (): ActiveQuery
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить представителей сегмента
     *
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents(): array
    {
        return RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->getBasicConfirmId(), 'interview_confirm_segment.status' => '1'])->all();
    }


    /**
     * Вопросы для проверки и ответы на них
     * создаются на этапе генерации проблем сегмента
     *
     * @return ActiveQuery
     */
    public function getExpectedResults(): ActiveQuery
    {
        return $this->hasMany(ExpectedResultsInterviewConfirmProblem::class, ['problem_id' => 'id']);
    }


    /**
     * Список вопросов для проверки и ответов на них
     *
     * @return string
     */
    public function getListExpectedResultsInterview(): string
    {
        $str = ''; $n = 1;
        foreach ($this->expectedResults as $expectedResult) {
            $str .= '<b>' . $n . '.</b> ' . $expectedResult->getQuestion() . ' (' . $expectedResult->getAnswer() . ') </br>';
            $n++;
        }
        return $str;
    }


    /**
     * @return array
     */
    public static function getValuesForSelectIndicatorPositivePassage(): array
    {
        return [
            5 => 'Показатель положительного прохождения теста - 5%',
            10 => 'Показатель положительного прохождения теста - 10%',
            15 => 'Показатель положительного прохождения теста - 15%',
            20 => 'Показатель положительного прохождения теста - 20%',
            25 => 'Показатель положительного прохождения теста - 25%',
            30 => 'Показатель положительного прохождения теста - 30%',
            35 => 'Показатель положительного прохождения теста - 35%',
            40 => 'Показатель положительного прохождения теста - 40%',
            45 => 'Показатель положительного прохождения теста - 45%',
            50 => 'Показатель положительного прохождения теста - 50%',
            55 => 'Показатель положительного прохождения теста - 55%',
            60 => 'Показатель положительного прохождения теста - 60%',
            65 => 'Показатель положительного прохождения теста - 65%',
            70 => 'Показатель положительного прохождения теста - 70%',
            75 => 'Показатель положительного прохождения теста - 75%',
            80 => 'Показатель положительного прохождения теста - 80%',
            85 => 'Показатель положительного прохождения теста - 85%',
            90 => 'Показатель положительного прохождения теста - 90%',
            95 => 'Показатель положительного прохождения теста - 95%',
            100 => 'Показатель положительного прохождения теста - 100%',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
    public function behaviors(): array
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
            ExpectedResultsInterviewConfirmProblem::deleteAll(['problem_id' => $this->getId()]);
        });

        parent::init();
    }


    /**
     * @return false|int
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
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

                InterviewConfirmProblem::deleteAll(['respond_id' => $respond->getId()]);
                AnswersQuestionsConfirmProblem::deleteAll(['respond_id' => $respond->getId()]);
            }

            QuestionsConfirmProblem::deleteAll(['confirm_id' => $confirm->getId()]);
            RespondsProblem::deleteAll(['confirm_id' => $confirm->getId()]);

            $confirm->delete();
        }

        // Удаление директории проблемы
        $problemPathDelete = UPLOAD.'/user-'.$this->project->user->getId().'/project-'.$this->project->getId().'/segments/segment-'.$this->segment->getId().'/problems/problem-'.$this->getId();
        if (file_exists($problemPathDelete)) {
            FileHelper::removeDirectory($problemPathDelete);
        }

        // Удаление кэша для форм проблемы
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->getId().'/projects/project-'.$this->project->getId().'/segments/segment-'.$this->segment->getId().'/problems/problem-'.$this->getId();
        if (file_exists($cachePathDelete)) {
            FileHelper::removeDirectory($cachePathDelete);
        }

        // Удаление проблемы
        return $this->delete();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBasicConfirmId(): int
    {
        return $this->basic_confirm_id;
    }

    /**
     * @param int $basic_confirm_id
     */
    public function setBasicConfirmId(int $basic_confirm_id): void
    {
        $this->basic_confirm_id = $basic_confirm_id;
    }

    /**
     * @param int $id
     */
    public function setSegmentId(int $id): void
    {
        $this->segment_id = $id;
    }

    /**
     * @return int
     */
    public function getSegmentId(): int
    {
        return $this->segment_id;
    }

    /**
     * @param int $id
     */
    public function setProjectId(int $id): void
    {
        $this->project_id = $id;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * @return int
     */
    public function getConfirmSegmentId(): int
    {
        return $this->basic_confirm_id;
    }

    /**
     * Параметр разрешения экспертизы
     * @return string
     */
    public function getEnableExpertise(): string
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise(): void
    {
        $this->enable_expertise = EnableExpertise::ON;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getIndicatorPositivePassage(): int
    {
        return $this->indicator_positive_passage;
    }

    /**
     * @param int $indicator_positive_passage
     */
    public function setIndicatorPositivePassage(int $indicator_positive_passage): void
    {
        $this->indicator_positive_passage = $indicator_positive_passage;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updated_at;
    }

    /**
     * @return int|null
     */
    public function getTimeConfirm(): ?int
    {
        return $this->time_confirm;
    }

    /**
     * @param int|null $time_confirm
     */
    public function setTimeConfirm(int $time_confirm = null): void
    {
        $time_confirm ? $this->time_confirm = $time_confirm : $this->time_confirm = time();
    }

    /**
     * @return int|null
     */
    public function getExistConfirm(): ?int
    {
        return $this->exist_confirm;
    }

    /**
     * @param int $exist_confirm
     */
    public function setExistConfirm(int $exist_confirm): void
    {
        $this->exist_confirm = $exist_confirm;
    }

    /**
     * @return PropertyContainer
     */
    public function getPropertyContainer(): PropertyContainer
    {
        return $this->propertyContainer;
    }

    /**
     *
     */
    public function setPropertyContainer(): void
    {
        $this->propertyContainer = new PropertyContainer();
    }
}
