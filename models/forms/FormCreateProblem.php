<?php


namespace app\models\forms;

use app\models\ConfirmSegment;
use app\models\ExpectedResultsInterviewConfirmProblem;
use app\models\Problems;
use app\models\Projects;
use app\models\Segments;
use app\models\User;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Форма создания гипотезы проблемы
 *
 * Class FormCreateProblem
 * @package app\models\forms
 *
 * @property ExpectedResultsInterviewConfirmProblem $_expectedResultsInterview              Вопросы для проверки и ответы на них (интервью с ожидаемыми результатами)
 * @property string $description                                                            Описание проблемы
 * @property int $indicator_positive_passage                                                Показатель положительного прохождения теста
 * @property int $basic_confirm_id                                                          Идентификатор записи в таб. confirm_segment
 * @property CacheForm $_cacheManager                                                       Менеджер кэширования
 * @property string $cachePath                                                              Путь к файлу кэша
 */
class FormCreateProblem extends Model
{

    public $_expectedResultsInterview;
    public $description;
    public $indicator_positive_passage;
    public $basic_confirm_id;
    public $_cacheManager;
    public $cachePath;


    /**
     * FormCreateProblem constructor.
     *
     * @param Segments $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Segments $preliminaryHypothesis, array $config = [])
    {
        $this->setExpectedResultsInterview();
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
     * Получить путь к кэшу формы
     * @param Segments $preliminaryHypothesis
     * @return string
     */
    public static function getCachePath(Segments $preliminaryHypothesis): string
    {
        /**
         * @var Projects $project
         * @var User $user
         */
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        return '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$preliminaryHypothesis->getId().'/problems/formCreate/';
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
            [['basic_confirm_id', 'indicator_positive_passage'], 'integer'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'description' => 'Описание гипотезы проблемы сегмента',
            'indicator_positive_passage' => 'Показатель положительного прохождения теста',
        ];
    }


    /**
     * @return Problems
     * @throws NotFoundHttpException
     * @throws ErrorException
     */
    public function create(): Problems
    {
        /**
         * @var Problems $last_model
         */
        $last_model = Problems::find()->where(['basic_confirm_id' => $this->getBasicConfirmId()])->orderBy(['id' => SORT_DESC])->one();
        $confirmSegment = ConfirmSegment::findOne($this->getBasicConfirmId());

        $problem = new Problems();
        $problem->setProjectId($confirmSegment->hypothesis->getProjectId());
        $problem->setSegmentId($confirmSegment->getSegmentId());
        $problem->setBasicConfirmId($this->getBasicConfirmId());
        $problem->setDescription($this->getDescription());
        $problem->setIndicatorPositivePassage($this->getIndicatorPositivePassage());
        $last_model_number = $last_model ? explode(' ',$last_model->getTitle())[1] : 0;
        $problem->setTitle('ГПС ' . ($last_model_number + 1));

        $className = explode('\\', self::class)[3];
        $expectedResults = $_POST[$className]['_expectedResultsInterview'];

        if ($problem->save()) {
            $this->saveExpectedResultsInterview($expectedResults, $problem->getId());
            $this->getCacheManager()->deleteCache($this->getCachePathForm()); // Удаление кэша формы создания
            return $problem;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новую проблему');
    }


    /**
     * @param $query
     * @param int $problemId
     */
    private function saveExpectedResultsInterview($query, int $problemId): void
    {
        foreach ($query as $k => $q) {
            $newExpectedResultsInterview[$k] = new ExpectedResultsInterviewConfirmProblem();
            $newExpectedResultsInterview[$k]->setQuestion($q['question']);
            $newExpectedResultsInterview[$k]->setAnswer($q['answer']);
            $newExpectedResultsInterview[$k]->setProblemId($problemId);
            $newExpectedResultsInterview[$k]->save();
        }
    }

    /**
     * @return ExpectedResultsInterviewConfirmProblem
     */
    public function getExpectedResultsInterview(): ExpectedResultsInterviewConfirmProblem
    {
        return $this->_expectedResultsInterview;
    }

    /**
     *
     */
    public function setExpectedResultsInterview(): void
    {
        $this->_expectedResultsInterview = new ExpectedResultsInterviewConfirmProblem();
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
    public function getIndicatorPositivePassage(): int
    {
        return $this->indicator_positive_passage;
    }

    /**
     * @param int $indicator_positive_passage
     */
    public function setIndicatorPositivePassage(int $indicator_positive_passage): void
    {
        $this->indicator_positive_passage = $indicator_positive_passage;
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