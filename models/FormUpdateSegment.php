<?php


namespace app\models;

use yii\base\Model;

class FormUpdateSegment extends Model
{
    public $id;
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c'], 'safe'],
            [['description', 'specialization_of_activity_b2b', 'company_products', 'company_partner'], 'string', 'max' => 255],
            [['name', 'description', 'field_of_activity_b2c', 'field_of_activity_b2b', 'sort_of_activity_b2c', 'sort_of_activity_b2b', 'specialization_of_activity_b2c', 'specialization_of_activity_b2b', 'add_info', 'company_products', 'company_partner'], 'trim'],
            ['name', 'string', 'min' => 6, 'max' => 65],
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

    public function __construct($id, $config = [])
    {
        $model = Segment::findOne($id);
        $this->id = $id;
        $this->project_id = $model->project_id;
        $this->name = $model->name;
        $this->description = $model->description;
        $this->add_info = $model->add_info;
        $this->type_of_interaction_between_subjects = $model->type_of_interaction_between_subjects;

        if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C){

            $field_of_activity = TypeOfActivityB2C::findOne(['name' => $model->field_of_activity]);
            $this->field_of_activity_b2c = $field_of_activity->id;
            $this->sort_of_activity_b2c = $model->sort_of_activity;
            $this->specialization_of_activity_b2c = $model->specialization_of_activity;
            $this->age_from = $model->age_from;
            $this->age_to = $model->age_to;
            $this->gender_consumer = $model->gender_consumer;
            $this->education_of_consumer = $model->education_of_consumer;
            $this->income_from = $model->income_from;
            $this->income_to = $model->income_to;
            $this->quantity_from = $model->quantity_from;
            $this->quantity_to = $model->quantity_to;
            $this->market_volume_b2c = $model->market_volume;

        }elseif ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B) {

            $field_of_activity = TypeOfActivityB2B::findOne(['name' => $model->field_of_activity]);
            $this->field_of_activity_b2b = $field_of_activity->id;
            $this->sort_of_activity_b2b = $model->sort_of_activity;
            $this->specialization_of_activity_b2b = $model->specialization_of_activity;

            $this->company_products = $model->company_products;
            $this->quantity_from_b2b = $model->quantity_from;
            $this->quantity_to_b2b = $model->quantity_to;
            $this->company_partner = $model->company_partner;
            $this->income_company_from = $model->income_from;
            $this->income_company_to = $model->income_to;
            $this->market_volume_b2b = $model->market_volume;
        }

        parent::__construct($config);
    }


    public function update()
    {
        if ($this->validate()) {

            $segment = Segment::findOne($this->id);
            $segment->name = $this->name;
            $segment->description = $this->description;
            $segment->add_info = $this->add_info;

            if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C){

                $segment->age_from = $this->age_from;
                $segment->age_to = $this->age_to;

                $segment->gender_consumer = $this->gender_consumer;
                $segment->education_of_consumer = $this->education_of_consumer;

                $segment->income_from = $this->income_from;
                $segment->income_to = $this->income_to;

                $segment->quantity_from = $this->quantity_from;
                $segment->quantity_to = $this->quantity_to;

                $segment->market_volume = $this->market_volume_b2c;

                $this->updateDirName();

                return $segment->save() ? $segment : null;

            }elseif ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B) {

                $segment->company_products = $this->company_products;

                $segment->quantity_from = $this->quantity_from_b2b;
                $segment->quantity_to = $this->quantity_to_b2b;

                $segment->company_partner = $this->company_partner;

                $segment->income_from = $this->income_company_from;
                $segment->income_to = $this->income_company_to;

                $segment->market_volume = $this->market_volume_b2b;

                $this->updateDirName();

                return $segment->save() ? $segment : null;
            }
        }
        return false;
    }


    public function updateDirName()
    {
        $models = Segment::findAll(['project_id' => $this->project_id]);
        $project = Projects::findOne(['id' => $this->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        foreach ($models as $item){

            if ($this->id == $item->id && mb_strtolower($this->name) !== mb_strtolower($item->name)){

                $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                    mb_convert_encoding($this->translit($item->name) , "windows-1251") . '/';

                $old_dir = mb_strtolower($old_dir, "windows-1251");

                $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($project->project_name), "windows-1251") . '/segments/' .
                    mb_convert_encoding($this->translit($this->name) , "windows-1251") . '/';

                $new_dir = mb_strtolower($new_dir, "windows-1251");

                rename($old_dir, $new_dir);
            }
        }
    }


    public function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат

    }


    public function uniqueName($attr)
    {
        $models = Segment::findAll(['project_id' => $this->project_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name)) && $this->id != $item->id){

                $this->addError($attr, 'Сегмент с названием «'. $this->name .'» уже существует!');
            }
        }
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