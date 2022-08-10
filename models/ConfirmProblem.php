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
 * @property string $enable_expertise                      Параметр разрешения на экспертизу по даному этапу
 *
 * @property Problems $problem                          Проблема
 * @property RespondsProblem[] $responds                Респонденты, привязанные к подтверждению
 * @property Gcps[] $gcps                               Ценностные предложения
 * @property QuestionsConfirmProblem[] $questions       Вопросы, привязанные к подтверждению
 * @property Problems $hypothesis                       Гипотеза, к которой относится подтверждение
 */
class ConfirmProblem extends ActiveRecord implements ConfirmationInterface
{

    public const STAGE = 4;
    public const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'confirm_problem';
    }


    /**
     * @return int
     */
    public function getStage(): int
    {
        return self::STAGE;
    }


    /**
     * Проверка на ограничение кол-ва респондентов
     *
     * @return bool
     */
    public function checkingLimitCountRespond(): bool
    {
        if ($this->getCountRespond() < self::LIMIT_COUNT_RESPOND) {
            return true;
        }
        return false;
    }


    /**
     * Получить объект текущей проблемы
     *
     * @return ActiveQuery
     */
    public function getProblem(): ActiveQuery
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     *
     * @return ActiveQuery
     */
    public function getResponds(): ActiveQuery
    {
        return $this->hasMany(RespondsProblem::class, ['confirm_id' => 'id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getGcps(): ActiveQuery
    {
        return $this->hasMany(Gcps::class, ['basic_confirm_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     *
     * @return ActiveQuery
     */
    public function getQuestions(): ActiveQuery
    {
        return $this->hasMany(QuestionsConfirmProblem::class, ['confirm_id' => 'id']);
    }


    /**
     * Получить гипотезу подтверждения
     *
     * @return ActiveQuery
     */
    public function getHypothesis(): ActiveQuery
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
    public function queryQuestionsGeneralList(): array
    {
        $user = $this->problem->project->user;
        $questions = array(); //Добавляем в массив вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        /**
         * @var AllQuestionsConfirmProblem[] $attachQuestions
         */
        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmProblem::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmProblem::find()
            ->where(['user_id' => $user->getId()])
            ->orderBy(['id' => SORT_DESC])
            ->select('title')
            ->asArray()
            ->all();

        $qs = array(); // Добавляем в массив вопросы, предлагаемые по-умолчанию на данном этапе
        foreach ($defaultQuestions as $question) {
            $qs[] = $question['title'];
        }
        // Убираем из списка вопросов, которые когда-либо добавлял пользователь на данном этапе
        // вопросы, которые совпадают  с вопросами по-умолчанию
        foreach ($attachQuestions as $key => $queryQuestion) {
            if (in_array($queryQuestion['title'], $qs, false)) {
                unset($attachQuestions[$key]);
            }
        }

        //Убираем из списка для добавления вопросов, вопросы уже привязанные к данной программе
        $queryQuestions = array_merge($defaultQuestions, $attachQuestions);
        foreach ($queryQuestions as $key => $queryQuestion) {
            if (in_array($queryQuestion['title'], $questions, false)) {
                unset($queryQuestions[$key]);
            }
        }

        return $queryQuestions;
    }


    /**
     * @return bool
     */
    public function getButtonMovingNextStage(): bool
    {

        $count_interview = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_problem.id' => null]])->count();

        $count_positive = RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_problem.status' => '1'])->count();

        if ($this->gcps || (count($this->responds) === $count_interview && $this->getCountPositive() <= $count_positive)) {
            return true;
        }

        return false;
    }


    /**
     * @return int|string
     */
    public function getCountRespondsOfModel()
    {
        //Кол-во респондентов, у кот-х заполнены данные
        return RespondsProblem::find()->where(['confirm_id' => $this->getId()])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        return RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_problem.id' => null]])->count();
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        //Кол-во респондентов, кот-е подтвердили проблему
        return RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_problem.status' => '1'])->count();
    }


    /**
     * Путь к папке всего
     * кэша данного подтверждения
     *
     * @return string
     */
    public function getCachePath(): string
    {
        $problem = $this->problem;
        $segment = $problem->segment;
        $project = $problem->project;
        $user = $project->user;
        return '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().
            '/segments/segment-'.$segment->getId(). '/problems/problem-'.$problem->getId().'/confirm';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getProblemId(): int
    {
        return $this->problem_id;
    }

    /**
     * @param int $id
     */
    public function setProblemId(int $id): void
    {
        $this->problem_id = $id;
    }

    /**
     * @return int
     */
    public function getCountRespond(): int
    {
        return $this->count_respond;
    }

    /**
     * @param int $count
     */
    public function setCountRespond(int $count): void
    {
        $this->count_respond = $count;
    }

    /**
     * @return int
     */
    public function getCountPositive(): int
    {
        return $this->count_positive;
    }

    /**
     * @param int $count
     */
    public function setCountPositive(int $count): void
    {
        $this->count_positive = $count;
    }

    /**
     * @return string
     */
    public function getNeedConsumer(): string
    {
        return $this->need_consumer;
    }

    /**
     * @param string $needConsumer
     */
    public function setNeedConsumer(string $needConsumer): void
    {
        $this->need_consumer = $needConsumer;
    }

    /**
     * @return string
     */
    public function getEnableExpertise(): string
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise(): void
    {
        $this->enable_expertise = EnableExpertise::ON;
    }




}
