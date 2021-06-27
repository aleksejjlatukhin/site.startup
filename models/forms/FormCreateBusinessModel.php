<?php


namespace app\models\forms;

use app\models\BusinessModel;
use app\models\ConfirmMvp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Mvps;
use app\models\Projects;
use app\models\Segments;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

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
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($preliminaryHypothesis);
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
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
        $gcp = $preliminaryHypothesis->gcp;
        $problem = $preliminaryHypothesis->problem;
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$preliminaryHypothesis->id.'/business-model/formCreate/';

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

        $confirmMvp = ConfirmMvp::findOne($this->basic_confirm_id);
        $mvp = Mvps::findOne($confirmMvp->mvpId);
        $gcp = Gcps::findOne($mvp->gcpId);
        $problem = Problems::findOne($mvp->problem_id);
        $segment = Segments::findOne($mvp->segment_id);
        $project = Projects::findOne($mvp->project_id);

        $model = new BusinessModel();
        $model->basic_confirm_id = $this->basic_confirm_id;
        $model->mvp_id = $mvp->id;
        $model->gcp_id = $gcp->id;
        $model->problem_id = $problem->id;
        $model->segment_id = $segment->id;
        $model->project_id = $project->id;
        $model->relations = $this->relations;
        $model->partners = $this->partners;
        $model->distribution_of_sales = $this->distribution_of_sales;
        $model->resources = $this->resources;
        $model->cost = $this->cost;
        $model->revenue = $this->revenue;

        if ($model->save()){
            $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
            return $model;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить бизнес-модель');
    }
}