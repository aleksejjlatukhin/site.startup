<?php


namespace app\models\forms;

use app\models\RespondsGcp;
use yii\base\Model;

class CreateRespondGcpForm extends Model
{

    public $name;
    public $confirm_gcp_id;

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
        $model = new RespondsGcp();
        $model->confirm_gcp_id = $this->confirm_gcp_id;
        $model->name = $this->name;
        return $model->save() ? $model : null;
    }

    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsGcp::findAll(['confirm_gcp_id' => $this->confirm_gcp_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}