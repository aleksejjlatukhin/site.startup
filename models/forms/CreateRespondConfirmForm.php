<?php


namespace app\models\forms;

use app\models\RespondsConfirm;
use yii\base\Model;

class CreateRespondConfirmForm extends Model
{
    public $name;
    public $confirm_problem_id;

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
        $model = new RespondsConfirm();
        $model->confirm_problem_id = $this->confirm_problem_id;
        $model->name = $this->name;
        return $model->save() ? $model : null;
    }

    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsConfirm::findAll(['confirm_problem_id' => $this->confirm_problem_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}