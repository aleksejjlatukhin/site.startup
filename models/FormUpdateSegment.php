<?php


namespace app\models;

use yii\base\Model;

class FormUpdateSegment extends Model
{
    public $id;
    public $project_id;
    public $name;
    public $field_of_activity;
    public $sort_of_activity;
    public $add_info;
    public $age_from;
    public $age_to;
    public $income_from;
    public $income_to;
    public $quantity_from;
    public $quantity_to;
    public $market_volume_from;
    public $market_volume_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'field_of_activity', 'sort_of_activity',], 'required'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info'], 'trim'],
            [['project_id'], 'integer'],
            [['age_from', 'age_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100'],
            ['age_from', 'uniqueAge'],
            [['income_from', 'income_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '10000'],
            ['income_from', 'uniqueIncome'],
            [['quantity_from', 'quantity_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '1000000'],
            ['quantity_from', 'uniqueQuantity'],
            [['market_volume_from', 'market_volume_to'], 'integer', 'integerOnly' => TRUE, 'min' => '0', 'max' => '100000'],
            ['market_volume_from', 'uniqueMarketVolume'],
            [['field_of_activity', 'sort_of_activity', 'add_info'], 'string'],
            [['name',], 'string', 'min' => 6, 'max' => 48],
            [['name'], 'uniqueName'],
        ];
    }

    public function __construct($id, $config = [])
    {
        $model = Segment::findOne($id);
        $this->id = $id;
        $this->project_id = $model->project_id;
        $this->name = $model->name;
        $this->field_of_activity = $model->field_of_activity;
        $this->sort_of_activity = $model->sort_of_activity;
        $this->add_info = $model->add_info;
        $this->age_from = $model->age_from;
        $this->age_to = $model->age_to;
        $this->income_from = $model->income_from;
        $this->income_to = $model->income_to;
        $this->quantity_from = $model->quantity_from;
        $this->quantity_to = $model->quantity_to;
        $this->market_volume_from = $model->market_volume_from;
        $this->market_volume_to = $model->market_volume_to;

        parent::__construct($config);
    }


    public function update()
    {
        if ($this->validate()) {

            $model = Segment::findOne($this->id);
            $model->name = $this->name;
            $model->field_of_activity = $this->field_of_activity;
            $model->sort_of_activity = $this->sort_of_activity;
            $model->add_info = $this->add_info;
            $model->age_from = $this->age_from;
            $model->age_to = $this->age_to;
            $model->income_from = $this->income_from;
            $model->income_to = $this->income_to;
            $model->quantity_from = $this->quantity_from;
            $model->quantity_to = $this->quantity_to;
            $model->market_volume_from = $this->market_volume_from;
            $model->market_volume_to = $this->market_volume_to;
            $this->updateDirName();

            if (empty($model->creat_date)) {

                $model->creat_date = date('Y:m:d');
                $model->plan_gps = date('Y:m:d', (time() + 3600*24*30));
                $model->plan_ps = date('Y:m:d', (time() + 3600*24*60));
                $model->plan_dev_gcp = date('Y:m:d', (time() + 3600*24*90));
                $model->plan_gcp = date('Y:m:d', (time() + 3600*24*120));
                $model->plan_dev_gmvp = date('Y:m:d', (time() + 3600*24*150));
                $model->plan_gmvp = date('Y:m:d', (time() + 3600*24*180));
            }

            return $model->save() ? $model : null;
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


    public function uniqueAge($attr)
    {
        if ($this->age_from == $this->age_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueIncome($attr)
    {
        if ($this->income_from == $this->income_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueQuantity($attr)
    {
        if ($this->quantity_from == $this->quantity_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    public function uniqueMarketVolume($attr)
    {
        if ($this->market_volume_from == $this->market_volume_to){
            $this->addError($attr, 'Значения не должны совпадать.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Наименование сегмента',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Род деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity_from' => 'Потенциальное количество потребителей (тыс. чел.)',
            'market_volume_from' => 'Объем рынка (млн. руб./год)',
            'add_info' => 'Дополнительная информация',
        ];
    }
}