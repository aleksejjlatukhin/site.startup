<?php


namespace app\models\forms;

use app\models\ConfirmProblem;
use app\models\User;
use yii\base\ErrorException;
use yii\base\Model;
use app\models\Projects;
use app\models\Segments;
use app\models\Problems;
use app\models\Gcps;
use yii\web\NotFoundHttpException;

/**
 * Форма создания гипотезы ценностного предложения
 *
 * Class FormCreateGcp
 * @package app\models\forms
 *
 * @property string $good                           Формулировка перспективного продукта (товара / услуги)
 * @property string $benefit                        Какую выгоду дает использование данного продукта потребителю (представителю сегмента)
 * @property string $contrast                       По сравнению с каким продуктом заявлена выгода (с чем сравнивается)
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_problem
 * @property CacheForm $_cacheManager               Менеджер кэширования
 * @property string $cachePath                      Путь к файлу кэша
 */
class FormCreateGcp extends Model
{

    public $good;
    public $benefit;
    public $contrast;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateGcp constructor.
     * @param Problems $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Problems $preliminaryHypothesis, $config = [])
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
     * @param Problems $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Problems $preliminaryHypothesis)
    {
        /**
         * @var Segments $segment
         * @var Projects $project
         * @var User $user
         */
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId(). '/segments/segment-'.$segment->getId()
            .'/problems/problem-'.$preliminaryHypothesis->getId().'/gcps/formCreate/';

        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good', 'benefit', 'contrast'], 'trim'],
            [['good', 'contrast'], 'string', 'max' => 255],
            [['benefit'], 'string', 'max' => 500],
            [['basic_confirm_id'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'good' => 'Формулировка перспективного продукта',
            'benefit' => 'Какую выгоду дает использование данного продукта потребителю',
            'contrast' => 'По сравнению с каким продуктом заявлена выгода (с чем сравнивается)',
        ];
    }


    /**
     * @return Gcps
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create()
    {
        $last_model = Gcps::find()->where(['basic_confirm_id' => $this->getBasicConfirmId()])->orderBy(['id' => SORT_DESC])->one();
        $confirmProblem = ConfirmProblem::findOne($this->getBasicConfirmId());
        $problem = Problems::findOne($confirmProblem->getProblemId());
        $segment = Segments::findOne($problem->getSegmentId());
        $project = Projects::findOne($problem->getProjectId());

        $gcp = new Gcps();
        $gcp->setProjectId($project->getId());
        $gcp->setSegmentId($segment->getId());
        $gcp->setProblemId($problem->getId());
        $gcp->setBasicConfirmId($this->getBasicConfirmId());
        $last_model_number = explode(' ',$last_model->title)[1];
        $gcp->setTitle('ГЦП ' . ($last_model_number + 1));

        $gcp->description = 'Наш продукт ' . mb_strtolower($this->getGood()) . ' ';
        $gcp->description .= 'помогает ' . mb_strtolower($segment->getName()) . ', ';
        $gcp->description .= 'который хочет удовлетворить проблему ' . mb_strtolower($problem->getDescription()) . ', ';
        $gcp->description .= 'избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, ' . mb_strtolower($this->getBenefit()) . ', ';
        $gcp->description .= 'в отличии от ' . mb_strtolower($this->getContrast()) . '.';

        if ($gcp->save()){
            $this->getCacheManager()->deleteCache($this->getCachePathForm()); // Удаление кэша формы создания
            return $gcp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новое ценностное предложение');
    }

    /**
     * @return string
     */
    public function getGood()
    {
        return $this->good;
    }

    /**
     * @param string $good
     */
    public function setGood($good)
    {
        $this->good = $good;
    }

    /**
     * @return string
     */
    public function getBenefit()
    {
        return $this->benefit;
    }

    /**
     * @param string $benefit
     */
    public function setBenefit($benefit)
    {
        $this->benefit = $benefit;
    }

    /**
     * @return string
     */
    public function getContrast()
    {
        return $this->contrast;
    }

    /**
     * @param string $contrast
     */
    public function setContrast($contrast)
    {
        $this->contrast = $contrast;
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