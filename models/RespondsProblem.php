<?php

namespace app\models;

use app\models\interfaces\RespondsInterface;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

/**
 * Класс хранит информацию о респодентах на этапе подтверждения гипотезы проблемы сегмента
 *
 * Class RespondsProblem
 * @package app\models
 *
 * @property int $id                                    Идентификатор записи в таб. responds_problem
 * @property int $confirm_id                            Идентификатор записи в таб. confirm_problem
 * @property string $name                               ФИО респондента
 * @property string $info_respond                       Данные респондента
 * @property string $email                              Эл.почта респондента
 * @property int $date_plan                             Плановая дата интервью
 * @property string $place_interview                    Место проведения интервью
 *
 * @property ConfirmProblem $confirm                    Подтверждение проблемы
 * @property InterviewConfirmProblem $interview         Информация о проведении интервью
 * @property AnswersQuestionsConfirmProblem[] $answers  Ответы на вопросы интервью
 */
class RespondsProblem extends ActiveRecord implements RespondsInterface
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'responds_problem';
    }


    /**
     * Получить интевью респондента
     *
     * @return ActiveQuery
     */
    public function getInterview(): ActiveQuery
    {
        return $this->hasOne(InterviewConfirmProblem::class, ['respond_id' => 'id']);
    }


    /**
     * Получить модель подтверждения
     *
     * @return ActiveQuery
     */
    public function getConfirm(): ActiveQuery
    {
        return $this->hasOne(ConfirmProblem::class, ['id' => 'confirm_id']);
    }


    /**
     * Получить ответы респондента на вопросы
     *
     * @return ActiveQuery
     */
    public function getAnswers(): ActiveQuery
    {
        return $this->hasMany(AnswersQuestionsConfirmProblem::class, ['respond_id' => 'id']);
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
        $confirm = ConfirmProblem::findOne($this->getConfirmId());
        $problem = Problems::findOne($confirm->getProblemId());
        $segment = Segments::findOne($problem->getSegmentId());
        $project = Projects::findOne($problem->getProjectId());
        $user = User::findOne($project->getUserId());

        //Удаление интервью респондента
        if (InterviewConfirmProblem::findOne(['respond_id' => $this->getId()])) {
            InterviewConfirmProblem::deleteAll(['respond_id' => $this->getId()]);
        }
        //Удаление ответов респондента на вопросы интервью
        if (AnswersQuestionsConfirmProblem::findAll(['respond_id' => $this->getId()])) {
            AnswersQuestionsConfirmProblem::deleteAll(['respond_id' => $this->getId()]);
        }
        //Удаление дирректории респондента
        $del_dir = UPLOAD.'/user-'.$user->getId().'/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/problems/problem-'.$problem->getId().'/interviews/respond-'.$this->getId();
        if (file_exists($del_dir)) {
            FileHelper::removeDirectory($del_dir);
        }
        //Удаление кэша для форм респондента
        $cachePathDelete = '../runtime/cache/forms/user-'.$user->getId().'/projects/project-'.$project->getId().'/segments/segment-'.$segment->getId().'/problems/problem-'.$problem->getId().'/confirm/interviews/respond-'.$this->getId();
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
