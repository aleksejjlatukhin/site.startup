<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит объекты бизнес-моделей в бд
 *
 * Class BusinessModel
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. business_model
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_mvp
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $project_id                        Идентификатор записи в таб. projects
 * @property int $problem_id                        Идентификатор записи в таб. problems
 * @property int $gcp_id                            Идентификатор записи в таб. gcps
 * @property int $mvp_id                            Идентификатор записи в таб. mvps
 * @property string $relations                      Взаимоотношения с клиентами
 * @property string $partners                       Ключевые партнеры
 * @property string $distribution_of_sales          Каналы коммуникации и сбыта
 * @property string $resources                      Ключевые ресурсы
 * @property string $cost                           Структура издержек
 * @property string $revenue                        Потоки поступления доходов
 * @property int $created_at                        Дата создания mvp-продукта
 * @property int $updated_at                        Дата обновления mvp-продукта
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class BusinessModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_model';
    }


    /**
     * Получить объект проекта
     * @return ActiveQuery
     */
    public function getProject ()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить объект сегмента
     * @return ActiveQuery
     */
    public function getSegment ()
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
    }


    /**
     * Получить объект проблемы
     * @return ActiveQuery
     */
    public function getProblem ()
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * Получить объект Gcps
     * @return ActiveQuery
     */
    public function getGcp ()
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * Получить объект Mvps
     * @return ActiveQuery
     */
    public function getMvp ()
    {
        return $this->hasOne(Mvps::class, ['id' => 'mvp_id']);
    }


    /**
     * Найти Mvp, к которому относится бизнес-модель
     *
     * @return Mvps|null
     */
    public function findMvp()
    {
        return Mvps::findOne($this->getMvpId());
    }


    /**
     * Получить объект подтверждения Mvps
     * @return ActiveQuery
     */
    public function getConfirmMvp()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'basic_confirm_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['basic_confirm_id', 'relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'required'],
            [['basic_confirm_id', 'project_id', 'segment_id', 'problem_id', 'gcp_id', 'mvp_id', 'created_at', 'updated_at'], 'integer'],
            [['relations', 'distribution_of_sales', 'resources'], 'string', 'max' => 255],
            [['partners', 'cost', 'revenue'], 'string', 'max' => 1000],
            [['relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'trim'],
            ['enable_expertise', 'default', 'value' => EnableExpertise::OFF],
            ['enable_expertise', 'in', 'range' => [
                EnableExpertise::OFF,
                EnableExpertise::ON,
            ]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'confirm_mvp_id' => 'Confirm Mvps ID',
            'relations' => 'Взаимоотношения с клиентами',
            'partners' => 'Ключевые партнеры',
            'distribution_of_sales' => 'Каналы коммуникации и сбыта',
            'resources' => 'Ключевые ресурсы',
            'cost' => 'Структура издержек',
            'revenue' => 'Потоки поступления доходов',
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        parent::init();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getBasicConfirmId()
    {
        return $this->basic_confirm_id;
    }

    /**
     * @param int $basic_confirm_id
     */
    public function setBasicConfirmId($basic_confirm_id)
    {
        $this->basic_confirm_id = $basic_confirm_id;
    }

    /**
     * @return int
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }

    /**
     * @param int $segment_id
     */
    public function setSegmentId($segment_id)
    {
        $this->segment_id = $segment_id;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return int
     */
    public function getProblemId()
    {
        return $this->problem_id;
    }

    /**
     * @param int $problem_id
     */
    public function setProblemId($problem_id)
    {
        $this->problem_id = $problem_id;
    }

    /**
     * @return int
     */
    public function getGcpId()
    {
        return $this->gcp_id;
    }

    /**
     * @param int $gcp_id
     */
    public function setGcpId($gcp_id)
    {
        $this->gcp_id = $gcp_id;
    }

    /**
     * @return int
     */
    public function getMvpId()
    {
        return $this->mvp_id;
    }

    /**
     * @param int $mvp_id
     */
    public function setMvpId($mvp_id)
    {
        $this->mvp_id = $mvp_id;
    }

    /**
     * @return string
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param string $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * @return string
     */
    public function getPartners()
    {
        return $this->partners;
    }

    /**
     * @param string $partners
     */
    public function setPartners($partners)
    {
        $this->partners = $partners;
    }

    /**
     * @return string
     */
    public function getDistributionOfSales()
    {
        return $this->distribution_of_sales;
    }

    /**
     * @param string $distribution_of_sales
     */
    public function setDistributionOfSales($distribution_of_sales)
    {
        $this->distribution_of_sales = $distribution_of_sales;
    }

    /**
     * @return string
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param string $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param string $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return string
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * @param string $revenue
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return mixed
     */
    public function getConfirmMvpId()
    {
        return $this->basic_confirm_id;
    }

    /**
     * @return int
     */
    public function getEnableExpertise()
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise()
    {
        $this->enable_expertise = EnableExpertise::ON;
    }
}
