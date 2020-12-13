<?php

namespace app\models;

use Yii;
use yii\helpers\Html;


class ConfirmProblem extends \yii\db\ActiveRecord
{

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
            [['gps_id'], 'integer'],
            ['need_consumer', 'trim'],
            ['need_consumer', 'string', 'max' => 255],
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

            $respondConfirm = new RespondsConfirm();
            $respondConfirm->confirm_problem_id = $this->id;
            $respondConfirm->name = $respond->name;
            $respondConfirm->info_respond = $respond->info_respond;
            $respondConfirm->email = $respond->email;
            $respondConfirm->save();
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
        $segment = $this->problem->segment;
        $user = $this->problem->project->user;

        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestionsConfirmProblem::find()
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

            $general_question = new AllQuestionsConfirmProblem();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->type_of_interaction_between_subjects = $segment->type_of_interaction_between_subjects;
            $general_question->field_of_activity = $segment->field_of_activity;
            $general_question->sort_of_activity = $segment->sort_of_activity;
            $general_question->specialization_of_activity = $segment->specialization_of_activity;
            $general_question->save();
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


    /**
     * @param $question_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
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
        $segment = $this->problem->segment;

        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestionsConfirmProblem::find()
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

        $count_descInterview = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        $count_positive = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id, 'desc_interview_confirm.status' => '1'])->count();

        if ((count($this->responds) == $count_descInterview && $this->count_positive <= $count_positive) || (!empty($this->gcps))) {
            return true;
        }else {
            return false;
        }
    }


    public function getDataRespondsOfModel()
    {
        //Кол-во респондентов, у кот-х заполнены данные
        $count = RespondsConfirm::find()->where(['confirm_problem_id' => $this->id])
            ->andWhere(['not', ['info_respond' => '']])->count();

        return $count;
    }


    public function getDataDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id])->andWhere(['not', ['desc_interview_confirm.id' => null]])->count();

        return $count;
    }


    public function getDataMembersOfProblem()
    {
        //Кол-во респондентов, кот-е подтвердили проблему
        $count = RespondsConfirm::find()->with('descInterview')
            ->leftJoin('desc_interview_confirm', '`desc_interview_confirm`.`responds_confirm_id` = `responds_confirm`.`id`')
            ->where(['confirm_problem_id' => $this->id, 'desc_interview_confirm.status' => '1'])->count();

        return $count;
    }

}
