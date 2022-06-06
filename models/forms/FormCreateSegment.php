<?php


namespace app\models\forms;

use app\models\Projects;
use app\models\Segments;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

/**
 * Форма создания сегмента
 *
 * Class FormCreateSegment
 * @package app\models\forms
 */
class FormCreateSegment extends FormSegment
{

    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateSegment constructor.
     * @param Projects $project
     * @param array $config
     */
    public function __construct(Projects $project, $config = [])
    {
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($project);
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * @param Projects $project
     * @return string
     */
    public static function getCachePath(Projects $project)
    {
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id. '/projects/project-'.$project->id.'/segments/formCreate/';

        return $cachePath;
    }


    /**
     * Проверка заполнения полей формы
     * @return bool
     */
    public function checkFillingFields ()
    {
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
     * @return Segments|bool
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        if ($this->validate()){

            $segment = new Segments();
            $segment->setName($this->getName());
            $segment->setDescription($this->getDescription());
            $segment->setProjectId($this->getProjectId());
            $segment->setTypeOfInteractionBetweenSubjects($this->getTypeOfInteractionBetweenSubjects());
            $segment->setAddInfo($this->getAddInfo());

            if ($this->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2C){

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

                if ($segment->save()) {
                    $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
                    return $segment;
                }
                throw new NotFoundHttpException('Ошибка. Неудалось сохранить сегмент');

            }elseif ($this->getTypeOfInteractionBetweenSubjects() == Segments::TYPE_B2B) {

                $segment->setFieldOfActivity($this->getFieldOfActivityB2b());
                $segment->setSortOfActivity($this->getSortOfActivityB2b());
                $segment->setCompanyProducts($this->getCompanyProducts());
                $segment->setQuantityFrom($this->getQuantityFromB2b());
                $segment->setQuantityTo($this->getQuantityToB2b());
                $segment->setCompanyPartner($this->getCompanyPartner());
                $segment->setIncomeFrom($this->getIncomeCompanyFrom());
                $segment->setIncomeTo($this->getIncomeCompanyTo());
                $segment->setMarketVolume($this->getMarketVolumeB2b());

                if ($segment->save()) {
                    $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
                    return $segment;
                }
                throw new NotFoundHttpException('Неудалось сохранить сегмент');
            }

        }

        return false;
    }


    /**
     * @param $attr
     */
    public function uniqueName($attr)
    {
        $models = Segments::findAll(['project_id' => $this->getProjectId()]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->getName())) == mb_strtolower(str_replace(' ', '',$item->getName()))){

                $this->addError($attr, 'Сегмент с названием «'. $this->getName() .'» уже существует!');
            }
        }
    }
}