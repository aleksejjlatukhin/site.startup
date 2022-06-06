<?php


namespace app\models\forms;

use app\models\QuestionsConfirmGcp;
use app\models\QuestionsConfirmMvp;
use app\models\QuestionsConfirmProblem;
use app\models\QuestionsConfirmSegment;
use yii\base\Model;

/**
 * Форма для создания вопроса для интервью на этапе подтверждения гипотезы
 *
 * Class FormCreateQuestion
 * @package app\models\forms
 *
 * @property string $list_questions                 Поле для выбора нового вопроса из уже созданных ранее вопросов
 * @property int $confirm_id                        Идентификатор подтверждения гипотезы
 * @property string $title                          Описание вопроса
 */
class FormCreateQuestion extends Model
{

    public $list_questions;
    public $confirm_id;
    public $title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_id', 'title'], 'required'],
            [['confirm_id'], 'integer'],
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
     * @param QuestionsConfirmSegment|QuestionsConfirmProblem|QuestionsConfirmGcp|QuestionsConfirmMvp $model
     * @return array|null
     */
    public function create($model)
    {
        $model->setParams(['confirm_id' => $this->getConfirmId(), 'title' => $this->getTitle()]);
        if ($model->save()){
            $confirm = $model->findConfirm();
            $questions = $confirm->findQuestions();
            $queryQuestions = $confirm->queryQuestionsGeneralList();

            return ['model' => $model, 'questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getListQuestions()
    {
        return $this->list_questions;
    }

    /**
     * @param string $list_questions
     */
    public function setListQuestions($list_questions)
    {
        $this->list_questions = $list_questions;
    }

    /**
     * @return int
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }

    /**
     * @param int $id
     */
    public function setConfirmId($id)
    {
        $this->confirm_id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}