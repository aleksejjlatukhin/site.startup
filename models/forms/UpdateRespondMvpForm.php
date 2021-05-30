<?php


namespace app\models\forms;

use app\models\ConfirmMvp;
use app\models\interfaces\ConfirmationInterface;
use app\models\RespondsMvp;
use yii\web\NotFoundHttpException;

class UpdateRespondMvpForm extends UpdateFormRespond
{

    public $confirm_mvp_id;


    /**
     * UpdateRespondMvpForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $respond = RespondsMvp::findOne($id);
        foreach ($respond as $key => $value) {
            $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * Получить модель подтверждения
     * @return ConfirmMvp|ConfirmationInterface|null
     */
    public function getConfirm()
    {
        return ConfirmMvp::findOne($this->confirm_mvp_id);
    }


    /**
     * @return RespondsMvp|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        $respond = RespondsMvp::findOne($this->id);
        $respond->setName($this->name);
        $respond->setParams(['info_respond' => $this->info_respond, 'place_interview' => $this->place_interview, 'email' => $this->email]);
        $respond->setDatePlan(strtotime($this->date_plan));
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