<?php


namespace app\models;


use app\models\interfaces\CommunicationsInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\Html;


/**
 * Класс коммуникаций с экспертом
 * для доступа к проекту
 *
 * Class ProjectCommunications
 * @package app\models
 *
 * @property int $id                                Идентификатор коммуникации
 * @property int $sender_id                         Идентификатор отправителя коммуникации из таб.User
 * @property int $adressee_id                       Идентификатор получателя коммуникации из таб.User
 * @property int $type                              Тип коммуникации
 * @property int $project_id                        Идентификатор проекта, по которому отправлена коммуникация
 * @property int $status                            Статус коммуникации
 * @property int $pattern_id                        Идентификатор шаблона коммуникации
 * @property int $triggered_communication_id        Идентификатор коммуникации в ответ, на которую была создана данная коммуникация
 * @property int $cancel                            Параметр аннулирования коммуникации
 * @property int $created_at                        Дата создания коммуникации
 * @property int $updated_at                        Дата обновления коммуникации
 */
class ProjectCommunications extends ActiveRecord implements CommunicationsInterface
{

    const CANCEL_TRUE = 1111;
    const CANCEL_FALSE = 2222;
    const READ = 1000;
    const NO_READ = 500;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'project_communications';
    }


    /**
     * Получить объект доступа пользователя
     * к проекту по коммуникации
     *
     * @return ActiveQuery
     */
    public function getUserAccessToProject()
    {
        return $this->hasOne(UserAccessToProjects::class, ['communication_id' => 'id']);
    }


    /**
     * Получить объект
     * ответа по коммуникации
     *
     * @return ActiveQuery
     */
    public function getCommunicationResponse()
    {
        return $this->hasOne(CommunicationResponse::class, ['communication_id' => 'id']);
    }


    /**
     * Получить объект ответной коммуникации,
     * т.е. обращение от коммуникации на которую ответили,
     * а запрос на поиск коммуникации, которой ответили
     *
     * @return ProjectCommunications|null
     */
    public function getResponsiveCommunication()
    {
        return self::findOne(['triggered_communication_id' => $this->id]);
    }


    /**
     * Получить коммуникацию на которую,
     * была создана ответная коммуникация,
     * запрос выполняется от ответной коммуникации
     *
     * @return ProjectCommunications|null
     */
    public function getCommunicationAnswered()
    {
        return self::findOne(['id' => $this->triggered_communication_id]);
    }


    /**
     * Получить объект
     * эксперта
     *
     * @return User|null
     */
    public function getExpert()
    {
        if ($this->type == CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $expert = User::findOne($this->sender_id);
        } else {
            $expert = User::findOne($this->adressee_id);
        }
        return $expert;
    }


    /**
     * Получить объект проекта,
     * по которому создана коммуникация
     *
     * @return ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Поиск проекта, по которому создана коммуникация
     *
     * @return Projects|null
     */
    public function findProject()
    {
        return Projects::findOne($this->project_id);
    }


    /**
     * Получить объект
     * шаблона коммуникации
     *
     * @return ActiveQuery
     */
    public function getPattern()
    {
        return $this->hasOne(CommunicationPatterns::class, ['id' => 'pattern_id']);
    }


    /**
     * Получить объект содержащий
     * типы экспертиз назначенных
     * эксперту по данной коммуникации
     * @return ActiveQuery
     */
    public function getTypesAccessToExpertise()
    {
        return $this->hasOne(TypesAccessToExpertise::class, ['communication_id' => 'id']);
    }

    /**
     * Найти объект содержащий
     * типы экспертиз назначенных
     * эксперту по данной коммуникации
     * @return TypesAccessToExpertise|null
     */
    public function findTypesAccessToExpertise()
    {
        return TypesAccessToExpertise::findOne(['communication_id' => $this->getId()]);
    }


    /**
     * Получить описание
     * шаблона коммуникации
     *
     * @param bool $isSendEmail
     * @return string
     */
    public function getDescriptionPattern($isSendEmail = false)
    {
        $projectName_search = '{{наименование проекта}}';
        $projectName_replace = '«' . $this->project->project_name . '»';
        $linkProjectName_search = '{{наименование проекта, ссылка на проект}}';
        $linkProjectName_replace = Html::a($projectName_replace, ['/projects/index', 'id' => $this->project->user_id, 'project_id' => $this->project->id]);
        $linkProjectName_replace = $isSendEmail ? Html::a($projectName_replace, Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $this->project->user_id, 'project_id' => $this->project->id])) : $linkProjectName_replace;
        $pattern = $this->pattern;

        if ($pattern) {

            $description = $pattern->description;

            if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
                return str_replace($linkProjectName_search, $linkProjectName_replace, $description);
            } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
                return str_replace($projectName_search, $projectName_replace, $description);
            } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
                $typesAccessToExpertise_search = '{{список типов деятельности эксперта}}';
                $typesAccessToExpertise_replace = ExpertType::getContent($this->findTypesAccessToExpertise()->types);
                return str_replace($linkProjectName_search, $linkProjectName_replace, str_replace($typesAccessToExpertise_search, $typesAccessToExpertise_replace, $description));
            } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
                return str_replace($projectName_search, $projectName_replace, $description);
            } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
                return str_replace($projectName_search, $projectName_replace, $description);
            }

        } else {

            return $this->getDefaultPattern($isSendEmail);
        }
    }


    /**
     * Полушить шаблон
     * коммуникации по умолчанию
     *
     * @param bool $isSendEmail
     * @return string
     */
    public function getDefaultPattern($isSendEmail = false)
    {
        $projectName_search = '{{наименование проекта}}';
        $projectName_replace = '«' . $this->project->project_name . '»';
        $linkProjectName_search = '{{наименование проекта, ссылка на проект}}';
        $linkProjectName_replace = Html::a($projectName_replace, ['/projects/index', 'id' => $this->project->user_id, 'project_id' => $this->project->id]);
        $linkProjectName_replace = $isSendEmail ? Html::a($projectName_replace, Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $this->project->user_id, 'project_id' => $this->project->id])) : $linkProjectName_replace;

        if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_ABOUT_READINESS_CONDUCT_EXPERTISE;
            return str_replace($linkProjectName_search, $linkProjectName_replace, $defaultPattern);
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            $typesAccessToExpertise_search = '{{список типов деятельности эксперта}}';
            $typesAccessToExpertise_replace = ExpertType::getContent($this->findTypesAccessToExpertise()->types);
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_APPOINTS_EXPERT_PROJECT;
            return str_replace($linkProjectName_search, $linkProjectName_replace, str_replace($typesAccessToExpertise_search, $typesAccessToExpertise_replace, $defaultPattern));
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_DOES_NOT_APPOINTS_EXPERT_PROJECT;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_WITHDRAWS_EXPERT_FROM_PROJECT;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        } else {
            return '';
        }
    }


    /**
     * Получить статус
     * доступа к проекту
     *
     * @return string
     */
    public function getAccessStatus()
    {
        if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->userAccessToProject->cancel == UserAccessToProjects::CANCEL_TRUE) {
                return '<div class="text-danger">Закрыт</div>';
            } else {
                return '<div class="text-success">Открыт до ' . date('d.m.Y H:i', $this->userAccessToProject->date_stop) . '</div>';
            }
        } elseif (in_array($this->type, [CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE,
            CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT, CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT])) {
            return '<div class="text-danger">Закрыт</div>';
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            if ($this->userAccessToProject->cancel == UserAccessToProjects::CANCEL_TRUE) {
                return '<div class="text-danger">Закрыт</div>';
            } else {
                return '<div class="text-success">Бессрочный</div>';
            }
        } else {
            return '';
        }
    }


    /**
     * Получить тип (статус)
     * уведомления для эксперта
     *
     * @return string
     */
    public function getNotificationStatus()
    {
        if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->cancel == self::CANCEL_TRUE) {
                return '<div class="text-success">Запрос о готовности провести экспертизу</div>';
            } elseif ($this->status == self::READ) {
                return '<div class="text-success">Ответ получен</div>';
            } elseif ($this->status == self::NO_READ && time() < $this->userAccessToProject->date_stop) {
                return '<div class="text-warning">Требуется ответ</div>';
            } elseif ($this->status == self::NO_READ && time() > $this->userAccessToProject->date_stop) {
                return '<div class="text-danger">Просрочена дата ответа</div>';
            }
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            return '<div class="text-danger">Запрос отозван</div>';
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            return '<div class="text-success">Назначен(-а) на проект</div>';
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
            return '<div class="text-danger">Отказано в назначении на проект</div>';
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
            return '<div class="text-danger">Отозван(-а) с проекта</div>';
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'adressee_id', 'type', 'project_id', 'status', 'pattern_id', 'triggered_communication_id', 'cancel', 'created_at', 'updated_at'], 'integer'],
            [['sender_id', 'adressee_id', 'type', 'project_id'], 'required'],
            ['status', 'default', 'value' => self::NO_READ],
            ['status', 'in', 'range' => [
                self::READ,
                self::NO_READ
            ]],
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
     * Проверка на необходимость спросить эксперта
     * (о готовности провести экспертизу)
     *
     * @param int $expert_id
     * @param int $project_id
     * @return bool
     */
    public static function isNeedAskExpert($expert_id, $project_id)
    {
        $communications = self::find()->where(['project_id' => $project_id])->andWhere(['or', ['adressee_id' => $expert_id], ['sender_id' => $expert_id]]);
        $existCommunications = $communications->all();

        if (!$existCommunications) {
            // Если у эксперта ещё не было
            // коммуникаций по данному проекту
            return true;

        } else {

            $lastCommunication = $communications->orderBy('id DESC')->one();

            if (in_array($lastCommunication->type, [
                CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE,
                CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
                CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT
            ])) {
                // Если у эксперта последняя коммуникая по проекту соответствует типам
                // "отмена запроса", "отказано в назначении на проект", "отозван с проекта"
                return true;

            } elseif ($lastCommunication->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE
                && $lastCommunication->userAccessToProject->date_stop < time()) {
                // Последняя коммуникация эксперта по проекту "запрос эксперту о готовности
                // провести экспертизу" и время доступа к проекту закончилось
                return true;
            }
            elseif ($lastCommunication->type == CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE
                && $lastCommunication->communicationResponse->answer == CommunicationResponse::NEGATIVE_RESPONSE) {
                // Последняя коммуникая, отрицательный ответ эксперта на запрос о готовности провести экспертизу
                return true;
            }
            return false;
        }
    }


    /**
     * Проверка доступа к проведению экспертизы
     *
     * @param $expert_id
     * @param $project_id
     * @return bool
     */
    public static function checkOfAccessToCarryingExpertise($expert_id, $project_id)
    {
        $communications = self::find()->where(['project_id' => $project_id])->andWhere(['or', ['adressee_id' => $expert_id], ['sender_id' => $expert_id]]);
        $existCommunications = $communications->all();

        if (!$existCommunications) {
            // Если у эксперта ещё не было
            // коммуникаций по данному проекту
            return false;

        } else {
            $lastCommunication = $communications->orderBy('id DESC')->one();
            if ($lastCommunication->type == CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
                return true;
            }
            return false;
        }
    }


    /**
     * Показывать ли эксперту кнопку
     * ответа на коммуникацию
     *
     * @return bool
     */
    public function isNeedShowButtonAnswer()
    {
        if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE &&
            $this->cancel == self::CANCEL_FALSE && $this->status == self::NO_READ &&
            time() < $this->userAccessToProject->date_stop) {
            return true;
        }
        return false;
    }


    /**
     * Показывать ли кнопку
     * прочтения уведомления
     *
     * @return bool
     */
    public function isNeedReadButton()
    {
        if ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->status == self::NO_READ and time() > $this->userAccessToProject->date_stop) {
                return true;
            } elseif ($this->status == self::NO_READ and $this->cancel == self::CANCEL_TRUE) {
                return true;
            }
        } elseif (!in_array($this->type, [CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE, CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE])) {
            if ($this->status == self::NO_READ) {
                return true;
            }
        } elseif ($this->type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE &&
            ($this->cancel == self::CANCEL_TRUE && $this->status == self::NO_READ || time() > $this->userAccessToProject->date_stop)) {
            return true;
        }
        return false;
    }


    /**
     * @param int $adressee_id
     * @param int $project_id
     * @param int $type
     */
    public function setParams($adressee_id, $project_id, $type)
    {
        $this->setSenderId(Yii::$app->user->id);
        $this->setAdresseeId($adressee_id);
        $this->setProjectId($project_id);
        $this->setType($type);

        $pattern = CommunicationPatterns::find()
            ->select(['id'])
            ->where([
            'communication_type' => $type,
            'is_active' => CommunicationPatterns::ACTIVE,
            'is_remote' => CommunicationPatterns::NOT_REMOTE])
            ->one();

        if ($pattern) {
            $this->setPatternId($pattern->id);
        }
    }


    /**
     * Установка id коммуникации, которая была триггером
     * для создания данной коммуникации, т.е. данная коммуникация
     * является ответом для триггерной коммуникации
     *
     * @param $id
     */
    public function setTriggeredCommunicationId($id)
    {
        $this->triggered_communication_id = $id;
    }

    /**
     * Установить параметр
     * аннулирования коммуникации
     */
    public function setCancel()
    {
        $this->cancel = self::CANCEL_TRUE;
    }


    /**
     * Установить параметр
     * прочтения коммуникации
     */
    public function setStatusRead()
    {
        $this->status = self::READ;
    }


    /**
     * Получить id получателя коммуникации
     *
     * @return int
     */
    public function getAdresseeId()
    {
        return $this->adressee_id;
    }


    /**
     * @param int $adressee_id
     */
    public function setAdresseeId($adressee_id)
    {
        $this->adressee_id = $adressee_id;
    }


    /**
     * Получить id отправителя коммуникации
     *
     * @return int
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }


    /**
     * @param int $sender_id
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }


    /**
     * Получить id коммуникации
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Получить id проекта
     *
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
    public function getType()
    {
        return $this->type;
    }


    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return int
     */
    public function getPatternId()
    {
        return $this->pattern_id;
    }


    /**
     * @param int $pattern_id
     */
    public function setPatternId($pattern_id)
    {
        $this->pattern_id = $pattern_id;
    }

    /**
     * @return int
     */
    public function getTriggeredCommunicationId()
    {
        return $this->triggered_communication_id;
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