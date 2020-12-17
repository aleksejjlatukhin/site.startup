<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\RespondsGcp;

class UpdateRespondGcpForm extends Model
{

    public $id;
    public $confirm_gcp_id;
    public $name;
    public $info_respond;
    public $email;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'info_respond'], 'required'],
            [['name'], 'uniqueName'],
            [['name', 'info_respond', 'email'], 'trim'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'Адрес электронной почты',
        ];
    }


    public function __construct($id, $config = [])
    {
        $respond = RespondsGcp::findOne($id);
        $this->id = $id;
        $this->confirm_gcp_id = $respond->confirm_gcp_id;
        $this->name = $respond->name;
        $this->info_respond = $respond->info_respond;
        $this->email = $respond->email;
        parent::__construct($config);
    }


    public function updateRespond()
    {
        $respond = RespondsGcp::findOne($this->id);
        $respond->name = $this->name;
        $respond->info_respond = $this->info_respond;
        $respond->email = $this->email;
        return $respond->save() ? $respond : null;
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsGcp::findAll(['confirm_gcp_id' => $this->confirm_gcp_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }
}