<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "projects".
 *
 * @property string $id
 * @property int $user_id
 * @property string $created_at
 * @property string $update_at
 * @property string $project_fullname
 * @property string $project_name
 * @property string $description
 * @property string $rid
 * @property string $patent_number
 * @property string $patent_date
 * @property string $patent_name
 * @property string $core_rid
 * @property string $technology
 * @property string $layout_technology
 * @property string $register_name
 * @property string $register_date
 * @property string $site
 * @property string $invest_name
 * @property string $invest_date
 * @property int $invest_amount
 */
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

    public function getConcepts()
    {
        return $this->hasMany(Concept::class, ['project_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::class, ['project_id' => 'id']);
    }

    public function getSegments()
    {
        return $this->hasMany(Segment::class, ['project_id' => 'id']);
    }

    public function getConceptDesc($model)
    {
        $string = '';
        $j = 0;
        foreach ($model->segments as $segment) {
            if (!empty($segment->name)){
                $j++;
                $string .= "<i>Сегмент №$j:</i> <br>";
                $string .= $segment->name . "<br><br>";
            }
        }
        return $string;
    }

    public function getAuthorInfo($model)
    {
        $string = '';
        $j = 0;
        foreach ($model->authors as $author) {
            if (!empty($author->fio)){
                $j++;
                $string .= "<u>Сотрудник №$j</u> <br>";
                $string .= 'ФИО: ' . $author->fio . "<br>";
                $string .= 'Роль в проекте: ' . $author->role . "<br>";
                $string .= 'Опыт работы: ' . $author->experience . "<br><br>";
            }
        }
        return $string;
    }


    public function getFiles($model)
    {
        $doc = trim($model->files);
        $doc = substr($doc, 0, -1);
        $files = explode(',', $doc);
        return $files;
    }

    public function uploadfiles(){
        if($this->validate()){
            foreach($this->present_files as $file){
                $path = 'upload/files/' . $file->baseName . '.' . $file->extension;
                $this->files .= $file->baseName . '.' . $file->extension . ',';
                $file->saveAs($path);
                //$this->attachImage($path);
                //@unlink($path);
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'update_at', 'project_name'], 'required'],
            [['user_id', 'invest_amount'], 'integer'],
            [['created_at', 'update_at', 'patent_date', 'register_date', 'invest_date', 'date_of_announcement',], 'safe'],
            [['description', 'patent_name', 'core_rid', 'layout_technology', 'files'], 'string'],
            [['project_fullname','project_name', 'rid', 'patent_number', 'technology', 'register_name', 'site', 'invest_name', 'announcement_event',], 'string', 'max' => 255],
            [['present_files'], 'file', 'extensions' => 'png, jpg, odt, xlsx, txt, doc, docx, pdf', 'maxFiles' => 5],
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
            'update_at' => 'Дата обновления проекта',
            'project_fullname' => 'Полное наименование проекта',
            'project_name' => 'Сокращенное наименование проекта',
            'description' => 'Описание проекта',
            'rid' => 'Результат интеллектуальной деятельности',
            'patent_number' => 'Номер патента',
            'patent_date' => 'Дата получения патента',
            'patent_name' => 'Название патента',
            'core_rid' => 'Суть результата интеллектуальной деятельности',
            'technology' => 'На какой технологии основан проект',
            'layout_technology' => 'Макет базовой технологии',
            'register_name' => 'Зарегистрированное юр. лицо',
            'register_date' => 'Дата регистрации',
            'site' => 'Адрес сайта',
            'invest_name' => 'Инвестор',
            'invest_date' => 'Дата получения инвестиций',
            'invest_amount' => 'Сумма инвестиций',
            'date_of_announcement' => 'Дата анонсирования проекта',
            'announcement_event' => 'Мероприятие, на котором проект анонсирован впервые',
            'files' => 'Презентационные материалы',
        ];
    }
}
