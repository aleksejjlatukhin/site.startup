<?php


namespace app\models\forms;

use app\models\QuestionsConfirmGcp;
use app\models\QuestionsConfirmMvp;
use app\models\QuestionsConfirmProblem;
use app\models\QuestionsConfirmSegment;
use yii\base\Model;

/**
 * Форма редактирования вопроса для интервью на этапе подтверждения гипотезы
 *
 * Class FormUpdateQuestion
 * @package app\models\forms
 *
 * @property int $id                        Идентификатор вопроса
 * @property string $title                  Описание вопроса
 * @property QuestionsConfirmSegment|QuestionsConfirmProblem|QuestionsConfirmGcp|QuestionsConfirmMvp $_question
 */
class FormUpdateQuestion extends Model
{

    public $id;
    public $title;
    private $_question;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title'], 'required'],
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Описание вопроса',
        ];
    }


    /**
     * FormUpdateQuestion constructor.
     *
     * @param QuestionsConfirmSegment|QuestionsConfirmProblem|QuestionsConfirmGcp|QuestionsConfirmMvp $model
     * @param array $config
     */
    public function __construct($model, $config = [])
    {
        $this->setQuestion($model);
        $this->setId($model->getId());
        $this->setTitle($model->getTitle());
        parent::__construct($config);
    }


    /**
     * @return mixed
     */
    public function getConfirm()
    {
        return $this->getQuestion()->findConfirm();
    }


    /**
     * @return array|null
     */
    public function update()
    {
        $model = $this->getQuestion();
        $model->setTitle($this->getTitle());
        if ($model->save()) {
            $confirm = $model->findConfirm();
            $questions = $confirm->findQuestions();
            $queryQuestions = $confirm->queryQuestionsGeneralList();

            return ['model' => $model, 'questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return null;
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

    /**
     * @return QuestionsConfirmGcp|QuestionsConfirmMvp|QuestionsConfirmProblem|QuestionsConfirmSegment
     */
    public function getQuestion()
    {
        return $this->_question;
    }

    /**
     * @param QuestionsConfirmGcp|QuestionsConfirmMvp|QuestionsConfirmProblem|QuestionsConfirmSegment $question
     */
    public function setQuestion($question)
    {
        $this->_question = $question;
    }

}