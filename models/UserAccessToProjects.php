<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Класс для записи истории
 * доступа экспертов к проектам
 *
 * Class UserAccessToProjects
 * @package app\models
 *
 * @property int $id                            Идентификтор записи
 * @property int $user_id                       Идентификтор эксперта
 * @property int $project_id                    Идентификтор проекта
 * @property int $communication_id              Идентификтор коммуникации в таб. project_communications
 * @property int $communication_type            Тип коммуникации
 * @property int $cancel                        Флаг, указывает на отмену коммуникации
 * @property int $date_stop                     Дата окончания доступа к проекту
 * @property int $created_at                    Дата создания записи
 * @property int $updated_at                    Дата редактирования записи
 */
class UserAccessToProjects extends ActiveRecord
{

    const CANCEL_TRUE = 111;
    const CANCEL_FALSE = 222;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user_access_to_projects';
    }


    /**
     * Получить объект коммуникации
     * по которой был предоставлен
     * доступ к проекту
     *
     * @return ActiveQuery
     */
    public function getCommunication()
    {
        return $this->hasOne(ProjectCommunications::class, ['id' => 'communication_id']);
    }


    /**
     * Получить все коммуникации
     * пользователя по проекту
     *
     * @return array|ActiveRecord[]
     */
    public function getUserCommunications()
    {
        $communications = ProjectCommunications::find()
            ->where(['or', ['sender_id' => $this->getUserId()], ['adressee_id' => $this->getUserId()]])
            ->andWhere(['project_id' => $this->getProjectId()]);
         return $communications->all();
    }


    /**
     * Получить коммуникации пользователя
     * для таблицы админа
     *
     * @return array|ActiveRecord[]
     */
    public function getUserCommunicationsForAdminTable()
    {
        $communications = ProjectCommunications::find()
            ->where(['or', ['sender_id' => $this->getUserId()], ['adressee_id' => $this->getUserId()]])
            ->andWhere(['project_id' => $this->getProjectId()])
            ->andWhere(['not in', 'type', [
                CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT,
                CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
                CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT
            ]]);
        return $communications->all();
    }


    /**
     * Получить объект пользователя,
     * которому был предоставлен
     * доступ к проекту
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * Получить объект проекта
     * по которому был предоставлен доступ
     *
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id', 'communication_id', 'communication_type', 'cancel', 'date_stop', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'project_id', 'communication_id', 'communication_type'], 'required'],
            ['cancel', 'default', 'value' => self::CANCEL_FALSE],
            ['cancel', 'in', 'range' => [
                self::CANCEL_FALSE,
                self::CANCEL_TRUE
            ]],
        ];
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    /**
     * @param int $user_id
     * @param int $project_id
     * @param ProjectCommunications $communication
     */
    public function setParams ($user_id, $project_id, $communication)
    {
        $this->setUserId($user_id);
        $this->setProjectId($project_id);
        $this->setCommunicationId($communication->getId());
        $this->setCommunicationType($communication->getType());
        if ($this->getCommunicationType() == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($communication->getPatternId()) {
                $pattern = CommunicationPatterns::findOne($communication->getPatternId());
                $this->setDateStop(time() + ($pattern->getProjectAccessPeriod() * 24 * 60 * 60));
            } else {
                $this->setDateStop(time() + (CommunicationPatterns::DEFAULT_USER_ACCESS_TO_PROJECT * 24 * 60 * 60));
            }
        }
    }


    /**
     * Установить парметр аннулированного
     * доступа к проекту
     */
    public function setCancel()
    {
        $this->cancel = self::CANCEL_TRUE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * @param int $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return int
     */
    public function getCommunicationId()
    {
        return $this->communication_id;
    }

    /**
     * @param int $communication_id
     */
    public function setCommunicationId($communication_id)
    {
        $this->communication_id = $communication_id;
    }

    /**
     * @return int
     */
    public function getCommunicationType()
    {
        return $this->communication_type;
    }

    /**
     * @param int $communication_type
     */
    public function setCommunicationType($communication_type)
    {
        $this->communication_type = $communication_type;
    }

    /**
     * @return int
     */
    public function getCancel()
    {
        return $this->cancel;
    }

    /**
     * @return int
     */
    public function getDateStop()
    {
        return $this->date_stop;
    }

    /**
     * @param int $date_stop
     */
    public function setDateStop($date_stop)
    {
        $this->date_stop = $date_stop;
    }

    /**
     * @return int
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}