<?php


namespace app\models\forms;

use yii\base\Model;
use app\models\RespondsMvp;
use yii\web\NotFoundHttpException;

class UpdateRespondMvpForm extends Model
{

    public $id;
    public $confirm_mvp_id;
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
            [['name', 'info_respond', 'email', 'place_interview'], 'trim'],
            [['date_plan'], 'safe'],
            [['name'], 'uniqueName'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'email', 'place_interview'], 'string', 'max' => 255],
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
     * UpdateRespondMvpForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $respond = RespondsMvp::findOne($id);
        $this->id = $id;
        $this->confirm_mvp_id = $respond->confirm_mvp_id;
        $this->name = $respond->name;
        $this->info_respond = $respond->info_respond;
        $this->email = $respond->email;
        $this->place_interview = $respond->place_interview;
        $this->date_plan = $respond->date_plan;
        parent::__construct($config);
    }

    /**
     * @return RespondsMvp|null
     * @throws NotFoundHttpException
     */
    public function updateRespond()
    {
        $respond = RespondsMvp::findOne($this->id);
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
        $models = RespondsMvp::findAll(['confirm_mvp_id' => $this->confirm_mvp_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }

}