<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
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
        $question->save();
        $this->addQuestionToGeneralList($title);
    }


    public function addQuestionToGeneralList($title)
    {
        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestions::find()->select('title')->all();

        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){

            $allQuestions = new AllQuestions();
            $allQuestions->title = $title;
            $allQuestions->save();
        }
    }


    public function queryQuestionsGeneralList()
    {
        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestions::find()->orderBy(['id' => SORT_DESC])->all();
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


    public function getShowListQuestions()
    {
        $showListQuestions = '';

        if ($this->questions){

            $i = 0;
            foreach ($this->questions as $question){
                $i++;
                $showListQuestions .= '<p style="font-weight: 400;">'. $i . '. '.$question->title.'</p>';
            }
        }

        return $showListQuestions;
    }


    public function getRedirectRespondTable()
    {
        $data_responds = 0;
        $data_interview = 0;
        $data_interview_status = 0;
        foreach ($this->responds as $respond){
            if (!empty($respond->name) && !empty($respond->info_respond) && !empty($respond->date_plan) && !empty($respond->place_interview)){
                $data_responds++;

                if (!empty($respond->descInterview)){
                    $data_interview++;

                    if ($respond->descInterview->status == 1){
                        $data_interview_status++;
                    }
                }
            }
        }

        if ($data_responds == 0){

            return '<div style="font-weight: 700; margin: 25px 0 0 0;">Рекомендации</div>';

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status >= $this->count_positive && ($this->segment->exist_confirm === null || $this->segment->exist_confirm === 0)){

            return Html::a('Завершить', ['/interview/exist-confirm', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif (count($this->problems) != 0){

            return Html::a('Генерация ГПС', ['/generation-problem/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($this->segment->exist_confirm === 1 && $data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status >= $this->count_positive){

            return Html::a('Генерация ГПС', ['/generation-problem/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($this->segment->exist_confirm === 0 && $data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive){

            return '';

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive){

            return Html::a('Завершить', ['/interview/not-exist-confirm', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-danger', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ((count($this->problems) == 0) && ($data_responds != count($this->responds) || $data_interview != count($this->responds))){

            return '<div style="font-weight: 700; margin: 25px 0 0 0;">Рекомендации</div>';

        }else{

            return '';
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

        if ($sum !== 0){
            $value = round(($sum / count($this->responds) * 100) * 100) / 100;
        }else{
            $value = 0;
        }


        return "<progress max='100' value='$value' id='info-respond'></progress><p id='info-respond-text-indicator' class='text-center' style='font-weight: 700;font-size: 13px;'>$value  %</p>";
    }


    public function getDataDescInterviewsOfModel()
    {
        $sum = 0;
        foreach ($this->responds as $respond){
            if ($respond->descInterview){
                $sum++;
            }
        }

        if ($sum !== 0){
            $value = round(($sum / count($this->responds) * 100) *100) / 100;
        }else{
            $value = 0;
        }


        return "<progress max='100' value='$value' id='info-interview'></progress><p id='info-interview-text-indicator' class='text-center' style='font-weight: 700;font-size: 13px;'>$value  %</p>";
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

        if($sumPositive !== 0){
            $valPositive = round(($sumPositive / count($this->responds) * 100) *100) / 100;
        }else {
            $valPositive = 0;
        }


        if ($this->count_positive <= $sumPositive){
            return "<progress max='100' value='$valPositive' id='info-status' class='info-green'></progress><p id='info-status-text-indicator' class='text-center' style='font-weight: 700;font-size: 13px;'>$valPositive  %</p>";
        }

        if ($sumPositive < $this->count_positive){
            return "<progress max='100' value='$valPositive' id='info-status' class='info-red'></progress><p id='info-status-text-indicator' class='text-center' style='font-weight: 700;font-size: 13px;'>$valPositive  %</p>";
        }
    }


    public function getMessageAboutTheNextStep()
    {
        $sumPositive = 0; // Кол-во представителей сегмента
        $data_respond = 0; //Кол-во респондентов, которые имеют описание
        $data_desc = 0; // Кол-во проведенных интервью
        foreach ($this->responds as $respond){

            if (!empty($respond->info_respond) && !empty($respond->date_plan) && !empty($respond->place_interview)){
                $data_respond++;
            }

            if ($respond->descInterview){
                $data_desc++;

                if ($respond->descInterview->status == 1){
                    $sumPositive++;
                }
            }
        }


        if ($data_respond == 0){
            return '<span id="messageAboutTheNextStep" class="text-success">Начните заполнять данные о респондентах и интервью</span>';
        }

        if ($data_respond != 0 && count($this->responds) != $data_desc && empty($this->problems)){
            return '<span id="messageAboutTheNextStep" class="text-warning">Продолжите заполнение данных о респондентах и интервью</span>';
        }

        if ($sumPositive < $this->count_positive && count($this->responds) == $data_desc){
            return '<span id="messageAboutTheNextStep" class="text-danger">Недостаточное количество представителей сегмента, добавьте новых респондентов или завершите подтверждение</span>';
        }

        if ($this->count_positive <= $sumPositive && empty($this->problems) && $data_desc == count($this->responds) && ($this->segment->exist_confirm === null || $this->segment->exist_confirm === 0)){
            return '<span id="messageAboutTheNextStep" class="text-success">Завершите подтверждение и переходите к генерации ГПС</span>';
        }

        if (count($this->problems) != 0){

            return '<span id="messageAboutTheNextStep" class="text-success">Перейдите на страницу генерации гипотез проблем сегмента</span>';
        }

        if ($this->segment->exist_confirm === 1 && $data_respond == count($this->responds) && $data_desc == count($this->responds) && $sumPositive >= $this->count_positive){

            return '<span id="messageAboutTheNextStep" class="text-success">Перейдите на страницу генерации гипотез проблем сегмента</span>';

        }
    }


    public function getNextStep()
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

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->problems) && $this->count_positive <= $count_positive)){
            return true;
        }else{
            return false;
        }
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
            'question_1' => '',
            'question_2' => '',
            'question_3' => '',
            'question_4' => '',
            'question_5' => '',
            'question_6' => '',
            'question_7' => '',
            'question_8' => '',
        ];
    }
}
