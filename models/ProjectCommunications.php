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
 * @property int $id                                            Идентификатор коммуникации
 * @property int $sender_id                                     Идентификатор отправителя коммуникации из таб.User
 * @property int $adressee_id                                   Идентификатор получателя коммуникации из таб.User
 * @property int $type                                          Тип коммуникации
 * @property int $project_id                                    Идентификатор проекта, по которому отправлена коммуникация
 * @property int $status                                        Статус коммуникации
 * @property int $pattern_id                                    Идентификатор шаблона коммуникации
 * @property int|null $triggered_communication_id               Идентификатор коммуникации в ответ, на которую была создана данная коммуникация
 * @property int $cancel                                        Параметр аннулирования коммуникации
 * @property int $created_at                                    Дата создания коммуникации
 * @property int $updated_at                                    Дата обновления коммуникации
 *
 * @property UserAccessToProjects $userAccessToProject          объект доступа пользователя к проекту по коммуникации
 * @property CommunicationResponse $communicationResponse       объект ответа по коммуникации
 * @property ProjectCommunications $responsiveCommunication     объект ответной коммуникации, т.е. обращение от коммуникации на которую ответили, а запрос на поиск коммуникации, которой ответили
 * @property ProjectCommunications $communicationAnswered       коммуникация на которую, была создана ответная коммуникация, запрос выполняется от ответной коммуникации
 * @property User $expert                                       эксперт
 * @property Projects $project                                  объект проекта, по которому создана коммуникация
 * @property CommunicationPatterns $pattern                     шаблон коммуникации
 * @property TypesAccessToExpertise $typesAccessToExpertise     типы экспертиз назначенных эксперту по данной коммуникации
 * @property string $accessStatus                               Статус доступа к проекту
 * @property string $notificationStatus                         Тип (статус) уведомления для эксперта
 */
class ProjectCommunications extends ActiveRecord implements CommunicationsInterface
{

    public const CANCEL_TRUE = 1111;
    public const CANCEL_FALSE = 2222;
    public const READ = 1000;
    public const NO_READ = 500;


    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'project_communications';
    }


    /**
     * Получить объект доступа пользователя
     * к проекту по коммуникации
     *
     * @return ActiveQuery
     */
    public function getUserAccessToProject(): ActiveQuery
    {
        return $this->hasOne(UserAccessToProjects::class, ['communication_id' => 'id']);
    }


    /**
     * Получить объект
     * ответа по коммуникации
     *
     * @return ActiveQuery
     */
    public function getCommunicationResponse(): ActiveQuery
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
    public function getResponsiveCommunication(): ?ProjectCommunications
    {
        return self::findOne(['triggered_communication_id' => $this->getId()]);
    }


    /**
     * Получить коммуникацию на которую,
     * была создана ответная коммуникация,
     * запрос выполняется от ответной коммуникации
     *
     * @return ProjectCommunications|null
     */
    public function getCommunicationAnswered(): ?ProjectCommunications
    {
        return self::findOne(['id' => $this->getTriggeredCommunicationId()]);
    }


    /**
     * Получить объект
     * эксперта
     *
     * @return User|null
     */
    public function getExpert(): ?User
    {
        if ($this->getType() === CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $expert = User::findOne($this->getSenderId());
        } else {
            $expert = User::findOne($this->getAdresseeId());
        }
        return $expert;
    }


    /**
     * Получить объект проекта,
     * по которому создана коммуникация
     *
     * @return ActiveQuery
     */
    public function getProject(): ActiveQuery
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить объект
     * шаблона коммуникации
     *
     * @return ActiveQuery
     */
    public function getPattern(): ActiveQuery
    {
        return $this->hasOne(CommunicationPatterns::class, ['id' => 'pattern_id']);
    }


    /**
     * Получить объект содержащий
     * типы экспертиз назначенных
     * эксперту по данной коммуникации
     *
     * @return ActiveQuery
     */
    public function getTypesAccessToExpertise(): ActiveQuery
    {
        return $this->hasOne(TypesAccessToExpertise::class, ['communication_id' => 'id']);
    }


    /**
     * Получить описание
     * шаблона коммуникации
     *
     * @param bool $isSendEmail
     * @return string
     */
    public function getDescriptionPattern(bool $isSendEmail = false): string
    {
        $projectName_search = '{{наименование проекта}}';
        $projectName_replace = '«' . $this->project->getProjectName() . '»';
        $linkProjectName_search = '{{наименование проекта, ссылка на проект}}';
        $linkProjectName_replace = Html::a($projectName_replace, ['/projects/index', 'id' => $this->project->getUserId(), 'project_id' => $this->project->getId()]);
        $linkProjectName_replace = $isSendEmail ? Html::a($projectName_replace, Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $this->project->getUserId(), 'project_id' => $this->project->getId()])) : $linkProjectName_replace;
        $pattern = $this->pattern;

        if ($pattern) {

            $description = $pattern->getDescription();

            if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
                return str_replace($linkProjectName_search, $linkProjectName_replace, $description);
            }

            if ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
                return str_replace($projectName_search, $projectName_replace, $description);
            }

            if ($this->getType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
                $typesAccessToExpertise_search = '{{список типов деятельности эксперта}}';
                $typesAccessToExpertise_replace = ExpertType::getContent($this->typesAccessToExpertise->getTypes());
                return str_replace($linkProjectName_search, $linkProjectName_replace, str_replace($typesAccessToExpertise_search, $typesAccessToExpertise_replace, $description));
            }

            if ($this->getType() === CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
                return str_replace($projectName_search, $projectName_replace, $description);
            }

            if ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
                return str_replace($projectName_search, $projectName_replace, $description);
            }

        }

        return $this->getDefaultPattern($isSendEmail);
    }


    /**
     * Полушить шаблон
     * коммуникации по умолчанию
     *
     * @param bool $isSendEmail
     * @return string
     */
    public function getDefaultPattern(bool $isSendEmail = false): string
    {
        $projectName_search = '{{наименование проекта}}';
        $projectName_replace = '«' . $this->project->getProjectName() . '»';
        $linkProjectName_search = '{{наименование проекта, ссылка на проект}}';
        $linkProjectName_replace = Html::a($projectName_replace, ['/projects/index', 'id' => $this->project->getUserId(), 'project_id' => $this->project->getId()]);
        $linkProjectName_replace = $isSendEmail ? Html::a($projectName_replace, Yii::$app->urlManager->createAbsoluteUrl(['/projects/index', 'id' => $this->project->getUserId(), 'project_id' => $this->project->getId()])) : $linkProjectName_replace;

        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_ABOUT_READINESS_CONDUCT_EXPERTISE;
            return str_replace($linkProjectName_search, $linkProjectName_replace, $defaultPattern);
        }

        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        }

        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            $typesAccessToExpertise_search = '{{список типов деятельности эксперта}}';
            $typesAccessToExpertise_replace = ExpertType::getContent($this->typesAccessToExpertise->getTypes());
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_APPOINTS_EXPERT_PROJECT;
            return str_replace($linkProjectName_search, $linkProjectName_replace, str_replace($typesAccessToExpertise_search, $typesAccessToExpertise_replace, $defaultPattern));
        }

        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_DOES_NOT_APPOINTS_EXPERT_PROJECT;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        }

        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
            $defaultPattern = CommunicationPatterns::COMMUNICATION_DEFAULT_WITHDRAWS_EXPERT_FROM_PROJECT;
            return str_replace($projectName_search, $projectName_replace, $defaultPattern);
        }

        return '';
    }


    /**
     * Получить статус
     * доступа к проекту
     *
     * @return string
     */
    public function getAccessStatus(): string
    {
        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->userAccessToProject->getCancel() === UserAccessToProjects::CANCEL_TRUE) {
                return '<div class="text-danger">Закрыт</div>';
            }

            return '<div class="text-success">Открыт до ' . date('d.m.Y H:i', $this->userAccessToProject->date_stop) . '</div>';
        }

        if (in_array($this->getType(), [CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE,
            CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT, CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT], false)) {
            return '<div class="text-danger">Закрыт</div>';
        }

        if ($this->type === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            if ($this->userAccessToProject->getCancel() === UserAccessToProjects::CANCEL_TRUE) {
                return '<div class="text-danger">Закрыт</div>';
            }

            return '<div class="text-success">Бессрочный</div>';
        }

        return '';
    }


    /**
     * Получить тип (статус)
     * уведомления для эксперта
     *
     * @return string
     */
    public function getNotificationStatus(): string
    {
        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->getCancel() === self::CANCEL_TRUE) {
                return '<div class="text-success">Запрос о готовности провести экспертизу</div>';
            }
            if ($this->getStatus() === self::READ) {
                return '<div class="text-success">Ответ получен</div>';
            }
            if ($this->getStatus() === self::NO_READ && time() < $this->userAccessToProject->getDateStop()) {
                return '<div class="text-warning">Требуется ответ</div>';
            }
            if ($this->getStatus() === self::NO_READ && time() > $this->userAccessToProject->getDateStop()) {
                return '<div class="text-danger">Просрочена дата ответа</div>';
            }
        } elseif ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            return '<div class="text-danger">Запрос отозван</div>';
        } elseif ($this->getType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            return '<div class="text-success">Назначен(-а) на проект</div>';
        } elseif ($this->getType() === CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT) {
            return '<div class="text-danger">Отказано в назначении на проект</div>';
        } elseif ($this->getType() === CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT) {
            return '<div class="text-danger">Отозван(-а) с проекта</div>';
        }

        return '';
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
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
    public function behaviors(): array
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
    public static function isNeedAskExpert(int $expert_id, int $project_id): bool
    {
        $communications = self::find()->where(['project_id' => $project_id])->andWhere(['or', ['adressee_id' => $expert_id], ['sender_id' => $expert_id]]);
        $existCommunications = $communications->all();

        if (!$existCommunications) {
            // Если у эксперта ещё не было
            // коммуникаций по данному проекту
            return true;

        }

        /** @var self $lastCommunication */
        $lastCommunication = $communications->orderBy('id DESC')->one();

        if (in_array($lastCommunication->getType(), [
            CommunicationTypes::MAIN_ADMIN_WITHDRAWS_REQUEST_ABOUT_READINESS_CONDUCT_EXPERTISE,
            CommunicationTypes::MAIN_ADMIN_DOES_NOT_APPOINTS_EXPERT_PROJECT,
            CommunicationTypes::MAIN_ADMIN_WITHDRAWS_EXPERT_FROM_PROJECT
        ], false)) {
            // Если у эксперта последняя коммуникая по проекту соответствует типам
            // "отмена запроса", "отказано в назначении на проект", "отозван с проекта"
            return true;

        }

        if ($lastCommunication->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE
            && $lastCommunication->userAccessToProject->getDateStop() < time()) {
            // Последняя коммуникация эксперта по проекту "запрос эксперту о готовности
            // провести экспертизу" и время доступа к проекту закончилось
            return true;
        }

        if ($lastCommunication->getType() === CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE
            && $lastCommunication->communicationResponse->getAnswer() === CommunicationResponse::NEGATIVE_RESPONSE) {
            // Последняя коммуникая, отрицательный ответ эксперта на запрос о готовности провести экспертизу
            return true;
        }
        return false;
    }


    /**
     * Проверка доступа к проведению экспертизы
     *
     * @param int $expert_id
     * @param int $project_id
     * @return bool
     */
    public static function checkOfAccessToCarryingExpertise(int $expert_id, int $project_id): bool
    {
        $communications = self::find()->where(['project_id' => $project_id])->andWhere(['or', ['adressee_id' => $expert_id], ['sender_id' => $expert_id]]);
        $existCommunications = $communications->all();

        if (!$existCommunications) {
            // Если у эксперта ещё не было
            // коммуникаций по данному проекту
            return false;

        }

        /** @var self $lastCommunication */
        $lastCommunication = $communications->orderBy('id DESC')->one();
        if ($lastCommunication->getType() === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            return true;
        }
        return false;
    }


    /**
     * Показывать ли эксперту кнопку
     * ответа на коммуникацию
     *
     * @return bool
     */
    public function isNeedShowButtonAnswer(): bool
    {
        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE &&
            $this->getCancel() === self::CANCEL_FALSE && $this->getStatus() === self::NO_READ &&
            time() < $this->userAccessToProject->getDateStop()) {
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
    public function isNeedReadButton(): bool
    {
        if ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            if ($this->getStatus() === self::NO_READ && time() > $this->userAccessToProject->getDateStop()) {
                return true;
            }

            if ($this->getStatus() === self::NO_READ && $this->getCancel() === self::CANCEL_TRUE) {
                return true;
            }
        } elseif (!in_array($this->getType(), [CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE, CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE], false)) {
            if ($this->getStatus() === self::NO_READ) {
                return true;
            }
        } elseif ($this->getType() === CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE &&
            ($this->getCancel() === self::CANCEL_TRUE && $this->getStatus() === self::NO_READ || time() > $this->userAccessToProject->getDateStop())) {
            return true;
        }
        return false;
    }


    /**
     * @param int $adressee_id
     * @param int $project_id
     * @param int $type
     */
    public function setParams(int $adressee_id, int $project_id, int $type): void
    {
        $this->setSenderId(Yii::$app->user->getId());
        $this->setAdresseeId($adressee_id);
        $this->setProjectId($project_id);
        $this->setType($type);

        $pattern = CommunicationPatterns::findOne([
            'communication_type' => $type,
            'is_active' => CommunicationPatterns::ACTIVE,
            'is_remote' => CommunicationPatterns::NOT_REMOTE
            ]);

        if ($pattern) {
            $this->setPatternId($pattern->getId());
        }
    }


    /**
     * Установка id коммуникации, которая была триггером
     * для создания данной коммуникации, т.е. данная коммуникация
     * является ответом для триггерной коммуникации
     *
     * @param int|null $id
     */
    public function setTriggeredCommunicationId(int $id = null): void
    {
        $this->triggered_communication_id = $id;
    }

    /**
     * Установить параметр
     * аннулирования коммуникации
     */
    public function setCancel(): void
    {
        $this->cancel = self::CANCEL_TRUE;
    }


    /**
     * Установить параметр
     * прочтения коммуникации
     */
    public function setStatusRead(): void
    {
        $this->status = self::READ;
    }


    /**
     * Получить id получателя коммуникации
     *
     * @return int
     */
    public function getAdresseeId(): int
    {
        return $this->adressee_id;
    }


    /**
     * @param int $adressee_id
     */
    public function setAdresseeId(int $adressee_id): void
    {
        $this->adressee_id = $adressee_id;
    }


    /**
     * Получить id отправителя коммуникации
     *
     * @return int
     */
    public function getSenderId(): int
    {
        return $this->sender_id;
    }


    /**
     * @param int $sender_id
     */
    public function setSenderId(int $sender_id): void
    {
        $this->sender_id = $sender_id;
    }


    /**
     * Получить id коммуникации
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Получить id проекта
     *
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
     * @return int
     */
    public function getPatternId(): int
    {
        return $this->pattern_id;
    }


    /**
     * @param int $pattern_id
     */
    public function setPatternId(int $pattern_id): void
    {
        $this->pattern_id = $pattern_id;
    }

    /**
     * @return int
     */
    public function getTriggeredCommunicationId(): int
    {
        return $this->triggered_communication_id;
    }

    /**
     * @return int
     */
    public function getCancel(): int
    {
        return $this->cancel;
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


}