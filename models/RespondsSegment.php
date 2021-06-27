<?php

namespace app\models;

use app\models\interfaces\RespondsInterface;
use Throwable;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

class RespondsSegment extends ActiveRecord implements RespondsInterface
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_segment';
    }


    /**
     * Получить модель подтверждения
     * @return mixed|ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmSegment::class, ['id' => 'confirm_id']);
    }


    /**
     * Получить интевью респондента
     * @return mixed|ActiveQuery
     */
    public function getInterview()
    {
        return $this->hasOne(InterviewConfirmSegment::class, ['respond_id' => 'id']);
    }


    /**
     * Получить ответы респондента на вопросы
     * @return mixed|ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmSegment::class, ['respond_id' => 'id']);
    }


    /**
     * Установить id подтверждения
     * @param $confirmId
     * @return mixed
     */
    public function setConfirmId($confirmId)
    {
        $this->confirm_id = $confirmId;
    }


    /**
     * Получить id подтверждения
     * @return mixed
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }


    /**
     * Установить имя респондента
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        return $this->name = $name;
    }


    /**
     * Получить имя респондента
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param array $params
     * @return mixed|void
     */
    public function setParams(array $params)
    {
        $this->info_respond = $params['info_respond'];
        $this->place_interview = $params['place_interview'];
        $this->email = $params['email'];
    }


    /**
     * Установить плановую дату интервью
     * @param $datePlan
     */
    public function setDatePlan($datePlan)
    {
        $this->date_plan = $datePlan;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirm_id', 'name'], 'required'],
            [['name', 'info_respond', 'place_interview', 'email'], 'trim'],
            [['confirm_id'], 'integer'],
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
            $this->deleteDataRespond();
        });

        parent::init();
    }


    /**
     * Удаление связанных данных
     * по событию EVENT_AFTER_DELETE
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    private function deleteDataRespond()
    {
        $interview = InterviewConfirmSegment::findOne(['respond_id' => $this->id]);
        $answers = AnswersQuestionsConfirmSegment::findAll(['respond_id' => $this->id]);
        $confirm = ConfirmSegment::findOne($this->confirmId);
        $segment = Segments::findOne($confirm->segmentId);
        $project = Projects::findOne($segment->projectId);
        $user = User::findOne($project->userId);

        //Удаление интервью респондента
        if ($interview) $interview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->id.'/project-'.$project->id.'/segments/segment-'.$segment->id.'/interviews/respond-'.$this->id;
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->id.'/projects/project-'.$project->id.'/segments/segment-'.$segment->id.'/confirm/interviews/respond-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
    }

}
