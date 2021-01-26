<?php


namespace app\models\forms;

use app\models\User;
use app\models\Projects;
use app\models\Segment;
use app\models\TypeOfActivityB2C;
use app\models\TypeOfActivityB2B;


class FormCreateSegment extends FormSegment
{

    /**
     * Проверка заполнения полей формы
     * @return bool
     */
    public function checkFillingFields ()
    {
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

                $segment->gender_consumer = $this->gender_consumer;
                $segment->education_of_consumer = $this->education_of_consumer;

                $segment->income_from = $this->income_from;
                $segment->income_to = $this->income_to;

                $segment->quantity_from = $this->quantity_from;
                $segment->quantity_to = $this->quantity_to;

                $segment->market_volume = $this->market_volume_b2c;

                $this->createDirName();
                $this->deleteFileFormCreation();

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

                $this->createDirName();
                $this->deleteFileFormCreation();

                return $segment->save() ? $segment : null;
            }

        }

        return false;
    }


    public function createDirName()
    {
        $project = Projects::findOne(['id' => $this->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $segments_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/';

        $segment_dir = $segments_dir . '/' . mb_convert_encoding($this->translit($this->name) , "windows-1251") . '/';
        $segment_dir = mb_strtolower($segment_dir, "windows-1251");

        if (!file_exists($segment_dir)){
            mkdir($segment_dir, 0777);
        }
    }


    public function deleteFileFormCreation()
    {
        $project = Projects::findOne(['id' => $this->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        $file = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251") . '/' .
            mb_convert_encoding($this->translit($project->project_name) , "windows-1251") . '/segments/__segment__form__creation__file/form-creation-file.txt';

        if (file_exists($file)) {
            unlink($file);
        }
    }


    /**
     * @param $attr
     */
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