<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class CommunicationPatterns extends ActiveRecord
{

    const ACTIVE = 123;
    const NO_ACTIVE = 321;
    const NOT_REMOTE = 0;
    const REMOTE = 1;

    const COMMUNICATION_DEFAULT_ABOUT_READINESS_CONDUCT_EXPERTISE = 'Вы готовы провести экспертизу по проекту {{наименование проекта, ссылка на проект}} ? Для предварительной оценки Вам открыт доступ к проекту на 14 дней.';
    const DEFAULT_USER_ACCESS_TO_PROJECT = 14;
    const COMMUNICATION_DEFAULT_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE = 'Произошли изменения в проекте {{наименование проекта}}. Приносим Вам свои извинения, запрос на экспертизу отозван.';
    const COMMUNICATION_DEFAULT_APPOINTS_EXPERT_PROJECT = 'Вы назначены на экспертизу по проекту {{наименование проекта, ссылка на проект}} по типам деятельности: {{список типов деятельности эксперта}}. Приступайте к экспертизе на этапе описания проекта. Внимание! В работе эксперта есть ограничение по времени, не более 7 дней для выставления экспертной оценки после уведомления о необходимости провести экспертизу для той или иной сущности на этапе проекта.';
    const COMMUNICATION_DEFAULT_DOES_NOT_APPOINTS_EXPERT_PROJECT = 'Вы не назначены на экспертизу по проекту {{наименование проекта}}. Приносим Вам свои извинения, запрос на экспертизу отозван.';
    const COMMUNICATION_DEFAULT_WITHDRAWS_EXPERT_FROM_PROJECT = 'Вы отозваны с экспертизы по проекту {{наименование проекта}}. Подробную информацию получите у администратора сайта Spaccel.ru';


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'communication_patterns';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['communication_type', 'initiator', 'is_active', 'project_access_period', 'created_at', 'updated_at', 'is_remote'], 'integer'],
            [['description', 'communication_type', 'initiator'], 'required'],
            [['description'], 'string', 'max' => 255],
            [['description'], 'trim'],
            ['communication_type', 'in', 'range' => CommunicationTypes::getListTypes()],
            ['is_active', 'default', 'value' => self::NO_ACTIVE],
            ['is_active', 'in', 'range' => [
                self::NO_ACTIVE,
                self::ACTIVE
            ]],
            ['is_remote', 'default', 'value' => self::NOT_REMOTE],
            ['is_remote', 'in', 'range' => [
                self::NOT_REMOTE,
                self::REMOTE
            ]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Описание шаблона коммуникации',
            'project_access_period' => 'Срок доступа к проекту'
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
     * @param int $communication_type
     */
    public function setParams($communication_type)
    {
        $this->communication_type = $communication_type;
        $this->initiator = Yii::$app->user->id;
    }
}