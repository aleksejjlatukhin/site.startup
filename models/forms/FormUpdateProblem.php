<?php


namespace app\models\forms;

use app\models\ExpectedResultsInterviewConfirmProblem;
use app\models\Problems;
use yii\base\Model;

/**
 * Форма обновления гипотезы проблемы
 *
 * Class FormUpdateProblem
 * @package app\models\forms
 *
 * @property int $id                                            Идентификатор из таб. problems
 * @property mixed $_expectedResultsInterview                   Вопросы для проверки и ответы на них (интервью с ожидаемыми результатами)
 * @property string $description                                Описание проблемы
 * @property int $indicator_positive_passage                    Показатель положительного прохождения теста
 */
class FormUpdateProblem extends Model
{

    public $id;
    public $_expectedResultsInterview;
    public $description;
    public $indicator_positive_passage;


    /**
     * FormUpdateProblem constructor.
     * @param Problems $problem
     * @param array $config
     */
    public function __construct(Problems $problem, $config = [])
    {
        $this->setId($problem->getId());
        $this->setDescription($problem->getDescription());
        $this->setIndicatorPositivePassage($problem->getIndicatorPositivePassage());
        $this->setExpectedResultsInterview(ExpectedResultsInterviewConfirmProblem::findAll(['problem_id' => $this->getId()]));

        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicator_positive_passage'], 'integer'],
            [['description'], 'trim'],
            [['description'], 'string', 'max' => 2000],
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
     * Редактирование данных гипотезы проблемы
     * @return bool
     */
    public function update()
    {
        $model = Problems::findOne($this->getId());
        $model->setDescription($this->getDescription());
        $model->setIndicatorPositivePassage($this->getIndicatorPositivePassage());

        $className = explode('\\', self::class)[3];
        $query = $_POST[$className]['_expectedResultsInterview'];

        if ($model->save()) {
            $this->saveExpectedResultsInterview($query, $model->id);
            return true;
        }
        return false;
    }


    /**
     * @param $query
     * @param $problemId
     */
    private function saveExpectedResultsInterview ($query, $problemId)
    {
        $expectedResultsInterview = ExpectedResultsInterviewConfirmProblem::findAll(['problem_id' => $problemId]);

        if (empty($expectedResultsInterview)) {

            foreach ($query as $k => $q) {
                $newExpectedResultsInterview[$k] = new ExpectedResultsInterviewConfirmProblem();
                $newExpectedResultsInterview[$k]->setQuestion($q['question']);
                $newExpectedResultsInterview[$k]->setAnswer($q['answer']);
                $newExpectedResultsInterview[$k]->setProblemId($problemId);
                $newExpectedResultsInterview[$k]->save();
            }
        } else {

            $query = array_values($query);

            if (count($query) > count($expectedResultsInterview)) {

                foreach ($query as $i => $q) {

                    if (($i+1) <= count($expectedResultsInterview)) {
                        $expectedResultsInterview[$i]->setQuestion($q['question']);
                        $expectedResultsInterview[$i]->setAnswer($q['answer']);
                        $expectedResultsInterview[$i]->save();
                    } else {
                        $expectedResultsInterview[$i] = new ExpectedResultsInterviewConfirmProblem();
                        $expectedResultsInterview[$i]->setQuestion($q['question']);
                        $expectedResultsInterview[$i]->setAnswer($q['answer']);
                        $expectedResultsInterview[$i]->setProblemId($problemId);
                        $expectedResultsInterview[$i]->save();
                    }
                }

            } else {

                foreach ($query as $i => $q) {
                    $expectedResultsInterview[$i]->setQuestion($q['question']);
                    $expectedResultsInterview[$i]->setAnswer($q['answer']);
                    $expectedResultsInterview[$i]->save();
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getExpectedResultsInterview()
    {
        return $this->_expectedResultsInterview;
    }

    /**
     * @param mixed $expectedResultsInterview
     */
    public function setExpectedResultsInterview($expectedResultsInterview)
    {
        $this->_expectedResultsInterview = $expectedResultsInterview;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getIndicatorPositivePassage()
    {
        return $this->indicator_positive_passage;
    }

    /**
     * @param int $indicator_positive_passage
     */
    public function setIndicatorPositivePassage($indicator_positive_passage)
    {
        $this->indicator_positive_passage = $indicator_positive_passage;
    }
}