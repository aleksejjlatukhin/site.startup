<?php

namespace app\models\forms;

use app\models\ContractorTasks;
use yii\base\Model;

/**
 * Форма создания задания по гипотезе для исполнителя
 *
 * @property int $contractorId                 Идентификатор исполнителя
 * @property int $projectId                    Идентификатор проекта
 * @property int $activityId                   Идентификатор вида деятельности
 * @property int $type                         Тип задачи (связан с этапом проекта, по которому необходимо выполнить задание)
 * @property int $hypothesisId                 Идентификатор гипотезы (этапа проекта, по которому необходимо выполнить задание)
 * @property string $description               Описание задачи
 *
 * Class FormCreateTaskHypothesis
 * @package app\models\forms
 */
class FormCreateTaskHypothesis extends Model
{
    public $contractorId;
    public $projectId;
    public $activityId;
    public $type;
    public $hypothesisId;
    public $description;

    public function __construct(int $projectId, int $type, int $hypothesisId, $config = [])
    {
        $this->projectId = $projectId;
        $this->type = $type;
        $this->hypothesisId = $hypothesisId;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['contractorId', 'projectId', 'activityId', 'type', 'hypothesisId', 'description'], 'required'],
            [['contractorId', 'projectId', 'activityId', 'type', 'hypothesisId'], 'integer'],
            [['description'], 'string', 'max' => '2000'],

        ];
    }

    /**
     * @return ContractorTasks|null
     */
    public function create(): ?ContractorTasks
    {
        $model = new ContractorTasks();
        $model->setContractorId($this->getContractorId());
        $model->setProjectId($this->getProjectId());
        $model->setActivityId($this->getActivityId());
        $model->setType($this->getType());
        $model->setHypothesisId($this->getHypothesisId());
        $model->setDescription($this->getDescription());
        return $model->save() ? $model : null;
    }

    /**
     * @return int
     */
    public function getContractorId(): int
    {
        return $this->contractorId;
    }

    /**
     * @param int $contractorId
     */
    public function setContractorId(int $contractorId): void
    {
        $this->contractorId = $contractorId;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @return int
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }

    /**
     * @param int $activityId
     */
    public function setActivityId(int $activityId): void
    {
        $this->activityId = $activityId;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getHypothesisId(): int
    {
        return $this->hypothesisId;
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

}