<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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
     * @param $path
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function upload($path){

        if (!is_dir($path)){

            throw new NotFoundHttpException('Дирректория не существует!');

        }else{

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
    }


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
     * Изменение имени дирректории при редактировании проекта
     */
    public function updateProjectDirectory()
    {
        $user = User::findOne(['id' => $this->user_id]);
        $models = Projects::findAll(['user_id' => $this->user_id]);

        foreach ($models as $elem){

            if ($this->id == $elem->id && mb_strtolower(str_replace(' ', '',$this->project_name)) !== mb_strtolower(str_replace(' ', '',$elem->project_name))){

                $old_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($elem->project_name), "windows-1251") . '/';

                $old_dir = mb_strtolower($old_dir, "windows-1251");

                $new_dir = UPLOAD . mb_convert_encoding(mb_strtolower($user['username'], "windows-1251"), "windows-1251")
                    . '/' . mb_convert_encoding($this->translit($this->project_name), "windows-1251") . '/';

                $new_dir = mb_strtolower($new_dir, "windows-1251");

                rename($old_dir, $new_dir);
            }
        }
        return true;
    }


    /**
     * Сохранение команды(авторов)
     */
    public function saveAuthors ()
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
     * Создание дирректорий проекта
     * @param $user
     */
    public function createDirectories ($user)
    {
        $user_dir = UPLOAD . mb_convert_encoding($user['username'], "windows-1251") . '/';
        $user_dir = mb_strtolower($user_dir, "windows-1251");
        if (!file_exists($user_dir)){
            mkdir($user_dir, 0777);
        }

        $project_dir = $user_dir . '/' . mb_convert_encoding($this->translit($this->project_name) , "windows-1251") . '/';
        $project_dir = mb_strtolower($project_dir, "windows-1251");
        if (!file_exists($project_dir)){
            mkdir($project_dir, 0777);
        }

        $present_files_dir = $project_dir . '/present files/';
        if (!file_exists($present_files_dir)){
            mkdir($present_files_dir, 0777);
        }

        $segments_dir = $project_dir . '/segments/';
        if (!file_exists($segments_dir)){
            mkdir($segments_dir, 0777);
        }

        return $present_files_dir;
    }


    public function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат

    }


    public function deleteStage ()
    {

        if ($segments = $this->segments) {
            foreach ($segments as $segment) {
                $segment->deleteStage();
            }
        }

        Authors::deleteAll(['project_id' => $this->id]);
        PreFiles::deleteAll(['project_id' => $this->id]);

        $this->delete();
    }
}
