<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит подтверждения проблем сегментов в бд
 *
 * Class ConfirmProblem
 * @package app\models
 *
 * @property int $id                                    Идентификатор записи в таб. confirm_problem
 * @property int $problem_id                            Идентификатор записи в таб. problems
 * @property int $count_respond                         Количество респондентов
 * @property int $count_positive                        Количество респондентов, подтверждающих проблему
 * @property string $need_consumer                      Потребность потребителя
 * @property int $enable_expertise                      Параметр разрешения на экспертизу по даному этапу
 */
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
     *
     * @return bool
     */
    public function checkingLimitCountRespond()
    {
        if ($this->getCountRespond() < self::LIMIT_COUNT_RESPOND) return true;
        else return false;
    }


    /**
     * Получить объект текущей проблемы
     *
     * @return ActiveQuery
     */
    public function getProblem()
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * Найти проблему, к которому отновится подтверждение
     *
     * @return Problems|null
     */
    public function findProblem()
    {
        return Problems::findOne($this->getProblemId());
    }

    /**
     * Получить респондентов привязанных к подтверждению
     *
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsProblem::class, ['confirm_id' => 'id']);
    }

    public function getGcps()
    {
        return $this->hasMany(Gcps::class, ['basic_confirm_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     *
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmProblem::class, ['confirm_id' => 'id']);
    }


    /**
     * Найти вопросы привязанные к подтверждению
     *
     * @return QuestionsConfirmProblem[]
     */
    public function findQuestions()
    {
        return QuestionsConfirmProblem::findAll(['confirm_id' => $this->getId()]);
    }


    /**
     * Получить гипотезу подтверждения
     *
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['problem_id', 'count_respond', 'count_positive', 'need_consumer'], 'required'],
            [['problem_id'], 'integer'],
            ['need_consumer', 'trim'],
            ['need_consumer', 'string', 'max' => 255],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'max' => '100'],
            ['enable_expertise', 'default', 'value' => EnableExpertise::OFF],
            ['enable_expertise', 'in', 'range' => [
                EnableExpertise::OFF,
                EnableExpertise::ON,
            ]],
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
     * Список вопросов, который будет показан для добавления нового вопроса
     *
     * @return array
     */
    public function queryQuestionsGeneralList()
    {
        $user = $this->problem->project->user;
        $questions = array(); //Добавляем в массив вопросы уже привязанные к данной программе
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

        $count_interview = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_problem.id' => null]])->count();

        $count_positive = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_problem.status' => '1'])->count();

        if ((count($this->responds) == $count_interview && $this->getCountPositive() <= $count_positive) || (!empty($this->gcps))) {
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
        $count = RespondsProblem::find()->where(['confirm_id' => $this->getId()])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_problem.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        //Кол-во респондентов, кот-е подтвердили проблему
        $count = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_problem.status' => '1'])->count();

        return $count;
    }


    /**
     * Путь к папке всего
     * кэша данного подтверждения
     *
     * @return string
     */
    public function getCachePath()
    {
        $problem = $this->problem;
        $segment = $problem->segment;
        $project = $problem->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.
            '/segments/segment-'.$segment->id. '/problems/problem-'.$problem->id.'/confirm';
        return $cachePath;
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
    public function getProblemId()
    {
        return $this->problem_id;
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
    public function getCountRespond()
    {
        return $this->count_respond;
    }

    /**
     * @param int $count
     */
    public function setCountRespond($count)
    {
        $this->count_respond = $count;
    }

    /**
     * @return int
     */
    public function getCountPositive()
    {
        return $this->count_positive;
    }

    /**
     * @param int $count
     */
    public function setCountPositive($count)
    {
        $this->count_positive = $count;
    }

    /**
     * @return string
     */
    public function getNeedConsumer()
    {
        return $this->need_consumer;
    }

    /**
     * @param string $needConsumer
     */
    public function setNeedConsumer($needConsumer)
    {
        $this->need_consumer = $needConsumer;
    }

    /**
     * @return int
     */
    public function getEnableExpertise()
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise()
    {
        $this->enable_expertise = EnableExpertise::ON;
    }




}
