<?php

namespace app\models;

use app\models\interfaces\RespondsInterface;
use Throwable;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;

/**
 * Класс хранит информацию о респодентах на этапе подтверждения mvp-продукта
 *
 * Class RespondsMvp
 * @package app\models
 *
 * @property int $id                        Идентификатор записи в таб. responds_mvp
 * @property int $confirm_id                Идентификатор записи в таб. confirm_mvp
 * @property string $name                   ФИО респондента
 * @property string $info_respond           Данные респондента
 * @property string $email                  Эл.почта респондента
 * @property int $date_plan                 Плановая дата интервью
 * @property string $place_interview        Место проведения интервью
 */
class RespondsMvp extends ActiveRecord implements RespondsInterface
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds_mvp';
    }

    /**
     * Получить интевью респондента
     *
     * @return mixed|ActiveQuery
     */
    public function getInterview()
    {
        return $this->hasOne(InterviewConfirmMvp::class, ['respond_id' => 'id']);
    }

    /**
     * Получить модель подтверждения
     *
     * @return mixed|ActiveQuery
     */
    public function getConfirm()
    {
        return $this->hasOne(ConfirmMvp::class, ['id' => 'confirm_id']);
    }

    /**
     * @return ConfirmMvp|null
     */
    public function findConfirm()
    {
        return ConfirmMvp::findOne($this->getConfirmId());
    }

    /**
     * Получить ответы респондента на вопросы
     *
     * @return mixed|ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(AnswersQuestionsConfirmMvp::class, ['respond_id' => 'id']);
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
            [['confirm_id'], 'integer'],
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
        $descInterview = InterviewConfirmMvp::findOne(['respond_id' => $this->getId()]);
        $answers = AnswersQuestionsConfirmMvp::findAll(['respond_id' => $this->getId()]);
        $confirm = ConfirmMvp::findOne($this->getConfirmId());
        $mvp = Mvps::findOne($confirm->getMvpId());
        $gcp = Gcps::findOne($mvp->getGcpId());
        $problem = Problems::findOne($mvp->getProblemId());
        $segment = Segments::findOne($mvp->getSegmentId());
        $project = Projects::findOne($mvp->getProblem());
        $user = User::findOne($project->getUserId());

        //Удаление интервью респондента
        if ($descInterview) $descInterview->delete();
        //Удаление ответов респондента на вопросы интервью
        foreach ($answers as $answer) $answer->delete();
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->getId().'/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/problems/problem-'.$problem->getId().
            '/gcps/gcp-'.$gcp->getId().'/mvps/mvp-'.$mvp->getId().'/interviews/respond-'.$this->getId();
        if (file_exists($del_dir)) FileHelper::removeDirectory($del_dir);
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId(). '/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/mvps/mvp-'.$mvp->getId().'/confirm/interviews/respond-'.$this->getId();
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
