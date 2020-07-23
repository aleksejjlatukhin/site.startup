<?php


namespace app\models;

use app\models\TypeOfActivityB2C;
use app\models\TypeOfActivityB2B;
use yii\base\Model;



class FormCreateSegment extends Model
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
    public $main_problems_consumer;
    public $company_products;
    public $company_partner;
    public $add_info;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c'], 'integer'],
            [['description', 'specialization_of_activity_b2b', 'main_problems_consumer', 'company_products', 'company_partner'], 'string', 'max' => 255],
            [['name', 'description', 'field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c', 'specialization_of_activity_b2b', 'add_info', 'main_problems_consumer', 'company_products', 'company_partner'], 'trim'],
            ['name', 'string', 'min' => 6, 'max' => 48],
            ['name', 'uniqueName'],
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
            'main_problems_consumer' => 'Основные проблемы потребителя',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
        ];
    }

    public function create()
    {
        if ($this->validate()){

            $segment = new Segment();
            $segment->name = $this->name;
            $segment->description = $this->description;
            $segment->project_id = $this->project_id;
            $segment->type_of_interaction_between_subjects = $this->type_of_interaction_between_subjects;
            $segment->add_info = $this->add_info;

            if ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C){

                $field_of_activity = TypeOfActivityB2C::findOne($this->field_of_activity_b2c);
                $segment->field_of_activity = $field_of_activity->name;

                $sort_of_activity = TypeOfActivityB2C::findOne($this->sort_of_activity_b2c);
                $segment->sort_of_activity = $sort_of_activity->name;

                $specialization_of_activity = TypeOfActivityB2C::findOne($this->specialization_of_activity_b2c);
                $segment->specialization_of_activity = $specialization_of_activity->name;

                $segment->age_from = $this->age_from;
                $segment->age_to = $this->age_to;

                if ($this->gender_consumer == false) {
                    $segment->gender_consumer = Segment::GENDER_MAN;
                } else {
                    $segment->gender_consumer = Segment::GENDER_WOMAN;
                }

                $segment->education_of_consumer = $this->education_of_consumer;

                $segment->income_from = $this->income_from;
                $segment->income_to = $this->income_to;

                $segment->quantity_from = $this->quantity_from;
                $segment->quantity_to = $this->quantity_to;

                $segment->market_volume = $this->market_volume_b2c;
                $segment->main_problems_consumer = $this->main_problems_consumer;

                $segment->createRoadmap();

                return $segment->save() ? $segment : null;

            }elseif ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B) {

                $field_of_activity = TypeOfActivityB2B::findOne($this->field_of_activity_b2b);
                $segment->field_of_activity = $field_of_activity->name;

                $sort_of_activity = TypeOfActivityB2B::findOne($this->sort_of_activity_b2b);
                $segment->sort_of_activity = $sort_of_activity->name;

                $segment->specialization_of_activity = $this->specialization_of_activity_b2b;

                $segment->company_products = $this->company_products;

                $segment->quantity_from = $this->quantity_from_b2b;
                $segment->quantity_to = $this->quantity_to_b2b;

                $segment->company_partner = $this->company_partner;

                $segment->income_from = $this->income_company_from;
                $segment->income_to = $this->income_company_to;

                $segment->market_volume = $this->market_volume_b2b;

                $segment->createRoadmap();

                return $segment->save() ? $segment : null;
            }

        }

        return false;
    }

    public function uniqueName($attr)
    {
        $models = Segment::findAll(['project_id' => $this->project_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Сегмент с названием «'. $this->name .'» уже существует!');
            }
        }
    }
}