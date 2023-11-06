<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Класс, который хранит информацию о задачах поставленных исполнителям проектов
 *
 * Class ContractorTasks
 * @package app\models
 *
 * @property int $id                            Идентификатор задачи
 * @property int $contractor_id                 Идентификатор исполнителя
 * @property int $project_id                    Идентификатор проекта
 * @property int $activity_id                   Идентификатор вида деятельности
 * @property int $type                          Тип задачи (связан с этапом проекта, по которому необходимо выполнить задание)
 * @property int $status                        Статус задачи
 * @property int $hypothesis_id                 Идентификатор гипотезы (этапа проекта, по которому необходимо выполнить задание, т.е. например id проекта для создания сегментов, id подтверждения сегмента для подтверждения сегмента и т.д.)
 * @property string $description                Описание задачи
 * @property int $created_at                    Дата создания
 * @property int $updated_at                    Дата изменения
 *
 * @property User $contractor                   Объект исполнителя проекта
 * @property Projects $project                  Объект проекта
 * @property ContractorActivities $activity     Объект вида деятельности
 * @property Projects|ConfirmSegment|ConfirmProblem|ConfirmGcp|ConfirmMvp $hypothesis   Объект ссылки этапа проекта
 */
class ContractorTasks extends ActiveRecord
{
    public const TASK_STATUS_NEW = 12974543;
    public const TASK_STATUS_PROCESS = 4581456;
    public const TASK_STATUS_REJECTED = 9603574;
    public const TASK_STATUS_READY = 3863285;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'contractor_tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['contractor_id', 'project_id', 'type', 'hypothesis_id', 'description', 'activity_id'], 'required'],
            [['contractor_id', 'project_id', 'type', 'hypothesis_id', 'activity_id'], 'integer'],
            ['status', 'default', 'value' => self::TASK_STATUS_NEW],
            ['status', 'in', 'range' => [
                self::TASK_STATUS_NEW,
                self::TASK_STATUS_PROCESS,
                self::TASK_STATUS_REJECTED,
                self::TASK_STATUS_READY,
            ]],
            ['type', 'in', 'range' => [
                StageExpertise::SEGMENT,
                StageExpertise::CONFIRM_SEGMENT,
                StageExpertise::PROBLEM,
                StageExpertise::CONFIRM_PROBLEM,
                StageExpertise::GCP,
                StageExpertise::CONFIRM_GCP,
                StageExpertise::MVP,
                StageExpertise::CONFIRM_MVP,
            ]],
            [['description'], 'string', 'max' => '2000'],
        ];
    }

    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, function() {
            $this->sendCommunication();
        });
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * @return ActiveRecord|null
     */
    public function getHypothesis(): ?ActiveRecord
    {
        if ($this->getType() === StageExpertise::SEGMENT) {
            return Projects::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::CONFIRM_SEGMENT) {
            return ConfirmSegment::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::PROBLEM) {
            return ConfirmSegment::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::CONFIRM_PROBLEM) {
            return ConfirmProblem::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::GCP) {
            return ConfirmProblem::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::CONFIRM_GCP) {
            return ConfirmGcp::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::MVP) {
            return ConfirmGcp::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }
        if ($this->getType() === StageExpertise::CONFIRM_MVP) {
            return ConfirmMvp::find(false)
                ->andWhere(['id' => $this->getHypothesisId()])
                ->one();
        }

        return null;
    }


    /**
     * @return ActiveQuery
     */
    public function getContractor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'contractor_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getActivity(): ActiveQuery
    {
        return $this->hasOne(ContractorActivities::class, ['id' => 'activity_id']);
    }

    /**
     * @return ActiveRecord|null
     */
    public function getProject(): ?ActiveRecord
    {
        return Projects::find(false)
            ->andWhere(['id' => $this->getProjectId()])
            ->one();
    }

    /**
     * @return int|null
     */
    public function getTypeCommunication(): ?int
    {
        if ($this->getType() === StageExpertise::SEGMENT) {
            return ContractorCommunicationTypes::USER_APPOINTS_SEGMENT_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::CONFIRM_SEGMENT) {
            return ContractorCommunicationTypes::USER_APPOINTS_CONFIRM_SEGMENT_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::PROBLEM) {
            return ContractorCommunicationTypes::USER_APPOINTS_PROBLEM_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::CONFIRM_PROBLEM) {
            return ContractorCommunicationTypes::USER_APPOINTS_CONFIRM_PROBLEM_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::GCP) {
            return ContractorCommunicationTypes::USER_APPOINTS_GCP_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::CONFIRM_GCP) {
            return ContractorCommunicationTypes::USER_APPOINTS_CONFIRM_GCP_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::MVP) {
            return ContractorCommunicationTypes::USER_APPOINTS_MVP_TASK_CONTRACTOR;
        }
        if ($this->getType() === StageExpertise::CONFIRM_MVP) {
            return ContractorCommunicationTypes::USER_APPOINTS_CONFIRM_MVP_TASK_CONTRACTOR;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function sendCommunication(): bool
    {
        if (!$typeCommunication = $this->getTypeCommunication()) {
            return false;
        }

        $communication = new ContractorCommunications();
        $communication->setParams(
            $this->getContractorId(),
            $this->getProjectId(),
            $this->getActivityId(),
            $typeCommunication,
            $this->getType(),
            $this->getHypothesisId()
        );
        return $communication->save();
    }

    /**
     * Получить название этапа проекта
     *
     * @return string
     */
    public function getNameStage(): string
    {
        switch ($this->getType()) {
            case StageExpertise::SEGMENT:
                return 'генерация гипотезы целевого сегмента';
            case StageExpertise::CONFIRM_SEGMENT:
                return 'подтверждение гипотезы целевого сегмента';
            case StageExpertise::PROBLEM:
                return 'генерация гипотезы проблемы сегмента';
            case StageExpertise::CONFIRM_PROBLEM:
                return 'подтверждение гипотезы проблемы сегмента';
            case StageExpertise::GCP:
                return 'разработка гипотезы ценностного предложения';
            case StageExpertise::CONFIRM_GCP:
                return 'подтверждение гипотезы ценностного предложения';
            case StageExpertise::MVP:
                return 'разработка MVP';
            case StageExpertise::CONFIRM_MVP:
                return 'подтверждение MVP';
            default:
                return '';
        }
    }

    /**
     * @return string
     */
    public function getStageUrl(): string
    {
        switch ($this->getType()) {
            case StageExpertise::SEGMENT:
                return Url::to(['/segments/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_SEGMENT:
                return Url::to(['/confirm-segment/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::PROBLEM:
                return Url::to(['/problems/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_PROBLEM:
                return Url::to(['/confirm-problem/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::GCP:
                return Url::to(['/gcps/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_GCP:
                return Url::to(['/confirm-gcp/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::MVP:
                return Url::to(['/mvps/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_MVP:
                return Url::to(['/confirm-mvp/view', 'id' => $this->getHypothesisId()]);
            default:
                return '';
        }
    }

    /**
     * Получить ссылку на этап проекта
     *
     * @return string
     */
    public function getStageLink(): string
    {
        switch ($this->getType()) {
            case StageExpertise::SEGMENT:
                return Html::a('генерация гипотезы целевого сегмента', ['/segments/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_SEGMENT:
                return Html::a('подтверждение гипотезы целевого сегмента', ['/confirm-segment/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::PROBLEM:
                return Html::a('генерация гипотезы проблемы сегмента', ['/problems/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_PROBLEM:
                return Html::a('подтверждение гипотезы проблемы сегмента', ['/confirm-problem/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::GCP:
                return Html::a('разработка гипотезы ценностного предложения', ['/gcps/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_GCP:
                return Html::a('подтверждение гипотезы ценностного предложения', ['/confirm-gcp/view', 'id' => $this->getHypothesisId()]);
            case StageExpertise::MVP:
                return Html::a('разработка MVP', ['/mvps/index', 'id' => $this->getHypothesisId()]);
            case StageExpertise::CONFIRM_MVP:
                return Html::a('подтверждение MVP', ['/confirm-mvp/view', 'id' => $this->getHypothesisId()]);
            default:
                return '';
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
     * @return int
     */
    public function getContractorId(): int
    {
        return $this->contractor_id;
    }

    /**
     * @param int $contractor_id
     */
    public function setContractorId(int $contractor_id): void
    {
        $this->contractor_id = $contractor_id;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId(int $project_id): void
    {
        $this->project_id = $project_id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatusToString(): string
    {
        if ($this->status === self::TASK_STATUS_NEW) {
            return 'Новое';
        }
        if ($this->status === self::TASK_STATUS_PROCESS) {
            return 'В работе';
        }
        if ($this->status === self::TASK_STATUS_REJECTED) {
            return 'Отозвано';
        }
        if ($this->status === self::TASK_STATUS_READY) {
            return 'Готово';
        }

        return '';
    }

    /**
     * @return int
     */
    public function getHypothesisId(): int
    {
        return $this->hypothesis_id;
    }

    /**
     * @param int $hypothesis_id
     */
    public function setHypothesisId(int $hypothesis_id): void
    {
        $this->hypothesis_id = $hypothesis_id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updated_at;
    }

    /**
     * @return int
     */
    public function getActivityId(): int
    {
        return $this->activity_id;
    }

    /**
     * @param int $activity_id
     */
    public function setActivityId(int $activity_id): void
    {
        $this->activity_id = $activity_id;
    }
}