<?php


namespace app\models\forms;

use app\models\User;
use app\models\Projects;
use app\models\Segment;
use app\models\TypeOfActivityB2C;
use app\models\TypeOfActivityB2B;

class FormUpdateSegment extends FormSegment
{

    public $id;

    /**
     * FormUpdateSegment constructor.
     * @param $id
     * @param array $config
     */
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


    /**
     * Проверка заполнения полей формы
     * @return bool
     */
    public function checkFillingFields () {

        if ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C) {

            if (!empty($this->name) && !empty($this->description) && !empty($this->field_of_activity_b2c)
                && !empty($this->sort_of_activity_b2c) && !empty($this->specialization_of_activity_b2c)
                && !empty($this->age_from) && !empty($this->age_to) && !empty($this->gender_consumer)
                && !empty($this->education_of_consumer) && !empty($this->income_from) && !empty($this->income_to)
                && !empty($this->quantity_from) && !empty($this->quantity_to) && !empty($this->market_volume_b2c)) {

                return true;
            } else {
                return false;
            }
        } elseif ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B) {

            if (!empty($this->name) && !empty($this->description) && !empty($this->field_of_activity_b2b)
                && !empty($this->sort_of_activity_b2b) && !empty($this->specialization_of_activity_b2b)
                && !empty($this->company_products) && !empty($this->company_partner) && !empty($this->quantity_from_b2b)
                && !empty($this->quantity_to_b2b) && !empty($this->income_company_from) && !empty($this->income_company_to)
                && !empty($this->market_volume_b2b)) {

                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * @return Segment|bool|null
     */
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


    public function uniqueName($attr)
    {
        $models = Segment::findAll(['project_id' => $this->project_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Сегмент с названием «'. $this->name .'» уже существует!');
            }
        }
    }
}