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
 * Класс, который хранит объекты сегментов в бд
 *
 * Class Segments
 * @package app\models
 *
 * @property int $id                                                Идентификатор сегмента
 * @property int $project_id                                        Идентификатор проекта в таб.Projects
 * @property string $name                                           Наименование сегмента
 * @property string $description                                    Описание сегмента
 * @property int $type_of_interaction_between_subjects              Тип взаимодействия между субъектами
 * @property string $field_of_activity                              Сфера деятельности
 * @property string $sort_of_activity                               Вид / специализация деятельности
 * @property int $age_from                                          Возраст потребителя "от"
 * @property int $age_to                                            Возраст потребителя "до"
 * @property int $gender_consumer                                   Пол потребителя
 * @property int $education_of_consumer                             Образование потребителя
 * @property int $income_from                                       Доход потребителя "от"
 * @property int $income_to                                         Доход потребителя "до"
 * @property int $quantity                                          Потенциальное количество потребителей
 * @property int $market_volume                                     Объем рынка
 * @property string $company_products                               Продукция / услуги предприятия
 * @property string $company_partner                                Партнеры предприятия
 * @property string $add_info                                       Дополнительная информация
 * @property int $created_at                                        Дата создания сегмента
 * @property int $updated_at                                        Дата обновления сегмента
 * @property int|null $time_confirm                                 Дата подверждения сегмента
 * @property int|null $exist_confirm                                Параметр факта подтверждения сегмента
 * @property string $enable_expertise                               Параметр разрешения на экспертизу по даному этапу
 * @property int $use_wish_list                                     Параметр использования запроса из виш-листа при формирования сегмента
 * @property PropertyContainer $propertyContainer                   Свойство для реализации шаблона 'контейнер свойств'
 *
 * @property Projects $project                                      Проект, к которому принадлежит сегмент
 * @property ConfirmSegment $confirm                                Подтверждение сегмента
 * @property Problems[] $problems                                   Гипотезы проблем сегмента
 * @property Gcps[] $gcps                                           Гипотезы ценностных предложений
 * @property Mvps[] $mvps                                           Mvp-продукты
 * @property BusinessModel[] $businessModels                        Бизнес-модели
 * @property SegmentRequirement $segmentRequirement                 Связь с запросом B2B компаний
 */
class Segments extends ActiveRecord
{

    public const TYPE_B2C = 100;
    public const TYPE_B2B = 200;

    public const GENDER_MAN = 50;
    public const GENDER_WOMAN = 60;
    public const GENDER_ANY = 70;

    public const SECONDARY_EDUCATION = 50;
    public const SECONDARY_SPECIAL_EDUCATION = 100;
    public const HIGHER_INCOMPLETE_EDUCATION = 200;
    public const HIGHER_EDUCATION = 300;

    public const NOT_USE_WISH_LIST = 0;
    public const USE_WISH_LIST = 1;

    public const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    /**
     * @var PropertyContainer
     */
    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'segments';
    }


    /**
     * Segment constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setPropertyContainer();
        parent::__construct($config);
    }


    /**
     * Получить объект проекта
     *
     * @return ActiveQuery
     */
    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить объект подтверждения
     *
     * @return ActiveQuery
     */
    public function getConfirm(): ActiveQuery
    {
        return $this->hasOne(ConfirmSegment::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все проблемы сегмента
     *
     * @return ActiveQuery
     */
    public function getProblems(): ActiveQuery
    {
        return $this->hasMany(Problems::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все ЦП сегмента
     * @return ActiveQuery
     */
    public function getGcps(): ActiveQuery
    {
        return $this->hasMany(Gcps::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все Mv[ сегмента
     * @return ActiveQuery
     */
    public function getMvps(): ActiveQuery
    {
        return $this->hasMany(Mvps::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели сегмента
     * @return ActiveQuery
     */
    public function getBusinessModels(): ActiveQuery
    {
        return $this->hasMany(BusinessModel::class, ['segment_id' => 'id']);
    }


    /**
     * Получить связь с запросом B2B компаний
     * @return ActiveQuery
     */
    public function getSegmentRequirement(): ActiveQuery
    {
        return $this->hasOne(SegmentRequirement::class, ['segment_id' => 'id']);
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'time_confirm'], 'integer'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info', 'description'], 'trim'],
            [['project_id', 'type_of_interaction_between_subjects', 'gender_consumer', 'education_of_consumer', 'exist_confirm', 'use_wish_list'], 'integer'],
            [['age_from', 'age_to'], 'integer'],
            [['income_from', 'income_to'], 'integer'],
            [['quantity'], 'integer'],
            [['market_volume'], 'integer'],
            [['add_info'], 'string'],
            [['name',], 'string', 'min' => 2, 'max' => 65],
            [['description', 'company_products', 'company_partner'], 'string', 'max' => 2000],
            [['field_of_activity', 'sort_of_activity'], 'string', 'max' => 255],
            ['enable_expertise', 'default', 'value' => EnableExpertise::OFF],
            ['enable_expertise', 'in', 'range' => [
                EnableExpertise::OFF,
                EnableExpertise::ON,
            ]],
            ['use_wish_list', 'in', 'range' => [
                self::USE_WISH_LIST,
                self::NOT_USE_WISH_LIST,
            ]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Тип взаимодействия с потребителями',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Вид / специализация деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity' => 'Потенциальное количество потребителей',
            'market_volume' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
            'use_wish_list' => 'Использовать запросы B2B компаний'
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


    /**
     * @return void
     */
    public function init(): void
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
     * @return false|int
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function deleteStage ()
    {
        if ($problems = $this->problems) {
            foreach ($problems as $problem) {
                $problem->deleteStage();
            }
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                InterviewConfirmSegment::deleteAll(['respond_id' => $respond->getId()]);
                AnswersQuestionsConfirmSegment::deleteAll(['respond_id' => $respond->getId()]);
            }

            QuestionsConfirmSegment::deleteAll(['confirm_id' => $confirm->getId()]);
            RespondsSegment::deleteAll(['confirm_id' => $confirm->getId()]);
            $confirm->delete();
        }

        // Удаление директории сегмента
        $segmentPathDelete = UPLOAD.'/user-'.$this->project->user->getId().'/project-'.$this->project->getId().'/segments/segment-'.$this->getId();
        if (file_exists($segmentPathDelete)) {
            FileHelper::removeDirectory($segmentPathDelete);
        }

        // Удаление кэша для форм сегмента
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->getId().'/projects/project-'.$this->project->getId().'/segments/segment-'.$this->getId();
        if (file_exists($cachePathDelete)) {
            FileHelper::removeDirectory($cachePathDelete);
        }

        if ($this->segmentRequirement) {
            SegmentRequirement::deleteAll(['segment_id' => $this->getId()]);
        }

        // Удаление сегмента
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
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
    public function getTypeOfInteractionBetweenSubjects(): int
    {
        return $this->type_of_interaction_between_subjects;
    }

    /**
     * @param int $type_of_interaction_between_subjects
     */
    public function setTypeOfInteractionBetweenSubjects(int $type_of_interaction_between_subjects): void
    {
        $this->type_of_interaction_between_subjects = $type_of_interaction_between_subjects;
    }

    /**
     * @return string
     */
    public function getFieldOfActivity(): string
    {
        return $this->field_of_activity;
    }

    /**
     * @param string $field_of_activity
     */
    public function setFieldOfActivity(string $field_of_activity): void
    {
        $this->field_of_activity = $field_of_activity;
    }

    /**
     * @return string
     */
    public function getSortOfActivity(): string
    {
        return $this->sort_of_activity;
    }

    /**
     * @param string $sort_of_activity
     */
    public function setSortOfActivity(string $sort_of_activity): void
    {
        $this->sort_of_activity = $sort_of_activity;
    }

    /**
     * @return int
     */
    public function getAgeFrom(): int
    {
        return $this->age_from;
    }

    /**
     * @param int $age_from
     */
    public function setAgeFrom(int $age_from): void
    {
        $this->age_from = $age_from;
    }

    /**
     * @return int
     */
    public function getAgeTo(): int
    {
        return $this->age_to;
    }

    /**
     * @param int $age_to
     */
    public function setAgeTo(int $age_to): void
    {
        $this->age_to = $age_to;
    }

    /**
     * @return int
     */
    public function getGenderConsumer(): int
    {
        return $this->gender_consumer;
    }

    /**
     * @param int $gender_consumer
     */
    public function setGenderConsumer(int $gender_consumer): void
    {
        $this->gender_consumer = $gender_consumer;
    }

    /**
     * @return int
     */
    public function getEducationOfConsumer(): int
    {
        return $this->education_of_consumer;
    }

    /**
     * @param int $education_of_consumer
     */
    public function setEducationOfConsumer(int $education_of_consumer): void
    {
        $this->education_of_consumer = $education_of_consumer;
    }

    /**
     * @return int
     */
    public function getIncomeFrom(): int
    {
        return $this->income_from;
    }

    /**
     * @param int $income_from
     */
    public function setIncomeFrom(int $income_from): void
    {
        $this->income_from = $income_from;
    }

    /**
     * @return int
     */
    public function getIncomeTo(): int
    {
        return $this->income_to;
    }

    /**
     * @param int $income_to
     */
    public function setIncomeTo(int $income_to): void
    {
        $this->income_to = $income_to;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getMarketVolume(): int
    {
        return $this->market_volume;
    }

    /**
     * @param int $market_volume
     */
    public function setMarketVolume(int $market_volume): void
    {
        $this->market_volume = $market_volume;
    }

    /**
     * @return string
     */
    public function getCompanyProducts(): string
    {
        return $this->company_products;
    }

    /**
     * @param string $company_products
     */
    public function setCompanyProducts(string $company_products): void
    {
        $this->company_products = $company_products;
    }

    /**
     * @return string
     */
    public function getCompanyPartner(): string
    {
        return $this->company_partner;
    }

    /**
     * @param string $company_partner
     */
    public function setCompanyPartner(string $company_partner): void
    {
        $this->company_partner = $company_partner;
    }

    /**
     * @return string
     */
    public function getAddInfo(): string
    {
        return $this->add_info;
    }

    /**
     * @param string $add_info
     */
    public function setAddInfo(string $add_info): void
    {
        $this->add_info = $add_info;
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

    /**
     * @return int
     */
    public function getUseWishList(): int
    {
        return $this->use_wish_list;
    }

    /**
     * @param int $use_wish_list
     */
    public function setUseWishList(int $use_wish_list): void
    {
        $this->use_wish_list = $use_wish_list;
    }

    /**
     * @return void
     */
    public function changeUseWishList(): void
    {
        $this->use_wish_list = $this->use_wish_list === self::USE_WISH_LIST ? self::NOT_USE_WISH_LIST : self::USE_WISH_LIST;
    }
}
