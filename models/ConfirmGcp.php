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
        $segment = $this->gcp->segment;
        $user = $this->gcp->project->user;

        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestionsConfirmGcp::find()
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

            $general_question = new AllQuestionsConfirmGcp();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->type_of_interaction_between_subjects = $segment->type_of_interaction_between_subjects;
            $general_question->field_of_activity = $segment->field_of_activity;
            $general_question->sort_of_activity = $segment->sort_of_activity;
            $general_question->specialization_of_activity = $segment->specialization_of_activity;
            $general_question->save();
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
        $segment = $this->gcp->segment;

        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestionsConfirmGcp::find()
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

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->mvps))) {
            return true;
        }else {
            return false;
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


    public function getDataMembersOfGcp()
    {
        $sumPositive = 0; // Кол-во подтвердивших ЦП
        foreach ($this->responds as $respond){

            if ($respond->descInterview){
                if ($respond->descInterview->status == 1){
                    $sumPositive++;
                }
            }
        }

        return $sumPositive;
    }

}
