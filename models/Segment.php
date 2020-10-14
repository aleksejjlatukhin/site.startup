<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * This is the model class for table "segments".
 *
 * @property string $id
 * @property int $project_id
 * @property string $name
 * @property string $field_of_activity
 * @property string $sort_of_activity
 * @property string $age
 * @property string $income
 * @property string $quantity
 * @property string $market_volume
 * @property string $add_info
 */
class Segment extends \yii\db\ActiveRecord
{

    const TYPE_B2C = 100;
    const TYPE_B2B = 200;

    const GENDER_MAN = 50;
    const GENDER_WOMAN = 60;
    const GENDER_ANY = 70;

    const SECONDARY_EDUCATION = 50;
    const SECONDARY_SPECIAL_EDUCATION = 100;
    const HIGHER_INCOMPLETE_EDUCATION = 200;
    const HIGHER_EDUCATION = 300;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'segments';
    }

    public function getProject()
    {
        return $this->hasOne(Projects::class, ['id' => 'project_id']);
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['segment_id' => 'id']);
    }

    public function getProblems ()
    {
        return $this->hasMany(GenerationProblem::class, ['segment_id' => 'id']);
    }

    public function getGcps ()
    {
        return $this->hasMany(Gcp::class, ['segment_id' => 'id']);
    }

    public function getMvps ()
    {
        return $this->hasMany(Mvp::class, ['segment_id' => 'id']);
    }

    public function getBusinessModel ()
    {
        return $this->hasOne(BusinessModel::class, ['segment_id' => 'id']);
    }

    public function getAllInformation ()
    {
        return DetailView::widget([
            'model' => $this,
            'attributes' => [

                'name',
                'description:ntext',

                [
                    'attribute' => 'type_of_interaction_between_subjects',
                    'label' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
                    'value' => function ($model) {
                        if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C){
                            return 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)';
                        }
                        elseif ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B){
                            return 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)';
                        }
                        else{
                            return '';
                        }
                    },
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'field_of_activity_b2c',
                    'label' => 'Сфера деятельности потребителя',
                    'value' => function ($model) {
                        return $model->field_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],

                [
                    'attribute' => 'field_of_activity_b2b',
                    'label' => 'Сфера деятельности предприятия',
                    'value' => function ($model) {
                        return $model->field_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],

                [
                    'attribute' => 'sort_of_activity_b2c',
                    'label' => 'Вид деятельности потребителя',
                    'value' => function ($model) {
                        return $model->sort_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],

                [
                    'attribute' => 'sort_of_activity_b2b',
                    'label' => 'Вид деятельности предприятия',
                    'value' => function ($model) {
                        return $model->sort_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],

                [
                    'attribute' => 'specialization_of_activity_b2c',
                    'label' => 'Специализация вида деятельности потребителя',
                    'value' => function ($model) {
                        return $model->specialization_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],

                [
                    'attribute' => 'specialization_of_activity_b2b',
                    'label' => 'Специализация вида деятельности предприятия',
                    'value' => function ($model) {
                        return $model->specialization_of_activity;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],

                [
                    'attribute' => 'company_products',
                    'label' => 'Продукция / услуги предприятия',
                    'value' => function ($model) {
                        return $model->company_products;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],

                [
                    'attribute' => 'company_partner',
                    'label' => 'Партнеры предприятия',
                    'value' => function ($model) {
                        return $model->company_partner;
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],

                [
                    'attribute' => 'age',
                    'label' => 'Возраст потребителя',
                    'value' => function ($model) {
                        if ($model->age_from !== null && $model->age_to !== null){
                            return 'от ' . number_format($model->age_from, 0, '', ' ') . ' до '
                                . number_format($model->age_to, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],

                [
                    'attribute' => 'gender_consumer',
                    'label' => 'Пол потребителя',
                    'value' => function ($model) {
                        if ($model->gender_consumer == Segment::GENDER_WOMAN) {
                            return 'Женский';
                        } elseif ($model->gender_consumer == Segment::GENDER_MAN) {
                            return 'Мужской';
                        } else {
                            return 'Не важно';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],

                [
                    'attribute' => 'education_of_consumer',
                    'label' => 'Образование потребителя',
                    'value' => function ($model) {
                        if ($model->education_of_consumer == Segment::SECONDARY_EDUCATION) {
                            return 'Среднее образование';
                        }elseif ($model->education_of_consumer == Segment::SECONDARY_SPECIAL_EDUCATION) {
                            return 'Среднее образование (специальное)';
                        }elseif ($model->education_of_consumer == Segment::HIGHER_INCOMPLETE_EDUCATION) {
                            return 'Высшее образование (незаконченное)';
                        }elseif ($model->education_of_consumer == Segment::HIGHER_EDUCATION) {
                            return 'Высшее образование';
                        }else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],


                [
                    'attribute' => 'income_b2c',
                    'label' => 'Доход потребителя (тыс. руб./мес.)',
                    'value' => function ($model) {
                        if ($model->income_from !== null && $model->income_to !== null){
                            return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                                . number_format($model->income_to, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],


                [
                    'attribute' => 'income_b2b',
                    'label' => 'Доход предприятия (млн. руб./год)',
                    'value' => function ($model) {
                        if ($model->income_from !== null && $model->income_to !== null){
                            return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                                . number_format($model->income_to, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],


                [
                    'attribute' => 'quantity_b2c',
                    'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                    'value' => function ($model) {
                        if ($model->quantity_from !== null && $model->quantity_to !== null){
                            return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                                . number_format($model->quantity_to, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                ],


                [
                    'attribute' => 'quantity_b2b',
                    'label' => 'Потенциальное количество представителей сегмента (ед.)',
                    'value' => function ($model) {
                        if ($model->quantity_from !== null && $model->quantity_to !== null){
                            return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                                . number_format($model->quantity_to, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'visible' => ($this->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                ],


                [
                    'attribute' => 'market_volume',
                    'label' => 'Объем рынка (млн. руб./год)',
                    'value' => function ($model) {
                        if ($model->market_volume !== null){
                            return number_format($model->market_volume, 0, '', ' ');
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'add_info',
                    'visible' => !empty($this->add_info),
                ],
            ],
        ]);
    }
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'time_confirm'], 'integer'],
            [['name', 'field_of_activity', 'sort_of_activity', 'add_info', 'description', 'specialization_of_activity'], 'trim'],
            [['project_id', 'type_of_interaction_between_subjects', 'gender_consumer', 'education_of_consumer', 'exist_confirm'], 'integer'],
            [['age_from', 'age_to'], 'integer'],
            [['income_from', 'income_to'], 'integer'],
            [['quantity_from', 'quantity_to'], 'integer'],
            [['market_volume'], 'integer'],
            [['add_info'], 'string'],
            [['name',], 'string', 'min' => 6, 'max' => 65],
            [['field_of_activity', 'sort_of_activity', 'specialization_of_activity', 'description', 'company_products', 'company_partner'], 'string', 'max' => 255],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'name' => 'Наименование сегмента',
            'description' => 'Краткое описание сегмента',
            'type_of_interaction_between_subjects' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
            'field_of_activity' => 'Сфера деятельности потребителя',
            'sort_of_activity' => 'Вид деятельности потребителя',
            'specialization_of_activity' => 'Специализация вида деятельности потребителя',
            'age_from' => 'Возраст потребителя',
            'gender_consumer' => 'Пол потребителя',
            'education_of_consumer' => 'Образование потребителя',
            'income_from' => 'Доход потребителя (тыс. руб./мес.)',
            'quantity_from' => 'Потенциальное количество потребителей (тыс. чел.)',
            'market_volume' => 'Объем рынка (млн. руб./год)',
            'company_products' => 'Продукция / услуги предприятия',
            'company_partner' => 'Партнеры предприятия',
            'add_info' => 'Дополнительная информация',
        ];
    }


    /* Поведения */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }


    public function showRoadmapSegment()
    {
        $roadmap = new Roadmap($this->id);

        $content = '';

        $content .= '<div class="content_roadmap">
                        
                        <div class="roadmap_row_header">

                            <div class="roadmap_block_stage" style="display: none;">Сегменты</div>
            
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

                $fact_gps = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps')), ['/generation-problem/index', 'id' => $roadmap->getProperty('id_confirm_segment')], ['class' => 'roadmap_block_date_link_success']);
            }else {

                $fact_gps = Html::a(date('d.m.y',$roadmap->getProperty('fact_gps')), ['/generation-problem/index', 'id' => $roadmap->getProperty('id_confirm_segment')], ['class' => 'roadmap_block_date_link_danger']);
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

                $fact_gcp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp')), ['/gcp/index', 'id' => $roadmap->getProperty('id_confirm_problem')], ['class' => 'roadmap_block_date_link_success']);
            }else {

                $fact_gcp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gcp')), ['/gcp/index', 'id' => $roadmap->getProperty('id_confirm_problem')], ['class' => 'roadmap_block_date_link_danger']);
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


        if ($roadmap->getProperty('fact_gmvp') != null) {

            if ($roadmap->getProperty('fact_gmvp') <= $roadmap->getProperty('plan_gmvp')){

                $fact_gmvp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gmvp')), ['/mvp/index', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_success']);
            }else {

                $fact_gmvp = Html::a(date('d.m.y',$roadmap->getProperty('fact_gmvp')), ['/mvp/index', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_danger']);
            }
        }else {
            $fact_gmvp = '_ _ _ _ _ _';
        }


        if ($roadmap->getProperty('fact_gmvp_confirm') != null) {

            if ($roadmap->getProperty('fact_gmvp_confirm') <= $roadmap->getProperty('plan_gmvp_confirm')){

                $fact_gmvp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gmvp_confirm')), ['/confirm-mvp/view', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_success']);
            }else {

                $fact_gmvp_confirm = Html::a(date('d.m.y',$roadmap->getProperty('fact_gmvp_confirm')), ['/confirm-mvp/view', 'id' => $roadmap->getProperty('id_confirm_gcp')], ['class' => 'roadmap_block_date_link_danger']);
            }
        }else {
            $fact_gmvp_confirm = '_ _ _ _ _ _';
        }



        $content .= '<div class="roadmap_row_dates" style="width: 1120px;">

                            <div class="roadmap_block_name_segment" style="display: none;">
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
                                    '.date('d.m.y',$roadmap->getProperty('plan_gmvp')).'
                                </div>
                                
                                <div>
                                    '.$fact_gmvp.'
                                </div>
                            
                            </div>
                            
                            <div class="roadmap_block_date">
                            
                                <div>
                                    '.date('d.m.y',$roadmap->getProperty('plan_gmvp_confirm')).'
                                </div>
                                
                                <div>
                                    '.$fact_gmvp_confirm.'
                                </div>
                            
                            </div>
                            
                        </div>
                        
                    </div>';


        return $content;
    }
}
