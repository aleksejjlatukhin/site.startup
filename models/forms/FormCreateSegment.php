<?php


namespace app\models\forms;

use app\models\Projects;
use app\models\Segments;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;


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
            $segment->name = $this->name;
            $segment->description = $this->description;
            $segment->project_id = $this->project_id;
            $segment->type_of_interaction_between_subjects = $this->type_of_interaction_between_subjects;
            $segment->add_info = $this->add_info;

            if ($this->type_of_interaction_between_subjects == Segments::TYPE_B2C){

                $segment->field_of_activity = $this->field_of_activity_b2c;
                $segment->sort_of_activity = $this->sort_of_activity_b2c;
                $segment->age_from = $this->age_from;
                $segment->age_to = $this->age_to;
                $segment->gender_consumer = $this->gender_consumer;
                $segment->education_of_consumer = $this->education_of_consumer;
                $segment->income_from = $this->income_from;
                $segment->income_to = $this->income_to;
                $segment->quantity_from = $this->quantity_from;
                $segment->quantity_to = $this->quantity_to;
                $segment->market_volume = $this->market_volume_b2c;

                if ($segment->save()) {
                    $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
                    return $segment;
                }
                throw new NotFoundHttpException('Ошибка. Неудалось сохранить сегмент');

            }elseif ($this->type_of_interaction_between_subjects == Segments::TYPE_B2B) {

                $segment->field_of_activity = $this->field_of_activity_b2b;
                $segment->sort_of_activity = $this->sort_of_activity_b2b;
                $segment->company_products = $this->company_products;
                $segment->quantity_from = $this->quantity_from_b2b;
                $segment->quantity_to = $this->quantity_to_b2b;
                $segment->company_partner = $this->company_partner;
                $segment->income_from = $this->income_company_from;
                $segment->income_to = $this->income_company_to;
                $segment->market_volume = $this->market_volume_b2b;

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
        $models = Segments::findAll(['project_id' => $this->project_id]);

        foreach ($models as $item){

            if (mb_strtolower(str_replace(' ', '', $this->name)) == mb_strtolower(str_replace(' ', '',$item->name))){

                $this->addError($attr, 'Сегмент с названием «'. $this->name .'» уже существует!');
            }
        }
    }
}