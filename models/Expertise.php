<?php


namespace app\models;


use app\models\forms\expertise\FormExpertiseManyAnswer;
use app\models\interfaces\ConfirmationInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс, который хранит данные о экспертизах, проведенные экспертами
 *
 * Class Expertise
 * @package app\models
 *
 * @property $id                    Идентификатор экспертизы
 * @property $stage                 Этап проекта от 0 до 9, по которому проведена экспертиза
 * @property $stage_id              Идентификатор этапа проекта, по которому проведена экспертиза
 * @property $expert_id             Идентификатор эксперта
 * @property $user_id               Идентификатор проектанта проекта, по которому проходит экспертиза
 * @property $type_expert           Тип деятельности эксперта, по которому была проведена экспертиза
 * @property $estimation            Оценка (оценки) выставленная экспертом
 * @property $comment               Комментарий(рекомендации) эксперта
 * @property $communication_id      Идентификатор коммуникации из таблицы project_communications, по которой был дан доступ к экспертизе
 * @property $completed             Параметр завершенности экспертизы, если экспертизы завершена, то её могут видеть другие пользователи. Если экспертиза завершена, то будут отправлены коммуникации (уведомления) проектанту и трекеру
 * @property $created_at            Время создания экспертизы
 * @property $updated_at            Время обновления экспертизы
 */
class Expertise extends ActiveRecord
{

    const COMPLETED = 1001;
    const NO_COMPLETED = 1010;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expertise';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Получить этап, на котором
     * проходит экспертиза
     *
     * @return int
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Установить этап, на котором
     * проходит экспертиза
     *
     * @param int $stage
     */
    public function setStage($stage)
    {
        $this->stage = $stage;
    }

    /**
     * Получить ID этапа, на котором
     * проходит экспертиза
     *
     * @return int
     */
    public function getStageId()
    {
        return $this->stage_id;
    }

    /**
     * Установить ID этапа, на котором
     * проходит экспертиза
     *
     * @param int $stageId
     */
    public function setStageId($stageId)
    {
        $this->stage_id = $stageId;
    }

    /**
     * Получить ID User эксперта
     *
     * @return int
     */
    public function getExpertId()
    {
        return $this->expert_id;
    }

    /**
     * Установить ID User эксперта
     *
     * @param int $expertId
     */
    public function setExpertId($expertId)
    {
        $this->expert_id = $expertId;
    }

    /**
     * Получить ID User проектанта
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Установить ID User проектанта
     *
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    /**
     * Получить тип экспертной деятельности
     *
     * @return int
     */
    public function getTypeExpert()
    {
        return $this->type_expert;
    }

    /**
     * Установить тип экспертной деятельности
     *
     * @param int $typeExpert
     */
    public function setTypeExpert($typeExpert)
    {
        $this->type_expert = $typeExpert;
    }

    /**
     * Получить оценку эксперта
     *
     * @return string
     */
    public function getEstimation()
    {
        return $this->estimation;
    }

    /**
     * Установить оценку эксперта
     *
     * @param $estimation
     */
    public function setEstimation($estimation)
    {
        $this->estimation = $estimation;
    }


    /**
     * Получить общее количество баллов по всем вопросам
     * по одной экспертизе одного эксперта
     *
     * @return int|string
     */
    public function getGeneralEstimationByOne()
    {
        $stageClass = StageExpertise::getClassByStage(StageExpertise::getList()[$this->stage]);
        $interfaces = class_implements($stageClass);
        if (!isset($interfaces[ConfirmationInterface::class])) {
            return $this->estimation;
        }
        return FormExpertiseManyAnswer::getGeneralEstimationByOne($this->estimation);
    }

    /**
     * Получить комментарий эксперта
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Установить уомментарий эксперта
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Получить ID коммуникации
     *
     * @return int
     */
    public function getCommunicationId()
    {
        return $this->communication_id;
    }

    /**
     * Установить ID коммуникации
     *
     * @param int $communicationId
     */
    public function setCommunicationId($communicationId)
    {
        $this->communication_id = $communicationId;
    }

    /**
     * Получить параметр
     * завершения экспертизы
     *
     * @return int
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Установить параметр
     * завершения экспертизы
     */
    public function setCompleted()
    {
        $this->completed = self::COMPLETED;
    }


    /**
     * Получение объекта коммуникации, по которой была назначена экспертиза
     * @return ProjectCommunications|null
     */
    public function findProjectCommunication()
    {
        return ProjectCommunications::findOne($this->communication_id);
    }


    /**
     * Получение объекта проектанта
     * @return User|null
     */
    public function findUser()
    {
        return User::findOne($this->user_id);
    }


    /**
     * Получение объекта эксперта
     * @return User|null
     */
    public function findExpert()
    {
        return User::findOne($this->expert_id);
    }


    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getExpert()
    {
        return $this->hasOne(User::class, ['id' => 'expert_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stage', 'stage_id', 'expert_id', 'user_id', 'type_expert', 'estimation', 'comment', 'communication_id'], 'required'],
            [['stage', 'stage_id', 'expert_id', 'user_id', 'type_expert', 'communication_id', 'created_at', 'updated_at'], 'integer'],
            ['stage', 'in', 'range' => array_keys(StageExpertise::getList())],
            [['estimation'], 'string'],
            [['comment'], 'string', 'max' => 2000],
            ['completed', 'default', 'value' => self::NO_COMPLETED],
            ['completed', 'in', 'range' => [
                self::NO_COMPLETED,
                self::COMPLETED
            ]],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * Проверка на наличие и завершенность экспертизы
     *
     * @param $stage
     * @param $stageId
     * @param $type
     * @param $expertId
     * @return int|null
     */
    public static function checkExistAndCheckCompleted($stage, $stageId, $type, $expertId)
    {
        $expertise = self::findOne([
            'stage' => $stage,
            'stage_id' => $stageId,
            'type_expert' => $type,
            'expert_id' => $expertId
        ]);

        return $expertise ? $expertise->completed : null;
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->completed == self::COMPLETED) {
            // Отправка уведомления о завершении экспетизы при ее создании
            $this->sendCommunications();
        } elseif (!$insert) {
            if (isset($changedAttributes['completed']) && $changedAttributes['completed'] != $this->completed && $this->completed == self::COMPLETED) {
                // Отправка уведомления о завершении экспетизы при ее обновлении
                $this->sendCommunications();
            } elseif (!isset($changedAttributes['completed']) && ((isset($changedAttributes['estimation']) || isset($changedAttributes['comment'])) || isset($changedAttributes['estimation']) && isset($changedAttributes['comment'])) && $this->completed == self::COMPLETED) {
                // Отправка уведомления об обновлении данных ранее завершенной экспертизы
                $this->sendCommunications(true);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * Сохранение экспертизы
     *
     * @return bool
     */
    public function saveRecord()
    {
        return ($this->validate() && $this->save()) ? true : false;
    }


    /**
     * Отправка коммуникаций по экспертизе трекеру и проектанту
     *
     * @param bool $update
     */
    private function sendCommunications($update = false)
    {
        $communication = $this->findProjectCommunication();
        if (!$update) {
            // Отправить коммуникацию о завершении экспертизы
            DuplicateCommunications::create($communication, $this->findUser(), TypesDuplicateCommunication::EXPERT_COMPLETED_EXPERTISE, $this);
            DuplicateCommunications::create($communication, $this->findUser()->admin, TypesDuplicateCommunication::EXPERT_COMPLETED_EXPERTISE, $this);
        }else {
            // Отправить коммуникацию об обновлении данных ранее завершенной экспертизы
            DuplicateCommunications::create($communication, $this->findUser(), TypesDuplicateCommunication::EXPERT_UPDATE_DATA_COMPLETED_EXPERTISE, $this);
            DuplicateCommunications::create($communication, $this->findUser()->admin, TypesDuplicateCommunication::EXPERT_UPDATE_DATA_COMPLETED_EXPERTISE, $this);
        }
    }

}