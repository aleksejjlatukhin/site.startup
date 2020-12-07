<?php


namespace app\models\forms;

use app\models\Respond;
use yii\base\Model;

class CreateRespondForm extends Model
{
    public $name;
    public $interview_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, имя, отчество',
        ];
    }


    public function create ()
    {
        $model = new Respond();
        $model->interview_id = $this->interview_id;
        $model->name = $this->name;
        return $model->save() ? $model : null;
    }

    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = Respond::findAll(['interview_id' => $this->interview_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}