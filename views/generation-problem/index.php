<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация гипотез проблем сегмента';

$this->registerCssFile('@web/css/problem-index-style.css');
?>
<div class="generation-problem-index">


    <div class="row project_info_data">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name_link">
            <span style="padding-right: 20px; font-weight: 400; font-size: 20px;">Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links',
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
            'onclick' => 'return false',
        ]) ?>

        <?= Html::a('Дорожная карта проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapProject",
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
            'onclick' => 'return false',
        ]) ?>

    </div>



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

    <?= \yii\widgets\DetailView::widget([
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
                'attribute' => 'updated_at',
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


    <div class="row navigation_blocks">

        <?= Html::a('<div class="stage_number">1</div><div>Генерация гипотез целевых сегментов</div>',
            ['/segment/index', 'id' => $project->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">2</div><div>Подтверждение гипотез целевых сегментов</div>',
            ['/interview/view', 'id' => $interview->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">3</div>
            <div>Генерация гипотез проблем сегментов</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">4</div>
            <div>Подтверждение гипотез проблем сегментов</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">5</div>
            <div>Разработка гипотез ценностных предложений</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">6</div>
            <div>Подтверждение гипотез ценностных предложений</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">7</div>
            <div>Разработка гипотез MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">8</div>
            <div>Подтверждение гипотез MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 project_name_link">
            <span style="padding-right: 10px; font-weight: 400; font-size: 20px;">Сегмент:</span>

            <?php
                $segment_name = $segment->name;
                if (mb_strlen($segment_name) > 25){
                    $segment_name = mb_substr($segment_name, 0, 25) . '...';
                }
            ?>

            <?= '<span title="'.$segment->name.'">' . $segment_name . '</span>'; ?>

        </div>

        <?= Html::a('Данные сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 segment_header_links',
            'data-toggle' => 'modal',
            'data-target' => '#data_segment_modal',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 segment_header_links text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapSegment",
        ]) ?>

    </div>


    <?php
    // Модальное окно - Данные сегмента
    Modal::begin([
        'options' => [
            'id' => 'data_segment_modal',
            'class' => 'data_segment_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Информация о сегменте</h3>',
    ]);
    ?>

    <?= $segment->allInformation; ?>

    <?php
    Modal::end();
    ?>



    <?php

    $gridColumns = [

        [
            'attribute' => 'title',
            'label' => 'Наименование',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Наименование</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1,],
            'value' => function ($model, $key, $index, $widget) {

                if ($model){

                    return '<div class="text-center" style="padding: 0 5px;">' . $model->title . '</div>';
                }
            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
        ],


        [
            'attribute' => 'description',
            'label' => 'Описание гипотезы',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Описание гипотезы</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                return '<div style="padding: 0 5px;">' . $model->description . '</div>';
            },
            'format' => 'raw',
        ],


        [
            'attribute' => 'date_create',
            'label' => 'Дата создания',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата создания</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                return '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $model->created_at) . '</div>';
            },
            'format' => 'raw',
        ],


        [
            'attribute' => 'status',
            'label' => 'Подтверждение',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Подтверждение</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                if ($model->exist_confirm === 1) {

                    return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/confirm-problem/view', 'id' => $model->confirm->id])) . '</div>';

                }elseif ($model->exist_confirm === null) {

                    return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]), Url::to(['/confirm-problem/create', 'id' => $model->id])) . '</div>';

                }elseif ($model->exist_confirm === 0) {

                    return '<div class="text-center" style="padding: 0 5px;">' . Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]), Url::to(['/confirm-problem/view', 'id' => $model->confirm->id])) . '</div>';

                }else {
                    return '';
                }
            },
            'format' => 'raw',
        ],

        [
            'attribute' => 'date_confirm',
            'label' => 'Дата подтверждения',
            'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата подтверждения</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                if ($model->time_confirm) {

                    return '<div class="text-center" style="padding: 0 5px;">'. date("d.m.y", $model->time_confirm) .'</div>';
                }else {
                    return '';
                }
            },
            'format' => 'raw',
        ],

    ]

    ?>

    <div class="row" style="margin-top: 10px;">

    <?php

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'showPageSummary' => false, //whether to display the page summary row for the grid view.
        'pjax' => false,
        'hashExportConfig' => false,
        'striped' => false,
        'bordered' => true,
        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'before' => false,
            'before' => '<div><span style="margin-left: 30px;margin-right: 20px;">Генерация гипотез проблем сегмента</span>'

                . Html::a('i', ['#'], [
                    'style' => ['margin-rigth' => '20px', 'font-size' => '16px', 'font-weight' => '700', 'padding' => '2px 10px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information_table_problems",
                    'title' => 'Посмотреть описание',
                ]) . '</div>',
            'beforeOptions' => ['class' => 'header-table'],

            'after' => false,
        ],
        'toolbar' => false,
        'condensed' => true,
        'summary' => false,
        'hover' => true,
        'columns' => $gridColumns,
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' =>  Html::a(Html::img('@web/images/icons/add_plus_elem.png', ['style' => ['width' => '25px', 'margin-right' => '10px']]), Url::to(['/interview/data-availability-for-next-step', 'id' => $interview->id]), ['id' => 'checking_the_possibility']) . 'Гипотеза проблемы сегмента', 'options' => ['colspan' => 3, 'class' => 'font-segment-header-table text-center', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px']]],
                    ['content' => 'Проблема сегмента', 'options' => ['colspan' => 2, 'class' => 'font-header-table', 'style' => ['padding-top' => '15px', 'padding-bottom' => '15px', 'text-align' => 'center']]],
                ],
                'options' => [
                    'class' => 'style-header-table-kartik',
                ]
            ]
        ],
    ]);

    ?>

    </div>


    <?php

    // Модальное окно - информация о завершении подтверждения
    Modal::begin([
        'options' => [
            'id' => 'information_table_problems',
            'class' => 'information_table_problems',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Сгенерируйте необходимые гипотезы. <br>Далее переходите к их подтверждению.
    </h4>

    <?php

    Modal::end();

    ?>


    <?php
    // Модальное окно - создание ГПС
    Modal::begin([
        'options' => [
            'id' => 'problem_create_modal',
            'class' => 'problem_create_modal',
        ],
        'size' => 'modal-lg',
        //'header' => '<div class="text-center"><span style="font-size: 24px;">Создание гипотезы проблемы сегмента</span></div>',
    ]);
    ?>


    <?php

    $gridColumns = [

        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
        ],


        [
            'attribute' => 'name',
            'label' => 'Респондент',
            'header' => '<div class="font-segment-header-table">Респондент</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1,],
            'value' => function ($model, $key, $index, $widget) {

                return '<div class="fio" style="padding: 0 5px;">' . Html::a($model->name, ['#'], [
                        'id' => "fio-$model->id",
                        'class' => 'table-kartik-link',
                        'data-toggle' => 'modal',
                        'data-target' => "#respond_positive_view_modal-$model->id",
                    ]) . '</div>';

            },
            'format' => 'raw',
            'hiddenFromExport' => true, // Убрать столбец при скачивании
        ],


        [
            'attribute' => 'name_export',
            'label' => 'Респондент',
            'header' => '<div class="font-segment-header-table">Респондент</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1,],
            'value' => function ($model, $key, $index, $widget) {

                return '<div class="fio" style="padding: 0 5px;">' . $model->name . '</div>';

            },
            'format' => 'raw',
            'hidden' => true,
        ],



        [
            'attribute' => 'result',
            'label' => 'Варианты проблем',
            'header' => '<div class="font-segment-header-table">Варианты проблем</div>',
            'groupOddCssClass' => 'kv',
            'groupEvenCssClass' => 'kv',
            'options' => ['colspan' => 1],
            'value' => function ($model, $key, $index, $widget) {

                return '<div style="padding: 0 5px;">' . $model->descInterview->result . '</div>';

            },
            'format' => 'html',
        ],

    ];

    ?>


    <?php

    echo GridView::widget([
        'dataProvider' => $dataProviderRespondsPositive,
        'showPageSummary' => false, //whether to display the page summary row for the grid view.
        'pjax' => true,
        'hashExportConfig' => false,
        'pjaxSettings' => [
            //'neverTimeout' => false,
            //'beforeGrid' => '',
            'options' => [
                'id' => 'problemsCreatePjax',
                //'enablePushState' => false,
            ],
            'loadingCssClass' => false,
        ],
        'striped' => false,
        'bordered' => true,
        'condensed' => true,
        'summary' => false,
        'hover' => true,

        'panel' => [
            'type' => 'default',
            'heading' => false,
            //'headingOptions' => ['class' => 'style-head-table-kartik-top'],
            'before' => '<div style="font-size: 28px; font-weight: 700; color: #F2F2F2;"><span style="margin-left: 30px;margin-right: 20px;">Создание гипотезы проблемы сегмента</span>'

                . Html::a('i', ['#'], [
                    'style' => ['margin-rigth' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-create-problem",
                    'title' => 'Посмотреть описание',
                ]) . '
</div>',
            'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
            'after' => false,
            //'footer' => false,
        ],

        'toolbar' => false,
        'columns' => $gridColumns,
        'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],
    ]);

    ?>


    <div class="generation-problem-form" style="margin-top: 20px;">

        <?php $form = ActiveForm::begin(['id' => 'gpsCreateForm', 'action' => Url::to(['/generation-problem/create', 'id' => $interview->id])]); ?>

        <? $placeholder = 'Напишите описание гипотезы проблемы сегмента. Примеры: 
- отсутствие путеводителя по комерциализации результатов интеллектуальной деятельности, 
- отсутствие необходимой информации по патентованию...' ?>

        <div class="row">
            <div class="col-md-12">

                <?= $form->field($newProblem, 'description')->label(false)->textarea(['rows' => 4, 'placeholder' => $placeholder]) ?>

            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-block btn-success',
                'style' => ['font-weight' => '700'],
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


    <?php
    Modal::end();
    ?>



    <?php
    // Модальное окно - Информационное окно в создании ГПС
    Modal::begin([
        'options' => [
            'id' => 'information-table-create-problem',
            'class' => 'information-table-create-problem',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Необходимо просмотреть и проанализировать все материалы интервью представителей сегмента и выявить проблемы, которые характерны для нескольких респондентов
    </h4>


    <?php
    Modal::end();
    ?>



    <!--Roadmap Project-->

    <?php

    // Модальное окно - дорожная карта проекта
    Modal::begin([
        'options' => [
            'id' => 'showRoadmapProject',
            'class' => 'showRoadmapProject',
        ],
        'size' => 'modal-lg',
        'header' => '<h2 class="text-center" style="font-size: 36px; color: #4F4F4F;">Дорожная карта проекта «' . $project->project_name . '»</h2>',
    ]);
    ?>

    <?= $project->showRoadmapProject();?>

    <?php

    Modal::end();

    ?>


    <!--Roadmap Segment-->

    <?php

    // Модальное окно - дорожная карта сегмента
    Modal::begin([
        'options' => [
            'id' => 'showRoadmapSegment',
            'class' => 'showRoadmapSegment',
        ],
        'size' => 'modal-lg',
        'header' => '<div class="roadmap_segment_modal_header_title">
                        <h2 class="roadmap_segment_modal_header_title_h2">Дорожная карта сегмента «' . $segment->name . '»</h2>
                     </div>',
    ]);
    ?>

    <?= $segment->showRoadmapSegment();?>

    <?php

    Modal::end();

    ?>



    <?php
    // Модальное окно - сообщение о том что данных недостаточно для создания ГПС
    Modal::begin([
        'options' => [
            'id' => 'problem_create_modal_error',
            'class' => 'problem_create_modal_error',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания ГПС.</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Вернитесь к подтверждению сегмента.
    </h4>

    <?php
    Modal::end();
    ?>


    <?php
    foreach ($interview->responds as $respond) :
        if ($respond->descInterview->status == 1) :
            // Модальное окно - Информамация о представителях сегмента
            Modal::begin([
                'options' => [
                    'id' => "respond_positive_view_modal-$respond->id",
                    'class' => 'respond_positive_view_modal',
                ],
                'size' => 'modal-lg',
                'header' => '<div class="text-center"><span style="font-size: 24px;">Информация о респонденте и интервью</span></div>',
            ]);
            ?>


            <?= yii\widgets\DetailView::widget([
            'model' => $respond,
            'attributes' => [

                [
                    'attribute' => 'name',
                    'label' => 'Ф.И.О. респондента',
                    'value' => function($model){
                        return '<div id="respond_name_'.$model->id.'">'.$model->name.'</div>';
                    },
                    'format' => 'raw',
                ],

                'info_respond',
                'email',
                'place_interview',

                [
                    'attribute' => 'date_plan',
                    'label' => 'Запланированная дата интервью',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                [
                    'attribute' => 'created_descInterview',
                    'label' => 'Дата создания интервью',
                    'value' => function($model){
                        return $model->descInterview->created_at;
                    },
                    'contentOptions' => ['id' => "created_at_interview_$model->id"],
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                [
                    'attribute' => 'updated_descInterview',
                    'label' => 'Последнее изменение интервью',
                    'value' => function($model){
                        return $model->descInterview->updated_at;
                    },
                    'contentOptions' => ['id' => "updated_at_interview_$model->id"],
                    'format' => ['date', 'dd.MM.yyyy'],

                ],

                [
                    'attribute' => 'description',
                    'label' => 'Материалы интервью',
                    'value' => function($model){
                        return $model->descInterview->description;
                    },
                ],

                [
                    'attribute' => 'interview_file',
                    'label' => 'Файл',
                    'value' => function($model){
                        $string = '';
                        $string .= Html::a($model->descInterview->interview_file, ['/desc-interview/download', 'id' => $model->descInterview->id], ['class' => '']);
                        return $string;
                    },
                    'format' => 'html',
                ],

                [
                    'attribute' => 'result',
                    'label' => 'Вывод из интервью',
                    'value' => function($model){
                        return $model->descInterview->result;
                    },
                ],

                [
                    'attribute' => 'respond_status',
                    'label' => 'Является ли респондент представителем сегмента?',
                    'value' => function($model){
                        if ($model->descInterview){
                            return !$model->descInterview->status ? '<span style="color:red">Нет</span>' : '<span style="color:green">Да</span>';
                        }else{
                            return '';
                        }

                    },
                    'format' => 'html',
                ],

            ],
        ]) ?>


            <?php
            Modal::end();
        endif;
    endforeach;
    ?>


</div>


<?php

$script = "

    $(document).ready(function() {
    
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.tab', this).each(function(){

          var height = $(this).height();
        
           $('.tablinks').css('height', height);
        
        });
        
        //Фон для модального окна информации при отказе в добавлении ГПС
        var info_problem_create_modal_error = $('#problem_create_modal_error').find('.modal-content');
        info_problem_create_modal_error.css('background-color', '#707F99');
        
        //Фон для модального окна информации при создании ГПС 
        var information_modal = $('#information-table-create-problem').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        ////Фон для модального окна информации в шапке таблицы 
        var information_table_problems_modal = $('#information_table_problems').find('.modal-content');
        information_table_problems_modal.css('background-color', '#707F99');
        
        
        
    
    });
    
    
    
    //При попытке добавить ГПС проверяем существуют ли необходимые данные
    //Если данных достаточно - показываем окно с формой
    //Если данных недостаточно - показываем окно с сообщением error
    $('#checking_the_possibility').on('click', function(){
    
        var url = $(this).attr('href');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){
                if(response['success']){
                    $('#problem_create_modal').modal('show');
                }else{
                    $('#problem_create_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        //e.preventDefault();
        return false;
    });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>