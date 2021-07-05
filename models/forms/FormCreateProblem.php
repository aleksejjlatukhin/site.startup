<?php


namespace app\models\forms;

use app\models\ConfirmSegment;
use app\models\ExpectedResultsInterviewConfirmProblem;
use app\models\Problems;
use app\models\Segments;
use yii\base\ErrorException;
use yii\base\Model;
use yii\web\NotFoundHttpException;

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
     * @param Segments $preliminaryHypothesis
     * @param array $config
     */
    public function __construct(Segments $preliminaryHypothesis, $config = [])
    {
        $this->_expectedResultsInterview = new ExpectedResultsInterviewConfirmProblem();
        $this->_cacheManager = new CacheForm();
        $this->cachePath = self::getCachePath($preliminaryHypothesis);
        $cacheName = 'formCreateHypothesisCache';
        if ($cache = $this->_cacheManager->getCache($this->cachePath, $cacheName)) {
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
    public static function getCachePath(Segments $preliminaryHypothesis)
    {
        $project = $preliminaryHypothesis->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$preliminaryHypothesis->id.'/problems/formCreate/';
        return $cachePath;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
    public function create()
    {
        $last_model = Problems::find()->where(['basic_confirm_id' => $this->basic_confirm_id])->orderBy(['id' => SORT_DESC])->one();
        $confirmSegment = ConfirmSegment::findOne($this->basic_confirm_id);

        $problem = new Problems();
        $problem->project_id = $confirmSegment->hypothesis->projectId;
        $problem->segment_id = $confirmSegment->segmentId;
        $problem->basic_confirm_id = $this->basic_confirm_id;
        $problem->description = $this->description;
        $problem->indicator_positive_passage = $this->indicator_positive_passage;
        $last_model_number = explode(' ',$last_model->title)[1];
        $problem->title = 'ГПС ' . ($last_model_number + 1);

        $className = explode('\\', self::class)[3];
        $expectedResults = $_POST[$className]['_expectedResultsInterview'];

        if ($problem->save()) {
            $this->saveExpectedResultsInterview($expectedResults, $problem->id);
            $this->_cacheManager->deleteCache($this->cachePath); // Удаление кэша формы создания
            return $problem;
        }
        throw new NotFoundHttpException('Ошибка. Не удалось сохранить новую проблему');
    }


    /**
     * @param $query
     * @param $problemId
     */
    private function saveExpectedResultsInterview($query, $problemId)
    {
        foreach ($query as $k => $q) {
            $newExpectedResultsInterview[$k] = new ExpectedResultsInterviewConfirmProblem();
            $newExpectedResultsInterview[$k]->question = $q['question'];
            $newExpectedResultsInterview[$k]->answer = $q['answer'];
            $newExpectedResultsInterview[$k]->setProblemId($problemId);
            $newExpectedResultsInterview[$k]->save();
        }
    }

}