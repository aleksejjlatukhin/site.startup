<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class Interview extends ActiveRecord implements ConfirmationInterface
{

    const STAGE = 2;
    const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview';
    }


    /**
     * @return int
     */
    public function getStage()
    {
        return self::STAGE;
    }


    /**
     * Проверка на ограничение кол-ва респондентов
     * @return bool
     */
    public function checkingLimitCountRespond()
    {
        if ($this->count_respond < self::LIMIT_COUNT_RESPOND) return true;
        else return false;
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmSegment::class, ['interview_id' => 'id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Respond::class, ['interview_id' => 'id']);
    }


    /**
     * Получить все проблемы по данному сегменту
     * @return ActiveQuery
     */
    public function getProblems()
    {
        return $this->hasMany(GenerationProblem::class, ['interview_id' => 'id']);
    }


    /**
     * Установить кол-во респондентов
     * @param $count
     */
    public function setCountRespond($count)
    {
        $this->count_respond = $count;
    }


    /**
     * @param $count
     */
    public function setCountPositive($count)
    {
        $this->count_positive = $count;
    }


    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->greeting_interview = $params['greeting_interview'];
        $this->view_interview = $params['view_interview'];
        $this->reason_interview = $params['reason_interview'];
    }


    /**
     * @param $id
     */
    public function setSegmentId($id)
    {
        $this->segment_id = $id;
    }


    /**
     * @return mixed
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['segment_id', 'count_respond', 'count_positive', 'greeting_interview', 'view_interview', 'reason_interview'], 'required'],
            [['segment_id'], 'integer'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => '2000'],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Информация о вас для респондентов',
            'reason_interview' => 'Причина и тема (что побудило) для проведения исследования',
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->segment->project->touch('updated_at');
            $this->segment->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->segment->project->touch('updated_at');
            $this->segment->project->user->touch('updated_at');
        });

        parent::init();
    }


    /**
     * Добавляем вопрос в общую базу,
     * если у данного пользователя его там ещё нет
     * @param $title
     */
    public function addQuestionToGeneralList($title)
    {
        $user = $this->segment->project->user;
        $baseQuestions = AllQuestionsConfirmSegment::find()->where(['user_id' => $user->id])->select('title')->all();
        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){
            $general_question = new AllQuestionsConfirmSegment();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->save();
        }
    }


    /**
     * Создание пустого ответа для нового вопроса для каждого респондента
     * @param $question_id
     */
    public function addAnswerConfirmSegment ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = new AnswersQuestionsConfirmSegment();
            $answer->question_id = $question_id;
            $answer->respond_id = $respond->id;
            $answer->save();
        }
    }


    /**
     * Удаление ответов по данному вопросу
     * у всех респондентов данного подтверждения
     * @param $question_id
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deleteAnswerConfirmSegment ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmSegment::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    /**
     * Список вопросов, который будет показан для добавления нового вопроса
     * @return array
     */
    public function queryQuestionsGeneralList()
    {
        $user = $this->segment->project->user;
        $questions = array(); // Добавляем в массив вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) $questions[] = $question['title'];

        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmSegment::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmSegment::find()
            ->where(['user_id' => $user->id])
            ->orderBy(['id' => SORT_DESC])
            ->select('title')
            ->asArray()
            ->all();


        $qs = array(); // Добавляем в массив вопросы, предлагаемые по-умолчанию на данном этапе
        foreach ($defaultQuestions as $question) $qs[] = $question['title'];
        // Убираем из списка вопросов, которые когда-либо добавлял пользователь на данном этапе
        // вопросы, которые совпадают  с вопросами по-умолчанию
        foreach ($attachQuestions as $key => $queryQuestion) {
            if (in_array($queryQuestion['title'], $qs)) {
                unset($attachQuestions[$key]);
            }
        }

        //Убираем из списка для добавления вопросов, вопросы уже привязанные к данной программе
        $queryQuestions = array_merge($defaultQuestions, $attachQuestions);
        foreach ($queryQuestions as $key => $queryQuestion) {
            if (in_array($queryQuestion['title'], $questions)) {
                unset($queryQuestions[$key]);
            }
        }

        return $queryQuestions;
    }


    /**
     * @return bool
     */
    public function getButtonMovingNextStage()
    {

        $count_descInterview = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $this->id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        $count_positive = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $this->id, 'desc_interview.status' => '1'])->count();

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->problems))) {
            return true;
        }else {
            return false;
        }
    }


    /**
     * @return int|string
     */
    public function getCountRespondsOfModel()
    {
        //Кол-во респондентов, у кот-х заполнены данные
        $count = Respond::find()->where(['interview_id' => $this->id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует интервью
        $count = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $this->id])->andWhere(['not', ['desc_interview.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во представителей сегмента
        $count = Respond::find()->with('descInterview')
            ->leftJoin('desc_interview', '`desc_interview`.`respond_id` = `responds`.`id`')
            ->where(['interview_id' => $this->id, 'desc_interview.status' => '1'])->count();

        return $count;
    }

}
