<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Информация о респондентах';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-index">

    <div class="table-respond-index-kartik">

        <?php

        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'header' => '',
            ],


            [
                'attribute' => 'name',
                'label' => 'Фамилия Имя Отчество',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Фамилия Имя Отчество</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1,],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model){

                        return '<div class="fio" style="padding: 0 5px;">' . Html::a($model->name, ['#'], [
                            'id' => "fio-$model->id",
                            'class' => 'table-kartik-link',
                            'data-toggle' => 'modal',
                            'data-target' => "#respond_view_modal-$model->id",
                            ]) . '</div>';
                    }
                },
                'format' => 'raw',
                'hiddenFromExport' => true, // Убрать столбец при скачивании
            ],


            [
                'attribute' => 'name_export',
                'label' => 'Фамилия Имя Отчество',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Фамилия Имя Отчество</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1,],
                'value' => function ($model, $key, $index, $widget) {

                    if ($model){

                        return '<div class="fio" style="padding: 0 5px;">' . $model->name . '</div>';
                    }
                },
                'format' => 'raw',
                'hidden' => true,
            ],


            [
                'attribute' => 'info_respond',
                'label' => 'Данные респондента',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Общая характеристика</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->info_respond)) {

                        return '<div style="padding: 0 5px;">' . $model->info_respond . '</div>';
                    }

                },
                'format' => 'html',
            ],


            [
                'attribute' => 'plan',
                'label' => 'План',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">План</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'width' => '70px',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->date_plan)){

                        return '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $model->date_plan) . '</div>';
                    }
                },
                'format' => 'html',
            ],


            [
                'attribute' => 'fact',
                'label' => 'Факт',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Факт</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'width' => '70px',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->descInterview->updated_at)){

                        $date_fact = date("d.m.y", $model->descInterview->updated_at);
                        return '<div class="text-center">' . Html::a(Html::encode($date_fact), Url::to(['#']), [
                            'class' => 'table-kartik-link',
                            'data-toggle' => 'modal',
                            'data-target' => "#view_descInterview_modal-$model->id",
                            'style' => ['padding' => '0 5px']
                        ]) . '</div>';

                    }elseif (!empty($model->info_respond) && !empty($model->place_interview) && !empty($model->date_plan) && empty($model->descInterview->updated_at)){

                        return '<div class="text-center">' . Html::a(
                                Html::img(['@web/images/icons/next-step.png'], ['style' => ['width' => '20px']]),
                                ['/respond/data-availability', 'id' => Yii::$app->request->get('id')],
                                ['onclick'=>
                                    "$.ajax({
        
                                        url: '".Url::to(['/respond/data-availability', 'id' => Yii::$app->request->get('id')])."',
                                        method: 'POST',
                                        cache: false,
                                        success: function(response){
                                            if (!response['error']) {
                                            
                                                //alert('Здесь вывести окно с формой создания интервью');
                                                $('#create_descInterview_modal-".$model->id."').modal('show');
                                                //$.pjax({container: '#respPjax'});
                                                //$.pjax({container: '#Pjax_modal_responds'});
                                                
                                                //Закрываем окно создания нового респондента
                                                //$('#respondCreate_modal').modal('hide');
                                                
                                                //Очищаем форму создания нового респондента
                                                //$('#new_respond_form')[0].reset();
                                                
                                            } else {
                                                
                                                $('#descInterviewCreate_modal_error').modal('show');
                                            }
                                        },
                                        error: function(){
                                            alert('Ошибка');
                                        }
                                    });
                            
                                    return false;
                                    
                                ",
                                ]) . '</div>';
                    }
                },
                'format' => 'raw',
                'hiddenFromExport' => true, // Убрать столбец при скачивании
            ],


            [
                'attribute' => 'fact_export',
                'label' => 'Факт',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Факт</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->descInterview->updated_at)){
                        $date_fact = date("d.m.y", $model->descInterview->updated_at);
                        return '<div class="text-center">' . Html::encode($date_fact). '</div>';
                    }
                },
                'format' => 'raw',
                'hidden' => true,
            ],


            [
                'attribute' => 'place_interview',
                'label' => 'Место проведения',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Адрес, организация</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->place_interview)){

                        return '<div style="padding: 0 5px;">' . $model->place_interview . '</div>';
                    }
                },
                'format' => 'html',
            ],


            [
                'attribute' => 'result',
                'label' => 'Варианты проблем',
                'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Заключение по интервью</div>',
                'groupOddCssClass' => 'kv',
                'groupEvenCssClass' => 'kv',
                'options' => ['colspan' => 1],
                'value' => function ($model, $key, $index, $widget) {

                    if (!empty($model->descInterview)){

                        return '<div style="padding: 0 5px;">' . $model->descInterview->result . '</div>';
                    }
                },
                'format' => 'html',
            ],


            /*['class' => 'kartik\grid\ActionColumn',
                'header' => '',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,[
                            'title' => Yii::t('yii', 'Delete'),
                            'id' => 'delete-respond-' . $model->id,
                            //'data-toggle'=>'tooltip',
                            'data-confirm' => Yii::t('yii', 'Вы уверены, что хотите удалить все данные о респонденте «'.$model->name.'»?'),
                            'data-method' => 'post',
                            'class' => 'delete-link',
                            //'onClick' => 'return confirm("Your confirmation message?")'
                            'data-pjax' => '#respPjax',
                            //'data-pjax'=> '0',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'delete') {
                        $url = Url::to(['/respond/delete-respond', 'id' =>$model->id]);
                    }
                    return $url;
                },
            ],*/


            ['class' => 'kartik\grid\ActionColumn',
                'header' => '',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'],[
                            'title' => Yii::t('yii', 'Delete'),
                            'data-toggle' => 'modal',
                            'data-target' => "#delete-respond-modal-$model->id",
                        ]);
                    },
                ],
            ],


            [
                'attribute' => 'delete_export',
                'header' => '<div></div>',
                'value' => function($model){
                    return Html::img(['@web/images/icons/cross delete.png'], ['style' => ['width' => '20px', 'margin' => '0 10px']]);
                },
                'format' => 'raw',
                'hidden' => true,
            ]
        ]

        ?>


        <?php

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'showPageSummary' => false, //whether to display the page summary row for the grid view.
            'pjax' => true,
            'hashExportConfig' => false,
            'pjaxSettings' => [
                //'neverTimeout' => false,
                //'beforeGrid' => '',
                'options' => [
                    'id' => 'respPjax',
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
                'before' => '<div style="font-size: 30px; font-weight: 700; color: #F2F2F2;">' .

Html::a('Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-sm btn-default pull-left font-header-table',  'style' => [ 'margin' => '5px', 'font-weight' => '700']]) .

'<span style="margin-left: 30px;margin-right: 20px;">' . Html::encode($this->title) . '</span>'

                    . Html::a('i', ['#'], [
                        'style' => ['margin-rigth' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                        'class' => 'table-kartik-link',
                        'data-toggle' => 'modal',
                        'data-target' => "#information-table-responds",
                        'title' => 'Посмотреть описание',
                    ]) . '
</div>',
                'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
                //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                //'footer' => '{export}',
                'after' => false,
                //'footer' => false,
            ],

            'toolbar' => [
                '{toggleData}',
                '{export}',
            ],

            'exportContainer' => ['class' => 'btn btn-group-sm', 'style' => ['padding' => '5px 5px']],
            'toggleDataContainer' => ['class' => 'btn btn-group-sm mr-2', 'style' => ['padding' => '5px 5px']],

            'toggleDataOptions' => [
                'all' => [
                    //'icon' => 'resize-full',
                    'label' => '<span class="font-header-table" style="font-weight: 700;">Все страницы</span>',
                    'class' => 'btn btn-default',
                    'title' => 'Show all data'
                ],
                'page' => [
                    //'icon' => 'resize-small',
                    'label' => '<span class="font-header-table" style="font-weight: 700;">Одна страница</span>',
                    'class' => 'btn btn-default',
                    'title' => 'Show first page data'
                ],
            ],

            'export' => [
                'showConfirmAlert' => false,
                'target' => GridView::TARGET_BLANK,
                'label' => '<span class="font-header-table" style="font-weight: 700;">Экпорт таблицы</span>',
                'options' => ['title' => false],
            ],

            'columns' => $gridColumns,

            'exportConfig' => [
                GridView::PDF => [

                    'filename' => 'Таблица_«Информация_о_респондентах»(Генерация_ГПС&&'.$segment_name.'&&'.$project_filename.')' ,

                    'config' => [

                        'marginRight' => 10,
                        'marginLeft' => 10,
                        //'cssInline' => '.positive-business-model-export{margin-right: 20px;}' .
                        //'.presentation-business-model-export{margin-left: 20px;}',

                        'methods' => [
                            'SetHeader' => ['<div style="color: #3c3c3c;">Таблица «Информация о респондентах»(Генерация ГПС / '.$segment->name.' / '.$project->project_name.')</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
                            'SetFooter' => ['<div style="color: #3c3c3c;">Страница {PAGENO}</div>'],
                        ],

                        'options' => [
                            //'title' => 'Сводная таблица проекта «'.$project->project_name.'»',
                            //'subject' => Yii::t('kvgrid', 'PDF export generated by kartik-v/yii2-grid extension'),
                            //'keywords' => Yii::t('kvgrid', 'krajee, grid, export, yii2-grid, pdf')
                        ],

                        //'contentBefore' => '',
                        //'contentAfter' => '',
                    ],

                ],
                GridView::EXCEL => [
                    'filename' => 'Таблица_«Информация_о_респондентах»(Генерация_ГПС&&'.$segment_name.'&&'.$project_filename.')' ,
                ],
                GridView::HTML => [
                    'filename' => 'Таблица_«Информация_о_респондентах»(Генерация_ГПС&&'.$segment_name.'&&'.$project_filename.')' ,
                ],
            ],

            //'floatHeader'=>true,
            //'floatHeaderOptions'=>['top'=>'50'],
            'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],

            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' => '', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ['content' =>  Html::a(Html::img('@web/images/icons/icon-plus.png', ['style' => ['width' => '30px', 'margin-right' => '10px']]), ['#'], ['data-toggle' => 'modal', 'data-target' => '#respondCreate_modal',]) . 'Респондент', 'options' => ['colspan' => 1, 'class' => 'font-segment-header-table text-center']],
                        ['content' => 'Данные респондента', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ['content' => 'Дата интервью', 'options' => ['colspan' => 2, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ['content' => 'Место проведения', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ['content' => 'Варианты проблем', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ['content' => '', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                    ],

                    'options' => [
                        'class' => 'style-header-table-kartik',
                    ]
                ]
            ],
        ]);

        ?>



        <?php

        // Форма добавления нового респондента
        Modal::begin([
            'options' => [
                'id' => 'respondCreate_modal'
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center">Добавление респондента</h3>',
            /*'toggleButton' => [
                'label' => 'Модальное окно',
            ],*/
            //'footer' => '',
        ]);

        $form = ActiveForm::begin([
            'id' => 'new_respond_form',
            'action' => "/respond/create?id=$interview->id",
        ]); ?>

        <div class="">
            <?= $form->field($newRespond, 'name')->textInput(['maxlength' => true])->label('Напишите Ф.И.О. респондента') ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success',
                'id' => 'save_respond'
            ]) ?>
        </div>

        <?php ActiveForm::end();

        Modal::end();

        // Сообщение о том, что респондент с таким именем уже есть
        Modal::begin([
            'options' => [
                'id' => 'respondCreate_modal_error',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center">Внимание!</h3>',
        ]);
        ?>

        <h4 class="text-danger text-center">
            Респондент с таким именем уже есть!<br>Имя респондента должно быть уникальным!
        </h4>

        <?php
        Modal::end();
        ?>

        <div class="modal-windows-respond">

        <?php

        foreach ($models as $i => $model) :

        // Модальное окно - информация о респонденте
        Modal::begin([
            'options' => [
                'id' => "respond_view_modal-$model->id",
                'class' => 'respond_view_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center header-view-modal">Сведения о респонденте «'.$model->name.'»'.Html::a('Редактировать', ['#'],[
                    'id' => 'go_to_update_respond',
                    'class' => 'btn btn-success pull-left go_to_update_respond',
                    'data-toggle' => 'modal',
                    'data-target' => "#respond_update_modal-$model->id",
                    ]).'</h3>',
        ]);
            // Контент страницы информации о респонденте
        ?>

        <div class="respond-view">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    [
                        'attribute' => 'name',
                        'label' => 'Ф.И.О. респондента',
                        'contentOptions' => ['id' => "respond_name_$model->id"],
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'info_respond',
                        'contentOptions' => ['id' => "info_respond_$model->id"],
                        'format' => 'raw',
                    ],

                    [
                        'attribute' =>'email',
                        'contentOptions' => ['id' => "email_respond_$model->id"],
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'place_interview',
                        'contentOptions' => ['id' => "place_interview_respond_$model->id"],
                        'format' => 'raw',
                    ],

                    [
                        'attribute' => 'date_plan',
                        'label' => 'Запланированная дата интервью',
                        'contentOptions' => ['id' => "date_plan_respond_$model->id"],
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],

                ],
            ]) ?>


        </div>

        <?php

        Modal::end();

        // Форма редактирование информации о респонденте
        Modal::begin([
            'options' => [
                'id' => "respond_update_modal-$model->id",
                'class' => 'respond_update_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center header-update-modal">Редактирование информации: «'.$model->name.'»'.Html::a('Назад', ['#'],[
                    'id' => 'go_to_the_viewing_respond',
                    'class' => 'btn btn-default pull-left go_to_the_viewing_respond',
                    'data-toggle' => 'modal',
                    'data-target' => "#respond_view_modal-$model->id",
                ]).'</h3>',
        ]);

            // Контент страницы редактирования информации о респонденте
        ?>

        <div class="respond-form">

            <?php $form = ActiveForm::begin([
                'action' => "/respond/update?id=$model->id",
                'id' => "formUpdateRespond-$model->id",
            ]); ?>

            <div class="row">
                <div class="col-md-12">

                    <?= $form->field($updateRespondForms[$i], 'name')->textInput(['maxlength' => true]) ?>

                </div>

                <div class="col-md-8">

                    <?= $form->field($updateRespondForms[$i], 'info_respond')->textarea(['rows' => 1]) ?>

                    <?= $form->field($updateRespondForms[$i], 'email')->textInput() ?>

                    <?= $form->field($updateRespondForms[$i], 'place_interview')->textInput(['maxlength' => true]) ?>

                </div>

                <div class="col-md-4">


                    <?= $form->field($updateRespondForms[$i], 'date_plan', [
                        'template' => '<div style="padding-top: 5px;">{label}</div><div>{input}</div>'
                    ])->label('Запланированная дата интервью')->widget(\yii\jui\DatePicker::class, [
                        'dateFormat' => 'dd.MM.yyyy',
                        'inline' => true,
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.MM.yyyy',
                        ],
                        //'language' => 'ru',
                        'options' => [
                            'class' => 'form-control input-md',
                            'readOnly'=>'readOnly',
                            'id' => "datePlan-$model->id",

                        ],
                    ]) ?>

                </div>

            </div>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-success',
                    ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

        <?php

        Modal::end();

        if (empty($model->descInterview)) :

        // Форма создания интервью для респондента
        Modal::begin([
            'options' => [
                'id' => "create_descInterview_modal-$model->id",
                'class' => 'create_descInterview_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Для создания интервью заполните следующие поля:</h3>',
        ]);

        // Контент страницы создания интервью для респондента
        ?>

            <div class="desc-interview-create-form">

                <?php $form = ActiveForm::begin([
                    'action' => "/desc-interview/create?id=$model->id",
                    'id' => "formCreateDescInterview-$model->id",
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>



                <div class="row">
                    <div class="col-md-12">

                        <?= $form->field($createDescInterviewForms[$i], 'description')->textarea(['rows' => 2])->label('Материалы, полученные во время интервью') ?>

                        <div class="container row">
                            <div class="pull-left">

                                <p class="feed"><b>Файл (доступные расширения: png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls)</b></p>
                                <?php if (!empty($createDescInterviewForms[$i]->interview_file)) : ?>
                                    <p><?= $form->field($createDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp']])->fileInput()->label('') ?></p>
                                <?php endif;?>

                                <?php if (empty($createDescInterviewForms[$i]->interview_file)) : ?>
                                    <p><?= $form->field($createDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput(['id' => "descInterviewCreateFile-$model->id"])->label('') ?></p>
                                <?php endif;?>

                                <p>
                                    <?php
                                    if (!empty($createDescInterviewForms[$i]->interview_file))
                                    {
                                        echo Html::a($createDescInterviewForms[$i]->interview_file, ['/desc-interview/download', 'id' => $createDescInterviewForms[$i]->id], ['class' => 'btn btn-default feedback']) .
                                            ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/desc-interview/delete-file', 'id' => $createDescInterviewForms[$i]->id], [
                                                'onclick'=>
                                                    "$.ajax({
                                                         type:'POST',
                                                         cache: false,
                                                         url: '".Url::to(['/desc-interview/delete-file', 'id' => $createDescInterviewForms[$i]->id])."',
                                                         success  : function(response) {
                                                             $('.link-del').html(response);
                                                             $('.feedback').remove();
                                                         }
                                                    });
                                                 return false;
                                                 $('.feedback').remove();
                                                 ",
                                                'class' => "link-del",
                                            ]);
                                    }
                                    ?>
                                </p>

                            </div>
                        </div>

                        <?= $form->field($createDescInterviewForms[$i], 'result')->textarea(['rows' => 2]) ?>

                        <?= $form->field($createDescInterviewForms[$i], 'status', ['template' => '<div class="col-md-12" style="padding-left: 0">{label}</div><div class="col-md-12" style="padding-left: 0; margin-bottom: 10px;"><div class="col-md-2" style="padding-left: 0">{input}</div></div>'])->dropDownList([ '0' => 'Нет', '1' => 'Да', ]) ?>

                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        <?php

        Modal::end();

        endif;



        // Форма создания интервью для респондента
        Modal::begin([
            'options' => [
                'id' => "view_descInterview_modal-$model->id",
                'class' => 'view_descInterview_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Сведения о проведенном интервью'.Html::a('Редактировать', ['#'],[
                    'id' => 'go_to_update_interview',
                    'class' => 'btn btn-success pull-left go_to_update_interview',
                    'data-toggle' => 'modal',
                    'data-target' => "#interview_update_modal-$model->id",
            ]).'</h3>',
        ]);

        // Контент страницы создания интервью для респондента
        ?>

            <div class="desc-interview-view">
                <div class="row">
                    <div class="col-md-12">

                        <?= DetailView::widget([
                            'model' => $model,

                            'attributes' => [

                                [
                                    'attribute' => 'name',
                                    'label' => 'Ф.И.О. респондента',
                                    'contentOptions' => ['id' => "respond_name_interview_$model->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'created_at',
                                    'label' => 'Дата создания интервью',
                                    'value' => function($model){
                                        return $model->descInterview->created_at;
                                    },
                                    'contentOptions' => ['id' => "created_at_interview_$model->id"],
                                    'format' => ['date', 'dd.MM.yyyy'],

                                ],

                                [
                                    'attribute' => 'updated_at',
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
                                        $string = '';
                                        if ($model->descInterview){
                                            return $model->descInterview->description;
                                        }else{
                                            return $string;
                                        }
                                    },
                                    'contentOptions' => ['id' => "description_interview_$model->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'interview_file',
                                    'label' => 'Файл',
                                    'value' => function($model){
                                        $string = '';
                                        if (!empty($model->descInterview->interview_file)){
                                            return Html::a($model->descInterview->interview_file,
                                                ['/desc-interview/download', 'id' => $model->descInterview->id],
                                                ['class' => "interview_file-$model->id"]
                                            );

                                        }else {
                                            return $string;
                                        }
                                    },
                                    'contentOptions' => ['id' => "file_interview_$model->id"],
                                    //'visible' => !empty($model->interview_file),
                                    'format' => 'html',
                                ],

                                [
                                    'attribute' => 'result',
                                    'label' => 'Вывод',
                                    'value' => function($model){
                                        $string = '';
                                        if ($model->descInterview){
                                            return $model->descInterview->result;
                                        }else{
                                            return $string;
                                        }
                                    },
                                    'contentOptions' => ['id' => "result_interview_$model->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'status',
                                    'label' => 'Данный респондент является представителем сегмента?',
                                    'value' => !$model->descInterview->status ? '<span style="color:red">Нет</span>' : '<span style="color:green">Да</span>',
                                    'contentOptions' => ['id' => "status_respond_$model->id"],
                                    'format' => 'html',
                                ],
                            ],
                        ]) ?>

                    </div>
                </div>
            </div>

        <?php

        Modal::end();

        // Форма редактирование информации о интервью
        Modal::begin([
            'options' => [
                'id' => "interview_update_modal-$model->id",
                'class' => 'interview_update_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center header-update-modal">Редактирование данных из интервью'.Html::a('Назад', ['#'],[
                    'id' => 'go_to_the_viewing_interview',
                    'class' => 'btn btn-default pull-left go_to_the_viewing_interview',
                    'data-toggle' => 'modal',
                    'data-target' => "#view_descInterview_modal-$model->id",
            ]).'</h3>',
        ]);

        // Контент страницы редактирования информации о интервью
        ?>

            <div class="desc-interview-update-form">

                <?php if ($model->descInterview) : ?>

                <?php $form = ActiveForm::begin([
                    'action' => "/desc-interview/update?id=".$model->descInterview->id ,
                    'id' => "formUpdateDescInterview-".$model->descInterview->id ,
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>


                <div class="row">
                    <div class="col-md-12">

                        <?= $form->field($updateDescInterviewForms[$i], 'description')->textarea(['rows' => 2])->label('Материалы, полученные во время интервью') ?>

                        <div class="container row">
                            <div class="pull-left">

                                <p class="feed"><b>Файл (доступные расширения: png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls)</b></p>
                                <?php if (!empty($updateDescInterviewForms[$i]->interview_file)) : ?>
                                    <p><?= $form->field($updateDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp']])->fileInput()->label('') ?></p>
                                <?php endif;?>

                                <?php if (empty($updateDescInterviewForms[$i]->interview_file)) : ?>
                                    <p><?= $form->field($updateDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput(['id' => "descInterviewUpdateFile-$model->id"])->label('') ?></p>
                                <?php endif;?>

                                <p>
                                    <?php
                                    if (!empty($updateDescInterviewForms[$i]->interview_file))
                                    {
                                        echo Html::a($updateDescInterviewForms[$i]->interview_file, ['/desc-interview/download', 'id' => $updateDescInterviewForms[$i]->id], ['class' => "btn btn-default feedback interview_file_update-$model->id"]) .
                                            ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id], [
                                                'onclick'=>
                                                    "$.ajax({
                                                         type:'POST',
                                                         cache: false,
                                                         url: '".Url::to(['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id])."',
                                                         success  : function(response) {
                                                             $('.interview_file-".$model->id."').html('');
                                                             $('.link-del').html(response);
                                                             $('.feedback').remove();
                                                         }
                                                    });
                                                 return false;
                                                 $('.feedback').remove();
                                                 ",
                                                'class' => "link-del",
                                            ]);
                                    }
                                    ?>
                                </p>

                            </div>
                        </div>

                        <?= $form->field($updateDescInterviewForms[$i], 'result')->textarea(['rows' => 2]) ?>

                        <?= $form->field($updateDescInterviewForms[$i], 'status', ['template' => '<div class="col-md-12" style="padding-left: 0">{label}</div><div class="col-md-12" style="padding-left: 0; margin-bottom: 10px;"><div class="col-md-2" style="padding-left: 0">{input}</div></div>'])->dropDownList([ '0' => 'Нет', '1' => 'Да', ]) ?>

                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <?php endif; ?>

            </div>


        <?php

        Modal::end();




        // Подтверждение удаления респондента
        Modal::begin([
            'options' => [
                'id' => "delete-respond-modal-$model->id",
                'class' => 'delete_respond_modal',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center header-update-modal">Подтверждение</h3>',
            'footer' => '<div class="text-center">'.

                Html::a('Отмена', ['#'],[
                    'class' => 'btn btn-default',
                    'style' => ['width' => '120px'],
                    'id' => "cancel-delete-respond-$model->id",
                ]).

                Html::a('Ок', ['/respond/delete-respond', 'id' =>$model->id],[
                    'class' => 'btn btn-default',
                    'style' => ['width' => '120px'],
                    'id' => "confirm-delete-respond-$model->id",
                ]).

                '</div>'
        ]);

        // Контент страницы - подтверждение удаления респондента
        ?>

        <h4 class="text-center">Вы уверены, что хотите удалить все данные<br>о респонденте «<?= $model->name ?>»?</h4>

        <?php

        Modal::end();

        endforeach;

        ?>

        </div>

        <?php
        // Сообщение о том, что респондент с таким именем уже есть
        Modal::begin([
            'options' => [
                'id' => 'respondUpdate_modal_error',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center">Внимание!</h3>',
        ]);
        ?>

        <h4 class="text-danger text-center">
            Респондент с таким именем уже есть!<br>Имя респондента должно быть уникальным!
        </h4>

        <?php
        Modal::end();
        ?>


        <?php
        // Сообщение о том, что данные по заданным респондентам отсутствуют
        Modal::begin([
            'options' => [
                'id' => 'descInterviewCreate_modal_error',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center">Внимание!</h3>',
        ]);
        ?>

        <h4 class="text-danger text-center">
            Для перехода к созданию интервью,<br> необходимо заполнить вводные данные<br>по всем заданным респондентам.
        </h4>

        <?php
        Modal::end();
        ?>


        <?php
        // Описание выполнения задачи на данной странице
        Modal::begin([
            'options' => [
                'id' => 'information-table-responds',
            ],
            'size' => 'modal-md',
            'header' => '<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">1. Пройдите последовательно по ссылкам в таблице, заполняя информацию о каждом респонденте.</h4>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            2. Затем переходите к заполнению данных по интервью, при необходимости добавляйте новых респондентов.
        </h4>

        <?php
        Modal::end();
        ?>

    </div>

</div>


<?php

$script = "
    
    $(document).ready(function() {
    
        //Фон для модального окна информации
        var information_modal = $('#information-table-responds').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //При переходе в окно редактирования закрываем описание респондента
        $('.go_to_update_respond').on('click', function(){
            $('.respond_view_modal').modal('hide');
        });
    
        //При клике на кнопку --Назад-- закрываем редактирование респондента
        $('.go_to_the_viewing_respond').on('click', function(){
            $('.respond_update_modal').modal('hide');
        });
        
        //При переходе в окно редактирования закрываем описание интервью
        $('.go_to_update_interview').on('click', function(){
            $('.view_descInterview_modal').modal('hide');
        });
        
        //При клике на кнопку --Назад-- закрываем редактирование интервью
        $('.go_to_the_viewing_interview').on('click', function(){
            $('.interview_update_modal').modal('hide');
        });
        
    }); 
    
    
    
    //При сохранении нового респондента отправляем данные через ajax 
    $('#new_respond_form').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                if (!response['error']) {
                    
                    //Закрываем окно создания нового респондента
                    $('#respondCreate_modal').modal('hide');
                    
                    //Перезагружаем страницу
                    location.reload();
                    
                } else {
                    $('#respondCreate_modal_error').modal('show');
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

foreach ($models as $i => $model) :

$script2 = "

    $(document).ready(function() {

        //Стилизация модального окна для удаления респондента
        var modal_header_delete_respond = $('#delete-respond-modal-".$model->id."').find('.modal-header');
        modal_header_delete_respond.css('background-color', '#ffb02e');
        modal_header_delete_respond.css('color', '#ffffff');
        modal_header_delete_respond.css('border-radius', '5px 5px 0 0');
        
        var modal_footer_delete_respond = $('#delete-respond-modal-".$model->id."').find('.modal-footer');
        modal_footer_delete_respond.css('background-color', '#ffb02e');
        modal_footer_delete_respond.css('border-radius', '0 0 5px 5px');
    });

    // CONFIRM RESPOND DELETE
    $('#confirm-delete-respond-".$model->id."').on('click',function(e) {
        
         var url = $(this).attr('href');
         $.ajax({
              url: url,
              method: 'POST',
              cache: false,
              success: function() {
                   
                   //Закрываем окно подтверждения
                   $('#delete-respond-modal-".$model->id."').modal('hide');
                            
                   //Обновляем страницу
                   $.pjax({container: '#respPjax', url: location.href});
              }
         });
         e.preventDefault();
         return false;
    });



    // CANCEL RESPOND DELETE
    $('#cancel-delete-respond-".$model->id."').on('click',function(e) {
        
         //Закрываем окно подтверждения
         $('#delete-respond-modal-".$model->id."').modal('hide');
         
         e.preventDefault();
         return false;
    });
    
    
    //Сохранении данных из формы редактирование дынных респондента и 
    //передача новых данных в модальное окно view
    $('#formUpdateRespond-".$model->id."').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){

                if (!response['error']) {

                    $.pjax({container: '#respPjax', url: location.href});
                    $('.respond_update_modal').modal('hide');
                    
                    var name_respond = response.name;
                    var info_respond = response.info_respond;
                    var email_respond = response.email;
                    var place_interview_respond = response.place_interview;
                    var date_plan_respond = response.date_plan;
                    date_plan_respond = new Date(date_plan_respond*1000).toLocaleDateString();
                    
                    $('#respond_name_".$model->id."').html(name_respond);
                    $('#respond_name_interview_".$model->id."').html(name_respond);
                    $('#info_respond_".$model->id."').html(info_respond);
                    $('#email_respond_".$model->id."').html(email_respond);
                    $('#place_interview_respond_".$model->id."').html(place_interview_respond);
                    $('#date_plan_respond_".$model->id."').html(date_plan_respond);
                    
                    
                } else {

                    $('#respondUpdate_modal_error').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    
    //Создание интервью при сохранении данных из формы 
    $('#formCreateDescInterview-".$model->id."').on('beforeSubmit', function(e){
    
        var data = new FormData(this);
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response){

                //Закрываем модальное окно с формой
                $('.create_descInterview_modal').modal('hide');
                
                //Перезагружаем страницу
                location.reload();
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    //Редактирование интервью при сохранении данных из формы 
    $('#formUpdateDescInterview-".$model->descInterview->id."').on('beforeSubmit', function(e){
    
        var data = new FormData(this);
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(response){
                
                //Закрываем модальное окно с формой
                $('.interview_update_modal').modal('hide');
                
                //Перезагружаем страницу
                location.reload();

            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //При нажатии на ссылку отдать файл на скачивание
    $('.interview_file-".$model->id."').on('click', function(e){
    
        //var url = '/desc-interview/download?id=".$model->descInterview->id."';
        
        var url = $('.interview_file-".$model->id."').attr('href');
        
        document.location.href = url;
    
        e.preventDefault();

        return false;
    });
    
    
    //При нажатии на ссылку отдать файл на скачивание
    $('.interview_file_update-".$model->id."').on('click', function(e){
        
        var url = $('.interview_file-".$model->id."').attr('href');
        
        document.location.href = url;
    
        e.preventDefault();

        return false;
    });
    
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script2, $position);

endforeach;
?>