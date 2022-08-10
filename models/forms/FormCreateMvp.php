<?php


namespace app\models\forms;

use app\models\ConfirmGcp;
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
 * Форма создания mvp-продукта
 *
 * Class FormCreateMvp
 * @package app\models\forms
 *
 * @property string $description                Описание mvp-продукта
 * @property int $basic_confirm_id              Идентификатор записи в таб. confirm_gcp
 * @property CacheForm $_cacheManager           Менеджер кэширования
 * @property string $cachePath                  Путь к файлу кэша
 */
class FormCreateMvp extends Model
{

    public $description;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateMvp constructor.
     * @param Gcps $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Gcps $preliminaryHypothesis, array $config = [])
    {
        $this->setCacheManager();
        $this->setCachePathForm(self::getCachePath($preliminaryHypothesis));
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->getCacheManager()->getCache($this->getCachePathForm(), $cacheName)) {
            $className = explode('\\', self::class)[3];
            foreach ($cache[$className] as $key => $value) {
                $this[$key] = $value;
            }
        }

        parent::__construct($config);
    }


    /**
     * @param Gcps $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Gcps $preliminaryHypothesis): string
    {
        /**
         * @var Problems $problem
         * @var Segments $segment
         * @var Projects $project
         * @var User $user
         */
        $problem = $preliminaryHypothesis->problem;
        $segment = $preliminaryHypothesis->segment;
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        return '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$preliminaryHypothesis->getId().'/mvps/formCreate/';
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
            [['basic_confirm_id'], 'integer'],
        ];
    }


    /**
     * @return Mvps
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create(): Mvps
    {
        /** @var Mvps $last_model */
        $last_model = Mvps::find()->where(['basic_confirm_id' => $this->getBasicConfirmId()])->orderBy(['id' => SORT_DESC])->one();
        $confirmGcp = ConfirmGcp::findOne($this->getBasicConfirmId());
        $gcp = Gcps::findOne($confirmGcp->getGcpId());
        $problem = Problems::findOne($gcp->getProblemId());
        $segment = Segments::findOne($gcp->getSegmentId());
        $project = Projects::findOne($gcp->getProjectId());

        $mvp = new Mvps();
        $mvp->setProjectId($project->getId());
        $mvp->setSegmentId($segment->getId());
        $mvp->setProblemId($problem->getId());
        $mvp->setGcpId($gcp->getId());
        $mvp->setBasicConfirmId($this->getBasicConfirmId());
        $mvp->setDescription($this->getDescription());
        $last_model_number = explode(' ',$last_model->getTitle())[1];
        $mvp->setTitle('MVP ' . ($last_model_number + 1));

        if ($mvp->save()){
            $this->getCacheManager()->deleteCache($this->getCachePathForm()); // Удаление кэша формы создания
            return $mvp;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новый продукт (MVP)');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getBasicConfirmId(): int
    {
        return $this->basic_confirm_id;
    }

    /**
     * @param int $basic_confirm_id
     */
    public function setBasicConfirmId(int $basic_confirm_id): void
    {
        $this->basic_confirm_id = $basic_confirm_id;
    }

    /**
     * @return CacheForm
     */
    public function getCacheManager(): CacheForm
    {
        return $this->_cacheManager;
    }

    /**
     *
     */
    public function setCacheManager(): void
    {
        $this->_cacheManager = new CacheForm();
    }

    /**
     * @return string
     */
    public function getCachePathForm(): string
    {
        return $this->cachePath;
    }

    /**
     * @param string $cachePath
     */
    public function setCachePathForm(string $cachePath): void
    {
        $this->cachePath = $cachePath;
    }

}