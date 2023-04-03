<?php

namespace app\models;

use app\models\forms\CacheForm;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Класс, который хранит объекты описания проектов в бд
 *
 * Class Projects
 * @package app\models
 *
 * @property int $id                                            Идентификатор проекта
 * @property int $user_id                                       Идентификатор проектанта в таб.User
 * @property int $created_at                                    Дата создания проекта
 * @property int $updated_at                                    Дата обновления проекта
 * @property string $project_fullname                           Полное наименое проекта
 * @property string $project_name                               Короткое наименование проекта
 * @property string $description                                Описание проекта
 * @property string $purpose_project                            Цель проекта
 * @property string $rid                                        Результат интеллектуальной деятельности
 * @property string $patent_number                              Номер патента
 * @property int $patent_date                                   Дата получения патента
 * @property string $patent_name                                Наименование патента
 * @property string $core_rid                                   Суть результата интеллектуальной деятельности
 * @property string $technology                                 Технология, на которой основан проект
 * @property string $layout_technology                          Макет базовой технологии
 * @property string $register_name                              Зарегистрированное юр. лицо
 * @property int $register_date                                 Дата регистрации юр. лица
 * @property string $site                                       Адрес сайта
 * @property string $invest_name                                Инвестор
 * @property int $invest_date                                   Дата получения инвестиций
 * @property int $invest_amount                                 Сумма инвестиций
 * @property int $date_of_announcement                          Дата анонсирования проекта
 * @property string $announcement_event                         Мероприятие, на котором анонсирован проект
 * @property string $enable_expertise                           Параметр разрешения на экспертизу по даному этапу
 * @property int|null $enable_expertise_at                      Дата разрешения на экспертизу по даному этапу
 * @property $present_files                                     Поле для загрузки презентационных файлов
 * @property CacheForm $_cacheManager                           Менеджер для кэширования
 *
 * @property User $user                                         Проектант
 * @property Authors[] $authors                                 Авторы проекта
 * @property Segments[] $segments                               Сегменты
 * @property Problems[] $problems                               Проблемы
 * @property Gcps[] $gcps                                       Ценностные предложения
 * @property Mvps[] $mvps                                       Mvp-продукты
 * @property BusinessModel[] $businessModels                    Бизнес-модели
 * @property PreFiles[] $preFiles                               Презентационные файлы
 * @property ProjectCommunications[] $projectCommunications     Коммуникации админа организации и экспертов по проекту
 */
class Projects extends ActiveRecord
{
    public const PERIOD_TARGET_DATE_FOR_APPOINT_EXPERT = 14*24*60*60;
    public const PERIOD_TARGET_DATE_FOR_ASK_EXPERT = 7*24*60*60;

    public $present_files;
    public $_cacheManager;


    /**
     * Projects constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->setCacheManager();
        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'projects';
    }


    /**
     * @param User $user
     * @return string
     */
    public static function getCachePath(User $user): string
    {
        return '../runtime/cache/forms/user-'.$user->id. '/projects/formCreate/';
    }


    /**
     * Получить объект пользователя
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }


    /**
     * Получить всех авторов проекта
     *
     * @return ActiveQuery
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Authors::class, ['project_id' => 'id']);
    }


    /**
     * Получить все сегменты проекта
     *
     * @return ActiveQuery
     */
    public function getSegments(): ActiveQuery
    {
        return $this->hasMany(Segments::class, ['project_id' => 'id']);
    }


    /**
     * Получить все проблемы проекта
     *
     * @return ActiveQuery
     */
    public function getProblems(): ActiveQuery
    {
        return $this->hasMany(Problems::class, ['project_id' => 'id']);
    }


    /**
     * Получить все ценностные предложения проекта
     *
     * @return ActiveQuery
     */
    public function getGcps(): ActiveQuery
    {
        return $this->hasMany(Gcps::class, ['project_id' => 'id']);
    }


    /**
     * Получить все Mvp проекта
     *
     * @return ActiveQuery
     */
    public function getMvps(): ActiveQuery
    {
        return $this->hasMany(Mvps::class, ['project_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели проекта
     *
     * @return ActiveQuery
     */
    public function getBusinessModels(): ActiveQuery
    {
        return $this->hasMany(BusinessModel::class, ['project_id' => 'id']);
    }


    /**
     * Получить прикрепленные файлы
     *
     * @return ActiveQuery
     */
    public function getPreFiles(): ActiveQuery
    {
        return $this->hasMany(PreFiles::class, ['project_id' => 'id']);
    }


    /**
     * Получить объект проекта,
     * по которому создана коммуникация
     *
     * @return ActiveQuery
     */
    public function getProjectCommunications(): ActiveQuery
    {
        return $this->hasMany(ProjectCommunications::class, ['project_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'project_name'], 'required'],
            [['created_at', 'updated_at','user_id',], 'integer'],
            [['invest_amount'], 'integer', 'integerOnly' => TRUE, 'min' => '1'],
            [['patent_date', 'register_date', 'invest_date', 'date_of_announcement',], 'safe'],
            [['description', 'core_rid', 'layout_technology', 'purpose_project'], 'string', 'max' => 2000],
            ['project_name', 'string', 'min' => 3, 'max' => 32],
            ['project_name', 'uniqueName'],
            [['project_fullname', 'rid', 'patent_name', 'patent_number', 'technology', 'register_name', 'site', 'invest_name', 'announcement_event',], 'string', 'max' => 255],
            [['project_fullname', 'project_name', 'rid', 'patent_number', 'technology', 'register_name', 'site', 'invest_name', 'announcement_event', 'description', 'patent_name', 'core_rid', 'layout_technology', 'purpose_project'], 'trim'],
            [['present_files'], 'file', 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls', 'maxFiles' => 10],
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Дата создания проекта',
            'updated_at' => 'Дата обновления проекта',
            'project_fullname' => 'Полное наименование проекта',
            'project_name' => 'Сокращенное наименование проекта',
            'description' => 'Описание проекта',
            'purpose_project' => 'Цель проекта',
            'rid' => 'Результат интеллектуальной деятельности',
            'patent_number' => 'Номер патента',
            'patent_date' => 'Дата получения патента',
            'patent_name' => 'Наименование патента',
            'core_rid' => 'Суть результата интеллектуальной деятельности',
            'technology' => 'На какой технологии основан проект',
            'layout_technology' => 'Макет базовой технологии',
            'register_name' => 'Зарегистрированное юр. лицо',
            'register_date' => 'Дата регистрации',
            'site' => 'Адрес сайта',
            'invest_name' => 'Инвестор',
            'invest_date' => 'Дата получения инвестиций',
            'invest_amount' => 'Сумма инвестиций (руб.)',
            'date_of_announcement' => 'Дата анонсирования проекта',
            'announcement_event' => 'Мероприятие, на котором проект анонсирован впервые',
        ];
    }


    /**
     * @return void
     */
    public function init(): void
    {

        $this->on(self::EVENT_AFTER_INSERT, function (){
            $this->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_UPDATE, function (){
            $this->user->touch('updated_at');
        });

        $this->on(self::EVENT_AFTER_DELETE, function (){
            $this->user->touch('updated_at');
        });

        parent::init();
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
     * Показать авторов проекта
     * @param bool $mobile
     * @return string
     */
    public function showListAuthors(bool $mobile = false): string
    {
        $string = '';
        $j = 0;
        foreach ($this->authors as $k => $author) {

            $j++;
            if (!$mobile) {
                $string .= '<div style="padding-bottom: 10px;"><div style="font-weight: bold;">Сотрудник №'.$j.'</div>';
                $string .= '<div>ФИО: ' . $author->getFio() . '</div>';
                $string .= '<div>Роль в проекте: ' . $author->getRole() . '</div>';
                $string .= '<div>Опыт работы: ' . $author->getExperience() . '</div></div>';
            } else {
                $string .= '<div class="presentation-mobile-title-row">Сотрудник '.$j.'</div>';
                $string .= '<div class="presentation-mobile-simple-row">' . $author->getFio() . '</div>';
                $string .= '<div class="presentation-mobile-title-row">Роль в проекте</div>';
                $string .= '<div class="presentation-mobile-simple-row">' . $author->getRole() . '</div>';
                $string .= '<div class="presentation-mobile-title-row">Опыт работы</div>';
                $string .= '<div class="presentation-mobile-simple-row">' . $author->getExperience() . '</div>';
                if ($j !== count($this->authors)) {
                    $string .= '<div class="presentation-mobile-simple-row"></div>';
                }
            }

        }
        return $string;
    }


    /**
     * Загрузка презентационных файлов
     *
     * @return void
     * @throws Exception
     * @throws NotFoundHttpException
     */
    private function uploadPresentFiles(): void
    {

        $path = UPLOAD.'/user-'.$this->user->getId().'/project-'.$this->getId().'/present_files/';
        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        if($this->validate()){

            foreach($this->present_files as $file){

                $filename = Yii::$app->getSecurity()->generateRandomString(15);

                try{

                    $file->saveAs($path . $filename . '.' . $file->extension);

                    $preFiles = new PreFiles();
                    $preFiles->setFileName($file);
                    $preFiles->setServerFile($filename . '.' . $file->extension);
                    $preFiles->setProjectId($this->getId());
                    $preFiles->save(false);

                }catch (\Exception $e){

                    throw new NotFoundHttpException('Невозможно загрузить файл!');
                }
            }
        }
    }


    /**
     * @param $attr
     */
    public function uniqueName ($attr): void
    {
        $models = self::findAll(['user_id' => $this->getUserId()]);

        if (empty($this->id)) {
            //При создании проекта
            foreach ($models as $item) {
                if (mb_strtolower(str_replace(' ', '', $this->getProjectName())) === mb_strtolower(str_replace(' ', '', $item->getProjectName()))) {
                    $this->addError($attr, 'Проект с наименованием «'. $this->getProjectName() .'» уже существует!');
                }
            }
        } else {
            //При редактировании проекта
            foreach ($models as $item) {
                if ($this->getId() !== $item->getId() && mb_strtolower(str_replace(' ', '', $this->getProjectName())) === mb_strtolower(str_replace(' ', '', $item->getProjectName()))) {
                    $this->addError($attr, 'Проект с наименованием «'. $this->getProjectName() .'» уже существует!');
                }
            }
        }
    }


    /**
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function create(): bool
    {

        //Преобразование даты в число
        $this->setPatentDate();
        $this->setRegisterDate();
        $this->setInvestDate();
        $this->setDateOfAnnouncement();

        if ($this->save()) {
            //Сохранение команды(авторов)
            $this->saveAuthors();
            //Загрузка презентационных файлов
            $this->setPresentFiles(UploadedFile::getInstances($this, 'present_files'));
            if ($this->getPresentFiles()) {
                $this->uploadPresentFiles();
            }

            return true;
        }
        return false;
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function updateProject(): bool
    {

        //Преобразование даты в число
        $this->setPatentDate();
        $this->setRegisterDate();
        $this->setInvestDate();
        $this->setDateOfAnnouncement();

        if ($this->save()) {
            //Сохранение команды(авторов)
            $this->saveAuthors();
            //Загрузка презентационных файлов
            $this->setPresentFiles(UploadedFile::getInstances($this, 'present_files'));
            $this->uploadPresentFiles();

            return true;
        }
        return false;
    }


    /**
     * Разрешение эксертизы и отправка уведомлений
     * админу организации и трекеру
     *
     * @return bool
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function allowExpertise(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        $user = $this->user;
        $communication = new ProjectCommunications();
        $communication->setParams($user->mainAdmin->getId(), $this->getId(), CommunicationTypes::USER_ALLOWED_PROJECT_EXPERTISE, $this->getId());
        if ($communication->save() && DuplicateCommunications::create($communication, $user->admin, TypesDuplicateCommunication::USER_ALLOWED_EXPERTISE)) {
            $transaction->commit();
            $this->setEnableExpertise();
            if($this->update()) {
                //$this->sendCommunicationToEmail($communication);
                return true;
            }
        }

        $transaction->rollBack();
        return false;
    }


    /**
     * Отправка письма с уведомлением о разрешении экпертизы
     * по проекту на почту админу организации
     *
     * @param ProjectCommunications $communication
     * @return bool
     */
    private function sendCommunicationToEmail(ProjectCommunications $communication): bool
    {
        /* @var $user User */
        $user = User::findOne($communication->getAdresseeId());

        if ($user) {
            return Yii::$app->mailer->compose('communications__FromUserToMainAdmin', ['communication' => $communication])
                ->setFrom([Yii::$app->params['supportEmail'] => 'Spaccel.ru - Акселератор стартап-проектов'])
                ->setTo($user->getEmail())
                ->setSubject('Вам пришло новое уведомление на сайте Spaccel.ru')
                ->send();
        }

        return false;
    }


    /**
     * @param int $type
     * @return ProjectCommunications|null
     */
    public function getLastProjectCommunicationByType(int $type): ?ProjectCommunications
    {
        /** @var $result ProjectCommunications|null */
        $query = ProjectCommunications::find()
            ->andWhere(['cancel' => ProjectCommunications::CANCEL_FALSE])
            ->andWhere(['project_id' => $this->getId(), 'type' => $type]);

        if ($type === CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE) {
            $query->innerJoin('communication_response','`communication_response`.`communication_id` = `project_communications`.`id`')
                ->andWhere(['communication_response.answer' => CommunicationResponse::POSITIVE_RESPONSE]);
        }

        if ($type === CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT) {
            $communications = $query->orderBy(['created_at' => SORT_DESC])->all();
            $result = null;
            foreach ($communications as $communication) {
                /** @var ProjectCommunications $communication */
                if (!$communication->responsiveCommunication && !$result) {
                    $result = $communication;
                }
            }
            return $result;
        }

        $result = $query->orderBy(['created_at' => SORT_DESC])->one();
        return $result ?: null;
    }


    /**
     * Сохранение команды(авторов)
     */
    private function saveAuthors (): void
    {
        $workers = $this->authors;

        $arr_authors = $_POST['Authors'];
        $arr_authors = array_values($arr_authors);

        if (empty($workers)) {

            //При создании проекта

            foreach ($arr_authors as $arr_author) {

                $worker = new Authors();
                $worker->setFio($arr_author['fio']);
                $worker->setRole($arr_author['role']);
                $worker->setExperience($arr_author['experience']);
                $worker->setProjectId($this->getId());
                $worker->save();
            }
        } elseif (count($arr_authors) > count($workers)) {

            foreach ($arr_authors as $i => $arr_author) {

                if (($i+1) <= count($workers)) {
                    $workers[$i]->setFio($arr_author['fio']);
                    $workers[$i]->setRole($arr_author['role']);
                    $workers[$i]->setExperience($arr_author['experience']);
                    $workers[$i]->save();
                } else {
                    $worker = new Authors();
                    $worker->setFio($arr_author['fio']);
                    $worker->setRole($arr_author['role']);
                    $worker->setExperience($arr_author['experience']);
                    $worker->setProjectId($this->getId());
                    $worker->save();
                }
            }

        } else {

            foreach ($arr_authors as $i => $arr_author) {
                $workers[$i]->setFio($arr_author['fio']);
                $workers[$i]->setRole($arr_author['role']);
                $workers[$i]->setExperience($arr_author['experience']);
                $workers[$i]->save();
            }
        }
    }


    /**
     * @return false|int
     * @throws ErrorException
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function deleteStage ()
    {
        if ($segments = $this->segments) {
            foreach ($segments as $segment) {
                $segment->deleteStage();
            }
        }

        Authors::deleteAll(['project_id' => $this->getId()]);
        PreFiles::deleteAll(['project_id' => $this->getId()]);

        // Удаление директории проекта
        $projectPathDelete = UPLOAD.'/user-'.$this->user->getId().'/project-'.$this->getId();
        if (file_exists($projectPathDelete)) {
            FileHelper::removeDirectory($projectPathDelete);
        }

        // Удаление кэша для форм проекта
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->user->getId().'/projects/project-'.$this->getId();
        if (file_exists($cachePathDelete)) {
            FileHelper::removeDirectory($cachePathDelete);
        }

        // Удаление проекта
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
     * @return string
     */
    public function getProjectFullname(): string
    {
        return $this->project_fullname;
    }

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->project_name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPurposeProject(): string
    {
        return $this->purpose_project;
    }

    /**
     * @return string
     */
    public function getRid(): string
    {
        return $this->rid;
    }

    /**
     * @return string
     */
    public function getPatentNumber(): string
    {
        return $this->patent_number;
    }

    /**
     * @return int|null
     */
    public function getPatentDate(): ?int
    {
        return $this->patent_date;
    }

    /**
     * @return string
     */
    public function getPatentName(): string
    {
        return $this->patent_name;
    }

    /**
     * @return string
     */
    public function getCoreRid(): string
    {
        return $this->core_rid;
    }

    /**
     * @return string
     */
    public function getTechnology(): string
    {
        return $this->technology;
    }

    /**
     * @return string
     */
    public function getLayoutTechnology(): string
    {
        return $this->layout_technology;
    }

    /**
     * @return string
     */
    public function getRegisterName(): string
    {
        return $this->register_name;
    }

    /**
     * @return int|null
     */
    public function getRegisterDate(): ?int
    {
        return $this->register_date;
    }

    /**
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getInvestName(): string
    {
        return $this->invest_name;
    }

    /**
     * @return int|null
     */
    public function getInvestDate(): ?int
    {
        return $this->invest_date;
    }

    /**
     * @return int|null
     */
    public function getInvestAmount(): ?int
    {
        return $this->invest_amount;
    }

    /**
     * @return int|null
     */
    public function getDateOfAnnouncement(): ?int
    {
        return $this->date_of_announcement;
    }

    /**
     *
     */
    public function setPatentDate(): void
    {
        if ($this->patent_date) {
            $this->patent_date = strtotime($this->patent_date);
        }
    }

    /**
     *
     */
    public function setRegisterDate(): void
    {
        if ($this->register_date) {
            $this->register_date = strtotime($this->register_date);
        }
    }

    /**
     *
     */
    public function setInvestDate(): void
    {
        if ($this->invest_date) {
            $this->invest_date = strtotime($this->invest_date);
        }
    }

    /**
     *
     */
    public function setDateOfAnnouncement(): void
    {
        if ($this->date_of_announcement) {
            $this->date_of_announcement = strtotime($this->date_of_announcement);
        }
    }

    /**
     * @return mixed
     */
    public function getPresentFiles()
    {
        return $this->present_files;
    }

    /**
     * @param mixed $present_files
     */
    public function setPresentFiles($present_files): void
    {
        $this->present_files = $present_files;
    }

    /**
     * @return CacheForm
     */
    public function getCacheManager(): CacheForm
    {
        return $this->_cacheManager;
    }

    /**
     *
     */
    public function setCacheManager(): void
    {
        $this->_cacheManager = new CacheForm();
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getAnnouncementEvent(): string
    {
        return $this->announcement_event;
    }

    /**
     * @param string $announcement_event
     */
    public function setAnnouncementEvent(string $announcement_event): void
    {
        $this->announcement_event = $announcement_event;
    }

    /**
     * Параметр разрешения экспертизы
     *
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

    /**
     * @return int
     */
    public function getTargetDateAppointExpert(): int
    {
        return $this->getEnableExpertiseAt() ?
            $this->getEnableExpertiseAt() + self::PERIOD_TARGET_DATE_FOR_APPOINT_EXPERT :
            time() + self::PERIOD_TARGET_DATE_FOR_APPOINT_EXPERT;
    }

    /**
     * @return int
     */
    public function getTargetDateAskExpert(): int
    {
        return $this->getEnableExpertiseAt() ?
            $this->getEnableExpertiseAt() + self::PERIOD_TARGET_DATE_FOR_ASK_EXPERT :
            time() + self::PERIOD_TARGET_DATE_FOR_ASK_EXPERT;
    }
}
