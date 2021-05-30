<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

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
     * @return bool
     */
    public function checkingLimitCountRespond()
    {
        if ($this->count_respond < self::LIMIT_COUNT_RESPOND) return true;
        else return false;
    }


    /**
     * Получить объект текущего Gcp
     * @return ActiveQuery
     */
    public function getGcp()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsGcp::class, ['confirm_gcp_id' => 'id']);
    }


    /**
     * Получить все объекты Mvp данного подтверждения
     * @return ActiveQuery
     */
    public function getMvps()
    {
        return $this->hasMany(Mvp::class, ['confirm_gcp_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmGcp::class, ['confirm_gcp_id' => 'id']);
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
     * @param $id
     * @return mixed
     */
    public function setGcpId($id)
    {
        return $this->gcp_id = $id;
    }


    /**
     * @return mixed
     */
    public function getGcpId()
    {
        return $this->gcp_id;
    }


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
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
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gcp_id' => 'Gcp ID',
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
     * Добавляем вопрос в общую базу,
     * если у данного пользователя его там ещё нет
     * @param $title
     */
    public function addQuestionToGeneralList($title)
    {
        $user = $this->gcp->project->user;
        $baseQuestions = AllQuestionsConfirmGcp::find()->where(['user_id' => $user->id])->select('title')->all();
        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){
            $general_question = new AllQuestionsConfirmGcp();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->save();
        }
    }


    /**
     * Создание пустого ответа для нового вопроса для каждого респондента
     * @param $question_id
     */
    public function addAnswerConfirmGcp ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = new AnswersQuestionsConfirmGcp();
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
    public function deleteAnswerConfirmGcp ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmGcp::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    /**
     * Список вопросов, который будет показан для добавления нового вопроса
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
        $count_descInterview = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $this->id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();

        $count_positive = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $this->id, 'desc_interview_gcp.status' => '1'])->count();

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->mvps))) {
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
        $count = RespondsGcp::find()->where(['confirm_gcp_id' => $this->id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $this->id])->andWhere(['not', ['desc_interview_gcp.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во подтвердивших ЦП
        $count = RespondsGcp::find()->with('descInterview')
            ->leftJoin('desc_interview_gcp', '`desc_interview_gcp`.`responds_gcp_id` = `responds_gcp`.`id`')
            ->where(['confirm_gcp_id' => $this->id, 'desc_interview_gcp.status' => '1'])->count();

        return $count;
    }

}
