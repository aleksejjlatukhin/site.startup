<?php


namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class QuestionsConfirmMvp extends ActiveRecord
{

    private $_manager_answers;
    private $_creator_question_to_general_list;


    /**
     * QuestionsConfirmMvp constructor.
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
        return 'questions_confirm_mvp';
    }


    /**
     * Получить объект подтверждения
     * @return ActiveQuery
     */
    public function getConfirm ()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_id']);
    }


    /**
     * Получить все ответы на данный вопрос
     * @return array|ActiveRecord[]
     */
    public function getAnswers()
    {
        $answers = AnswersQuestionsConfirmMvp::find()->where(['question_id' => $this->id])
            ->andWhere(['not', ['answers_questions_confirm_mvp.answer' => '']])->all();
        return $answers;
    }


    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->confirm_id = $params['confirm_id'];
        $this->title = $params['title'];
    }


    /**
     * Изменение статуса вопроса
     */
    public function changeStatus()
    {
        if ($this->status === QuestionStatus::STATUS_NOT_STAR){
            $this->status = QuestionStatus::STATUS_ONE_STAR;
        } else {
            $this->status = QuestionStatus::STATUS_NOT_STAR;
        }
    }


    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
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
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
            $this->_manager_answers->create($this->confirm, $this->id);
            $this->_creator_question_to_general_list->create($this->confirm, $this->title);
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
            $this->_creator_question_to_general_list->create($this->confirm, $this->title);
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
            $this->_manager_answers->delete($this->confirm, $this->id);
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
        $questions = self::find()->where(['confirm_id' => $this->confirm->id])->andWhere(['!=', 'id', $this->id])->all();
        //Передаем обновленный список вопросов для добавления в программу
        $queryQuestions = $this->confirm->queryQuestionsGeneralList();
        array_push($queryQuestions, $this);

        if ($this->delete()) {
            return ['questions' => $questions, 'queryQuestions' => $queryQuestions];
        }
        return false;
    }

}