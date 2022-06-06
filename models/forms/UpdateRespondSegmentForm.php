<?php


namespace app\models\forms;

use app\models\interfaces\ConfirmationInterface;
use app\models\ConfirmSegment;
use app\models\RespondsSegment;
use yii\web\NotFoundHttpException;

/**
 * Форма редактирования информации о респонденте
 * на этапе подтверждения гипотезы целевого сегмента
 *
 * Class UpdateRespondSegmentForm
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
class UpdateRespondSegmentForm extends UpdateFormRespond
{


    /**
     * UpdateRespondForm constructor.
     * @param $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $respond = RespondsSegment::findOne($id);
        foreach ($respond as $key => $value) {
            $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * Получить модель подтверждения
     * @return ConfirmationInterface|ConfirmSegment|null
     */
    public function getConfirm()
    {
        return ConfirmSegment::findOne($this->getConfirmId());
    }


    /**
     * @return RespondsSegment|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        $respond = RespondsSegment::findOne($this->getId());
        $respond->setName($this->getName());
        $respond->setParams([
            'info_respond' => $this->getInfoRespond(),
            'place_interview' => $this->getPlaceInterview(),
            'email' => $this->getEmail()
        ]);
        $respond->setDatePlan(strtotime($this->getDatePlan()));
        if ($respond->save())
            return $respond;

        throw new NotFoundHttpException('Ошибка. Неудалось обновить данные респондента');
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = RespondsSegment::findAll(['confirm_id' => $this->getConfirmId()]);

        foreach ($models as $item){

            if ($this->getId() != $item->getId() && mb_strtolower(str_replace(' ', '', $this->getName())) == mb_strtolower(str_replace(' ', '',$item->getName()))){

                $this->addError($attr, 'Респондент с таким именем «'. $this->getName() .'» уже существует!');
            }
        }
    }

}