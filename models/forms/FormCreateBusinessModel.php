<?php


namespace app\models\forms;

use app\models\BusinessModel;
use app\models\ConfirmMvp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\Projects;
use app\models\Segments;
use app\models\User;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Форма создания бизнес-модели
 *
 * Class FormCreateBusinessModel
 * @package app\models\forms
 *
 * @property string $relations                      Взаимоотношения с клиентами
 * @property string $partners                       Ключевые партнеры
 * @property string $distribution_of_sales          Каналы коммуникации и сбыта
 * @property string $resources                      Ключевые ресурсы
 * @property string $cost                           Структура издержек
 * @property string $revenue                        Потоки поступления доходов
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_mvp
 * @property CacheForm $_cacheManager               Менеджер кэширования
 * @property string $cachePath                      Путь к файлу кэша
 */
class FormCreateBusinessModel extends Model
{

    public $partners;
    public $resources;
    public $relations;
    public $distribution_of_sales;
    public $cost;
    public $revenue;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateBusinessModel constructor.
     * @param Mvps $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Mvps $preliminaryHypothesis, $config = [])
    {
        $this->setCacheManager();
        $this->setCachePathForm(self::getCachePath($preliminaryHypothesis));
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->getCacheManager()->getCache($this->getCachePathForm(), $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) $this[$key] = $value;
        }

        parent::__construct($config);
    }


    /**
     * @param Mvps $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Mvps $preliminaryHypothesis)
    {
        /**
         * @var Gcps $gcp
         * @var Problems $problem
         * @var Segments $segment
         * @var Projects $project
         * @var User $user
         */
        $gcp = $preliminaryHypothesis->gcp;
        $problem = $preliminaryHypothesis->problem;
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/mvps/mvp-'.$preliminaryHypothesis->getId().'/business-model/formCreate/';

        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['relations', 'distribution_of_sales', 'resources'], 'string', 'max' => 255],
            [['partners', 'cost', 'revenue'], 'string', 'max' => 1000],
            [['relations', 'partners', 'distribution_of_sales', 'resources', 'cost', 'revenue'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'relations' => 'Взаимоотношения с клиентами',
            'partners' => 'Ключевые партнеры',
            'distribution_of_sales' => 'Каналы коммуникации и сбыта',
            'resources' => 'Ключевые ресурсы',
            'cost' => 'Структура издержек',
            'revenue' => 'Потоки поступления доходов',
        ];
    }


    /**
     * @return BusinessModel
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create (){

        $confirmMvp = ConfirmMvp::findOne($this->getBasicConfirmId());
        $mvp = Mvps::findOne($confirmMvp->getMvpId());
        $gcp = Gcps::findOne($mvp->getGcpId());
        $problem = Problems::findOne($mvp->getProblemId());
        $segment = Segments::findOne($mvp->getSegmentId());
        $project = Projects::findOne($mvp->getProjectId());

        $model = new BusinessModel();
        $model->setBasicConfirmId($this->getBasicConfirmId());
        $model->setMvpId($mvp->getId());
        $model->setGcpId($gcp->getId());
        $model->setProblemId($problem->getId());
        $model->setSegmentId($segment->getId());
        $model->setProjectId($project->getId());
        $model->setRelations($this->getRelations());
        $model->setPartners($this->getPartners());
        $model->setDistributionOfSales($this->getDistributionOfSales());
        $model->setResources($this->getResources());
        $model->setCost($this->getCost());
        $model->setRevenue($this->getRevenue());

        if ($model->save()){
            $this->getCacheManager()->deleteCache($this->getCachePathForm()); // Удаление кэша формы создания
            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить бизнес-модель');
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
     * @return CacheForm
     */
    public function getCacheManager()
    {
        return $this->_cacheManager;
    }

    /**
     *
     */
    public function setCacheManager()
    {
        $this->_cacheManager = new CacheForm();
    }

    /**
     * @return string
     */
    public function getCachePathForm()
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     */
    public function setCachePathForm($cachePath)
    {
        $this->cachePath = $cachePath;
    }
}