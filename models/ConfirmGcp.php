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
 * @property int $enable_expertise                      Параметр разрешения на экспертизу по даному этапу
 */
class ConfirmGcp extends ActiveRecord implements ConfirmationInterface
{

    const STAGE = 6;
    const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_gcp';
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
     * Получить объект текущего Gcps
     *
     * @return ActiveQuery
     */
    public function getGcp()
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * Найти ЦП, к которому относится подтверждение
     *
     * @return Gcps|null
     */
    public function findGcp()
    {
        return Gcps::findOne($this->getGcpId());
    }


    /**
     * Получить респондентов привязанных к подтверждению
     *
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsGcp::class, ['confirm_id' => 'id']);
    }


    /**
     * Получить все объекты Mvps данного подтверждения
     *
     * @return ActiveQuery
     */
    public function getMvps()
    {
        return $this->hasMany(Mvps::class, ['basic_confirm_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     *
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmGcp::class, ['confirm_id' => 'id']);
    }


    /**
     * Найти вопросы привязанные к подтверждению
     *
     * @return QuestionsConfirmGcp[]
     */
    public function findQuestions()
    {
        return QuestionsConfirmGcp::findAll(['confirm_id' => $this->getId()]);
    }


    /**
     * Получить гипотезу подтверждения
     *
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Gcps::class, ['id' => 'gcp_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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
    public function queryQuestionsGeneralList()
    {
        $user = $this->gcp->project->user;
        $questions = []; //Добавляем в массив вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) $questions[] = $question['title'];

        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmGcp::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmGcp::find()
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
        $count_interview = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_gcp.status' => '1'])->count();

        if ((count($this->responds) == $count_interview && $this->count_positive <= $count_positive) || (!empty($this->mvps))) {
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
        $count = RespondsGcp::find()->where(['confirm_id' => $this->getId()])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_gcp.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во подтвердивших ЦП
        $count = RespondsGcp::find()->with('interview')
            ->leftJoin('interview_confirm_gcp', '`interview_confirm_gcp`.`respond_id` = `responds_gcp`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_gcp.status' => '1'])->count();

        return $count;
    }


    /**
     * Путь к папке всего
     * кэша данного подтверждения
     * @return string
     */
    public function getCachePath()
    {
        $gcp = $this->gcp;
        $problem = $gcp->problem;
        $segment = $gcp->segment;
        $project = $gcp->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm';

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
     * @param int $id
     */
    public function setGcpId($id)
    {
        $this->gcp_id = $id;
    }


    /**
     * @return int
     */
    public function getGcpId()
    {
        return $this->gcp_id;
    }

    /**
     * Установить кол-во респондентов
     * @param int $count
     */
    public function setCountRespond($count)
    {
        $this->count_respond = $count;
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
    public function setCountPositive($count)
    {
        $this->count_positive = $count;
    }

    /**
     * @return int
     */
    public function getCountPositive()
    {
        return $this->count_positive;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise()
    {
        $this->enable_expertise = EnableExpertise::ON;
    }

    /**
     * @return int
     */
    public function getEnableExpertise()
    {
        return $this->enable_expertise;
    }
}
