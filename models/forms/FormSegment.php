<?php


namespace app\models\forms;

use app\models\Segments;
use yii\base\Model;

/**
 * Форма для создания и редактирования сегментов
 *
 * Class FormSegment
 * @package app\models\forms
 *
 * @property int $project_id                                        Идентификатор проекта в таб.Projects
 * @property string $name                                           Наименование сегмента
 * @property string $description                                    Описание сегмента
 * @property int $type_of_interaction_between_subjects              Тип взаимодействия между субъектами
 * @property string $field_of_activity_b2c                          Сфера деятельности потребителя
 * @property string $field_of_activity_b2b                          Сфера деятельности предприятия
 * @property string $sort_of_activity_b2c                           Вид / специализация деятельности потребителя
 * @property string $sort_of_activity_b2b                           Вид / специализация деятельности предприятия
 * @property int $age_from                                          Возраст потребителя "от"
 * @property int $age_to                                            Возраст потребителя "до"
 * @property int $gender_consumer                                   Пол потребителя
 * @property int $education_of_consumer                             Образование потребителя
 * @property int $income_from                                       Доход потребителя "от"
 * @property int $income_to                                         Доход потребителя "до"
 * @property int $income_company_from                               Доход предприятия "от"
 * @property int $income_company_to                                 Доход предприятия "до"
 * @property int $quantity                                          Потенциальное количество потребителей
 * @property int $quantity_b2b                                      Потенциальное количество предприятий
 * @property int $market_volume_b2c                                 Объем рынка b2c
 * @property int $market_volume_b2b                                 Объем рынка b2b
 * @property string $company_products                               Продукция / услуги предприятия
 * @property string $company_partner                                Партнеры предприятия
 * @property string $add_info                                       Дополнительная информация
 */
abstract class FormSegment extends Model
{

    public $name;
    public $project_id;
    public $description;
    public $type_of_interaction_between_subjects;
    public $field_of_activity_b2c;
    public $sort_of_activity_b2c;
    public $field_of_activity_b2b;
    public $sort_of_activity_b2b;
    public $age_from;
    public $age_to;
    public $income_from;
    public $income_to;
    public $income_company_from;
    public $income_company_to;
    public $quantity;
    public $quantity_b2b;
    public $gender_consumer;
    public $education_of_consumer;
    public $market_volume_b2c;
    public $market_volume_b2b;
    public $company_products;
    public $company_partner;
    public $add_info;


    /**
     * Проверка заполнения полей формы
     * @return bool
     */
    abstract public function checkFillingFields (): bool;


    /**
     * Проверка на уникальное имя сегмента
     * @param $attr
     */
    abstract public function uniqueName($attr);


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b'], 'string', 'max' => 255],
            [['name', 'description', 'field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'add_info', 'company_products', 'company_partner'], 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 65],
            ['name', 'uniqueName'],
            [['description', 'company_products', 'company_partner'], 'string', 'max' => 2000],
            [['add_info'], 'string'],
            [['age_from', 'age_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100'],
            [['income_from', 'income_to'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['income_company_from', 'income_company_to'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['quantity', 'quantity_b2b'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['market_volume_b2c', 'market_volume_b2b'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000000'],
            [['project_id', 'gender_consumer', 'education_of_consumer'], 'integer'],
            ['type_of_interaction_between_subjects', 'in', 'range' => [
                Segments::TYPE_B2C,
                Segments::TYPE_B2B,
            ]],
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Тип взаимодействия с потребителями',
            'field_of_activity_b2c' => 'Сфера деятельности потребителя',
            'field_of_activity_b2b' => 'Сфера деятельности предприятия',
            'sort_of_activity_b2c' => 'Вид / специализация деятельности потребителя',
            'sort_of_activity_b2b' => 'Вид / специализация деятельности предприятия',
            'age_from' => 'Возраст потребителя',
            'age_to' => 'Возраст потребителя',
            'income_from' => 'Доход потребителя',
            'income_to' => 'Доход потребителя',
            'income_company_from' => 'Доход предприятия',
            'income_company_to' => 'Доход предприятия',
            'quantity' => 'Потенциальное количество потребителей',
            'quantity_b2b' => 'Потенциальное количество представителей сегмента',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'market_volume_b2c' => 'Объем рынка (млн. руб./год)',
            'market_volume_b2b' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
        ];
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
    public function getFieldOfActivityB2c(): string
    {
        return $this->field_of_activity_b2c;
    }

    /**
     * @param string $field_of_activity_b2c
     */
    public function setFieldOfActivityB2c(string $field_of_activity_b2c): void
    {
        $this->field_of_activity_b2c = $field_of_activity_b2c;
    }

    /**
     * @return string
     */
    public function getFieldOfActivityB2b(): string
    {
        return $this->field_of_activity_b2b;
    }

    /**
     * @param string $field_of_activity_b2b
     */
    public function setFieldOfActivityB2b(string $field_of_activity_b2b): void
    {
        $this->field_of_activity_b2b = $field_of_activity_b2b;
    }

    /**
     * @return string
     */
    public function getSortOfActivityB2c(): string
    {
        return $this->sort_of_activity_b2c;
    }

    /**
     * @param string $sort_of_activity_b2c
     */
    public function setSortOfActivityB2c(string $sort_of_activity_b2c): void
    {
        $this->sort_of_activity_b2c = $sort_of_activity_b2c;
    }

    /**
     * @return string
     */
    public function getSortOfActivityB2b(): string
    {
        return $this->sort_of_activity_b2b;
    }

    /**
     * @param string $sort_of_activity_b2b
     */
    public function setSortOfActivityB2b(string $sort_of_activity_b2b): void
    {
        $this->sort_of_activity_b2b = $sort_of_activity_b2b;
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
    public function getIncomeCompanyFrom(): int
    {
        return $this->income_company_from;
    }

    /**
     * @param int $income_company_from
     */
    public function setIncomeCompanyFrom(int $income_company_from): void
    {
        $this->income_company_from = $income_company_from;
    }

    /**
     * @return int
     */
    public function getIncomeCompanyTo(): int
    {
        return $this->income_company_to;
    }

    /**
     * @param int $income_company_to
     */
    public function setIncomeCompanyTo(int $income_company_to): void
    {
        $this->income_company_to = $income_company_to;
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
    public function getQuantityB2b(): int
    {
        return $this->quantity_b2b;
    }

    /**
     * @param int $quantity_b2b
     */
    public function setQuantityB2b(int $quantity_b2b): void
    {
        $this->quantity_b2b = $quantity_b2b;
    }

    /**
     * @return int
     */
    public function getMarketVolumeB2c(): int
    {
        return $this->market_volume_b2c;
    }

    /**
     * @param int $market_volume_b2c
     */
    public function setMarketVolumeB2c(int $market_volume_b2c): void
    {
        $this->market_volume_b2c = $market_volume_b2c;
    }

    /**
     * @return int
     */
    public function getMarketVolumeB2b(): int
    {
        return $this->market_volume_b2b;
    }

    /**
     * @param int $market_volume_b2b
     */
    public function setMarketVolumeB2b(int $market_volume_b2b): void
    {
        $this->market_volume_b2b = $market_volume_b2b;
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

}