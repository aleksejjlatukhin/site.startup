<?php

namespace app\models;

use app\models\interfaces\RespondsInterface;
use Throwable;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

/**
 * Класс хранит информацию о респодентах на этапе подтверждения гипотезы сегмента
 *
 * Class RespondsSegment
 * @package app\models
 *
 * @property int $id                        Идентификатор записи в таб. responds_segment
 * @property int $confirm_id                Идентификатор записи в таб. confirm_segment
 * @property string $name                   ФИО респондента
 * @property string $info_respond           Данные респондента
 * @property string $email                  Эл.почта респондента
 * @property int $date_plan                 Плановая дата интервью
 * @property string $place_interview        Место проведения интервью
 */
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
     * @return ConfirmSegment|null
     */
    public function findConfirm()
    {
        return ConfirmSegment::findOne($this->getConfirmId());
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
     * @param array $params
     * @return mixed|void
     */
    public function setParams(array $params)
    {
        $this->setInfoRespond($params['info_respond']);
        $this->setPlaceInterview($params['place_interview']);
        $this->setEmail($params['email']);
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
     *
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    private function deleteDataRespond()
    {
        $interview = InterviewConfirmSegment::findOne(['respond_id' => $this->getId()]);
        $answers = AnswersQuestionsConfirmSegment::findAll(['respond_id' => $this->getId()]);
        $confirm = ConfirmSegment::findOne($this->getConfirmId());
        $segment = Segments::findOne($confirm->getSegmentId());
        $project = Projects::findOne($segment->getProjectId());
        $user = User::findOne($project->getUserId());

        //Удаление интервью респондента
        if ($interview) $interview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->getId().'/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/interviews/respond-'.$this->getId();
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/confirm/interviews/respond-'.$this->getId();
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $confirmId
     */
    public function setConfirmId($confirmId)
    {
        $this->confirm_id = $confirmId;
    }


    /**
     * @return int
     */
    public function getConfirmId()
    {
        return $this->confirm_id;
    }


    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getInfoRespond()
    {
        return $this->info_respond;
    }

    /**
     * @param string $info_respond
     */
    public function setInfoRespond($info_respond)
    {
        $this->info_respond = $info_respond;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getDatePlan()
    {
        return $this->date_plan;
    }

    /**
     * @param int $datePlan
     */
    public function setDatePlan($datePlan)
    {
        $this->date_plan = $datePlan;
    }

    /**
     * @return string
     */
    public function getPlaceInterview()
    {
        return $this->place_interview;
    }

    /**
     * @param string $place_interview
     */
    public function setPlaceInterview($place_interview)
    {
        $this->place_interview = $place_interview;
    }
}
