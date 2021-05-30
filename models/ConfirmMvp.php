<?php

namespace app\models;

use app\models\interfaces\ConfirmationInterface;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

class ConfirmMvp extends ActiveRecord implements ConfirmationInterface
{

    const STAGE = 8;
    const LIMIT_COUNT_RESPOND = 100;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_mvp';
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
     * Получить объект текущего Mvp
     * @return ActiveQuery
     */
    public function getMvp()
    {
        return $this->hasOne(Mvp::class, ['id' => 'mvp_id']);
    }


    /**
     * Получить респондентов привязанных к подтверждению
     * @return ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(RespondsMvp::class, ['confirm_mvp_id' => 'id']);
    }


    /**
     * Получить объект бизнес модели
     * @return ActiveQuery
     */
    public function getBusiness()
    {
        return $this->hasOne(BusinessModel::class, ['confirm_mvp_id' => 'id']);
    }


    /**
     * Получить вопросы привязанные к подтверждению
     * @return ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmMvp::class, ['confirm_mvp_id' => 'id']);
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
     */
    public function setMvpId($id)
    {
        $this->mvp_id = $id;
    }


    /**
     * @return mixed
     */
    public function getMvpId()
    {
        return $this->mvp_id;
    }


    /**
     * Получить гипотезу подтверждения
     * @return ActiveQuery
     */
    public function getHypothesis()
    {
        return $this->hasOne(Mvp::class, ['id' => 'mvp_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mvp_id', 'count_respond', 'count_positive'], 'required'],
            [['mvp_id'], 'integer'],
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
            'mvp_id' => 'Mvp ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->mvp->project->touch('updated_at');
            $this->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->mvp->project->touch('updated_at');
            $this->mvp->project->user->touch('updated_at');
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
        $user = $this->mvp->project->user;
        $baseQuestions = AllQuestionsConfirmMvp::find()->where(['user_id' => $user->id])->select('title')->all();
        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){
            $general_question = new AllQuestionsConfirmMvp();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->save();
        }
    }


    /**
     * Создание пустого ответа для нового вопроса для каждого респондента
     * @param $question_id
     */
    public function addAnswerConfirmMvp ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = new AnswersQuestionsConfirmMvp();
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
    public function deleteAnswerConfirmMvp ($question_id)
    {
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmMvp::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    /**
     * @return array
     */
    public function queryQuestionsGeneralList()
    {
        $user = $this->mvp->project->user;
        $questions = []; //Добавляем в массив $questions вопросы уже привязанные к данной программе
        foreach ($this->questions as $question) $questions[] = $question['title'];

        // Вопросы, предлагаемые по-умолчанию на данном этапе
        $defaultQuestions = AllQuestionsConfirmMvp::defaultListQuestions();
        // Вопросы, которые когда-либо добавлял пользователь на данном этапе
        $attachQuestions = AllQuestionsConfirmMvp::find()
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
        $count_descInterview = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

        $count_positive = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id, 'desc_interview_mvp.status' => '1'])->count();

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->business))) {
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
        $count = RespondsMvp::find()->where(['confirm_mvp_id' => $this->id])->andWhere(['not', ['info_respond' => '']])
            ->andWhere(['not', ['date_plan' => null]])->andWhere(['not', ['place_interview' => '']])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

        return $count;
    }


    /**
     * @return int|string
     */
    public function getCountConfirmMembers()
    {
        // Кол-во подтвердивших MVP
        $count = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id, 'desc_interview_mvp.status' => '1'])->count();

        return $count;
    }
}
