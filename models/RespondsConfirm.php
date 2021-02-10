<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

class RespondsConfirm extends \yii\db\ActiveRecord
{
    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_confirm';
    }

    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewConfirm::class, ['responds_confirm_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_problem_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmProblem::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_problem_id', 'name'], 'required'],
            [['confirm_problem_id'], 'integer'],
            [['date_plan'], 'integer'],
            [['name', 'info_respond', 'email', 'place_interview'], 'trim'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'email', 'place_interview'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Респондент',
            'info_respond' => 'Данные респондента',
            'email' => 'E-mail',
            'date_plan' => 'Плановая дата интервью',
            'place_interview' => 'Место проведения интервью',
        ];
    }


    public function addAnswersForNewRespond()
    {
        $questions = QuestionsConfirmProblem::find()->where(['confirm_problem_id' => $this->confirm_problem_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmProblem();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->problem->project->touch('updated_at');
            $this->confirm->problem->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_BEFORE_DELETE, function (){
            $this->deleteDataRespond();
        });

        parent::init();
    }


    /**
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
     */
    private function deleteDataRespond()
    {
        $descInterview = DescInterviewConfirm::findOne(['responds_confirm_id' => $this->id]);
        $answers = AnswersQuestionsConfirmProblem::findAll(['respond_id' => $this->id]);
        $confirmProblem = ConfirmProblem::findOne(['id' => $this->confirm_problem_id]);
        $problem = GenerationProblem::findOne(['id' => $confirmProblem->gps_id]);
        $segment = Segment::findOne(['id' => $problem->segment_id]);
        $project = Projects::findOne(['id' => $problem->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        //Удаление интервью респондента
        if ($descInterview) $descInterview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/interviews/respond-'.$this->id;
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.'/confirm/interviews/respond-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
        //Обновление данных подтверждения
        $confirmProblem->count_respond = $confirmProblem->count_respond - 1;
        $confirmProblem->save();
    }

}
