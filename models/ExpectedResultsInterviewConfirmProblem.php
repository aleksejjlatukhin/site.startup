<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Вопросы для проверки и ответы на них
 * создаются на этапе генерации проблем сегмента
 *
 * Class ExpectedResultsInterviewConfirmProblem
 * @package app\models
 *
 * @property int $id                        Идентификатор записи в таб. expected_results_interview_confirm_problem
 * @property int $problem_id                Идентификатор записи в таб. problems
 * @property string $question               Вопрос
 * @property string $answer                 Ответ
 */
class ExpectedResultsInterviewConfirmProblem extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expected_results_interview_confirm_problem';
    }


    /**
     * @return ActiveQuery
     */
    public function getProblem()
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['problem_id', 'question', 'answer'], 'required'],
            [['problem_id'], 'integer'],
            [['question', 'answer'], 'string', 'max' => 255],
            [['question', 'answer'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'question' => 'Вопрос',
            'answer' => 'Ответ',
        ];
    }


    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->problem->project->touch('updated_at');
            $this->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->problem->project->touch('updated_at');
            $this->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->problem->project->touch('updated_at');
            $this->problem->project->user->touch('updated_at');
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
     * @param int $id
     */
    public function setProblemId($id)
    {
        $this->problem_id = $id;
    }

    /**
     * @return int
     */
    public function getProblemId()
    {
        return $this->problem_id;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

}