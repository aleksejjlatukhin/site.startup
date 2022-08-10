<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит объекты подтверждений ценностных предложений в бд
 *
 * Class ConfirmGcp
 * @package app\models
 *
 * @property int $id                                    Идентификатор записи в таб. confirm_gcp
 * @property int $gcp_id                                Идентификатор записи в таб. gcps
 * @property int $count_respond                         Количество респондентов
 * @property int $count_positive                        Количество респондентов, подтверждающих ценностное предложение
 * @property string $enable_expertise                   Параметр разрешения на экспертизу по даному этапу
 *
 * @property Gcps $gcp                                  Ценностное предложение
 * @property RespondsGcp[] $responds                    Респонденты, привязанные к подтверждению
 * @property Mvps[] $mvps                               Mvp-продукты
 * @property QuestionsConfirmGcp[] $questions           Вопросы, привязанные к подтверждению
 * @property Gcps $hypothesis                           Гипотеза, к которой относится подтверждение
 */
class ConfirmGcp extends ActiveRecord implements ConfirmationInterface
{

    public const STAGE = 6;
    public const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'confirm_gcp';
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
     * Получить объект текущего Gcps
     *
     * @return ActiveQuery
     */
    public function getGcp(): ActiveQuery
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     *
     * @return ActiveQuery
     */
    public function getResponds(): ActiveQuery
    {
        return $this->hasMany(RespondsGcp::class, ['confirm_id' => 'id']);
    }


    /**
     * Получить все объекты Mvps данного подтверждения
     *
     * @return ActiveQuery
     */
    public function getMvps(): ActiveQuery
    {
        return $this->hasMany(Mvps::class, ['basic_confirm_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     *
     * @return ActiveQuery
     */
    public function getQuestions(): ActiveQuery
    {
        return $this->hasMany(QuestionsConfirmGcp::class, ['confirm_id' => 'id']);
    }


    /**
     * Получить гипотезу подтверждения
     *
     * @return ActiveQuery
     */
    public function getHypothesis(): ActiveQuery
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id'], 'integer'],
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
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->gcp->project->touch('updated_at');
            $this->gcp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->gcp->project->touch('updated_at');
            $this->gcp->project->user->touch('updated_at');
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
        $user = $this->gcp->project->user;
        $questions = []; //Добавляем в массив вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        /**
         * @var AllQuestionsConfirmGcp[] $attachQuestions
         */
        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmGcp::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmGcp::find()
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
        $count_interview = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_gcp.status' => '1'])->count();

        if ($this->mvps || (count($this->responds) === $count_interview && $this->getCountPositive() <= $count_positive)) {
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
        return RespondsGcp::find()->where(['confirm_id' => $this->getId()])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        return RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во подтвердивших ЦП
        return RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_gcp.status' => '1'])->count();
    }


    /**
     * Путь к папке всего
     * кэша данного подтверждения
     * @return string
     */
    public function getCachePath(): string
    {
        $gcp = $this->gcp;
        $problem = $gcp->problem;
        $segment = $gcp->segment;
        $project = $gcp->project;
        $user = $project->user;
        return '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId(). '/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/confirm';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setGcpId(int $id): void
    {
        $this->gcp_id = $id;
    }


    /**
     * @return int
     */
    public function getGcpId(): int
    {
        return $this->gcp_id;
    }

    /**
     * Установить кол-во респондентов
     * @param int $count
     */
    public function setCountRespond(int $count): void
    {
        $this->count_respond = $count;
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
    public function setCountPositive(int $count): void
    {
        $this->count_positive = $count;
    }

    /**
     * @return int
     */
    public function getCountPositive(): int
    {
        return $this->count_positive;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise(): void
    {
        $this->enable_expertise = EnableExpertise::ON;
    }

    /**
     * @return string
     */
    public function getEnableExpertise(): string
    {
        return $this->enable_expertise;
    }
}
