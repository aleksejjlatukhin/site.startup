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
     * @return array|ActiveRecord[]
     */
    public function getUserCommunications()
    {
        $communications = ProjectCommunications::find()
            ->where(['or', ['sender_id' => $this->user_id], ['adressee_id' => $this->user_id]])
            ->andWhere(['project_id' => $this->project_id]);
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
            ->where(['or', ['sender_id' => $this->user_id], ['adressee_id' => $this->user_id]])
            ->andWhere(['project_id' => $this->project_id])
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
        $this->user_id = $user_id;
        $this->project_id = $project_id;
        $this->communication_id = $communication->id;
        $this->communication_type = $communication->type;
        if ($this->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($communication->pattern_id) {
                $pattern = CommunicationPatterns::findOne($communication->pattern_id);
                $this->date_stop = time() + ($pattern->project_access_period * 24 * 60 * 60);
            } else {
                $this->date_stop = time() + (CommunicationPatterns::DEFAULT_USER_ACCESS_TO_PROJECT * 24 * 60 * 60);
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

}