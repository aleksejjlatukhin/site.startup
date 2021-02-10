<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

class RespondsMvp extends \yii\db\ActiveRecord
{

    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_mvp';
    }


    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewMvp::class, ['responds_mvp_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_mvp_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmMvp::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_mvp_id', 'name'], 'required'],
            [['confirm_mvp_id'], 'integer'],
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
        $questions = QuestionsConfirmMvp::find()->where(['confirm_mvp_id' => $this->confirm_mvp_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmMvp();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->mvp->project->touch('updated_at');
            $this->confirm->mvp->project->user->touch('updated_at');
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
        $descInterview = DescInterviewMvp::findOne(['responds_mvp_id' => $this->id]);
        $answers = AnswersQuestionsConfirmMvp::findAll(['respond_id' => $this->id]);
        $confirmMvp = ConfirmMvp::findOne(['id' => $this->confirm_mvp_id]);
        $mvp = Mvp::findOne(['id' => $confirmMvp->mvp_id]);
        $gcp = Gcp::findOne(['id' => $mvp->gcp_id]);
        $problem = GenerationProblem::findOne(['id' => $mvp->problem_id]);
        $segment = Segment::findOne(['id' => $mvp->segment_id]);
        $project = Projects::findOne(['id' => $mvp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        //Удаление интервью респондента
        if ($descInterview) $descInterview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.
            '/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/interviews/respond-'.$this->id;
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id. '/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/mvps/mvp-'.$mvp->id.'/confirm/interviews/respond-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
        //Обновление данных подтверждения
        $confirmMvp->count_respond = $confirmMvp->count_respond - 1;
        $confirmMvp->save();
    }

}
