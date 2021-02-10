<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

class RespondsGcp extends \yii\db\ActiveRecord
{

    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_gcp';
    }


    public function getDescInterview()
    {
        return $this->hasOne(DescInterviewGcp::class, ['responds_gcp_id' => 'id']);
    }

    public function getConfirm()
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_gcp_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmGcp::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_gcp_id', 'name',], 'required'],
            [['confirm_gcp_id'], 'integer'],
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
        $questions = QuestionsConfirmGcp::find()->where(['confirm_gcp_id' => $this->confirm_gcp_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmGcp();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->gcp->project->touch('updated_at');
            $this->confirm->gcp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->gcp->project->touch('updated_at');
            $this->confirm->gcp->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->gcp->project->touch('updated_at');
            $this->confirm->gcp->project->user->touch('updated_at');
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
        $descInterview = DescInterviewGcp::find()->where(['responds_gcp_id' => $this->id])->one();
        $answers = AnswersQuestionsConfirmGcp::find()->where(['respond_id' => $this->id])->all();
        $confirmGcp = ConfirmGcp::findOne($this->confirm_gcp_id);
        $gcp = Gcp::find()->where(['id' => $confirmGcp->gcp_id])->one();
        $problem = GenerationProblem::findOne(['id' => $gcp->problem_id]);
        $segment = Segment::findOne(['id' => $gcp->segment_id]);
        $project = Projects::findOne(['id' => $gcp->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        //Удаление интервью респондента
        if ($descInterview) $descInterview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/problems/problem-'.$problem->id.
            '/gcps/gcp-'.$gcp->id.'/interviews/respond-'.$this->id;
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.
            '/problems/problem-'.$problem->id.'/gcps/gcp-'.$gcp->id.'/confirm/interviews/respond-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
        //Обновление данных подтверждения
        $confirmGcp->count_respond = $confirmGcp->count_respond - 1;
        $confirmGcp->save();
    }

}
