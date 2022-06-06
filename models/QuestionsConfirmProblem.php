<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * Класс хранит в бд вопросы интервью на этапе подтверждения гипотезы проблемы
 *
 * Class QuestionsConfirmProblem
 * @package app\models
 *
 * @property int $id                            Идентификатор записи в таб. questions_confirm_problem
 * @property int $confirm_id                    Идентификатор записи в таб. confirm_problem
 * @property string $title                      Описание вопроса
 * @property int $status                        Параметр указывает на важность вопроса
 * @property int $created_at                    Дата создания вопроса
 * @property int $updated_at                    Дата обновления вопроса
 */
class QuestionsConfirmProblem extends ActiveRecord
{

    private $_manager_answers;
    private $_creator_question_to_general_list;


    /**
     * QuestionsConfirmProblem constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_manager_answers = new ManagerForAnswersAtQuestion();
        $this->_creator_question_to_general_list = new CreatorQuestionToGeneralList();

        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions_confirm_problem';
    }


    /**
     * Получить объект подтверждения
     *
     * @return ActiveQuery
     */
    public function getConfirm ()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_id']);
    }


    /**
     * Найти подтверждение гипотезы,
     * к которому относится вопрос
     *
     * @return ConfirmProblem|null
     */
    public function findConfirm()
    {
        return ConfirmProblem::findOne($this->getConfirmId());
    }


    /**
     * Получить все ответы на данный вопрос
     *
     * @return array|ActiveRecord[]
     */
    public function getAnswers()
    {
        $answers = AnswersQuestionsConfirmProblem::find()->where(['question_id' => $this->getId()])
            ->andWhere(['not', ['answers_questions_confirm_problem.answer' => '']])->all();
        return $answers;
    }


    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->setConfirmId($params['confirm_id']);
        $this->setTitle($params['title']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_id', 'title'], 'required'],
            [['confirm_id', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => QuestionStatus::STATUS_NOT_STAR],
            ['status', 'in', 'range' => [
                QuestionStatus::STATUS_NOT_STAR,
                QuestionStatus::STATUS_ONE_STAR
            ]],
            [['title'], 'string', 'max' => 255],
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
            $this->_manager_answers->create($this->confirm, $this->getId());
            $this->_creator_question_to_general_list->create($this->confirm, $this->getTitle());
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
            $this->_creator_question_to_general_list->create($this->confirm, $this->getTitle());
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
            $this->_manager_answers->delete($this->confirm, $this->getId());
        });

        parent::init();
    }


    /**
     * @return array|bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function deleteAndGetData()
    {
        // Получить список вопросов без удаленного вопроса
        $questions = self::find()->where(['confirm_id' => $this->confirm->getId()])->andWhere(['!=', 'id', $this->getId()])->all();
        //Передаем обновленный список вопросов для добавления в программу
        $queryQuestions = $this->confirm->queryQuestionsGeneralList();
        array_push($queryQuestions, $this);

        if ($this->delete()) {
            return ['questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return false;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }

    /**
     * @param int $confirm_id
     */
    public function setConfirmId($confirm_id)
    {
        $this->confirm_id = $confirm_id;
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
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param int $status
     */
    private function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Изменение статуса вопроса
     */
    public function changeStatus()
    {
        if ($this->getStatus() === QuestionStatus::STATUS_NOT_STAR){
            $this->setStatus(QuestionStatus::STATUS_ONE_STAR);
        } else {
            $this->setStatus(QuestionStatus::STATUS_NOT_STAR);
        }
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}