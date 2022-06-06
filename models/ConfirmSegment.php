<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит объекты подтверждения сегментов в бд
 *
 * Class ConfirmSegment
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. confirm_segment
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $count_respond                     Количество респондентов
 * @property int $count_positive                    Количество респондентов, соответствующих сегменту
 * @property string $greeting_interview             Приветствие в начале встречи
 * @property string $view_interview                 Информация о вас для респондентов
 * @property string $reason_interview               Причина и тема (что побудило) для проведения исследования
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class ConfirmSegment extends ActiveRecord implements ConfirmationInterface
{

    const STAGE = 2;
    const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_segment';
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
        if ($this->getCountRespond() < self::LIMIT_COUNT_RESPOND) return true;
        else return false;
    }


    /**
     * Получить объект текущего сегмента
     * @return ActiveQuery
     */
    public function getSegment()
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
    }


    /**
     * Поиск текущего сегмент
     * @return Segments|null
     */
    public function findSegment()
    {
        return Segments::findOne($this->getSegmentId());
    }


    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmSegment::class, ['confirm_id' => 'id']);
    }


    /**
     * Найти вопросы привязанные к подтверждению
     *
     * @return QuestionsConfirmSegment[]
     */
    public function findQuestions()
    {
        return QuestionsConfirmSegment::findAll(['confirm_id' => $this->getId()]);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsSegment::class, ['confirm_id' => 'id']);
    }


    /**
     * Получить все проблемы по данному сегменту
     * @return ActiveQuery
     */
    public function getProblems()
    {
        return $this->hasMany(Problems::class, ['basic_confirm_id' => 'id']);
    }


    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->setGreetingInterview($params['greeting_interview']);
        $this->setViewInterview($params['view_interview']);
        $this->setReasonInterview($params['reason_interview']);
    }


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
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

        $count_interview = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_segment.id' => null]])->count();

        $count_positive = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_segment.status' => '1'])->count();

        if ((count($this->responds) == $count_interview && $this->getCountPositive() <= $count_positive) || (!empty($this->problems))) {
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
        $count = RespondsSegment::find()->where(['confirm_id' => $this->getId()])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует интервью
        $count = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->getId()])->andWhere(['not', ['interview_confirm_segment.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во представителей сегмента
        $count = RespondsSegment::find()->with('interview')
            ->leftJoin('interview_confirm_segment', '`interview_confirm_segment`.`respond_id` = `responds_segment`.`id`')
            ->where(['confirm_id' => $this->getId(), 'interview_confirm_segment.status' => '1'])->count();

        return $count;
    }


    /**
     * Путь к папке всего
     * кэша данного подтверждения
     * @return string
     */
    public function getCachePath()
    {
        $segment = $this->segment;
        $project = $segment->project;
        $user = $project->user;
        $cachePath = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm';
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
    public function setSegmentId($id)
    {
        $this->segment_id = $id;
    }


    /**
     * @return int
     */
    public function getSegmentId()
    {
        return $this->segment_id;
    }


    /**
     * Параметр разрешения экспертизы
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
    public function getGreetingInterview()
    {
        return $this->greeting_interview;
    }

    /**
     * @param string $greeting_interview
     */
    public function setGreetingInterview($greeting_interview)
    {
        $this->greeting_interview = $greeting_interview;
    }

    /**
     * @return string
     */
    public function getViewInterview()
    {
        return $this->view_interview;
    }

    /**
     * @param string $view_interview
     */
    public function setViewInterview($view_interview)
    {
        $this->view_interview = $view_interview;
    }

    /**
     * @return string
     */
    public function getReasonInterview()
    {
        return $this->reason_interview;
    }

    /**
     * @param string $reason_interview
     */
    public function setReasonInterview($reason_interview)
    {
        $this->reason_interview = $reason_interview;
    }

}
