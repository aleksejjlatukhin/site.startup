<?php

namespace app\models;

use Yii;


class ConfirmMvp extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_mvp';
    }

    public function getMvp()
    {
        return $this->hasOne(Mvp::class, ['id' => 'mvp_id']);
    }

    public function getFeedbacks()
    {
        return $this->hasMany(FeedbackExpertMvp::class, ['confirm_mvp_id' => 'id']);
    }

    public function getResponds()
    {
        return $this->hasMany(RespondsMvp::class, ['confirm_mvp_id' => 'id']);
    }

    public function getBusiness()
    {
        return $this->hasOne(BusinessModel::class, ['confirm_mvp_id' => 'id']);
    }

    public function getQuestions()
    {
        return $this->hasMany(QuestionsConfirmMvp::class, ['confirm_mvp_id' => 'id']);
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


    //Создание респондентов для программы подтверждения MVP из респондентов подтвердивших ГЦП
    public function createRespondConfirm ($responds)
    {
        foreach ($responds as $respond) {

            $respondConfirm = new RespondsMvp();
            $respondConfirm->confirm_mvp_id = $this->id;
            $respondConfirm->name = $respond->name;
            $respondConfirm->info_respond = $respond->info_respond;
            $respondConfirm->email = $respond->email;
            $respondConfirm->save();
        }
    }


    public function addQuestionDefault ($title)
    {
        $question = new QuestionsConfirmMvp();
        $question->confirm_mvp_id = $this->id;
        $question->title = $title;

        if ($question->save()) {
            $this->addAnswerConfirmMvp($question->id);
            $this->addQuestionToGeneralList($title);
        }
    }


    public function addQuestionToGeneralList($title)
    {
        $segment = $this->mvp->segment;
        $user = $this->mvp->project->user;

        //Добавляем вопрос в общую базу, если его там ещё нет
        $baseQuestions = AllQuestionsConfirmMvp::find()
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

            $general_question = new AllQuestionsConfirmMvp();
            $general_question->title = $title;
            $general_question->user_id = $user->id;
            $general_question->type_of_interaction_between_subjects = $segment->type_of_interaction_between_subjects;
            $general_question->field_of_activity = $segment->field_of_activity;
            $general_question->sort_of_activity = $segment->sort_of_activity;
            $general_question->specialization_of_activity = $segment->specialization_of_activity;
            $general_question->save();
        }
    }


    public function addAnswerConfirmMvp ($question_id)
    {
        //Создание пустого ответа для нового вопроса для каждого респондента
        foreach ($this->responds as $respond) {

            $answer = new AnswersQuestionsConfirmMvp();
            $answer->question_id = $question_id;
            $answer->respond_id = $respond->id;
            $answer->save();

        }
    }


    public function deleteAnswerConfirmMvp ($question_id)
    {
        //Удаление ответов по данному вопросу у всех респондентов данного подтверждения
        foreach ($this->responds as $respond) {
            $answer = AnswersQuestionsConfirmMvp::find()->where(['question_id' => $question_id, 'respond_id' => $respond->id])->one();
            $answer->delete();
        }
    }


    public function queryQuestionsGeneralList()
    {
        $segment = $this->mvp->segment;

        //Добавляем в массив $questions вопросы уже привязанные к данной программе
        $questions = [];
        foreach ($this->questions as $question) {
            $questions[] = $question['title'];
        }

        $allQuestions = AllQuestionsConfirmMvp::find()
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


    public function getCountRespondsOfModel()
    {
        //Кол-во респондентов, у кот-х заполнены данные
        $count = RespondsMvp::find()->where(['confirm_mvp_id' => $this->id])
            ->andWhere(['not', ['info_respond' => '']])->count();

        return $count;
    }


    public function getCountDescInterviewsOfModel()
    {
        // Кол-во респондентов, у кот-х существует анкета
        $count = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id])->andWhere(['not', ['desc_interview_mvp.id' => null]])->count();

        return $count;
    }


    public function getCountConfirmMembers()
    {
        // Кол-во подтвердивших MVP
        $count = RespondsMvp::find()->with('descInterview')
            ->leftJoin('desc_interview_mvp', '`desc_interview_mvp`.`responds_mvp_id` = `responds_mvp`.`id`')
            ->where(['confirm_mvp_id' => $this->id, 'desc_interview_mvp.status' => '1'])->count();

        return $count;
    }
}
