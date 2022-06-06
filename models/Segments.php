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
 * @property int $quantity_from                                     Потенциальное количество потребителей "от"
 * @property int $quantity_to                                       Потенциальное количество потребителей "до"
 * @property int $market_volume                                     Объем рынка
 * @property string $company_products                               Продукция / услуги предприятия
 * @property string $company_partner                                Партнеры предприятия
 * @property string $add_info                                       Дополнительная информация
 * @property int $created_at                                        Дата создания сегмента
 * @property int $updated_at                                        Дата обновления сегмента
 * @property int $time_confirm                                      Дата подверждения сегмента
 * @property int $exist_confirm                                     Параметр факта подтверждения сегмента
 * @property int $enable_expertise                                  Параметр разрешения на экспертизу по даному этапу
 */
class Segments extends ActiveRecord
{

    const TYPE_B2C = 100;
    const TYPE_B2B = 200;

    const GENDER_MAN = 50;
    const GENDER_WOMAN = 60;
    const GENDER_ANY = 70;

    const SECONDARY_EDUCATION = 50;
    const SECONDARY_SPECIAL_EDUCATION = 100;
    const HIGHER_INCOMPLETE_EDUCATION = 200;
    const HIGHER_EDUCATION = 300;

    const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'segments';
    }


    /**
     * Segment constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->propertyContainer = new PropertyContainer();

        parent::__construct($config);
    }


    /**
     * Получить объект проектв
     * @return ActiveQuery
     */
    public function getProject()
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
     * Получить объект подтверждения
     * @return ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmSegment::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все проблемы сегмента
     * @return ActiveQuery
     */
    public function getProblems ()
    {
        return $this->hasMany(Problems::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все ЦП сегмента
     * @return ActiveQuery
     */
    public function getGcps ()
    {
        return $this->hasMany(Gcps::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все Mv[ сегмента
     * @return ActiveQuery
     */
    public function getMvps ()
    {
        return $this->hasMany(Mvps::class, ['segment_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели сегмента
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['segment_id' => 'id']);
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'time_confirm'], 'integer'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info', 'description'], 'trim'],
            [['project_id', 'type_of_interaction_between_subjects', 'gender_consumer', 'education_of_consumer', 'exist_confirm'], 'integer'],
            [['age_from', 'age_to'], 'integer'],
            [['income_from', 'income_to'], 'integer'],
            [['quantity_from', 'quantity_to'], 'integer'],
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
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Вид / специализация деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity_from' => 'Потенциальное количество потребителей (тыс. чел.)',
            'market_volume' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
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
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
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

                InterviewConfirmSegment::deleteAll(['respond_id' => $respond->id]);
                AnswersQuestionsConfirmSegment::deleteAll(['respond_id' => $respond->id]);
            }

            QuestionsConfirmSegment::deleteAll(['confirm_id' => $confirm->id]);
            RespondsSegment::deleteAll(['confirm_id' => $confirm->id]);
            $confirm->delete();
        }

        // Удаление директории сегмента
        $segmentPathDelete = UPLOAD.'/user-'.$this->project->user->id.'/project-'.$this->project->id.'/segments/segment-'.$this->id;
        if (file_exists($segmentPathDelete)) FileHelper::removeDirectory($segmentPathDelete);

        // Удаление кэша для форм сегмента
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->id.'/projects/project-'.$this->project->id.'/segments/segment-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление сегмента
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
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getTypeOfInteractionBetweenSubjects()
    {
        return $this->type_of_interaction_between_subjects;
    }

    /**
     * @param int $type_of_interaction_between_subjects
     */
    public function setTypeOfInteractionBetweenSubjects($type_of_interaction_between_subjects)
    {
        $this->type_of_interaction_between_subjects = $type_of_interaction_between_subjects;
    }

    /**
     * @return string
     */
    public function getFieldOfActivity()
    {
        return $this->field_of_activity;
    }

    /**
     * @param string $field_of_activity
     */
    public function setFieldOfActivity($field_of_activity)
    {
        $this->field_of_activity = $field_of_activity;
    }

    /**
     * @return string
     */
    public function getSortOfActivity()
    {
        return $this->sort_of_activity;
    }

    /**
     * @param string $sort_of_activity
     */
    public function setSortOfActivity($sort_of_activity)
    {
        $this->sort_of_activity = $sort_of_activity;
    }

    /**
     * @return int
     */
    public function getAgeFrom()
    {
        return $this->age_from;
    }

    /**
     * @param int $age_from
     */
    public function setAgeFrom($age_from)
    {
        $this->age_from = $age_from;
    }

    /**
     * @return int
     */
    public function getAgeTo()
    {
        return $this->age_to;
    }

    /**
     * @param int $age_to
     */
    public function setAgeTo($age_to)
    {
        $this->age_to = $age_to;
    }

    /**
     * @return int
     */
    public function getGenderConsumer()
    {
        return $this->gender_consumer;
    }

    /**
     * @param int $gender_consumer
     */
    public function setGenderConsumer($gender_consumer)
    {
        $this->gender_consumer = $gender_consumer;
    }

    /**
     * @return int
     */
    public function getEducationOfConsumer()
    {
        return $this->education_of_consumer;
    }

    /**
     * @param int $education_of_consumer
     */
    public function setEducationOfConsumer($education_of_consumer)
    {
        $this->education_of_consumer = $education_of_consumer;
    }

    /**
     * @return int
     */
    public function getIncomeFrom()
    {
        return $this->income_from;
    }

    /**
     * @param int $income_from
     */
    public function setIncomeFrom($income_from)
    {
        $this->income_from = $income_from;
    }

    /**
     * @return int
     */
    public function getIncomeTo()
    {
        return $this->income_to;
    }

    /**
     * @param int $income_to
     */
    public function setIncomeTo($income_to)
    {
        $this->income_to = $income_to;
    }

    /**
     * @return int
     */
    public function getQuantityFrom()
    {
        return $this->quantity_from;
    }

    /**
     * @param int $quantity_from
     */
    public function setQuantityFrom($quantity_from)
    {
        $this->quantity_from = $quantity_from;
    }

    /**
     * @return int
     */
    public function getQuantityTo()
    {
        return $this->quantity_to;
    }

    /**
     * @param int $quantity_to
     */
    public function setQuantityTo($quantity_to)
    {
        $this->quantity_to = $quantity_to;
    }

    /**
     * @return int
     */
    public function getMarketVolume()
    {
        return $this->market_volume;
    }

    /**
     * @param int $market_volume
     */
    public function setMarketVolume($market_volume)
    {
        $this->market_volume = $market_volume;
    }

    /**
     * @return string
     */
    public function getCompanyProducts()
    {
        return $this->company_products;
    }

    /**
     * @param string $company_products
     */
    public function setCompanyProducts($company_products)
    {
        $this->company_products = $company_products;
    }

    /**
     * @return string
     */
    public function getCompanyPartner()
    {
        return $this->company_partner;
    }

    /**
     * @param string $company_partner
     */
    public function setCompanyPartner($company_partner)
    {
        $this->company_partner = $company_partner;
    }

    /**
     * @return string
     */
    public function getAddInfo()
    {
        return $this->add_info;
    }

    /**
     * @param string $add_info
     */
    public function setAddInfo($add_info)
    {
        $this->add_info = $add_info;
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
