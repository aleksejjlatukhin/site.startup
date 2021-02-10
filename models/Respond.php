<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;

class Respond extends \yii\db\ActiveRecord
{
    const LIMIT_COUNT = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    public function getConfirm()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }

    public function getDescInterview()
    {
        return $this->hasOne(DescInterview::class, ['respond_id' => 'id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmSegment::class, ['respond_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interview_id', 'name'], 'required'],
            [['name', 'info_respond', 'place_interview', 'email'], 'trim'],
            [['interview_id'], 'integer'],
            [['date_plan'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['info_respond', 'place_interview', 'email'], 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Неверный формат адреса электронной почты'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Фамилия, имя, отчество',
            'info_respond' => 'Данные респондента',
            'email' => 'E-mail',
            'date_plan' => 'Плановая дата интервью',
            'place_interview' => 'Место проведения интервью',
        ];
    }


    /**
     *
     */
    public function addAnswersForNewRespond()
    {
        $questions = QuestionsConfirmSegment::find()->where(['interview_id' => $this->interview_id])->all();

        foreach ($questions as $question){

            $answer = new AnswersQuestionsConfirmSegment();
            $answer->question_id = $question->id;
            $answer->respond_id = $this->id;
            $answer->save();
        }
    }


    public function init()
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->confirm->segment->project->touch('updated_at');
            $this->confirm->segment->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->confirm->segment->project->touch('updated_at');
            $this->confirm->segment->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->confirm->segment->project->touch('updated_at');
            $this->confirm->segment->project->user->touch('updated_at');
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
        $descInterview = DescInterview::findOne(['respond_id' => $this->id]);
        $answers = AnswersQuestionsConfirmSegment::findAll(['respond_id' => $this->id]);
        $interview = Interview::findOne(['id' => $this->interview_id]);
        $segment = Segment::findOne(['id' => $interview->segment_id]);
        $project = Projects::findOne(['id' => $segment->project_id]);
        $user = User::findOne(['id' => $project->user_id]);

        //Удаление интервью респондента
        if ($descInterview) $descInterview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/interviews/respond-'.$this->id;
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/interviews/respond-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
        //Обновление данных подтверждения
        $interview->count_respond = $interview->count_respond - 1;
        $interview->save();
    }
}
