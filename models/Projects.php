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
 * @property int $id                                Идентификатор проекта
 * @property int $user_id                           Идентификатор проектанта в таб.User
 * @property int $created_at                        Дата создания проекта
 * @property int $updated_at                        Дата обновления проекта
 * @property string $project_fullname               Полное наименое проекта
 * @property string $project_name                   Короткое наименование проекта
 * @property string $description                    Описание проекта
 * @property string $purpose_project                Цель проекта
 * @property string $rid                            Результат интеллектуальной деятельности
 * @property string $patent_number                  Номер патента
 * @property int $patent_date                       Дата получения патента
 * @property string $patent_name                    Наименование патента
 * @property string $core_rid                       Суть результата интеллектуальной деятельности
 * @property string $technology                     Технология, на которой основан проект
 * @property string $layout_technology              Макет базовой технологии
 * @property string $register_name                  Зарегистрированное юр. лицо
 * @property int $register_date                     Дата регистрации юр. лица
 * @property string $site                           Адрес сайта
 * @property string $invest_name                    Инвестор
 * @property int $invest_date                       Дата получения инвестиций
 * @property int $invest_amount                     Сумма инвестиций
 * @property int $date_of_announcement              Дата анонсирования проекта
 * @property int $enable_expertise                  Параметр разрешения на экспертизу по даному этапу
 */
class Projects extends ActiveRecord
{

    public $present_files;
    public $_cacheManager;


    /**
     * Projects constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->_cacheManager = new CacheForm();

        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
    }


    /**
     * @param User $user
     * @return string
     */
    public static function getCachePath(User $user)
    {
        return '../runtime/cache/forms/user-'.$user->id. '/projects/formCreate/';
    }


    /**
     * Получить объект пользователя
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * Поиск проектанта,
     * которому принадлежит проект
     *
     * @return User|null
     */
    public function findUser()
    {
        return User::findOne($this->user_id);
    }


    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * Получить всех авторов проекта
     * @return ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['project_id' => 'id']);
    }


    /**
     * Получить все сегменты проекта
     * @return ActiveQuery
     */
    public function getSegments()
    {
        return $this->hasMany(Segments::class, ['project_id' => 'id']);
    }


    /**
     * Получить все проблемы проекта
     * @return ActiveQuery
     */
    public function getProblems ()
    {
        return $this->hasMany(Problems::class, ['project_id' => 'id']);
    }


    /**
     * Получить все ценностные предложения проекта
     * @return ActiveQuery
     */
    public function getGcps ()
    {
        return $this->hasMany(Gcps::class, ['project_id' => 'id']);
    }


    /**
     * Получить все Mvp проекта
     * @return ActiveQuery
     */
    public function getMvps ()
    {
        return $this->hasMany(Mvps::class, ['project_id' => 'id']);
    }


    /**
     * Получить все бизнес-модели проекта
     * @return ActiveQuery
     */
    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['project_id' => 'id']);
    }


    /**
     * Получить прикрепленные файлы
     * @return ActiveQuery
     */
    public function getPreFiles()
    {
        return $this->hasMany(PreFiles::class, ['project_id' => 'id']);
    }


    /**
     * Параметр разрешения экспертизы
     * @return int
     */
    public function getEnableExpertise()
    {
        return $this->enable_expertise;
    }


    /**
     *  Установить разрешение на экспертизу
     */
    public function setEnableExpertise()
    {
        $this->enable_expertise = EnableExpertise::ON;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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


    public function init()
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
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }


    /**
     * Показать авторов проекта
     * @return string
     */
    public function showListAuthors()
    {
        $string = '';
        $j = 0;
        foreach ($this->getAuthors() as $author) {

            $j++;
            $string .= '<div style="padding-bottom: 10px;"><div style="font-weight: bold;">Сотрудник №'.$j.'</div>';
            $string .= '<div>ФИО: ' . $author->fio . '</div>';
            $string .= '<div>Роль в проекте: ' . $author->role . '</div>';
            $string .= '<div>Опыт работы: ' . $author->experience . '</div></div>';

        }
        return $string;
    }


    /**
     * Загрузка презентационных файлов
     * @return bool
     * @throws NotFoundHttpException
     * @throws Exception
     */
    private function uploadPresentFiles(){

        $path = UPLOAD.'/user-'.$this->user->id.'/project-'.$this->id.'/present_files/';
        if (!is_dir($path)) FileHelper::createDirectory($path);

        if($this->validate()){

            foreach($this->present_files as $file){

                $filename = Yii::$app->getSecurity()->generateRandomString(15);

                try{

                    $file->saveAs($path . $filename . '.' . $file->extension);

                    $preFiles = new PreFiles();
                    $preFiles->file_name = $file;
                    $preFiles->server_file = $filename . '.' . $file->extension;
                    $preFiles->project_id = $this->id;
                    $preFiles->save(false);

                }catch (\Exception $e){

                    throw new NotFoundHttpException('Невозможно загрузить файл!');
                }
            }
            return true;
        }else{
            return false;
        }

    }


    /**
     * @param $attr
     */
    public function uniqueName ($attr)
    {
        $models = Projects::findAll(['user_id' => $this->user_id]);

        if (empty($this->id)) {
            //При создании проекта
            foreach ($models as $item) {
                if (mb_strtolower(str_replace(' ', '', $this->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                    $this->addError($attr, 'Проект с наименованием «'. $this->project_name .'» уже существует!');
                }
            }
        } else {
            //При редактировании проекта
            foreach ($models as $item) {
                if ($this->id != $item->id && mb_strtolower(str_replace(' ', '', $this->project_name)) == mb_strtolower(str_replace(' ', '', $item->project_name))) {
                    $this->addError($attr, 'Проект с наименованием «'. $this->project_name .'» уже существует!');
                }
            }
        }
    }


    /**
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function create(){

        //Преобразование даты в число
        $this->setPatentDate();
        $this->setRegisterDate();
        $this->setInvestDate();
        $this->setDateOfAnnouncement();

        if ($this->save()) {
            //Сохранение команды(авторов)
            $this->saveAuthors();
            //Загрузка презентационных файлов
            $this->present_files = UploadedFile::getInstances($this, 'present_files');
            if ($this->present_files) $this->uploadPresentFiles();

            return true;
        }
        return false;
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function updateProject(){

        //Преобразование даты в число
        $this->setPatentDate();
        $this->setRegisterDate();
        $this->setInvestDate();
        $this->setDateOfAnnouncement();

        if ($this->save()) {
            //Сохранение команды(авторов)
            $this->saveAuthors();
            //Загрузка презентационных файлов
            $this->present_files = UploadedFile::getInstances($this, 'present_files');
            $this->uploadPresentFiles();

            return true;
        }
        return false;
    }


    /**
     * Сохранение команды(авторов)
     */
    private function saveAuthors ()
    {
        $workers = Authors::find()->where(['project_id' => $this->id])->all();

        if (empty($workers)) {

            //При создании проекта
            $arr_authors = $_POST['Authors'];
            $arr_authors = array_values($arr_authors);

            foreach ($arr_authors as $arr_author) {

                $worker = new Authors();
                $worker->setFio($arr_author['fio']);
                $worker->setRole($arr_author['role']);
                $worker->setExperience($arr_author['experience']);
                $worker->setProjectId($this->id);
                $worker->save();
            }
        } else {

            //При редактировании проекта
            $arr_authors = $_POST['Authors'];
            $arr_authors = array_values($arr_authors);

            if (count($arr_authors) > count($workers)) {

                foreach ($arr_authors as $i => $arr_author) {

                    if (($i+1) <= count($workers)) {
                        $workers[$i]->setFio($arr_authors[$i]['fio']);
                        $workers[$i]->setRole($arr_authors[$i]['role']);
                        $workers[$i]->setExperience($arr_authors[$i]['experience']);
                        $workers[$i]->save();
                    } else {
                        $worker = new Authors();
                        $worker->setFio($arr_authors[$i]['fio']);
                        $worker->setRole($arr_authors[$i]['role']);
                        $worker->setExperience($arr_authors[$i]['experience']);
                        $worker->setProjectId($this->id);
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
    }


    /**
     * @throws Throwable
     * @throws ErrorException
     * @throws StaleObjectException
     */
    public function deleteStage ()
    {
        if ($segments = $this->segments) {
            foreach ($segments as $segment) {
                $segment->deleteStage();
            }
        }

        Authors::deleteAll(['project_id' => $this->id]);
        PreFiles::deleteAll(['project_id' => $this->id]);

        // Удаление директории проекта
        $projectPathDelete = UPLOAD.'/user-'.$this->user->id.'/project-'.$this->id;
        if (file_exists($projectPathDelete)) FileHelper::removeDirectory($projectPathDelete);

        // Удаление кэша для форм проекта
        $cachePathDelete = '../runtime/cache/forms/user-'.$this->user->id.'/projects/project-'.$this->id;
        if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

        // Удаление проекта
        $this->delete();
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

    /**
     * @return string
     */
    public function getProjectFullname()
    {
        return $this->project_fullname;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPurposeProject()
    {
        return $this->purpose_project;
    }

    /**
     * @return string
     */
    public function getRid()
    {
        return $this->rid;
    }

    /**
     * @return string
     */
    public function getPatentNumber()
    {
        return $this->patent_number;
    }

    /**
     * @return int
     */
    public function getPatentDate()
    {
        return $this->patent_date;
    }

    /**
     * @return string
     */
    public function getPatentName()
    {
        return $this->patent_name;
    }

    /**
     * @return string
     */
    public function getCoreRid()
    {
        return $this->core_rid;
    }

    /**
     * @return string
     */
    public function getTechnology()
    {
        return $this->technology;
    }

    /**
     * @return string
     */
    public function getLayoutTechnology()
    {
        return $this->layout_technology;
    }

    /**
     * @return string
     */
    public function getRegisterName()
    {
        return $this->register_name;
    }

    /**
     * @return int
     */
    public function getRegisterDate()
    {
        return $this->register_date;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getInvestName()
    {
        return $this->invest_name;
    }

    /**
     * @return int
     */
    public function getInvestDate()
    {
        return $this->invest_date;
    }

    /**
     * @return int
     */
    public function getInvestAmount()
    {
        return $this->invest_amount;
    }

    /**
     * @return int
     */
    public function getDateOfAnnouncement()
    {
        return $this->date_of_announcement;
    }

    /**
     *
     */
    public function setPatentDate()
    {
        if ($this->patent_date) {
            $this->patent_date = strtotime($this->patent_date);
        }
    }

    /**
     *
     */
    public function setRegisterDate()
    {
        if ($this->register_date) {
            $this->register_date = strtotime($this->register_date);
        }
    }

    /**
     *
     */
    public function setInvestDate()
    {
        if ($this->invest_date) {
            $this->invest_date = strtotime($this->invest_date);
        }
    }

    /**
     *
     */
    public function setDateOfAnnouncement()
    {
        if ($this->date_of_announcement) {
            $this->date_of_announcement = strtotime($this->date_of_announcement);
        }
    }
}
