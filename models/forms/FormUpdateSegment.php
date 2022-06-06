<?php


namespace app\models\forms;

use app\models\Segments;
use yii\web\NotFoundHttpException;

/**
 * Форма редактирования сегмента
 *
 * Class FormUpdateSegment
 * @package app\models\forms
 *
 * @property int $id            Идентификатор сегмента
 */
class FormUpdateSegment extends FormSegment
{

    public $id;


    /**
     * FormUpdateSegment constructor.
     * @param int $id
     * @param array $config
     */
    public function __construct($id, $config = [])
    {
        $model = Segments::findOne($id);
        $this->setId($id);
        $this->setProjectId($model->getProjectId());
        $this->setName($model->getName());
        $this->setDescription($model->getDescription());
        $this->setAddInfo($model->getAddInfo());
        $this->setTypeOfInteractionBetweenSubjects($model->getTypeOfInteractionBetweenSubjects());

        if ($model->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2C){

            $this->setFieldOfActivityB2c($model->getFieldOfActivity());
            $this->setSortOfActivityB2c($model->getSortOfActivity());
            $this->setAgeFrom($model->getAgeFrom());
            $this->setAgeTo($model->getAgeTo());
            $this->setGenderConsumer($model->getGenderConsumer());
            $this->setEducationOfConsumer($model->getEducationOfConsumer());
            $this->setIncomeFrom($model->getIncomeFrom());
            $this->setIncomeTo($model->getIncomeTo());
            $this->setQuantityFrom($model->getQuantityFrom());
            $this->setQuantityTo($model->getQuantityTo());
            $this->setMarketVolumeB2c($model->getMarketVolume());

        }elseif ($model->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2B) {

            $this->setFieldOfActivityB2b($model->getFieldOfActivity());
            $this->setSortOfActivityB2b($model->getSortOfActivity());
            $this->setCompanyProducts($model->getCompanyProducts());
            $this->setQuantityFromB2b($model->getQuantityFrom());
            $this->setQuantityToB2b($model->getQuantityTo());
            $this->setCompanyPartner($model->getCompanyPartner());
            $this->setIncomeCompanyFrom($model->getIncomeFrom());
            $this->setIncomeCompanyTo($model->getIncomeTo());
            $this->setMarketVolumeB2b($model->getMarketVolume());
        }

        parent::__construct($config);
    }


    /**
     * Проверка заполнения полей формы
     * @return bool
     */
    public function checkFillingFields () {

        if ($this->type_of_interaction_between_subjects == Segments::TYPE_B2C) {

            if (!empty($this->name) && !empty($this->description) && !empty($this->field_of_activity_b2c)
                && !empty($this->sort_of_activity_b2c) && !empty($this->age_from) && !empty($this->age_to)
                && !empty($this->gender_consumer) && !empty($this->education_of_consumer) && !empty($this->income_from)
                && !empty($this->income_to) && !empty($this->quantity_from) && !empty($this->quantity_to)
                && !empty($this->market_volume_b2c)) {

                return true;
            } else {
                return false;
            }
        } elseif ($this->type_of_interaction_between_subjects == Segments::TYPE_B2B) {

            if (!empty($this->name) && !empty($this->description) && !empty($this->field_of_activity_b2b)
                && !empty($this->sort_of_activity_b2b) && !empty($this->company_products) && !empty($this->company_partner)
                && !empty($this->quantity_from_b2b) && !empty($this->quantity_to_b2b) && !empty($this->income_company_from)
                && !empty($this->income_company_to) && !empty($this->market_volume_b2b)) {

                return true;
            } else {
                return false;
            }
        }
        return false;
    }


    /**
     * @return Segments|null
     * @throws NotFoundHttpException
     */
    public function update()
    {
        $segment = Segments::findOne($this->getId());
        $segment->setName($this->getName());
        $segment->setDescription($this->getDescription());
        $segment->setAddInfo($this->getAddInfo());

        if ($segment->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2C){

            $segment->setFieldOfActivity($this->getFieldOfActivityB2c());
            $segment->setSortOfActivity($this->getSortOfActivityB2c());
            $segment->setAgeFrom($this->getAgeFrom());
            $segment->setAgeTo($this->getAgeTo());
            $segment->setGenderConsumer($this->getGenderConsumer());
            $segment->setEducationOfConsumer($this->getEducationOfConsumer());
            $segment->setIncomeFrom($this->getIncomeFrom());
            $segment->setIncomeTo($this->getIncomeTo());
            $segment->setQuantityFrom($this->getQuantityFrom());
            $segment->setQuantityTo($this->getQuantityTo());
            $segment->setMarketVolume($this->getMarketVolumeB2c());

            if ($segment->save()) return $segment;
            throw new NotFoundHttpException('Неудалось сохранить сегмент');

        }elseif ($segment->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2B) {

            $segment->setFieldOfActivity($this->getFieldOfActivityB2b());
            $segment->setSortOfActivity($this->getSortOfActivityB2b());
            $segment->setCompanyProducts($this->getCompanyProducts());
            $segment->setQuantityFrom($this->getQuantityFromB2b());
            $segment->setQuantityTo($this->getQuantityToB2b());
            $segment->setCompanyPartner($this->getCompanyPartner());
            $segment->setIncomeFrom($this->getIncomeCompanyFrom());
            $segment->setIncomeTo($this->getIncomeCompanyTo());
            $segment->setMarketVolume($this->getMarketVolumeB2b());

            if ($segment->save()) return $segment;
            throw new NotFoundHttpException('Неудалось сохранить сегмент');
        }
        return null;
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = Segments::findAll(['project_id' => $this->getProjectId()]);

        foreach ($models as $item){

            if ($this->getId() != $item->getId() && mb_strtolower(str_replace(' ', '', $this->getName())) == mb_strtolower(str_replace(' ', '',$item->getName()))){

                $this->addError($attr, 'Сегмент с названием «'. $this->getName() .'» уже существует!');
            }
        }
    }

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
}