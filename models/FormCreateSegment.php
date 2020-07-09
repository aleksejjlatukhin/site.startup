<?php


namespace app\models;

use yii\base\Model;


class FormCreateSegment extends Model
{
    public $name;
    public $project_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'project_id'], 'required'],
            ['name', 'trim'],
            ['name', 'string', 'min' => 6, 'max' => 48],
            ['name', 'uniqueName'],
            [['project_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование сегмента',
        ];
    }

    public function create()
    {
        if ($this->validate()){

            $segment = new Segment();
            $segment->name = $this->name;
            $segment->project_id = $this->project_id;
            return $segment->save() ? $segment : null;
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