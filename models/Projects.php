<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

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

    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['project_id' => 'id']);
    }

    public function getPreFiles()
    {
        return $this->hasMany(PreFiles::class, ['project_id' => 'id']);
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
            [['description', 'patent_name', 'core_rid', 'layout_technology'], 'string'],
            ['project_name', 'string', 'min' => 3, 'max' => 32],
            [['project_fullname', 'rid', 'patent_number', 'technology', 'register_name', 'site', 'invest_name', 'announcement_event',], 'string', 'max' => 255],
            [['project_fullname', 'project_name', 'rid', 'patent_number', 'technology', 'register_name', 'site', 'invest_name', 'announcement_event', 'description', 'patent_name', 'core_rid', 'layout_technology'], 'trim'],
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


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function showRoadmapProject()
    {
        $roadmaps = [];

        foreach ($this->segments as $i => $segment){
            $roadmaps[$i] = new Roadmap($segment->id);
        }

        $content = '';

        $content .= '<div class="content_roadmap">
                        
                        <div class="roadmap_row_header">

                            <div class="roadmap_block_stage">Сегменты</div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Генерация ГЦС</div>
                                <div>Дата создания</div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Подтверждение ГЦС</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Генерация ГПС</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Подтверждение ГПС</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Разработка ГЦП</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Подтверждение ГЦП</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Разработка ГMVP</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                            <div class="roadmap_block_stage text-center">
                                <div>Подтверждение ГMVP</div>
                                <div>
                                    <div>План</div>
                                    <div>Факт</div>
                                </div>
                            </div>
            
                        </div>';

        foreach ($roadmaps as $roadmap) {

            $segment_name = $roadmap->getProperty('segment_name');
            if (mb_strlen($segment_name) > 25) {
                $segment_name = mb_substr($segment_name, 0, 25) . '...';
            }


            if ($roadmap->getProperty('fact_segment_confirm') != null) {

                if ($roadmap->getProperty('fact_segment_confirm') <= $roadmap->getProperty('plan_segment_confirm')){

                    $fact_segment_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_segment_confirm')), ['/interview/view', 'id' => $roadmap->getProperty('id_confirm_segment')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_segment_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_segment_confirm')), ['/interview/view', 'id' => $roadmap->getProperty('id_confirm_segment')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_segment_confirm = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_gps') != null) {

                if ($roadmap->getProperty('fact_gps') <= $roadmap->getProperty('plan_gps')){

                    $fact_gps = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps')), ['/generation-problem/index', 'id' => $roadmap->getProperty('id_page_last_problem')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_gps = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps')), ['/generation-problem/index', 'id' => $roadmap->getProperty('id_page_last_problem')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_gps = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_gps_confirm') != null) {

                if ($roadmap->getProperty('fact_gps_confirm') <= $roadmap->getProperty('plan_gps_confirm')){

                    $fact_gps_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps_confirm')), ['/confirm-problem/view', 'id' => $roadmap->getProperty('id_confirm_problem')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_gps_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps_confirm')), ['/confirm-problem/view', 'id' => $roadmap->getProperty('id_confirm_problem')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_gps_confirm = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_gcp') != null) {

                if ($roadmap->getProperty('fact_gcp') <= $roadmap->getProperty('plan_gcp')){

                    $fact_gcp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp')), ['/gcp/index', 'id' => $roadmap->getProperty('id_page_last_gcp')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_gcp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp')), ['/gcp/index', 'id' => $roadmap->getProperty('id_page_last_gcp')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_gcp = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_gcp_confirm') != null) {

                if ($roadmap->getProperty('fact_gcp_confirm') <= $roadmap->getProperty('plan_gcp_confirm')){

                    $fact_gcp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp_confirm')), ['/confirm-gcp/view', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_gcp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp_confirm')), ['/confirm-gcp/view', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_gcp_confirm = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_mvp') != null) {

                if ($roadmap->getProperty('fact_mvp') <= $roadmap->getProperty('plan_mvp')){

                    $fact_mvp = Html::a(date('d.m.y',$roadmap->getProperty('fact_mvp')), ['/mvp/index', 'id' => $roadmap->getProperty('id_page_last_mvp')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_mvp = Html::a(date('d.m.y',$roadmap->getProperty('fact_mvp')), ['/mvp/index', 'id' => $roadmap->getProperty('id_page_last_mvp')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_mvp = '_ _ _ _ _ _';
            }


            if ($roadmap->getProperty('fact_mvp_confirm') != null) {

                if ($roadmap->getProperty('fact_mvp_confirm') <= $roadmap->getProperty('plan_mvp_confirm')){

                    $fact_mvp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_mvp_confirm')), ['/confirm-mvp/view', 'id' => $roadmap->getProperty('id_confirm_mvp')], ['class' => 'roadmap_block_date_link_success']);
                }else {

                    $fact_mvp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_mvp_confirm')), ['/confirm-mvp/view', 'id' => $roadmap->getProperty('id_confirm_mvp')], ['class' => 'roadmap_block_date_link_danger']);
                }
            }else {
                $fact_mvp_confirm = '_ _ _ _ _ _';
            }



            $content .= '<div class="roadmap_row_dates">

                            <div class="roadmap_block_name_segment" title="'.$roadmap->getProperty('segment_name').'">
                                '.$segment_name.'
                            </div>
                            
                            <div class="roadmap_block_date_segment">
                                '.date('d.m.y',$roadmap->getProperty('created_at')).'
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_segment_confirm')).'
                                </div>
                                
                                <div>
                                    '.$fact_segment_confirm.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_gps')).'
                                </div>
                                
                                <div>
                                    '.$fact_gps.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_gps_confirm')).'
                                </div>
                                
                                <div>
                                    '.$fact_gps_confirm.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_gcp')).'
                                </div>
                                
                                <div>
                                    '.$fact_gcp.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_gcp_confirm')).'
                                </div>
                                
                                <div>
                                    '.$fact_gcp_confirm.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_mvp')).'
                                </div>
                                
                                <div>
                                    '.$fact_mvp.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_mvp_confirm')).'
                                </div>
                                
                                <div>
                                    '.$fact_mvp_confirm.'
                                </div>
                            
                            </div>
                            
                        </div>';
        }

        $content .= '</div>';

        return $content;
    }
}
