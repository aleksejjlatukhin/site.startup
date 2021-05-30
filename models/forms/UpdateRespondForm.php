<?php


namespace app\models\forms;

use app\models\interfaces\ConfirmationInterface;
use app\models\Interview;
use app\models\Respond;
use yii\web\NotFoundHttpException;

class UpdateRespondForm extends UpdateFormRespond
{

    public $interview_id;


    /**
     * UpdateRespondForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $respond = Respond::findOne($id);
        foreach ($respond as $key => $value) {
            $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * Получить модель подтверждения
     * @return ConfirmationInterface|Interview|null
     */
    public function getConfirm()
    {
        return Interview::findOne($this->interview_id);
    }


    /**
     * @return Respond|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        $respond = Respond::findOne($this->id);
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
        $models = Respond::findAll(['interview_id' => $this->interview_id]);

        foreach ($models as $item){

            if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->name .'» уже существует!');
            }
        }
    }

}