<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\RespondsConfirm;
use yii\web\NotFoundHttpException;

class UpdateRespondConfirmForm extends Model
{
    public $id;
    public $confirm_problem_id;
    public $name;
    public $info_respond;
    public $place_interview;
    public $email;
    public $date_plan;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'info_respond', 'place_interview'], 'required'],
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
     * UpdateRespondConfirmForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $respond = RespondsConfirm::findOne($id);
        $this->id = $id;
        $this->confirm_problem_id = $respond->confirm_problem_id;
        $this->name = $respond->name;
        $this->info_respond = $respond->info_respond;
        $this->email = $respond->email;
        $this->place_interview = $respond->place_interview;
        $this->date_plan = $respond->date_plan;
        parent::__construct($config);
    }


    /**
     * @return RespondsConfirm|null
     * @throws NotFoundHttpException
     */
    public function updateRespond()
    {
        $respond = RespondsConfirm::findOne($this->id);
        $respond->name = $this->name;
        $respond->info_respond = $this->info_respond;
        $respond->place_interview = $this->place_interview;
        $respond->email = $this->email;
        $respond->date_plan = strtotime($this->date_plan);
        if ($respond->save()) return $respond;
        throw new NotFoundHttpException('Ошибка. Неудалось обновить данные респондента');
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsConfirm::findAll(['confirm_problem_id' => $this->confirm_problem_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }

}