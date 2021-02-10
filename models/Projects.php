<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class Projects extends ActiveRecord
{

    public $present_files;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'projects';
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['project_id' => 'id']);
    }

    public function getSegments()
    {
        return $this->hasMany(Segment::class, ['project_id' => 'id']);
    }

    public function getProblems ()
    {
        return $this->hasMany(GenerationProblem::class, ['project_id' => 'id']);
    }

    public function getGcps ()
    {
        return $this->hasMany(Gcp::class, ['project_id' => 'id']);
    }

    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['project_id' => 'id']);
    }

    public function getBusinessModels ()
    {
        return $this->hasMany(BusinessModel::class, ['project_id' => 'id']);
    }

    public function getPreFiles()
    {
        return $this->hasMany(PreFiles::class, ['project_id' => 'id']);
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


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function showListAuthors()
    {
        $string = '';
        $j = 0;
        foreach ($this->authors as $author) {

            $j++;
            $string .= '<div style="padding-bottom: 10px;"><div style="font-weight: 700;">Сотрудник №'.$j.'</div>';
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
     * @throws \yii\base\Exception
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
     * @throws NotFoundHttpException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function create(){

        //Преобразование даты в число
        if ($this->patent_date) $this->patent_date = strtotime($this->patent_date);
        if ($this->register_date) $this->register_date = strtotime($this->register_date);
        if ($this->invest_date) $this->invest_date = strtotime($this->invest_date);
        if ($this->date_of_announcement) $this->date_of_announcement = strtotime($this->date_of_announcement);

        if ($this->save()) {
            //Сохранение команды(авторов)
            $this->saveAuthors();
            //Загрузка презентационных файлов
            $this->present_files = UploadedFile::getInstances($this, 'present_files');
            if ($this->present_files) $this->uploadPresentFiles();
            //Удаление кэша формы создания проекта
            $cachePathDelete = '../runtime/cache/forms/user-'.$this->user->id. '/projects/formCreate';
            if (file_exists($cachePathDelete)) FileHelper::removeDirectory($cachePathDelete);

            return true;
        }
        return false;
    }


    /**
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function updateProject(){

        //Преобразование даты в число
        if ($this->patent_date) $this->patent_date = strtotime($this->patent_date);
        if ($this->register_date) $this->register_date = strtotime($this->register_date);
        if ($this->invest_date) $this->invest_date = strtotime($this->invest_date);
        if ($this->date_of_announcement) $this->date_of_announcement = strtotime($this->date_of_announcement);

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
                $worker->fio = $arr_author['fio'];
                $worker->role = $arr_author['role'];
                $worker->experience = $arr_author['experience'];
                $worker->project_id = $this->id;
                $worker->save();
            }
        } else {

            //При редактировании проекта
            $arr_authors = $_POST['Authors'];
            $arr_authors = array_values($arr_authors);

            if (count($arr_authors) > count($workers)) {

                foreach ($arr_authors as $i => $arr_author) {

                    if (($i+1) <= count($workers)) {
                        $workers[$i]->fio = $arr_authors[$i]['fio'];
                        $workers[$i]->role = $arr_authors[$i]['role'];
                        $workers[$i]->experience = $arr_authors[$i]['experience'];
                        $workers[$i]->save();
                    } else {
                        $worker = new Authors();
                        $worker->fio = $arr_authors[$i]['fio'];
                        $worker->role = $arr_authors[$i]['role'];
                        $worker->experience = $arr_authors[$i]['experience'];
                        $worker->project_id = $this->id;
                        $worker->save();
                    }
                }

            } else {

                foreach ($arr_authors as $i => $arr_author) {
                    $workers[$i]->fio = $arr_author['fio'];
                    $workers[$i]->role = $arr_author['role'];
                    $workers[$i]->experience = $arr_author['experience'];
                    $workers[$i]->save();
                }
            }
        }
    }


    /**
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     * @throws \yii\db\StaleObjectException
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
}
