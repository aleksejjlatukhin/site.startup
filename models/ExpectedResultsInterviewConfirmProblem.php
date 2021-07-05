<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Вопросы для проверки и ответы на них
 * создаются на этапе генерации проблем сегмента
 * Class ExpectedResultsInterviewConfirmProblem
 * @package app\models
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
     * @param $id
     */
    public function setProblemId($id)
    {
        $this->problem_id = $id;
    }


    /**
     * @return mixed
     */
    public function getProblemId()
    {
        return $this->problem_id;
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

}