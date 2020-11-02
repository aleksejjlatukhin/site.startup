<?php


namespace app\models;

use yii\base\Model;

class UpdateRespondMvpForm extends Model
{

    public $id;
    public $confirm_mvp_id;
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
            [['name', 'info_respond', 'email'], 'trim'],
            [['name', 'info_respond', 'email'], 'string', 'max' => 255],
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
        $respond = RespondsMvp::findOne($id);
        $this->id = $id;
        $this->confirm_mvp_id = $respond->confirm_mvp_id;
        $this->name = $respond->name;
        $this->info_respond = $respond->info_respond;
        $this->email = $respond->email;
        parent::__construct($config);
    }


    public function updateRespond($respond)
    {
        $respond->name = $this->name;
        $respond->info_respond = $this->info_respond;
        $respond->email = $this->email;
        return $respond->save() ? $respond : null;
    }
}