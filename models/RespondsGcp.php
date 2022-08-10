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
 * Класс хранит информацию о респодентах на этапе подтверждения гипотезы ценностного предложения
 *
 * Class RespondsGcp
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. responds_gcp
 * @property int $confirm_id                        Идентификатор записи в таб. confirm_gcp
 * @property string $name                           ФИО респондента
 * @property string $info_respond                   Данные респондента
 * @property string $email                          Эл.почта респондента
 * @property int $date_plan                         Плановая дата интервью
 * @property string $place_interview                Место проведения интервью
 *
 * @property ConfirmGcp $confirm                    Подтверждение гипотезы ценностного предложения
 * @property InterviewConfirmGcp $interview         Информация о проведении интервью
 * @property AnswersQuestionsConfirmGcp[] $answers  Ответы на вопросы интервью
 */
class RespondsGcp extends ActiveRecord implements RespondsInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'responds_gcp';
    }

    /**
     * Получить интевью респондента
     *
     * @return ActiveQuery
     */
    public function getInterview(): ActiveQuery
    {
        return $this->hasOne(InterviewConfirmGcp::class, ['respond_id' => 'id']);
    }

    /**
     * Получить модель подтверждения
     *
     * @return ActiveQuery
     */
    public function getConfirm(): ActiveQuery
    {
        return $this->hasOne(ConfirmGcp::class, ['id' => 'confirm_id']);
    }

    /**
     * Получить ответы респондента на вопросы
     *
     * @return ActiveQuery
     */
    public function getAnswers(): ActiveQuery
    {
        return $this->hasMany(AnswersQuestionsConfirmGcp::class, ['respond_id' => 'id']);
    }

    /**
     * @param array $params
     * @return void
     */
    public function setParams(array $params): void
    {
        $this->setInfoRespond($params['info_respond']);
        $this->setPlaceInterview($params['place_interview']);
        $this->setEmail($params['email']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['confirm_id', 'name',], 'required'],
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
    public function attributeLabels(): array
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
            $this->deleteDataRespond();
        });

        parent::init();
    }

    /**
     * @return void
     * @throws ErrorException
     */
    private function deleteDataRespond(): void
    {
        $confirm = ConfirmGcp::findOne($this->getConfirmId());
        $gcp = Gcps::findOne($confirm->getGcpId());
        $problem = Problems::findOne($gcp->getProblemId());
        $segment = Segments::findOne($gcp->getSegmentId());
        $project = Projects::findOne($gcp->getProjectId());
        $user = User::findOne($project->getUserId());

        //Удаление интервью респондента
        if (InterviewConfirmGcp::findOne(['respond_id' => $this->getId()])) {
            InterviewConfirmGcp::deleteAll(['respond_id' => $this->getId()]);
        }
        //Удаление ответов респондента на вопросы интервью
        if (AnswersQuestionsConfirmGcp::findAll(['respond_id' => $this->getId()])) {
            AnswersQuestionsConfirmGcp::deleteAll(['respond_id' => $this->getId()]);
        }
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->getId().'/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/problems/problem-'.$problem->getId().
            '/gcps/gcp-'.$gcp->getId().'/interviews/respond-'.$this->getId();
        if (file_exists($del_dir)) {
            FileHelper::removeDirectory($del_dir);
        }
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$segment->getId().
            '/problems/problem-'.$problem->getId().'/gcps/gcp-'.$gcp->getId().'/confirm/interviews/respond-'.$this->getId();
        if (file_exists($cachePathDelete)) {
            FileHelper::removeDirectory($cachePathDelete);
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $confirmId
     */
    public function setConfirmId(int $confirmId): void
    {
        $this->confirm_id = $confirmId;
    }

    /**
     * @return int
     */
    public function getConfirmId(): int
    {
        return $this->confirm_id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getInfoRespond(): string
    {
        return $this->info_respond;
    }

    /**
     * @param string $info_respond
     */
    public function setInfoRespond(string $info_respond): void
    {
        $this->info_respond = $info_respond;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int|null
     */
    public function getDatePlan(): ?int
    {
        return $this->date_plan;
    }

    /**
     * @param int $datePlan
     */
    public function setDatePlan(int $datePlan): void
    {
        $this->date_plan = $datePlan;
    }

    /**
     * @return string
     */
    public function getPlaceInterview(): string
    {
        return $this->place_interview;
    }

    /**
     * @param string $place_interview
     */
    public function setPlaceInterview(string $place_interview): void
    {
        $this->place_interview = $place_interview;
    }
}
