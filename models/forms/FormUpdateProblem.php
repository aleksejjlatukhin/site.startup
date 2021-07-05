<?php


namespace app\models\forms;

use app\models\ExpectedResultsInterviewConfirmProblem;
use app\models\Problems;
use yii\base\Model;

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
        $this->id = $problem->id;
        $this->description = $problem->description;
        $this->indicator_positive_passage = $problem->indicator_positive_passage;
        $this->_expectedResultsInterview = ExpectedResultsInterviewConfirmProblem::findAll(['problem_id' => $this->id]);

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
        $model = Problems::findOne($this->id);
        $model->description = $this->description;
        $model->indicator_positive_passage = $this->indicator_positive_passage;

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
                $newExpectedResultsInterview[$k]->question = $q['question'];
                $newExpectedResultsInterview[$k]->answer = $q['answer'];
                $newExpectedResultsInterview[$k]->setProblemId($problemId);
                $newExpectedResultsInterview[$k]->save();
            }
        } else {

            $query = array_values($query);

            if (count($query) > count($expectedResultsInterview)) {

                foreach ($query as $i => $q) {

                    if (($i+1) <= count($expectedResultsInterview)) {
                        $expectedResultsInterview[$i]->question = $q['question'];
                        $expectedResultsInterview[$i]->answer = $q['answer'];
                        $expectedResultsInterview[$i]->save();
                    } else {
                        $expectedResultsInterview[$i] = new ExpectedResultsInterviewConfirmProblem();
                        $expectedResultsInterview[$i]->question = $q['question'];
                        $expectedResultsInterview[$i]->answer = $q['answer'];
                        $expectedResultsInterview[$i]->setProblemId($problemId);
                        $expectedResultsInterview[$i]->save();
                    }
                }

            } else {

                foreach ($query as $i => $q) {
                    $expectedResultsInterview[$i]->question = $q['question'];
                    $expectedResultsInterview[$i]->answer = $q['answer'];
                    $expectedResultsInterview[$i]->save();
                }
            }
        }
    }
}