<?php


namespace app\models\forms;

use app\models\Segment;
use yii\base\Model;

abstract class FormSegment extends Model
{

    public $name;
    public $project_id;
    public $description;
    public $type_of_interaction_between_subjects;
    public $field_of_activity_b2c;
    public $sort_of_activity_b2c;
    public $specialization_of_activity_b2c;
    public $field_of_activity_b2b;
    public $sort_of_activity_b2b;
    public $specialization_of_activity_b2b;
    public $age_from;
    public $age_to;
    public $income_from;
    public $income_to;
    public $income_company_from;
    public $income_company_to;
    public $quantity_from;
    public $quantity_to;
    public $quantity_from_b2b;
    public $quantity_to_b2b;
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
    abstract public function checkFillingFields ();

    /**
     * Проверка на уникальное имя сегмента
     * @param $attr
     */
    abstract public function uniqueName($attr);

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c'], 'safe'],
            [['field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c', 'specialization_of_activity_b2b'], 'string', 'max' => 255],
            [['name', 'description', 'field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c', 'specialization_of_activity_b2b', 'add_info', 'company_products', 'company_partner'], 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 65],
            ['name', 'uniqueName'],
            [['description', 'company_products', 'company_partner'], 'string', 'max' => 2000],
            [['add_info'], 'string'],
            [['age_from', 'age_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100'],
            [['income_from', 'income_to'], 'integer', 'integerOnly' => TRUE, 'min' => '5000', 'max' => '1000000'],
            [['income_company_from', 'income_company_to'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['quantity_from', 'quantity_to', 'quantity_from_b2b', 'quantity_to_b2b'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['market_volume_b2c', 'market_volume_b2b'], 'integer', 'integerOnly' => TRUE, 'min' => '1', 'max' => '1000000'],
            [['project_id', 'gender_consumer', 'education_of_consumer'], 'integer'],
            ['type_of_interaction_between_subjects', 'in', 'range' => [
                Segment::TYPE_B2C,
                Segment::TYPE_B2B,
            ]],
        ];
    }


    public function attributeLabels()
    {
        return [
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
            'field_of_activity_b2c' => 'Сфера деятельности потребителя',
            'field_of_activity_b2b' => 'Сфера деятельности предприятия',
            'sort_of_activity_b2c' => 'Вид деятельности потребителя',
            'sort_of_activity_b2b' => 'Вид деятельности предприятия',
            'specialization_of_activity_b2c' => 'Специализация вида деятельности потребителя',
            'specialization_of_activity_b2b' => 'Специализация вида деятельности предприятия',
            'age_from' => 'Возраст потребителя',
            'age_to' => 'Возраст потребителя',
            'income_from' => 'Доход потребителя',
            'income_to' => 'Доход потребителя',
            'income_company_from' => 'Доход предприятия',
            'income_company_to' => 'Доход предприятия',
            'quantity_from' => 'Потенциальное количество потребителей',
            'quantity_to' => 'Потенциальное количество потребителей',
            'quantity_from_b2b' => 'Потенциальное количество представителей сегмента',
            'quantity_to_b2b' => 'Потенциальное количество представителей сегмента',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'market_volume_b2c' => 'Объем рынка (млн. руб./год)',
            'market_volume_b2b' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
        ];
    }

}