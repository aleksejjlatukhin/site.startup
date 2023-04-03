<?php

namespace app\models;

use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\db\ActiveRecord;

/**
 * Класс, который хранит объекты ценностных предложений в бд
 *
 * Class Gcps
 * @package app\models
 *
 * @property int $id                                Идентификатор записи в таб. gcps
 * @property int $basic_confirm_id                  Идентификатор записи в таб. confirm_problem
 * @property int $segment_id                        Идентификатор записи в таб. segments
 * @property int $project_id                        Идентификатор записи в таб. projects
 * @property int $problem_id                        Идентификатор записи в таб. problems
 * @property string $title                          Сформированное системой название ценностного предложения
 * @property string $description                    Описание ценностного предложения
 * @property int $created_at                        Дата создания ЦП
 * @property int $updated_at                        Дата обновления ЦП
 * @property int $time_confirm                      Дата подверждения ЦП
 * @property int $exist_confirm                     Параметр факта подтверждения ЦП
 * @property string $enable_expertise               Параметр разрешения на экспертизу по даному этапу
 * @property int|null $enable_expertise_at          Дата разрешения на экспертизу по даному этапу
 * @property PropertyContainer $propertyContainer   Свойство для реализации шаблона 'контейнер свойств'
 *
 * @property ConfirmGcp $confirm                    Подтверждение ценностного предложения
 * @property BusinessModel[] $businessModels        Бизнес-модели
 * @property Mvps[] $mvps                           Mvp-продукты
 * @property Problems $problem                      Проблема
 * @property Segments $segment                      Сегмент
 * @property Projects $project                      Проект
 * @property RespondsProblem[] $respondsAgents      Респонденты, которые подтвердили текущую проблему
 */
class Gcps extends ActiveRecord
{

    public const EVENT_CLICK_BUTTON_CONFIRM = 'event click button confirm';

    public $propertyContainer;


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'gcps';
    }


    /**
     * Gcps constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setPropertyContainer();
        parent::__construct($config);
    }


    /**
     * Получить объект подтверждения данного Gcps
     *
     * @return ActiveQuery
     */
    public function getConfirm(): ActiveQuery
    {
        return $this->hasOne(ConfirmGcp::class, ['gcp_id' => 'id']);
    }


    /**
     * @return int
     */
    public function getConfirmProblemId(): int
    {
        return $this->basic_confirm_id;
    }


    /**
     * Получить все бизнес-модели данного Gcps
     *
     * @return ActiveQuery
     */
    public function getBusinessModels(): ActiveQuery
    {
        return $this->hasMany(BusinessModel::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить все объекты Mvps данного Gcps
     *
     * @return ActiveQuery
     */
    public function getMvps(): ActiveQuery
    {
        return $this->hasMany(Mvps::class, ['gcp_id' => 'id']);
    }


    /**
     * Получить объект текущей проблемы
     *
     * @return ActiveQuery
     */
    public function getProblem(): ActiveQuery
    {
        return $this->hasOne(Problems::class, ['id' => 'problem_id']);
    }


    /**
     * Получить объект текущего сегмента
     *
     * @return ActiveQuery
     */
    public function getSegment (): ActiveQuery
    {
        return $this->hasOne(Segments::class, ['id' => 'segment_id']);
    }


    /**
     * Получить объект текущего проекта
     *
     * @return ActiveQuery
     */
    public function getProject (): ActiveQuery
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }


    /**
     * Получить респондентов, которые
     * подтвердтлт текущую проблему
     *
     * @return array|ActiveRecord[]
     */
    public function getRespondsAgents(): array
    {
        return RespondsProblem::find()->with('interview')
            ->leftJoin('interview_confirm_problem', '`interview_confirm_problem`.`respond_id` = `responds_problem`.`id`')
            ->where(['confirm_id' => $this->getConfirmProblemId(), 'interview_confirm_problem.status' => '1'])->all();
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'description'], 'trim'],
            [['time_confirm', 'basic_confirm_id', 'exist_confirm', 'project_id', 'segment_id', 'problem_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 1500],
            [['title'], 'string', 'max' => 255],
            ['enable_expertise', 'default', 'value' => EnableExpertise::OFF],
            ['enable_expertise', 'in', 'range' => [
                EnableExpertise::OFF,
                EnableExpertise::ON,
            ]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Наименование ГЦП',
            'description' => 'Формулировка ГЦП',
            'date_create' => 'Дата создания',
            'date_confirm' => 'Дата подтверждения'
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


    public function init()
    {

        $this->on(self::EVENT_CLICK_BUTTON_CONFIRM, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->project->touch('updated_at');
            $this->project->user->touch('updated_at');
        });

        parent::init();
    }


    /**
     * Разрешение эксертизы и отправка уведомлений
     * эксперту и трекеру (если на проект назначен экперт)
     *
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function allowExpertise(): bool
    {
        $user = $this->project->user;
        if ($expertIds = ProjectCommunications::getExpertIdsByProjectId($this->getProjectId())) {
            $transaction = Yii::$app->db->beginTransaction();

            $communicationIds = [];
            foreach ($expertIds as $i => $expertId) {
                $communication = new ProjectCommunications();
                $communication->setParams($expertId, $this->getProjectId(), CommunicationTypes::USER_ALLOWED_GCP_EXPERTISE, $this->getId());
                if ($i === 0 && $communication->save() && DuplicateCommunications::create($communication, $user->admin, TypesDuplicateCommunication::USER_ALLOWED_EXPERTISE)) {
                    $communicationIds[] = $communication->getId();
                } elseif ($communication->save()) {
                    $communicationIds[] = $communication->getId();
                }
            }

            if (count($communicationIds) === count($expertIds)) {
                $this->setEnableExpertise();
                if ($this->update()) {
                    $transaction->commit();
                    return true;
                }
            }

            $transaction->rollBack();
            return false;
        }

        $this->setEnableExpertise();
        return (bool)$this->update();
    }


    /**
     * @return false|int
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function deleteStage ()
    {
        if ($mvps = $this->mvps) {
            foreach ($mvps as $mvp) {
                $mvp->deleteStage();
            }
        }

        if ($confirm = $this->confirm) {

            $responds = $confirm->responds;
            foreach ($responds as $respond) {

                InterviewConfirmGcp::deleteAll(['respond_id' => $respond->getId()]);
                AnswersQuestionsConfirmGcp::deleteAll(['respond_id' => $respond->getId()]);
            }

            QuestionsConfirmGcp::deleteAll(['confirm_id' => $confirm->getId()]);
            RespondsGcp::deleteAll(['confirm_id' => $confirm->getId()]);
            $confirm->delete();
        }

        // Удаление директории ГЦП
        $gcpPathDelete = UPLOAD.'/user-'.$this->project->user->getId().'/project-'.$this->project->getId().'/segments/segment-'.$this->segment->getId().
            '/problems/problem-'.$this->problem->getId().'/gcps/gcp-'.$this->getId();
        if (file_exists($gcpPathDelete)) {
            FileHelper::removeDirectory($gcpPathDelete);
        }

        // Удаление кэша для форм ГЦП
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->project->user->getId().'/projects/project-'.$this->project->getId().'/segments/segment-'.$this->segment->getId().
            '/problems/problem-'.$this->problem->getId().'/gcps/gcp-'.$this->getId();
        if (file_exists($cachePathDelete)) {
            FileHelper::removeDirectory($cachePathDelete);
        }

        // Удаление ГЦП
        return $this->delete();
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
    public function getBasicConfirmId(): int
    {
        return $this->basic_confirm_id;
    }

    /**
     * @param int $basic_confirm_id
     */
    public function setBasicConfirmId(int $basic_confirm_id): void
    {
        $this->basic_confirm_id = $basic_confirm_id;
    }

    /**
     * @param int $segment_id
     */
    public function setSegmentId(int $segment_id): void
    {
        $this->segment_id = $segment_id;
    }

    /**
     * @return int
     */
    public function getSegmentId(): int
    {
        return $this->segment_id;
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
    public function getProjectId(): int
    {
        return $this->project_id;
    }

    /**
     * @param int $problem_id
     */
    public function setProblemId(int $problem_id): void
    {
        $this->problem_id = $problem_id;
    }

    /**
     * @return int
     */
    public function getProblemId(): int
    {
        return $this->problem_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @return int|null
     */
    public function getTimeConfirm(): ?int
    {
        return $this->time_confirm;
    }

    /**
     * @param int|null $time_confirm
     */
    public function setTimeConfirm(int $time_confirm = null): void
    {
        $time_confirm ? $this->time_confirm = $time_confirm : $this->time_confirm = time();
    }

    /**
     * @return int|null
     */
    public function getExistConfirm(): ?int
    {
        return $this->exist_confirm;
    }

    /**
     * @param int $exist_confirm
     */
    public function setExistConfirm(int $exist_confirm): void
    {
        $this->exist_confirm = $exist_confirm;
    }

    /**
     * @return PropertyContainer
     */
    public function getPropertyContainer(): PropertyContainer
    {
        return $this->propertyContainer;
    }

    /**
     *
     */
    public function setPropertyContainer(): void
    {
        $this->propertyContainer = new PropertyContainer();
    }

    /**
     * @return string
     */
    public function getEnableExpertise(): string
    {
        return $this->enable_expertise;
    }

    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise(): void
    {
        $this->enable_expertise = EnableExpertise::ON;
        $this->setEnableExpertiseAt(time());
    }

    /**
     * @return int|null
     */
    public function getEnableExpertiseAt(): ?int
    {
        return $this->enable_expertise_at;
    }

    /**
     * @param int $enable_expertise_at
     */
    public function setEnableExpertiseAt(int $enable_expertise_at): void
    {
        $this->enable_expertise_at = $enable_expertise_at;
    }
}
