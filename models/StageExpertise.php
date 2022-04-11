<?php


namespace app\models;


/**
 * Класс, который хранит значения
 * этапов экспертизы по проекту
 *
 * Class StageExpertise
 * @package app\models
 */
class StageExpertise
{

    const PROJECT = 0;
    const SEGMENT = 1;
    const CONFIRM_SEGMENT = 2;
    const PROBLEM = 3;
    const CONFIRM_PROBLEM = 4;
    const GCP = 5;
    const CONFIRM_GCP = 6;
    const MVP = 7;
    const CONFIRM_MVP = 8;
    const BUSINESS_MODEL = 9;

    /**
     * @var array
     */
    private static $list = [
        self::PROJECT => 'project',
        self::SEGMENT => 'segment',
        self::CONFIRM_SEGMENT => 'confirm_segment',
        self::PROBLEM => 'problem',
        self::CONFIRM_PROBLEM => 'confirm_problem',
        self::GCP => 'gcp',
        self::CONFIRM_GCP => 'confirm_gcp',
        self::MVP => 'mvp',
        self::CONFIRM_MVP => 'confirm_mvp',
        self::BUSINESS_MODEL => 'business_model'
    ];

    /**
     * @var array
     */
    private static $listClasses = [
        'project' => Projects::class,
        'segment' => Segments::class,
        'confirm_segment' => ConfirmSegment::class,
        'problem' => Problems::class,
        'confirm_problem' => ConfirmProblem::class,
        'gcp' => Gcps::class,
        'confirm_gcp' => ConfirmGcp::class,
        'mvp' => Mvps::class,
        'confirm_mvp' => ConfirmMvp::class,
        'business_model' => BusinessModel::class
    ];

    /**
     * @return array
     */
    public static function getList()
    {
        return self::$list;
    }

    /**
     * @param int|string $value
     * @return false|int|string
     */
    public static function getKey($value)
    {
        return array_search($value, self::$list);
    }

    /**
     * @return array
     */
    public static function getListClasses()
    {
        return self::$listClasses;
    }

    /**
     * Получить класс объекта,
     * по которому проводится экспертиза,
     * по параметру stage из url
     *
     * @param $stage string
     * @return mixed
     */
    public static function getClassByStage($stage)
    {
        return self::getListClasses()[$stage];
    }


    /**
     * Массив с названиями этапов проекта
     *
     * @return array
     */
    private static $listTitle = [
        'project' => 'описание проекта',
        'segment' => 'генерация гипотезы целевого сегмента',
        'confirm_segment' => 'подтверждение гипотезы целевого сегмента',
        'problem' => 'генерация гипотезы проблемы сегмента',
        'confirm_problem' => 'подтверждение гипотезы проблемы сегмента',
        'gcp' => 'разработка гипотезы ценностного предложения',
        'confirm_gcp' => 'подтверждение гипотезы ценностного предложения',
        'mvp' => 'разработка MVP',
        'confirm_mvp' => 'подтверждение MVP',
        'business_model' => 'генерация бизнес-модели'
    ];


    /**
     * Получить название этапа экспертизы
     *
     * @param string $stage
     * @param int $stageId
     * @return string
     */
    public static function getTitle($stage, $stageId)
    {
        $title = '';
        $class = self::getClassByStage($stage);
        $obj = $class::findOne($stageId);

        if ($obj instanceof Projects) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->project_name . '»';
        } elseif ($obj instanceof Segments) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->name . '»';
        } elseif ($obj instanceof ConfirmSegment) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->segment->name . '»';
        } elseif ($obj instanceof Problems) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->title . '»';
        } elseif ($obj instanceof ConfirmProblem) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->problem->title . '»';
        } elseif ($obj instanceof Gcps) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->title . '»';
        } elseif ($obj instanceof ConfirmGcp) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->gcp->title . '»';
        } elseif ($obj instanceof Mvps) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->title . '»';
        } elseif ($obj instanceof ConfirmMvp) {
            $title = self::$listTitle[$stage] . '</br> «' . $obj->mvp->title . '»';
        } elseif ($obj instanceof BusinessModel) {
            $title = self::$listTitle[$stage] . '</br> для «' . $obj->mvp->title . '»';
        }

        return $title;
    }

}