<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "interview".
 */
class Interview extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview';
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
            [['greeting_interview', 'view_interview', 'reason_interview'], 'string', 'max' => 255],
            [['greeting_interview', 'view_interview', 'reason_interview'], 'trim'],
        ];
    }


    public function getSegment()
    {
        return $this->hasOne(Segment::class, ['id' => 'segment_id']);
    }


    public function getQuestions()
    {
        return $this->hasMany(Questions::class, ['interview_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(Respond::class, ['interview_id' => 'id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpert::class, ['interview_id' => 'id']);
    }

    public function getProblems()
    {
        return $this->hasMany(GenerationProblem::class, ['interview_id' => 'id']);
    }


    public function createRespond()
    {
        for ($i = 1; $i <= $this->count_respond; $i++ )
        {
            $newRespond[$i] = new Respond();
            $newRespond[$i]->interview_id = $this->id;
            $newRespond[$i]->name = 'Респондент ' . $i;
            $newRespond[$i]->save();
        }
    }

    public function addQuestionDefault($title)
    {
        $question = new Questions();
        $question->interview_id = $this->id;
        $question->title = $title;

        if ($question->save()) {
            $this->addQuestionToGeneralList($title);
        }
    }


    public function addQuestionToGeneralList($title)
    {
        $segment = $this->segment;
        $user = $this->segment->project->user;

        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestions::find()
            ->where([
                'type_of_interaction_between_subjects' => $segment->type_of_interaction_between_subjects,
                'field_of_activity' => $segment->field_of_activity,
                'sort_of_activity' => $segment->sort_of_activity,
                'specialization_of_activity' => $segment->specialization_of_activity,
                /*'user_id' => $user->id*/
            ])->select('title')->all();

        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){

            $general_question = new AllQuestions();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->type_of_interaction_between_subjects = $segment->type_of_interaction_between_subjects;
            $general_question->field_of_activity = $segment->field_of_activity;
            $general_question->sort_of_activity = $segment->sort_of_activity;
            $general_question->specialization_of_activity = $segment->specialization_of_activity;
            $general_question->save();
        }
    }


    public function queryQuestionsGeneralList()
    {
        $segment = $this->segment;

        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestions::find()
            ->where([
                'type_of_interaction_between_subjects' => $segment->type_of_interaction_between_subjects,
                'field_of_activity' => $segment->field_of_activity,
                'sort_of_activity' => $segment->sort_of_activity,
                'specialization_of_activity' => $segment->specialization_of_activity
            ])
            ->orderBy(['id' => SORT_DESC])
            ->select('title')
            ->asArray()
            ->all();

        $queryQuestions = $allQuestions;

        //Убираем из списка для добавления вопросов,
        //вопросы уже привязанные к данной программе
        foreach ($queryQuestions as $key => $queryQuestion) {
            if (in_array($queryQuestion['title'], $questions)) {
                unset($queryQuestions[$key]);
            }
        }

        return $queryQuestions;
    }


    public function getButtonMovingNextStage()
    {
        $count_descInterview = 0;
        $count_positive = 0;

        foreach ($this->responds as $respond) {

            if ($respond->descInterview){
                $count_descInterview++;

                if ($respond->descInterview->status == 1){
                    $count_positive++;
                }
            }
        }

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->problems))) {
            return true;
        }else {
            return false;
        }
    }


    public function getDataRespondsOfModel()
    {
        $sum = 0;
        foreach ($this->responds as $respond){
            if (!empty($respond->info_respond) && !empty($respond->date_plan) && !empty($respond->place_interview)){
                $sum++;
            }
        }

        return $sum;
    }


    public function getDataDescInterviewsOfModel()
    {
        $sum = 0;
        foreach ($this->responds as $respond){
            if ($respond->descInterview){
                $sum++;
            }
        }

        return $sum;
    }


    public function getDataMembersOfSegment()
    {
        $sumPositive = 0; // Кол-во представителей сегмента
        foreach ($this->responds as $respond){

            if ($respond->descInterview){
                if ($respond->descInterview->status == 1){
                    $sumPositive++;
                }
            }
        }

        return $sumPositive;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'segment_id' => 'Segment ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Количество респондентов, соответствующих сегменту',
            'greeting_interview' => 'Приветствие в начале встречи',
            'view_interview' => 'Представление интервьюера',
            'reason_interview' => 'Почему мне интересно',
        ];
    }
}
