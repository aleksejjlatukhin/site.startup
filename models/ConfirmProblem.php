<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "confirm_problem".
 *
 * @property string $id
 * @property int $gps_id
 * @property int $count_respond
 * @property int $count_positive
 * @property string $greeting_interview
 * @property string $view_interview
 * @property string $reason_interview
 * @property string $question_1
 * @property string $question_2
 * @property string $question_3
 * @property string $question_4
 * @property string $question_5
 * @property string $question_6
 * @property string $question_7
 * @property string $question_8
 */
class ConfirmProblem extends \yii\db\ActiveRecord
{

    public $exist_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_problem';
    }

    public function getProblem()
    {
        return $this->hasOne(GenerationProblem::class, ['id' => 'gps_id']);
    }


    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertConfirm::class, ['confirm_problem_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsConfirm::class, ['confirm_problem_id' => 'id']);
    }

    public function getGcps()
    {
        return $this->hasMany(Gcp::class, ['confirm_problem_id' => 'id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmProblem::class, ['confirm_problem_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gps_id', 'count_respond', 'count_positive', 'need_consumer'], 'required'],
            [['gps_id', 'exist_confirm'], 'integer'],
            ['need_consumer', 'trim'],
            ['need_consumer', 'string', 'max' => 255],
            [['count_respond', 'count_positive'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gps_id' => 'Gps ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
            'need_consumer' => 'Потребность потребителя',
        ];
    }

    //Создание респондентов для программы подтверждения ГПС из представителей сегмента
    public function createRespondConfirm ($responds)
    {
        foreach ($responds as $respond) {
            if ($respond->descInterview->status == 1){

                $respondConfirm = new RespondsConfirm();
                $respondConfirm->confirm_problem_id = $this->id;
                $respondConfirm->name = $respond->name;
                $respondConfirm->info_respond = $respond->info_respond;
                $respondConfirm->email = $respond->email;
                $respondConfirm->save();
            }
        }
    }


    public function addQuestionDefault ($title)
    {
        $question = new QuestionsConfirmProblem();
        $question->confirm_problem_id = $this->id;
        $question->title = $title;

        if ($question->save()) {
            $this->addAnswerConfirmProblem($question->id);
            $this->addQuestionToGeneralList($title);
        }
    }


    public function addQuestionToGeneralList($title)
    {
        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestionsConfirmProblem::find()->select('title')->all();

        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){

            $allQuestions = new AllQuestionsConfirmProblem();
            $allQuestions->title = $title;
            $allQuestions->save();
        }
    }

    public function addAnswerConfirmProblem ($question_id)
    {
        //Создание пустого ответа для нового вопроса для каждого респондента
        foreach ($this->responds as $respond) {

            $answer = new AnswersQuestionsConfirmProblem();
            $answer->question_id = $question_id;
            $answer->respond_id = $respond->id;
            $answer->save();

        }
    }


    public function deleteAnswerConfirmProblem ($question_id)
    {
        //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmProblem::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    public function queryQuestionsGeneralList()
    {
        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestionsConfirmProblem::find()->orderBy(['id' => SORT_DESC])->all();
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
            if (!empty($respond->name) && !empty($respond->info_respond)){
                $data_responds++;

                if (!empty($respond->descInterview)){
                    $data_interview++;

                    if ($respond->descInterview->status == 1){
                        $data_interview_status++;
                    }
                }
            }
        }

        if ($data_interview == 0){

            return Html::a('Начать', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($data_responds == count($this->responds) && $data_interview != 0 && $data_interview == count($this->responds) && $data_interview_status >= $this->count_positive){

            return Html::a('Редактировать <span class="glyphicon glyphicon-pencil"></span>', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 20px 0 0']]) .
                Html::a('Завершить', ['/confirm-problem/not-exist-confirm', 'id' => $this->id], [
                    'class' => 'btn btn-default finish_program',
                    'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0', 'display' => 'none'],
                    'data' => [
                        'confirm' => 'Проблема не подтверждена!<br>Вы действительно хотите завершить программу подтверждения ' . $this->problem->title . ' ?',
                        'method' => 'post',
                    ],
                ]);

        }elseif (count($this->gcps) != 0){

            return Html::a('Редактировать <span class="glyphicon glyphicon-pencil"></span>', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive && $this->problem->exist_confirm === null){

            return Html::a('Добавить', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-danger', 'style' => ['font-weight' => '700', 'margin' => '20px 10px 0 0']]) .
                Html::a('Завершить', ['/confirm-problem/not-exist-confirm', 'id' => $this->id], [
                    'class' => 'btn btn-default finish_program',
                    'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0'],
                    'data' => [
                        'confirm' => 'Проблема не подтверждена!<br>Вы действительно хотите завершить программу подтверждения ' . $this->problem->title . ' ?',
                        'method' => 'post',
                    ],
                ]);

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive && $this->problem->exist_confirm === 0){

            return Html::a('Добавить', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-danger', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ((count($this->gcps) == 0) && ($data_responds != count($this->responds) || $data_interview != count($this->responds))){

            return Html::a('Продолжить', ['/responds-confirm/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }else{

            return '';
        }
    }


    public function getMessageAboutTheNextStep()
    {
        $sumPositive = 0; // Кол-во представителей сегмента
        $data_respond = 0; //Кол-во респондентов, которые имеют описание
        $data_desc = 0; // Кол-во проведенных интервью
        foreach ($this->responds as $respond){

            if (!empty($respond->info_respond)){
                $data_respond++;
            }

            if ($respond->descInterview){
                $data_desc++;

                if ($respond->descInterview->status == 1){
                    $sumPositive++;
                }
            }
        }


        if ($data_desc == 0){
            return '<span id="messageAboutTheNextStep" class="text-success">Начните заполнять анкетные данные респондентов</span>';
        }

        if ($data_respond != 0 && count($this->responds) != $data_desc && empty($this->gcps) && $data_desc != 0){
            return '<span id="messageAboutTheNextStep" class="text-warning">Продолжите заполнение анкетных данных респондентов</span>';
        }

        if ($sumPositive < $this->count_positive && count($this->responds) == $data_desc){
            return '<span id="messageAboutTheNextStep" class="text-danger">Недостаточное количество респондентов, подтвердивших проблему</span>';
        }

        if ($this->count_positive <= $sumPositive && empty($this->gcps) && $data_desc == count($this->responds)){
            return '<span id="messageAboutTheNextStep" class="text-success">Переходите к генерации ГЦП</span>';
        }
    }


    public function getDataRespondsOfModel()
    {
        $sum = 0;
        foreach ($this->responds as $respond){
            if (!empty($respond->info_respond)){
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
        $sumPositive = 0; // Кол-во респондентов подтверживших проблему
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

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->gcps) && $this->count_positive <= $count_positive)){
            return true;
        }else{
            return false;
        }
    }


    public function pointerOnThirdStep()
    {

        $sumPositive = 0; // Кол-во представителей сегмента
        $data_respond = 0; //Кол-во респондентов, которые имеют описание
        $data_desc = 0; // Кол-во проведенных интервью

        foreach ($this->responds as $respond){

            if (!empty($respond->info_respond)){
                $data_respond++;
            }

            if ($respond->descInterview){
                $data_desc++;

                if ($respond->descInterview->status == 1){
                    $sumPositive++;
                }
            }
        }


        if ($data_desc == 0 && empty($this->gcps)){

            return '<div class="text-center text-danger" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
        }

        if ($data_respond != 0 && count($this->responds) != $data_desc && $data_desc != 0 && empty($this->gcps)){

            return '<div class="text-center text-danger" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
        }

        if ($sumPositive < $this->count_positive && count($this->responds) == $data_desc && empty($this->gcps)){

            return '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>' .

                '<div class="text-center finish_program_success" style="padding: 50px 0; display: none;">' . Html::a('Завершить программу подтверждения ' . $this->problem->title . ' и<br>перейти к генерации ценностных предложений', ['/confirm-problem/exist-confirm', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';
        }


        if ($this->count_positive <= $sumPositive && $data_desc == count($this->responds) && $this->problem->exist_confirm != 1 && empty($this->gcps)){

            if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return '<div class="text-center finish_program_success" style="padding: 50px 0;">' . Html::a('Завершить программу подтверждения ' . $this->problem->title . ' и<br>перейти к генерации ценностных предложений', ['/confirm-problem/exist-confirm', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>' .

                    '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px; display: none;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';

            }else {

                return '<div class="text-center text-warning finish_program_success" style="padding: 50px 0;">Пользователь пока не завершил программу подтверждения ' . $this->problem->title . '.</div>' .

                    '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px; display: none">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
            }
        }


        if ($this->count_positive <= $sumPositive && empty($this->gcps) && $data_desc == count($this->responds) && $this->problem->exist_confirm == 1){

            if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return '<div class="text-center" style="padding: 50px 0;">' . Html::a('Переходите к генерации ценностных предложений', ['/gcp/index', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';

            }else {

                return '<div class="text-center text-warning" style="padding: 50px 0;">Пользователь пока не сгенерировал ни одной гипотезы ценностного предложения</div>';
            }
        }


        if (!empty($this->gcps)){

            return '<div class="text-center" style="padding: 50px 0;">' . Html::a('Переход на страницу гипотез ценностных предложений', ['/gcp/index', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';
        }


    }

}
