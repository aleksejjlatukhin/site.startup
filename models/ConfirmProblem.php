<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class ConfirmProblem extends ActiveRecord implements ConfirmationInterface
{

    const STAGE = 4;
    const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_problem';
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
     * Получить объект текущей проблемы
     * @return ActiveQuery
     */
    public function getProblem()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'gps_id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsConfirm::class, ['confirm_problem_id' => 'id']);
    }

    public function getGcps()
    {
        return $this->hasMany(Gcp::class, ['confirm_problem_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmProblem::class, ['confirm_problem_id' => 'id']);
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
     * Установить id проблемы
     * @param $id
     * @return mixed
     */
    public function setProblemId($id)
    {
        return $this->gps_id = $id;
    }


    /**
     * Установить количество респондентов
     * @param $count
     * @return mixed
     */
    public function setCountPositive($count)
    {
        return $this->count_positive = $count;
    }


    /**
     * Уствновить потребность потребителя
     * @param $needConsumer
     * @return mixed
     */
    public function setNeedConsumer($needConsumer)
    {
        return $this->need_consumer = $needConsumer;
    }


    /**
     * @return mixed
     */
    public function getProblemId()
    {
        return $this->gps_id;
    }


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'gps_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gps_id', 'count_respond', 'count_positive', 'need_consumer'], 'required'],
            [['gps_id'], 'integer'],
            ['need_consumer', 'trim'],
            ['need_consumer', 'string', 'max' => 255],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
            'need_consumer' => 'Потребность потребителя',
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

        parent::init();
    }


    /**
     * Добавляем вопрос в общую базу,
     * если у данного пользователя его там ещё нет
     * @param $title
     */
    public function addQuestionToGeneralList($title)
    {
        $user = $this->problem->project->user;
        $baseQuestions = AllQuestionsConfirmProblem::find()->where(['user_id' => $user->id])->select('title')->all();
        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){
            $general_question = new AllQuestionsConfirmProblem();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->save();
        }
    }


    /**
     * Создание пустого ответа для нового вопроса для каждого респондента
     * @param $question_id
     */
    public function addAnswerConfirmProblem ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = new AnswersQuestionsConfirmProblem();
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
    public function deleteAnswerConfirmProblem ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmProblem::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    /**
     * Список вопросов, который будет показан для добавления нового вопроса
     * @return array
     */
    public function queryQuestionsGeneralList()
    {
        $user = $this->problem->project->user;
        $questions = []; //Добавляем в массив вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) $questions[] = $question['title'];

        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmProblem::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmProblem::find()
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

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id, 'desc_interview_confirm.status' => '1'])->count();

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->gcps))) {
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
        $count = RespondsConfirm::find()->where(['confirm_problem_id' => $this->id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        //Кол-во респондентов, кот-е подтвердили проблему
        $count = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id, 'desc_interview_confirm.status' => '1'])->count();

        return $count;
    }

}
