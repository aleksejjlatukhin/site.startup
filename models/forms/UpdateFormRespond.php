<?php


namespace app\models\forms;

use app\models\interfaces\ConfirmationInterface;
use yii\base\Model;

/**
 * Форма редактирования информации о респонденте
 *
 * Class UpdateFormRespond
 * @package app\models\forms
 *
 * @property int $id                                Идентификатор респондента
 * @property string $name                           ФИО респондента
 * @property string $info_respond                   Другая информация о респонденте
 * @property string $place_interview                Место проведения интервью
 * @property string $email                          Эл.почта респондента
 * @property $date_plan                             Плановая дата проведения интервью
 * @property int $confirm_id                        Идентификатор подтверждения гипотезы, к которому отновится респондент
 */
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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getInfoRespond()
    {
        return $this->info_respond;
    }

    /**
     * @param string $info_respond
     */
    public function setInfoRespond($info_respond)
    {
        $this->info_respond = $info_respond;
    }

    /**
     * @return string
     */
    public function getPlaceInterview()
    {
        return $this->place_interview;
    }

    /**
     * @param string $place_interview
     */
    public function setPlaceInterview($place_interview)
    {
        $this->place_interview = $place_interview;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDatePlan()
    {
        return $this->date_plan;
    }

    /**
     * @param mixed $date_plan
     */
    public function setDatePlan($date_plan)
    {
        $this->date_plan = $date_plan;
    }

    /**
     * @return int
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }

    /**
     * @param int $confirm_id
     */
    public function setConfirmId($confirm_id)
    {
        $this->confirm_id = $confirm_id;
    }
}