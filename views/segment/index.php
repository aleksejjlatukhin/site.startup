<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;

use app\models\TypeOfActivityB2B;
use app\models\TypeOfActivityB2C;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\Segment;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация гипотез целевых сегментов';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/segments-index-style.css');

?>
<div class="segment-index">

    <div class="row" style="border-radius: 0 0 12px 12px; background: #707F99;">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name_link">
            <span style="padding-right: 20px; font-weight: 400;">Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links',
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
        ]) ?>

        <?= Html::a('Дорожная карта сегментов', ['/segment/roadmap', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links  text-center',
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links  text-center',
        ]) ?>

    </div>


    <div class="row" style="margin-top: 30px;">

        <?= Html::a('<div class="new_segment_link_block" style="display:flex; align-items: center;"><div>' . Html::img('/images/icons/add_plus_elem.png', ['width' => '25px']) . '</div><div class="new_segment_link">Новый сегмент</div></div>', Url::to(['/segment/create', 'id' => $project->id]), [
            'class' => 'col-md-3 new_segment_link',
            //'style' => ['padding-left' => '28px'],
            'data-toggle' => 'modal',
            'data-target' => "#create_segment_modal",
        ]) ?>

        <div class="col-md-9" style="font-size: 28px; margin-top: -7px;">

            Этап 1 из 9.

            <span class=""style="font-weight: 700;">
                <?= $this->title; ?>
            </span>

        </div>



    </div>


    <?php

        $gridColumns = [

            [
                'attribute' => 'status',
                'label' => false,
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model->exist_confirm === 1) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/interview/view', 'id' => $model->interview->id])) . '</div>';

                    }elseif ($model->exist_confirm === null && empty($model->interview)) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]), Url::to(['/interview/create', 'id' => $model->id])) . '</div>';

                    }elseif ($model->exist_confirm === null && !empty($model->interview)) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]), Url::to(['/interview/view', 'id' => $model->interview->id])) . '</div>';

                    }elseif ($model->exist_confirm === 0) {

                        return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/interview/view', 'id' => $model->interview->id])) . '</div>';

                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'name',
                'encodeLabel' => false,
                'label' => '<div style="margin: 15px 0; width: 200px; display: inline-block; color: #4F4F4F;">Наименование сегмента' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'</div>',
                'value' => function ($model) {
                    if (empty($model->creat_date)){

                        return Html::a(Html::encode($model->name), Url::to(['/segment/update', 'id' => $model->id]), [
                            'class' => 'table-kartik-link',
                            'title' => 'Редактирование',
                        ]);

                    } else {

                        if ($model->interview) {

                            return Html::a(Html::encode($model->name), Url::to(['/interview/view', 'id' => $model->interview->id]), [
                                'class' => 'table-kartik-link',
                                'title' => 'Переход к программе генерации ГПС',
                            ]);

                        } else {

                            return Html::a(Html::encode($model->name), Url::to(['/interview/create', 'id' => $model->id]), [
                                'class' => 'table-kartik-link',
                                'title' => 'Создание программы генерации ГПС',
                            ]);
                        }
                    }
                },
                'format' => 'raw',
                'contentOptions'=>['style'=>'white-space: normal; width: 200px;'],
                //'enableSorting' => false,
            ],

            [
                'attribute' => 'type_of_interaction_between_subjects',
                'encodeLabel' => false,
                'label' => '<div class="text-center" style="margin: 15px 0; width: 50px; display: inline-block; color: #4F4F4F;">Тип' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'</div>',
                //'header' => 'Тип',
                'value' => function($model){
                    if ($model->type_of_interaction_between_subjects === \app\models\Segment::TYPE_B2C) {
                        return '<div class="">B2C</div>';
                    }
                    elseif ($model->type_of_interaction_between_subjects === \app\models\Segment::TYPE_B2B) {
                        return '<div class="">B2B</div>';
                    }
                    else {
                        return '';
                    }
                },
                'format' => 'raw',
                'contentOptions'=>['style'=>'white-space: normal; width: 50px;'],
                //'enableSorting' => false
            ],

            [
                'attribute' => 'field_of_activity',
                'encodeLabel' => false,
                'label' => '<div style="margin: 15px 0; width: 235px; display: inline-block; color: #4F4F4F;">Сфера деятельности' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'</div>',
                'value' => function($model) {

                    $field_of_activity = $model->field_of_activity;

                    if (mb_strlen($field_of_activity) > 65) {
                        $field_of_activity = mb_substr($field_of_activity, 0, 65);
                        $field_of_activity = $field_of_activity . ' ...';
                    }

                    return '<div title="' . $model->field_of_activity . '">' . $field_of_activity . '</div>';
                },
                'contentOptions'=>['style'=>'white-space: normal; width: 235px;'],
                'format' => 'raw',
                //'enableSorting' => false,
            ],

            [
                'attribute' => 'sort_of_activity',
                'encodeLabel' => false,
                'label' => '<div style="margin: 15px 0; width: 235px; display: inline-block; color: #4F4F4F;">Вид деятельности' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'</div>',
                'value' => function($model) {

                    $sort_of_activity = $model->sort_of_activity;

                    if (mb_strlen($sort_of_activity) > 65) {
                        $sort_of_activity = mb_substr($sort_of_activity, 0, 65);
                        $sort_of_activity = $sort_of_activity . ' ...';
                    }

                    return '<div title="' . $model->sort_of_activity . '">' . $sort_of_activity . '</div>';
                },
                'contentOptions' => ['style'=>'white-space: normal; width: 235px;'],
                'format' => 'raw',
                //'enableSorting' => false
            ],

            [
                'attribute' => 'specialization_of_activity',
                'encodeLabel' => false,
                'label' => '<div style="margin: 15px 0; width: 235px; display: inline-block; color: #4F4F4F;">Специализация' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'</div>',
                'value' => function($model) {

                    $specialization_of_activity = $model->specialization_of_activity;

                    if (mb_strlen($specialization_of_activity) > 65) {
                        $specialization_of_activity = mb_substr($specialization_of_activity, 0, 65);
                        $specialization_of_activity = $specialization_of_activity . ' ...';
                    }

                    return '<div title="' . $model->specialization_of_activity . '">' . $specialization_of_activity . '</div>';
                },
                'contentOptions' => ['style'=>'white-space: normal; width: 235px;'],
                'format' => 'raw',
                //'enableSorting' => false
            ],

            [
                'attribute' => 'market_volume',
                'encodeLabel' => false,
                'label' => '<div style="margin: 15px 0; width: 120px; display: inline-block; color: #4F4F4F;">

                    <div style="display:flex; align-items: center; width: 120px; color: #4F4F4F; margin-bottom: -7px;">
                        
                        <div>
                            <div>Объем рынка</div>
                            <div style="font-weight: 400; font-size: 14px;margin-top: -5px;">млн. руб./год</div>
                        </div>
                        
                        ' . Html::img('/images/icons/icon_vector_down.png', ['style' => ['height' => '17px', 'margin-left' => '10px']]) .'
                    
                    </div>
                </div>',
                'contentOptions' => ['style' => 'white-space: normal; width: 110px; padding: 5px 20px;', 'class' => 'text-right'],
                //'enableSorting' => false
            ],

            [
                'attribute' => 'detail',
                'encodeLabel' => false,
                'label' => false,
                'value' => function($model){

                    return '<div class="text-center">' .

                        Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '25px', 'margin-right' => '20px']]),['/segment/view', 'id' => $model->id], [
                            'class' => '',
                            'title' => 'Смотреть',
                            'data-toggle' => 'modal',
                            'data-target' => "#segment_view_modal-$model->id",
                        ]) .

                        Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '17px', 'margin-right' => '20px']]),['/segment/update', 'id' => $model->id], [
                            'class' => '',
                            'title' => 'Редактировать',
                            'data-toggle' => 'modal',
                            'data-target' => "#update_segment_modal-$model->id",
                        ]) .

                        Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '17px']]),['#'], [
                            'class' => '',
                            'title' => 'Удалить'
                        ]) .

                        '</div>';

                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'white-space: normal;'],
                //'enableSorting' => false
            ],

        ];

    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showPageSummary' => false, //whether to display the page summary row for the grid view.
        //'showHeader' => false, // Скрытие header у всех столбцов
        'summary' => false,
        'id' => 'TableSegments',
        'pjax' => false,
        'pjaxSettings' => [
            //'neverTimeout' => false,
            //'beforeGrid' => '',
            'options' => [
                'id' => 'segmentsPjax',
                //'enablePushState' => false,
            ],
            'loadingCssClass' => false,
        ],
        'options' => ['class' => 'row'],
        'striped' => false,
        'bordered' => false,
        'condensed' => true,
        'hover' => true,
        'toolbar' => false,
        'columns' => $gridColumns,
        //'headerRowOptions' => ['class' => ''],
        //'rowOptions' => ['class' => GridView::TYPE_DANGER]
    ]); ?>


    <?php
    // Модальное окно - данные проекта
    Modal::begin([
        'options' => [
            'id' => 'data_project_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Исходные данные по проекту</h3>',
    ]);
    ?>

    <?= DetailView::widget([
        'model' => $project,
        //'options' => ['class' => 'table table-bordered detail-view'], //Стилизация таблицы
        'attributes' => [

            'project_name',
            'project_fullname:ntext',
            'description:ntext',
            'rid',
            'core_rid:ntext',
            'patent_number',

            [
                'attribute' => 'patent_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'patent_name:ntext',

            [
                'attribute'=>'Команда проекта',
                'value' => $project->getAuthorInfo($project),
                'format' => 'html',
            ],

            'technology',
            'layout_technology:ntext',
            'register_name',

            [
                'attribute' => 'register_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'site',
            'invest_name',

            [
                'attribute' => 'invest_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'invest_amount',
                'value' => function($project){
                    if($project->invest_amount !== null){
                        return number_format($project->invest_amount, 0, '', ' ');
                    }
                },
            ],

            [
                'attribute' => 'date_of_announcement',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'announcement_event',

            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'update_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'pre_files',
                'label' => 'Презентационные файлы',
                'value' => function($model){
                    $string = '';
                    foreach ($model->preFiles as $file){
                        $string .= Html::a($file->file_name, ['/projects/download', 'id' => $file->id], ['class' => '']) . '<br>';
                    }
                    return $string;
                },
                'format' => 'html',
            ]

        ],
    ]) ?>

    <?php
    Modal::end();
    ?>


    <?php
    // Модальное окно - создание нового сегмента
    Modal::begin([
        'options' => [
            'id' => 'create_segment_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Создание нового сегмента</h3>',
    ]);
    ?>

    <div class="segment-form-create">

        <?php $form = ActiveForm::begin(['id' => 'formCreateSegment', 'action' => Url::to(['/segment/create', 'id' => $project->id])]); ?>

        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($newSegment, 'name', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-5">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Наименование сегмента *')->textInput(['maxlength' => true]);
            ?>

        </div>

        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($newSegment, 'description', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Краткое описание сегмента *')->textarea(['rows' => 2]);
            ?>

        </div>

        <div class="row" style="margin-bottom: 10px;">

            <?php
            $list_of_interactions = [
                Segment::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
                Segment::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
            ];
            ?>

            <?= $form->field($newSegment, 'type_of_interaction_between_subjects', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12 type_of_interaction">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Вид информационного и экономического взаимодействия между субъектами рынка *')->widget(Select2::class, [
                'data' => $list_of_interactions,
                'options' => [
                    'id' => 'type-interaction',
                ],
                'disabled' => false,  //Сделать поле неактивным
                'hideSearch' => true, //Скрытие поиска
            ]);
            ?>

        </div>


        <div class="form-template-b2c">

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $listOfAreasOfActivityB2C = TypeOfActivityB2C::getListOfAreasOfActivity();
                $listOfAreasOfActivityB2C = ArrayHelper::map($listOfAreasOfActivityB2C,'id', 'name');
                ?>

                <?= $form->field($newSegment, 'field_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Сфера деятельности потребителя *')->widget(Select2::class, [
                    'data' => $listOfAreasOfActivityB2C,
                    'options' => [
                        'placeholder' => 'Выберите cферу деятельности потребителя',
                        'id' => 'listOfAreasOfActivityB2C',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'sort_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Вид деятельности потребителя *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfActivitiesB2C',
                        'placeholder' => 'Выберите вид деятельности потребителя'
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfAreasOfActivityB2C'],
                        'placeholder' => 'Выберите вид деятельности потребителя',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2c'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'specialization_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Специализация вида деятельности потребителя *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfSpecializationsB2C',
                        'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfActivitiesB2C'],
                        'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2c'])
                    ]
                ]);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#age_from").change(function () {
                        var value1 = $("input#age_from").val();
                        var value2 = $("input#age_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#age_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#age_to").change(function () {
                        var value1 = $("input#age_from").val();
                        var value2 = $("input#age_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#age_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($newSegment, 'age_from', [
                    'template' => '<div class="col-md-4" style="margin-top: 10px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}<div>{error}</div></div>'
                ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 100)</div>')
                    ->textInput(['type' => 'number', 'id' => 'age_from']);
                ?>

                <?= $form->field($newSegment, 'age_to', [
                    'template' => '<div class="col-md-4">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'age_to']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_gender = [
                    Segment::GENDER_ANY => 'Не важно',
                    Segment::GENDER_MAN => 'Мужской',
                    Segment::GENDER_WOMAN => 'Женский',
                ];
                ?>

                <?= $form->field($newSegment, 'gender_consumer', [
                    'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('<div>Пол потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                    ->widget(Select2::class, [
                        'data' => $list_gender,
                        'pluginOptions' => ['allowClear' => true],
                        'options' => [
                            //'id' => 'type-interaction',
                            'placeholder' => 'Выберите пол потребителя',
                        ],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_education = [
                    Segment::SECONDARY_EDUCATION => 'Среднее образование',
                    Segment::SECONDARY_SPECIAL_EDUCATION => 'Среднее образование (специальное)',
                    Segment::HIGHER_INCOMPLETE_EDUCATION => 'Высшее образование (незаконченное)',
                    Segment::HIGHER_EDUCATION => 'Высшее образование'
                ];
                ?>

                <?= $form->field($newSegment, 'education_of_consumer', [
                    'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('<div>Образование потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                    ->widget(Select2::class, [
                        'data' => $list_education,
                        'pluginOptions' => ['allowClear' => true],
                        'options' => [
                            //'id' => 'type-interaction',
                            'placeholder' => 'Выберите уровень образования потребителя',
                        ],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#income_from").change(function () {
                        var value1 = $("input#income_from").val();
                        var value2 = $("input#income_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#income_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#income_to").change(function () {
                        var value1 = $("input#income_from").val();
                        var value2 = $("input#income_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#income_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: 0px;">

                <?= $form->field($newSegment, 'income_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 5 000 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'income_from']);
                ?>

                <?= $form->field($newSegment, 'income_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to']);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#quantity_from").change(function () {
                        var value1 = $("input#quantity_from").val();
                        var value2 = $("input#quantity_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#quantity_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#quantity_to").change(function () {
                        var value1 = $("input#quantity_from").val();
                        var value2 = $("input#quantity_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#quantity_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($newSegment, 'quantity_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'quantity_from']);
                ?>

                <?= $form->field($newSegment, 'quantity_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($newSegment, 'market_volume_b2c', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'market_volume_b2c']);
                ?>

            </div>

        </div>


        <div class="form-template-b2b" style="display: none;">

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $listOfAreasOfActivityB2B = TypeOfActivityB2B::getListOfAreasOfActivity();
                $listOfAreasOfActivityB2B = ArrayHelper::map($listOfAreasOfActivityB2B,'id', 'name');
                ?>

                <?= $form->field($newSegment, 'field_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Сфера деятельности предприятия *')->widget(Select2::class, [
                    'data' => $listOfAreasOfActivityB2B,
                    'options' => [
                        'placeholder' => 'Выберите cферу деятельности предприятия',
                        'id' => 'listOfAreasOfActivityB2B',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'sort_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Вид деятельности предприятия *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfActivitiesB2B',
                        'placeholder' => 'Выберите вид деятельности предприятия',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfAreasOfActivityB2B'],
                        'placeholder' => 'Выберите вид деятельности предприятия',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2b'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'specialization_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Специализация вида деятельности предприятия *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfSpecializationsB2B',
                        'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfActivitiesB2B'],
                        'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2b'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($newSegment, 'company_products', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Продукция / услуги предприятия *')->textarea(['rows' => 2]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($newSegment, 'company_partner', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Партнеры предприятия *')->textarea(['rows' => 2])
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#quantity_from_b2b").change(function () {
                        var value1 = $("input#quantity_from_b2b").val();
                        var value2 = $("input#quantity_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#quantity_from_b2b").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#quantity_to_b2b").change(function () {
                        var value1 = $("input#quantity_from_b2b").val();
                        var value2 = $("input#quantity_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#quantity_to_b2b").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($newSegment, 'quantity_from_b2b', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'quantity_from_b2b']);
                ?>

                <?= $form->field($newSegment, 'quantity_to_b2b', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to_b2b']);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#income_from_b2b").change(function () {
                        var value1 = $("input#income_from_b2b").val();
                        var value2 = $("input#income_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#income_from_b2b").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#income_to_b2b").change(function () {
                        var value1 = $("input#income_from_b2b").val();
                        var value2 = $("input#income_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#income_to_b2b").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'income_company_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'income_from_b2b']);
                ?>

                <?= $form->field($newSegment, 'income_company_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to_b2b']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'market_volume_b2b', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'market_volume_b2b']);
                ?>

            </div>

        </div>


        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($newSegment, 'add_info', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
            ])->textarea(['rows' => 2]);
            ?>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php
    Modal::end();
    ?>



    <?php

    foreach ($models as $i => $model) :

    // Модальное окно - Редактирование сегмента
    Modal::begin([
        'options' => [
            'id' => 'update_segment_modal-' . $model->id,
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Редактирование данных сегмента</h3>',
    ]);
    ?>


        <div class="segment-update-form">

            <?php $form = ActiveForm::begin(['id' => 'formUpdateSegment-' .$model->id, 'action' => Url::to(['/segment/update', 'id' => $model->id])]); ?>

            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($updateSegments[$i], 'name', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-5">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Наименование сегмента *')->textInput(['maxlength' => true]);
                ?>

            </div>

            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($updateSegments[$i], 'description', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Краткое описание сегмента *')->textarea(['rows' => 2]);
                ?>

            </div>

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_of_interactions = [
                    Segment::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
                    Segment::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
                ];
                ?>

                <?= $form->field($updateSegments[$i], 'type_of_interaction_between_subjects', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12 type_of_interaction">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Вид информационного и экономического взаимодействия между субъектами рынка *')->widget(Select2::class, [
                    'data' => $list_of_interactions,
                    'options' => [
                        'id' => 'type-interaction-' . $model->id,
                    ],
                    'disabled' => true,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
                ?>

            </div>


            <div class="form-update-template-b2c-<?= $model->id; ?>">

                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $listOfAreasOfActivityB2C = TypeOfActivityB2C::getListOfAreasOfActivity();
                    $listOfAreasOfActivityB2C = ArrayHelper::map($listOfAreasOfActivityB2C,'id', 'name');
                    ?>

                    <?= $form->field($updateSegments[$i], 'field_of_activity_b2c', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Сфера деятельности потребителя *')->widget(Select2::class, [
                        'data' => $listOfAreasOfActivityB2C,
                        'options' => [
                            'placeholder' => 'Выберите cферу деятельности потребителя',
                            'id' => 'listOfAreasOfActivityB2C-' . $model->id,
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => ['allowClear' => true]
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'sort_of_activity_b2c', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Вид деятельности потребителя *')->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'options' => [
                            'id' => 'listOfActivitiesB2C-' . $model->id,
                            'placeholder' => 'Выберите вид деятельности потребителя'
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => [
                            'depends' => ['listOfAreasOfActivityB2C'],
                            'placeholder' => 'Выберите вид деятельности потребителя',
                            'nameParam' => 'name',
                            'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2c'])
                        ]
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'specialization_of_activity_b2c', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Специализация вида деятельности потребителя *')->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'options' => [
                            'id' => 'listOfSpecializationsB2C-' . $model->id,
                            'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => [
                            'depends' => ['listOfActivitiesB2C'],
                            'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                            'nameParam' => 'name',
                            'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2c'])
                        ]
                    ]);
                    ?>

                </div>


                <script>

                    $( function() {

                        var age_from = 'input#age_from-<?= $model->id; ?>';
                        var age_to = 'input#age_to-<?= $model->id; ?>';

                        //Изменение местоположения ползунка при вводе данных в первый элемент Input
                        $(age_from).change(function () {
                            var value1 = $(age_from).val();
                            var value2 = $(age_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $(age_from).val(value1);
                            }
                        });

                        //Изменение местоположения ползунка при вводе данных во второй элемент Input
                        $(age_to).change(function () {
                            var value1 = $(age_from).val();
                            var value2 = $(age_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value2 = value1;
                                $(age_to).val(value2);
                            }
                        });

                    } );
                </script>


                <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                    <?= $form->field($updateSegments[$i], 'age_from', [
                        'template' => '<div class="col-md-4" style="margin-top: 10px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 100)</div>')
                        ->textInput(['type' => 'number', 'id' => 'age_from-' . $model->id]);
                    ?>

                    <?= $form->field($updateSegments[$i], 'age_to', [
                        'template' => '<div class="col-md-4">{input}<div>{error}</div></div>'
                    ])->label(false)->textInput(['type' => 'number', 'id' => 'age_to-' . $model->id]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $list_gender = [
                        Segment::GENDER_ANY => 'Не важно',
                        Segment::GENDER_MAN => 'Мужской',
                        Segment::GENDER_WOMAN => 'Женский',
                    ];
                    ?>

                    <?= $form->field($updateSegments[$i], 'gender_consumer', [
                        'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('<div>Пол потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                        ->widget(Select2::class, [
                            'data' => $list_gender,
                            'pluginOptions' => ['allowClear' => true],
                            'options' => [
                                'id' => 'gender_consumer-' . $model->id,
                                'placeholder' => 'Выберите пол потребителя',
                            ],
                            'disabled' => false,  //Сделать поле неактивным
                            'hideSearch' => true, //Скрытие поиска
                        ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $list_education = [
                        Segment::SECONDARY_EDUCATION => 'Среднее образование',
                        Segment::SECONDARY_SPECIAL_EDUCATION => 'Среднее образование (специальное)',
                        Segment::HIGHER_INCOMPLETE_EDUCATION => 'Высшее образование (незаконченное)',
                        Segment::HIGHER_EDUCATION => 'Высшее образование'
                    ];
                    ?>

                    <?= $form->field($updateSegments[$i], 'education_of_consumer', [
                        'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('<div>Образование потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                        ->widget(Select2::class, [
                            'data' => $list_education,
                            'pluginOptions' => ['allowClear' => true],
                            'options' => [
                                'id' => 'education_of_consumer-' . $model->id,
                                'placeholder' => 'Выберите уровень образования потребителя',
                            ],
                            'disabled' => false,  //Сделать поле неактивным
                            'hideSearch' => true, //Скрытие поиска
                        ]);
                    ?>

                </div>


                <script>

                    $( function() {

                        var income_from = 'input#income_from-<?= $model->id; ?>';
                        var income_to = 'input#income_to-<?= $model->id; ?>';

                        //Изменение местоположения ползунка при вводе данных в первый элемент Input
                        $(income_from).change(function () {
                            var value1 = $(income_from).val();
                            var value2 = $(income_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $(income_from).val(value1);
                            }
                        });

                        //Изменение местоположения ползунка при вводе данных во второй элемент Input
                        $(income_to).change(function () {
                            var value1 = $(income_from).val();
                            var value2 = $(income_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value2 = value1;
                                $(income_to).val(value2);
                            }
                        });

                    } );
                </script>


                <div class="row" style="margin-bottom: 10px; margin-top: 0px;">

                    <?= $form->field($updateSegments[$i], 'income_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 5 000 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'income_from-' . $model->id]);
                    ?>

                    <?= $form->field($updateSegments[$i], 'income_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                    ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to-' . $model->id]);
                    ?>

                </div>


                <script>

                    $( function() {

                        var quantity_from = 'input#quantity_from-<?= $model->id; ?>';
                        var quantity_to = 'input#quantity_to-<?= $model->id; ?>';

                        //Изменение местоположения ползунка при вводе данных в первый элемент Input
                        $(quantity_from).change(function () {
                            var value1 = $(quantity_from).val();
                            var value2 = $(quantity_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $(quantity_from).val(value1);
                            }
                        });

                        //Изменение местоположения ползунка при вводе данных во второй элемент Input
                        $(quantity_to).change(function () {
                            var value1 = $(quantity_from).val();
                            var value2 = $(quantity_to).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value2 = value1;
                                $(quantity_to).val(value2);
                            }
                        });

                    } );
                </script>


                <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                    <?= $form->field($updateSegments[$i], 'quantity_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'quantity_from-' . $model->id]);
                    ?>

                    <?= $form->field($updateSegments[$i], 'quantity_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                    ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to-' . $model->id]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                    <?= $form->field($updateSegments[$i], 'market_volume_b2c', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'market_volume_b2c']);
                    ?>

                </div>

            </div>



            <div class="form-update-template-b2b-<?= $model->id; ?>" style="display: none;">

                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $listOfAreasOfActivityB2B = TypeOfActivityB2B::getListOfAreasOfActivity();
                    $listOfAreasOfActivityB2B = ArrayHelper::map($listOfAreasOfActivityB2B,'id', 'name');
                    ?>

                    <?= $form->field($updateSegments[$i], 'field_of_activity_b2b', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Сфера деятельности предприятия *')->widget(Select2::class, [
                        'data' => $listOfAreasOfActivityB2B,
                        'options' => [
                            'placeholder' => 'Выберите cферу деятельности предприятия',
                            'id' => 'listOfAreasOfActivityB2B-' . $model->id,
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => ['allowClear' => true]
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'sort_of_activity_b2b', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Вид деятельности предприятия *')->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'options' => [
                            'id' => 'listOfActivitiesB2B-' . $model->id,
                            'placeholder' => 'Выберите вид деятельности предприятия',
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => [
                            'depends' => ['listOfAreasOfActivityB2B'],
                            'placeholder' => 'Выберите вид деятельности предприятия',
                            'nameParam' => 'name',
                            'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2b'])
                        ]
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'specialization_of_activity_b2b', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Специализация вида деятельности предприятия *')->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'options' => [
                            'id' => 'listOfSpecializationsB2B-' . $model->id,
                            'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'pluginOptions' => [
                            'depends' => ['listOfActivitiesB2B'],
                            'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                            'nameParam' => 'name',
                            'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2b'])
                        ]
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($updateSegments[$i], 'company_products', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Продукция / услуги предприятия *')->textarea(['rows' => 2]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($updateSegments[$i], 'company_partner', [
                        'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                    ])->label('Партнеры предприятия *')->textarea(['rows' => 2])
                    ?>

                </div>


                <script>

                    $( function() {

                        var quantity_from_b2b = 'input#quantity_from_b2b-<?= $model->id; ?>';
                        var quantity_to_b2b = 'input#quantity_to_b2b-<?= $model->id; ?>';

                        //Изменение местоположения ползунка при вводе данных в первый элемент Input
                        $(quantity_from_b2b).change(function () {
                            var value1 = $(quantity_from_b2b).val();
                            var value2 = $(quantity_to_b2b).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $(quantity_from_b2b).val(value1);
                            }
                        });

                        //Изменение местоположения ползунка при вводе данных во второй элемент Input
                        $(quantity_to_b2b).change(function () {
                            var value1 = $(quantity_from_b2b).val();
                            var value2 = $(quantity_to_b2b).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value2 = value1;
                                $(quantity_to_b2b).val(value2);
                            }
                        });

                    } );
                </script>


                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($updateSegments[$i], 'quantity_from_b2b', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'quantity_from_b2b-' . $model->id]);
                    ?>

                    <?= $form->field($updateSegments[$i], 'quantity_to_b2b', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                    ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to_b2b-' . $model->id]);
                    ?>

                </div>


                <script>

                    $( function() {

                        var income_from_b2b = 'input#income_from_b2b-<?= $model->id; ?>';
                        var income_to_b2b = 'input#income_to_b2b-<?= $model->id; ?>';

                        //Изменение местоположения ползунка при вводе данных в первый элемент Input
                        $(income_from_b2b).change(function () {
                            var value1 = $(income_from_b2b).val();
                            var value2 = $(income_to_b2b).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $(income_from_b2b).val(value1);
                            }
                        });

                        //Изменение местоположения ползунка при вводе данных во второй элемент Input
                        $(income_to_b2b).change(function () {
                            var value1 = $(income_from_b2b).val();
                            var value2 = $(income_to_b2b).val();
                            if (parseInt(value1) > parseInt(value2)){
                                value2 = value1;
                                $(income_to_b2b).val(value2);
                            }
                        });

                    } );
                </script>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'income_company_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'income_from_b2b-' . $model->id]);
                    ?>

                    <?= $form->field($updateSegments[$i], 'income_company_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                    ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to_b2b-' . $model->id]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'market_volume_b2b', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                    ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                        ->textInput(['type' => 'number', 'id' => 'market_volume_b2b']);
                    ?>

                </div>

            </div>



            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($updateSegments[$i], 'add_info', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->textarea(['rows' => 2]);
                ?>

            </div>

            <?php


            ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>


        </div>


    <?php

    Modal::end();



    // Модальное окно - Просмотр данных сегмента
    Modal::begin([
        'options' => [
            'id' => 'segment_view_modal-' . $model->id,
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Просмотр данных сегмента</h3>',
    ]);
    ?>

    <?= $model->allInformation;?>

    <?php

    Modal::end();

    endforeach;

    ?>




    <?php
    // Модальное окно - Сегмент с таким именем уже существует
    Modal::begin([
        'options' => [
            'id' => 'segment_already_exists',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Сегмент с таким наименованием уже существует. Отредактируйте данное поле и сохраните форму.
    </h4>

    <?php
    Modal::end();
    ?>


    <?php
    // Модальное окно - Данные не загружены
    Modal::begin([
        'options' => [
            'id' => 'data_not_loaded',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Для сохранения формы сегмента необходимо<br>заполнить все поля со знаком *
    </h4>

    <?php
    Modal::end();
    ?>


    <?php

    //Если создан только один сегмент то выводим модальное окно при каждой загрузке страницы
    if (count($models) == 1) :

    // Модальное окно - после создания сегмента выводим окно информации о дальнеших действиях
    Modal::begin([
        'options' => [
            'id' => 'option_next_step',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        После того как будут сгенерированы необходимые сегменты, приступайте к генерации проблем сегмента, для этого перейдите по ссылке названия сегмента.
    </h4>

    <?php

    Modal::end();

    endif;

    ?>

</div>


<?php

$script = "

    $(document).ready(function() {
    
        // Проверка установленного значения B2C/B2B
        setInterval(function(){
        
            if($('#select2-type-interaction-container').html() === 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)'){
                
                $('.form-template-b2b').hide();
                $('.form-template-b2c').show();
            }
            
            else {
                
                $('.form-template-b2b').show();
                $('.form-template-b2c').hide();
            }
            
        }, 1000);
        
        
        //Фон для модального окна информации (сегмент с таким именем уже существует)
        var segment_already_exists_modal = $('#segment_already_exists').find('.modal-content');
        segment_already_exists_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации (данные не загружены)
        var data_not_loaded_modal = $('#data_not_loaded').find('.modal-content');
        data_not_loaded_modal.css('background-color', '#707F99');
        
        // Фон для модального окна информации информации о дальнеших действиях
        var option_next_step_modal = $('#option_next_step').find('.modal-content');
        option_next_step_modal.css('background-color', '#707F99');
        
        // Открытие модального окна о дальнейших 
        //действиях после создания первого сегмента
        $('#option_next_step').modal('show');
        
        
        
        //Возвращение скролла первого модального окна после закрытия второго
        $('.modal').on('hidden.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
                $('body').addClass('modal-open');
            }
        }).on('show.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
                $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
            }
        });
    
    });
    
    
    $('#formCreateSegment').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Если данные загружены и проверены
                if(response['success']){
                
                    //Закрываем модальное окно и делаем перезагрузку 
                    $('#create_segment_modal').modal('hide');
                    location.reload();
                }
                
                //Если сегмент с таким именем уже существует 
                if(response['segment_already_exists']){
                
                    $('#segment_already_exists').modal('show');
                }
                
                //Если данные не загружены
                if(response['data_not_loaded']){
                
                    $('#data_not_loaded').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });

";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>



<?php

foreach ($models as $model) :

$script2 = "

    $(document).ready(function() {
    
        // Проверка установленного значения B2C/B2B в форме редактирования
        if($('#select2-type-interaction-".$model->id."-container').html() === 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)'){
            $('.form-update-template-b2b-".$model->id."').hide();
            $('.form-update-template-b2c-".$model->id."').show();
        }
        else {  
            $('.form-update-template-b2b-".$model->id."').show();
            $('.form-update-template-b2c-".$model->id."').hide();
        }

    });
    
    
    $('#formUpdateSegment-".$model->id."').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Если данные загружены и проверены
                if(response['success']){
                    
                    //Закрываем модальное окно и делаем перезагрузку        
                    $('#update_segment_modal-".$model->id."').modal('hide');
                    location.reload();
                }
                
                //Если сегмент с таким именем уже существует 
                if(response['segment_already_exists']){
                
                    $('#segment_already_exists').modal('show');
                }
                
                //Если данные не загружены
                if(response['data_not_loaded']){
                
                    $('#data_not_loaded').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });

";
$position = \yii\web\View::POS_READY;
$this->registerJs($script2, $position);

endforeach;
?>