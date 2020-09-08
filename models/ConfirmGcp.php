<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "confirm_gcp".
 *
 * @property string $id
 * @property int $gcp_id
 * @property int $count_respond
 * @property int $count_positive
 */
class ConfirmGcp extends \yii\db\ActiveRecord
{

    public $exist_confirm;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_gcp';
    }

    public function getGcp()
    {
        return $this->hasOne(Gcp::class, ['id' => 'gcp_id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertGcp::class, ['confirm_gcp_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsGcp::class, ['confirm_gcp_id' => 'id']);
    }

    public function getMvps()
    {
        return $this->hasMany(Mvp::class, ['confirm_gcp_id' => 'id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmGcp::class, ['confirm_gcp_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gcp_id', 'count_respond', 'count_positive'], 'required'],
            [['gcp_id', 'exist_confirm'], 'integer'],
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
            'gcp_id' => 'Gcp ID',
            'count_respond' => 'Количество респондентов',
            'count_positive' => 'Необходимое количество позитивных ответов',
        ];
    }


    //Создание респондентов для программы подтверждения ГЦП из респондентов подтвердивших проблему
    public function createRespondConfirm ($responds)
    {
        foreach ($responds as $respond) {
            if ($respond->descInterview->status == 1){

                $respondConfirm = new RespondsGcp();
                $respondConfirm->confirm_gcp_id = $this->id;
                $respondConfirm->name = $respond->name;
                $respondConfirm->info_respond = $respond->info_respond;
                $respondConfirm->email = $respond->email;
                $respondConfirm->save();
            }
        }
    }


    public function addQuestionDefault ($title)
    {
        $question = new QuestionsConfirmGcp();
        $question->confirm_gcp_id = $this->id;
        $question->title = $title;

        if ($question->save()) {
            $this->addAnswerConfirmGcp($question->id);
            $this->addQuestionToGeneralList($title);
        }
    }


    public function addQuestionToGeneralList($title)
    {
        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestionsConfirmGcp::find()->select('title')->all();

        $existQuestions = 0;

        foreach ($baseQuestions as $baseQuestion){
            if ($baseQuestion->title == $title){
                $existQuestions++;
            }
        }

        if ($existQuestions == 0){

            $allQuestions = new AllQuestionsConfirmGcp();
            $allQuestions->title = $title;
            $allQuestions->save();
        }
    }


    public function addAnswerConfirmGcp ($question_id)
    {
        //Создание пустого ответа для нового вопроса для каждого респондента
        foreach ($this->responds as $respond) {

            $answer = new AnswersQuestionsConfirmGcp();
            $answer->question_id = $question_id;
            $answer->respond_id = $respond->id;
            $answer->save();

        }
    }


    public function deleteAnswerConfirmGcp ($question_id)
    {
        //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmGcp::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
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

        $allQuestions = AllQuestionsConfirmGcp::find()->orderBy(['id' => SORT_DESC])->all();
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

            return Html::a('Начать', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($data_responds == count($this->responds) && $data_interview != 0 && $data_interview == count($this->responds) && $data_interview_status >= $this->count_positive){

            return Html::a('Редактировать <span class="glyphicon glyphicon-pencil"></span>', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 20px 0 0']]) .
                Html::a('Завершить', ['/confirm-gcp/not-exist-confirm', 'id' => $this->id], [
                    'class' => 'btn btn-default finish_program',
                    'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0', 'display' => 'none'],
                    'data' => [
                        'confirm' => 'Ценностное предложение не подтверждено!<br>Вы действительно хотите завершить программу подтверждения ' . $this->gcp->title . ' ?',
                        'method' => 'post',
                    ],
                ]);

        }elseif (count($this->mvps) != 0){

            return Html::a('Редактировать <span class="glyphicon glyphicon-pencil"></span>', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn  btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive && $this->gcp->exist_confirm === null){

            return Html::a('Добавить', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-danger', 'style' => ['font-weight' => '700', 'margin' => '20px 10px 0 0']]) .
                Html::a('Завершить', ['/confirm-gcp/not-exist-confirm', 'id' => $this->id], [
                    'class' => 'btn btn-default finish_program',
                    'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0'],
                    'data' => [
                        'confirm' => 'Ценностное предложение не подтверждено!<br>Вы действительно хотите завершить программу подтверждения ' . $this->gcp->title . ' ?',
                        'method' => 'post',
                    ],
                ]);

        }elseif ($data_responds == count($this->responds) && $data_interview == count($this->responds) && $data_interview_status < $this->count_positive && $this->gcp->exist_confirm === 0){

            return Html::a('Добавить', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-danger', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

        }elseif ((count($this->mvps) == 0) && ($data_responds != count($this->responds) || $data_interview != count($this->responds))){

            return Html::a('Продолжить', ['/responds-gcp/index', 'id' => $this->id], ['id' => 'redirect_info_responds_table', 'class' => 'btn btn-default', 'style' => ['font-weight' => '700', 'margin' => '20px 0 0 0']]);

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

        if ($data_respond != 0 && count($this->responds) != $data_desc && empty($this->mvps) && $data_desc != 0){
            return '<span id="messageAboutTheNextStep" class="text-warning">Продолжите заполнение анкетных данных респондентов</span>';
        }

        if ($sumPositive < $this->count_positive && count($this->responds) == $data_desc){
            return '<span id="messageAboutTheNextStep" class="text-danger">Недостаточное количество респондентов, подтвердивших ценностное предложение</span>';
        }

        if ($this->count_positive <= $sumPositive && empty($this->mvps) && $data_desc == count($this->responds)){
            return '<span id="messageAboutTheNextStep" class="text-success">Переходите к генерации Minimum Viable Product</span>';
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
        $sumPositive = 0; //Кол-во респондентов подтверживших ценностное предложение
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

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->mvps) && $this->count_positive <= $count_positive)){
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


        if ($data_desc == 0 && empty($this->mvps)){

            return '<div class="text-center text-danger" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
        }

        if ($data_respond != 0 && count($this->responds) != $data_desc && $data_desc != 0 && empty($this->mvps)){

            return '<div class="text-center text-danger" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
        }

        if ($sumPositive < $this->count_positive && count($this->responds) == $data_desc && empty($this->mvps)){

            return '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>' .

                '<div class="text-center finish_program_success" style="padding: 50px 0; display: none;">' . Html::a('Завершить программу подтверждения ' . $this->gcp->title . ' и<br>перейти к генерации Minimum Viable Product', ['/confirm-gcp/exist-confirm', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';
        }


        if ($this->count_positive <= $sumPositive && $data_desc == count($this->responds) && $this->gcp->exist_confirm != 1 && empty($this->mvps)){

            if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return '<div class="text-center finish_program_success" style="padding: 50px 0;">' . Html::a('Завершить программу подтверждения ' . $this->gcp->title . ' и<br>перейти к генерации Minimum Viable Product', ['/confirm-gcp/exist-confirm', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>' .

                    '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px; display: none;">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';

            }else {

                return '<div class="text-center text-warning finish_program_success" style="padding: 50px 0;">Пользователь пока не завершил программу подтверждения ' . $this->gcp->title . '.</div>' .

                    '<div class="text-center text-danger not_next_step" style="padding: 50px 0; font-weight: 700; font-size: 16px; display: none">Данный этап пока не доступен, вернитесь на Шаг 2.</div>';
            }
        }


        if ($this->count_positive <= $sumPositive && empty($this->mvps) && $data_desc == count($this->responds) && $this->gcp->exist_confirm == 1){

            if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                return '<div class="text-center" style="padding: 50px 0;">' . Html::a('Переходите к генерации Minimum Viable Product', ['/mvp/index', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';

            }else {

                return '<div class="text-center text-warning" style="padding: 50px 0;">Пользователь пока не сгенерировал ни одной гипотезы Minimum Viable Product</div>';
            }
        }


        if (!empty($this->mvps)){

            return '<div class="text-center" style="padding: 50px 0;">' . Html::a('Переход на страницу гипотез Minimum Viable Product', ['/mvp/index', 'id' => $this->id], ['class' => 'btn btn-success', 'style' => ['font-weight' => '700', 'font-size' => '16px']]) . '</div>';
        }


    }
}
