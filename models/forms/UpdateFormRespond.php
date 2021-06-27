<?php


namespace app\models\forms;

use app\models\interfaces\ConfirmationInterface;
use yii\base\Model;

abstract class UpdateFormRespond extends Model
{

    public $id;
    public $name;
    public $info_respond;
    public $place_interview;
    public $email;
    public $date_plan;
    public $confirm_id;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'info_respond', 'place_interview', 'date_plan'], 'required'],
            [['name', 'info_respond', 'place_interview', 'email'], 'trim'],
            [['date_plan'], 'safe'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'place_interview', 'email'], 'string', 'max' => 255],
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
            'email' => 'E-mail',
            'date_plan' => 'Плановая дата интервью',
            'place_interview' => 'Место проведения интервью',
        ];
    }


    /**
     * @return ConfirmationInterface
     */
    abstract public function getConfirm();

    abstract public function update();

    abstract public function uniqueName($attr);
}