<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class QuestionsConfirmProblem extends ActiveRecord
{

    public $list_questions;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_problem';
    }


    /**
     * Получить объект подтверждения
     * @return ActiveQuery
     */
    public function getConfirm ()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }


    /**
     * Получить все ответы на данный вопрос
     * @return array|ActiveRecord[]
     */
    public function getAnswers()
    {
        $answers = AnswersQuestionsConfirmProblem::find()->where(['question_id' => $this->id])
            ->andWhere(['not', ['answers_questions_confirm_problem.answer' => '']])->all();
        return $answers;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_problem_id', 'title'], 'required'],
            [['confirm_problem_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'list_questions'], 'string', 'max' => 255],
            [['title'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ['title' => 'Описание вопроса'];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        parent::init();
    }
}