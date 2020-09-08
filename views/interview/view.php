<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Подтверждение гипотезы целевого сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/interview-view-style.css');
\yii\web\YiiAsset::register($this);
?>

<div class="interview-view table-project-kartik">


    <div class="row project_info_data" style="background: #707F99;">


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
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 segment_header_links text-center',
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


    <div class="row segment_info_data" style="border-radius: 0 0 12px 12px; background: #707F99;margin-top: 50px;">


        <div class="col-xs-12 col-md-12 col-lg-8 project_name_link">
            <span style="padding-right: 20px; font-weight: 400;">Сегмент:</span>
            <?= $segment->name; ?>
        </div>

        <?= Html::a('Данные сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 segment_header_links',
            'data-toggle' => 'modal',
            'data-target' => '#data_segment_modal',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['/segment/one-roadmap', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 segment_header_links text-center',
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


    <!-- Tab links -->
    <div class="tab row">
        <?php if ($model->nextStep === false) : ?>


            <button class="tablinks step_one_button link_create_interview" onclick="openCity(event, 'step_one')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 1</div>
                    <div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div>
                </div>
            </button>

            <button class="tablinks step_two_button link_create_interview" onclick="openCity(event, 'step_two')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 2</div>
                    <div class="link_create_interview-text_right">Сформировать список вопросов</div>
                </div>
            </button>

            <button class="tablinks step_two_button link_create_interview" onclick="openCity(event, 'step_three')" id="defaultOpen">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 3</div>
                    <div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div>
                </div>
            </button>

            <button class="tablinks step_four_button link_create_interview" onclick="openCity(event, 'step_four')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 4</div>
                    <div class="link_create_interview-text_right">Завершение подтверждения</div>
                </div>
            </button>

            <button class="tablinks step_five_button link_create_interview" onclick="openCity(event, 'feedbacks')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 5</div>
                    <div class="link_create_interview-text_right">Получить отзывы экспертов</div>
                </div>
            </button>


        <?php else : ?>


            <button class="tablinks step_one_button link_create_interview" onclick="openCity(event, 'step_one')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 1</div>
                    <div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div>
                </div>
            </button>

            <button class="tablinks step_two_button link_create_interview" onclick="openCity(event, 'step_two')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 2</div>
                    <div class="link_create_interview-text_right">Сформировать список вопросов</div>
                </div>
            </button>

            <button class="tablinks step_two_button link_create_interview" onclick="openCity(event, 'step_three')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 3</div>
                    <div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div>
                </div>
            </button>

            <button class="tablinks step_four_button link_create_interview" onclick="openCity(event, 'step_four')" id="defaultOpen">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 4</div>
                    <div class="link_create_interview-text_right">Завершение подтверждения</div>
                </div>
            </button>

            <button class="tablinks step_five_button link_create_interview" onclick="openCity(event, 'feedbacks')">
                <div class="link_create_interview-block_text">
                    <div class="link_create_interview-text_left">Шаг 5</div>
                    <div class="link_create_interview-text_right">Получить отзывы экспертов</div>
                </div>
            </button>


        <?php endif; ?>

    </div>

    <!-- Tab content -->

    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 1)-->
    <div id="step_one" class="tabcontent row">

        <?php

            echo DetailView::widget([
                'model' => $model,
                'id' => 'table-data-interview',
                'condensed' => true,
                'striped' => false,
                'bordered' => true,
                'hover' => true,
                'enableEditMode' => true,
                'mode' => DetailView::MODE_VIEW,
                'fadeDelay' => 300,
                'buttons1' => "{update}",
                'buttons2' => "{view}{save}",
                'updateOptions' => ['label' => 'Редактировать <span class="glyphicon glyphicon-pencil"></span>', 'title' => '', 'class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700', 'margin' => '10px']],
                'viewOptions' => ['label' => 'Просмотр', 'title' => '', 'class' => 'btn btn-sm btn-default' , 'style' => ['margin' => '10px', 'font-weight' => '700']],
                'saveOptions' => ['label' => 'Сохранить', 'title' => '', 'class' => 'btn btn-sm btn-success', 'style' => ['font-weight' => '700', 'margin' => '10px']],
                'panel' => [
                    'heading' => '<div style="font-size: 24px; color: #ffffff; padding: 11px 20px 11px 30px;">Текст легенды проблемного интервью</div>',
                    'type' => kartik\detail\DetailView::TYPE_DEFAULT,
                    'before' => false,
                    'headingOptions' => ['class' => 'header-table'],
                ],
                'formOptions' => [
                    'id' => 'update_data_interview',
                    'action' => Url::to(['/interview/update', 'id' => $model->id]),
                ],
                'attributes' => [

                    [
                        'attribute' => 'greeting_interview',
                        'label' => 'Приветствие в начале встречи:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'greeting_interview-view', 'style' => ['padding' => '10px']],
                        'type' => kartik\detail\DetailView::INPUT_TEXTAREA,
                    ],

                    [
                        'attribute' => 'view_interview',
                        'label' => 'Представление интервьюера:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'view_interview-view', 'style' => ['padding' => '10px']],
                        'type' => kartik\detail\DetailView::INPUT_TEXTAREA,
                    ],

                    [
                        'attribute' => 'reason_interview',
                        'label' => 'Почему мне интересно:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'reason_interview-view', 'style' => ['padding' => '10px']],
                        'type' => kartik\detail\DetailView::INPUT_TEXTAREA,
                    ],


                    [
                        'attribute' => 'count_data_interview',
                        'columns' => [
                            [
                                'attribute' => 'count_respond',
                                'label' => 'Количество респондентов:',
                                'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                                'valueColOptions' => ['class' => 'text-left', 'id' => 'count_respond-view'],
                                'type' => kartik\detail\DetailView::INPUT_HTML5 ,
                                //'contentOptions' => ['style' => 'white-space: normal;'],
                            ],

                            [
                                'attribute' => 'count_positive',
                                'label' => 'Количество респондентов, соответствующих сегменту:',
                                'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                                'valueColOptions' => ['class' => 'text-left', 'id' => 'count_positive-view'],
                                'type' => kartik\detail\DetailView::INPUT_HTML5 ,
                                //'contentOptions' => ['style' => 'white-space: normal;'],
                            ],
                        ],
                    ],
                ]
            ]);

        ?>


        <?php
        // Некорректное внесение данных в форму редактирования данных программы интервью
        Modal::begin([
            'options' => [
                'id' => 'error_update_data_interview',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Внимание!</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Количество респондентов не должно быть меньше количества респондентов, соответствующих сенгменту.
        </h4>

        <?php
        Modal::end();
        ?>


    </div>



    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 2)-->
    <div id="step_two" class="tabcontent row">


        <?php

        $gridColumnsQuestions = [

            [
                'class' => 'kartik\grid\SerialColumn',
                'header' => '',
            ],

            [
                'attribute' => 'title',
                'label' => 'Название вопроса',
                'header' => '<div class="text-center">Название вопроса</div>',
                'contentOptions' => ['style' => 'white-space: normal; padding-left: 10px;'],
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'header' => '',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '17px']]), $url,[
                            'title' => Yii::t('yii', 'Delete'),
                            'class' => 'delete-question-interview',
                            'id' => 'delete_question-'.$model->id,
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'delete') {
                        $url = Url::to(['/interview/delete-question', 'id' =>$model->id]);
                    }
                    return $url;
                },
            ],
        ];

        echo GridView::widget([
            'dataProvider' => $dataProviderQuestions,
            'showPageSummary' => false, //whether to display the page summary row for the grid view.
            'showHeader' => false, // Скрытие header у всех столбцов
            'id' => 'QuestionsTable',
            'pjax' => false,
            'striped' => false,
            'bordered' => true,
            'condensed' => true,
            'summary' => false,
            'hover' => true,
            'toolbar' => false,
            'columns' => $gridColumnsQuestions,
            'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'before' => '<div class="row" style="margin: 0; font-size: 24px; padding: 7px 0;"><div class="col-md-12 col-lg-6">
                <span style="color: #fff; padding-left: 15px;">Список вопросов для интервью</span></div>'

                    .   Html::a( '<div style="display:flex; align-items: center;"><div>' . Html::img('/images/icons/add_plus_elem.png', ['width' => '25px']) . '</div><div style="padding-left: 20px; color: #fff;">Новый вопрос</div></div>', ['#'], [
                        'class' => 'add_new_question_button col-xs-12 col-sm-4 col-lg-3',
                        'id' => 'buttonAddQuestion',
                    ])

                    .   Html::a( '<div style="display:flex; align-items: center;"><div>' . Html::img('/images/icons/add_plus_elem.png', ['width' => '25px']) . '</div><div style="padding-left: 20px; color: #fff;">Выбрать вопрос из списка</div></div>', ['#'], [
                        'class' => 'add_new_question_button col-xs-12 col-sm-4 col-lg-3',
                        'id' => 'buttonAddQuestionToGeneralList',
                    ])

                    .   '</div><div class="row form-newQuestion-panel kv-hide" style="display: none;"></div>
                    <div class="row form-QuestionsOfGeneralList-panel kv-hide" style="display: none;"></div>',

                'beforeOptions' => ['class' => 'header-table'],
                //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                //'footer' => '{export}',
                //'footer' => '',
                'after' => false,
                'footer' => false,
            ],
        ]);

        ?>


        <!--Форма для добаления нового вопроса-->
        <div class="row" style="display: none;">
            <div class="col-md-12 form-newQuestion" style="margin-top: 5px;">

                <? $form = ActiveForm::begin(['id' => 'addNewQuestion', 'action' => Url::to(['/interview/add-question', 'id' => $model->id])]);?>

                <div class="col-xs-12 col-md-10 col-lg-10">
                    <?= $form->field($newQuestion, 'title', ['template' => '{input}'])->textInput(['maxlength' => true, 'required' => true])->label(false); ?>
                </div>
                <div class="col-xs-12 col-md-2 col-lg-2">
                    <?= Html::submitButton('Сохранить', [
                        'class' => 'btn btn-lg btn-success col-xs-12',
                        'style' => [
                            'margin-bottom' => '15px',
                            'background' => '#52BE7F',
                            'width' => '130px',
                            'height' => '35px',
                            'padding-top' => '4px',
                            'padding-bottom' => '4px'
                        ]
                    ]); ?>
                </div>

                <? ActiveForm::end(); ?>

            </div>
        </div>

        <!--Строка нового вопроса-->
        <table style="display:none;">
            <tbody class="new-string-table-questions">
            <tr class="QuestionsTable" data-key="">
                <td class="kv-align-center kv-align-middle QuestionsTable" style="width: 50px;" data-col-seq="0"></td>
                <td class="QuestionsTable" data-col-seq="1"></td>
                <td class="skip-export kv-align-center kv-align-middle QuestionsTable" style="width: 50px;" data-col-seq="2">
                    <a id="" class="delete-question-interview" href="" title="Удалить">
                        <img src="/web/images/icons/icon_delete.png" alt="удалить" width="17px">
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

        <!--Форма для выбора вопроса из общего списка для  добавления в интервью-->
        <div class="row" style="display: none;">
            <div class="col-md-12 form-QuestionsOfGeneralList" style="margin-top: 5px;">

                <? $form = ActiveForm::begin(['id' => 'addNewQuestionOfGeneralList', 'action' => Url::to(['/interview/add-question', 'id' => $model->id])]);?>

                <div class="col-xs-12 col-md-10 col-lg-10">

                    <?php
                    $items = ArrayHelper::map($queryQuestions,'title','title');
                    //$params = ['prompt' => 'Выберите вариант из списка готовых вопросов'];
                    $params = ['prompt' => [
                        'text' => 'Выберите вариант из списка готовых вопросов',
                        'options' => [
                            'style' => [
                                'font-weight' => '700',
                            ],
                            //'class' => 'prompt-class',
                            //'value' => '',
                            //'selected' => true,
                        ]
                    ]]
                    ?>

                    <?= $form->field($newQuestion, 'title', ['template' => '{input}'])->dropDownList($items,$params)->label(false); ?>

                </div>

                <div class="col-xs-12 col-md-2 col-lg-2">
                    <?= Html::submitButton('Сохранить', [
                        'class' => 'btn btn-lg btn-success col-xs-12',
                        'style' => [
                            'margin-bottom' => '15px',
                            'background' => '#52BE7F',
                            'width' => '130px',
                            'height' => '35px',
                            'padding-top' => '4px',
                            'padding-bottom' => '4px'
                        ]
                    ]); ?>
                </div>

                <? ActiveForm::end(); ?>

            </div>
        </div>

    </div>





    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 3)-->
    <div id="step_three" class="tabcontent row">


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


                ['class' => 'kartik\grid\ActionColumn',
                    'header' => '',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '17px']]), ['#'],[
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
                        return Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '17px']]);
                    },
                    'format' => 'raw',
                    'hidden' => true,
                ]
            ]

            ?>


            <?php

            echo GridView::widget([
                'dataProvider' => $dataProviderQueryResponds,
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

                    //'before' => false,
                    'before' => '<div style="font-size: 24px; color: #ffffff;background: #707F99; padding: 7px 0;">' .

                        '<span style="margin-left: 30px;margin-right: 20px;">Информация о респондентах и интервью</span>'

                        . Html::a('i', ['#'], [
                            'style' => ['margin-rigth' => '20px', 'font-size' => '16px', 'font-weight' => '700', 'padding' => '2px 10px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                            'class' => 'table-kartik-link',
                            'data-toggle' => 'modal',
                            'data-target' => "#information-table-responds",
                            'title' => 'Посмотреть описание',
                        ]) . '</div>',
                    'beforeOptions' => ['class' => 'header-table'],


                    //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                    //'footer' => '{export}',
                    'after' => false,
                    //'footer' => false,
                ],

                'toolbar' => [
                    //'{toggleData}',
                    '{export}',
                ],

                'exportContainer' => ['class' => 'btn btn-group-sm', 'style' => ['padding' => '5px 5px', 'margin' => '3px']],
                //'toggleDataContainer' => ['class' => 'btn btn-group-sm mr-2', 'style' => ['padding' => '5px 5px']],

                /*'toggleDataOptions' => [
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
                ],*/

                'export' => [
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK,
                    'label' => '<span class="font-header-table" style="font-weight: 700;">Экпорт таблицы</span>',
                    'options' => ['title' => false],
                ],

                'columns' => $gridColumns,

                'exportConfig' => [
                    GridView::PDF => [

                        'filename' => 'Таблица_«Информация_о_респондентах»(Подтверждение_ГЦС&&'.$segment_name.'&&'.$project_filename.')' ,

                        'config' => [

                            'marginRight' => 10,
                            'marginLeft' => 10,
                            //'cssInline' => '.positive-business-model-export{margin-right: 20px;}' .
                            //'.presentation-business-model-export{margin-left: 20px;}',

                            'methods' => [
                                'SetHeader' => ['<div style="color: #3c3c3c;">Таблица «Информация о респондентах и интервью»(Подтверждение ГЦС / '.$segment->name.' / '.$project->project_name.')</div>||<div style="color: #3c3c3c;">Сгенерировано: ' . date("H:i d.m.Y") . '</div>'],
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
                        'filename' => 'Таблица_«Информация_о_респондентах»(Подтверждение_ГЦС&&'.$segment_name.'&&'.$project_filename.')' ,
                    ],
                    GridView::HTML => [
                        'filename' => 'Таблица_«Информация_о_респондентах»(Подтверждение_ГЦС&&'.$segment_name.'&&'.$project_filename.')' ,
                    ],
                ],

                //'floatHeader'=>true,
                //'floatHeaderOptions'=>['top'=>'50'],
                'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],

                'beforeHeader' => [
                    [
                        'columns' => [
                            ['content' => '', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                            ['content' =>  Html::a(Html::img('@web/images/icons/add_plus_elem.png', ['style' => ['width' => '25px', 'margin-right' => '10px']]), ['#'], ['data-toggle' => 'modal', 'data-target' => '#respondCreate_modal',]) . 'Респондент', 'options' => ['colspan' => 1, 'class' => 'font-segment-header-table text-center']],
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
                'action' => "/respond/create?id=$model->id",
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
                'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Внимание!</h3>',
            ]);
            ?>

            <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
                Респондент с таким именем уже есть!<br>Имя респондента должно быть уникальным!
            </h4>

            <?php
            Modal::end();
            ?>

            <div class="modal-windows-respond">

                <?php

                foreach ($responds as $i => $respond) :

                    // Модальное окно - информация о респонденте
                    Modal::begin([
                        'options' => [
                            'id' => "respond_view_modal-$respond->id",
                            'class' => 'respond_view_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center header-view-modal">Сведения о респонденте'.Html::a('Редактировать', ['#'],[
                                'id' => 'go_to_update_respond',
                                'class' => 'btn btn-success pull-left go_to_update_respond',
                                'data-toggle' => 'modal',
                                'data-target' => "#respond_update_modal-$respond->id",
                            ]).'</h3>',
                    ]);
                    // Контент страницы информации о респонденте
                    ?>

                    <div class="respond-view">

                        <?= \yii\widgets\DetailView::widget([
                            'model' => $respond,
                            'attributes' => [

                                [
                                    'attribute' => 'name',
                                    'label' => 'Ф.И.О. респондента',
                                    'contentOptions' => ['id' => "respond_name_$respond->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'info_respond',
                                    'contentOptions' => ['id' => "info_respond_$respond->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' =>'email',
                                    'contentOptions' => ['id' => "email_respond_$respond->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'place_interview',
                                    'contentOptions' => ['id' => "place_interview_respond_$respond->id"],
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'date_plan',
                                    'label' => 'Запланированная дата интервью',
                                    'contentOptions' => ['id' => "date_plan_respond_$respond->id"],
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
                            'id' => "respond_update_modal-$respond->id",
                            'class' => 'respond_update_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center header-update-modal">Редактирование информации о респонденте'.Html::a('Назад', ['#'],[
                                'id' => 'go_to_the_viewing_respond',
                                'class' => 'btn btn-default pull-left go_to_the_viewing_respond',
                                'data-toggle' => 'modal',
                                'data-target' => "#respond_view_modal-$respond->id",
                            ]).'</h3>',
                    ]);

                    // Контент страницы редактирования информации о респонденте
                    ?>

                    <div class="respond-form">

                        <?php $form = ActiveForm::begin([
                            'action' => "/respond/update?id=$respond->id",
                            'id' => "formUpdateRespond-$respond->id",
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
                                        'id' => "datePlan-$respond->id",

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

                    if (empty($respond->descInterview)) :

                        // Форма создания интервью для респондента
                        Modal::begin([
                            'options' => [
                                'id' => "create_descInterview_modal-$respond->id",
                                'class' => 'create_descInterview_modal',
                            ],
                            'size' => 'modal-lg',
                            'header' => '<h3 class="text-center">Для создания интервью заполните следующие поля:</h3>',
                        ]);

                        // Контент страницы создания интервью для респондента
                        ?>

                        <div class="desc-interview-create-form">

                            <?php $form = ActiveForm::begin([
                                'action' => "/desc-interview/create?id=$respond->id",
                                'id' => "formCreateDescInterview-$respond->id",
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
                                                <p><?= $form->field($createDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput(['id' => "descInterviewCreateFile-$respond->id"])->label('') ?></p>
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



                    // Форма просмотра интервью для респондента
                    Modal::begin([
                        'options' => [
                            'id' => "view_descInterview_modal-$respond->id",
                            'class' => 'view_descInterview_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center">Сведения о проведенном интервью'.Html::a('Редактировать', ['#'],[
                                'id' => 'go_to_update_interview',
                                'class' => 'btn btn-success pull-left go_to_update_interview',
                                'data-toggle' => 'modal',
                                'data-target' => "#interview_update_modal-$respond->id",
                            ]).'</h3>',
                    ]);

                    // Контент страницы просмотра интервью для респондента
                    ?>

                    <div class="desc-interview-view">
                        <div class="row">
                            <div class="col-md-12">

                                <?= \yii\widgets\DetailView::widget([
                                    'model' => $respond,

                                    'attributes' => [

                                        [
                                            'attribute' => 'name',
                                            'label' => 'Ф.И.О. респондента',
                                            'contentOptions' => ['id' => "respond_name_interview_$respond->id"],
                                            'format' => 'raw',
                                        ],

                                        [
                                            'attribute' => 'created_at',
                                            'label' => 'Дата создания интервью',
                                            'value' => function($model){

                                                return $model->descInterview->created_at;
                                            },
                                            'contentOptions' => ['id' => "created_at_interview_$respond->id"],
                                            'format' => ['date', 'dd.MM.yyyy'],

                                        ],

                                        [
                                            'attribute' => 'updated_at',
                                            'label' => 'Последнее изменение интервью',
                                            'value' => function($model){
                                                return $model->descInterview->updated_at;
                                            },
                                            'contentOptions' => ['id' => "updated_at_interview_$respond->id"],
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
                                            'contentOptions' => ['id' => "description_interview_$respond->id"],
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
                                            'contentOptions' => ['id' => "file_interview_$respond->id"],
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
                                            'contentOptions' => ['id' => "result_interview_$respond->id"],
                                            'format' => 'raw',
                                        ],

                                        [
                                            'attribute' => 'status',
                                            'label' => 'Данный респондент является представителем сегмента?',
                                            'value' => !$respond->descInterview->status ? '<span style="color:red">Нет</span>' : '<span style="color:green">Да</span>',
                                            'contentOptions' => ['id' => "status_respond_$respond->id"],
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
                            'id' => "interview_update_modal-$respond->id",
                            'class' => 'interview_update_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center header-update-modal">Редактирование данных из интервью'.Html::a('Назад', ['#'],[
                                'id' => 'go_to_the_viewing_interview',
                                'class' => 'btn btn-default pull-left go_to_the_viewing_interview',
                                'data-toggle' => 'modal',
                                'data-target' => "#view_descInterview_modal-$respond->id",
                            ]).'</h3>',
                    ]);

                    // Контент страницы редактирования информации о интервью
                    ?>

                    <div class="desc-interview-update-form">

                        <?php if ($respond->descInterview) : ?>

                            <?php $form = ActiveForm::begin([
                                'action' => "/desc-interview/update?id=".$respond->descInterview->id ,
                                'id' => "formUpdateDescInterview-".$respond->descInterview->id ,
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
                                                <p><?= $form->field($updateDescInterviewForms[$i], 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput(['id' => "descInterviewUpdateFile-$respond->id"])->label('') ?></p>
                                            <?php endif;?>

                                            <p>
                                                <?php
                                                if (!empty($updateDescInterviewForms[$i]->interview_file))
                                                {
                                                    echo Html::a($updateDescInterviewForms[$i]->interview_file, ['/desc-interview/download', 'id' => $updateDescInterviewForms[$i]->id], ['class' => "btn btn-default feedback interview_file_update-$respond->id"]) .
                                                        ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id], [
                                                            'onclick'=>
                                                                "$.ajax({
                                                         type:'POST',
                                                         cache: false,
                                                         url: '".Url::to(['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id])."',
                                                         success  : function(response) {
                                                             $('.interview_file-".$respond->id."').html('');
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
                            'id' => "delete-respond-modal-$respond->id",
                            'class' => 'delete_respond_modal',
                        ],
                        'size' => 'modal-md',
                        'header' => '<h3 class="text-center header-update-modal">Подтверждение</h3>',
                        'footer' => '<div class="text-center">'.

                            Html::a('Отмена', ['#'],[
                                'class' => 'btn btn-default',
                                'style' => ['width' => '120px'],
                                'id' => "cancel-delete-respond-$respond->id",
                            ]).

                            Html::a('Ок', ['/respond/delete-respond', 'id' =>$respond->id],[
                                'class' => 'btn btn-default',
                                'style' => ['width' => '120px'],
                                'id' => "confirm-delete-respond-$respond->id",
                            ]).

                            '</div>'
                    ]);

                    // Контент страницы - подтверждение удаления респондента
                    ?>

                    <h4 class="text-center">Вы уверены, что хотите удалить все данные<br>о респонденте «<?= $respond->name ?>»?</h4>

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
                'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Внимание!</h3>',
            ]);
            ?>

            <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
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
                'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Внимание!</h3>',
            ]);
            ?>

            <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
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




    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 4)-->
    <div id="step_four" class="tabcontent">

        <div class="d-inline-block header-table row" style="font-size: 24px; color: #ffffff; border-top: 1px solid #fff;">
            <div class="col-md-12" style="padding: 7px 20px 7px 30px;">
                Завершение подтверждения целевого сегмента
                <?= Html::a('i', ['#'], [
                'style' => ['margin-rigth' => '20px', 'margin-left' => '15px','font-size' => '16px', 'font-weight' => '700', 'padding' => '2px 10px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                'class' => 'table-kartik-link',
                'data-toggle' => 'modal',
                'data-target' => "#information_confirm_segment_result",
                'title' => 'Посмотреть описание',
                ]);?>
            </div>
        </div>

        <div class="row step_four">

            <div class="col-sm-6 col-md-3">
                <?= $model->redirectRespondTable; ?>
            </div>

            <div class="col-sm-6 col-md-9" style="font-weight: 700; height: 70px; padding: 25px 15px;">
                <?= $model->messageAboutTheNextStep; ?>
            </div>

            <div class="col-md-3" style="margin-bottom: 10px; font-weight: 700;">
                Данные респондентов
            </div>

            <div class="col-md-9">
                <?= Html::a($model->dataRespondsOfModel, ['#'],[
                    'data-toggle' => 'modal',
                    'data-target' => '#responds_exist',
                ]); ?>
            </div>

            <div class="col-md-3" style="margin-bottom: 10px; font-weight: 700;">
                Проведение интервью
            </div>

            <div class="col-md-9">
                <?= Html::a($model->dataDescInterviewsOfModel, ['#'], [
                    'data-toggle' => 'modal',
                    'data-target' => '#by_date_interview',
                ]); ?>
            </div>

            <div class="col-md-3" style="margin-bottom: 10px; font-weight: 700;">
                Представители сегмента
            </div>

            <div class="col-md-9">
                <?= Html::a($model->dataMembersOfSegment, ['#'], [
                    'data-toggle' => 'modal',
                    'data-target' => '#by_status_responds',
                ]); ?>
            </div>



            <?php
            // Модальное окно с проверкой заполнения данных о респондентах
            Modal::begin([
                'options' => [
                    'id' => 'responds_exist',
                    'class' => 'responds_exist',
                ],
                'size' => 'modal-lg',
                //'header' => '<h3 class="text-center">Проверка заполнения данных о респондентах</h3>',
            ]);
            ?>

            <?php

            $gridColumnsResponds_1 = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'header' => '',
                ],

                [
                    'attribute' => 'name',
                    'label' => 'Фамилия Имя Отчество',
                    'header' => '<div style="padding: 5px; color: #4F4F4F;">Фамилия Имя Отчество</div>',
                    'value' => function ($model) {
                        if ($model->info_respond && $model->date_plan && $model->place_interview){

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link go_view_respond_for_exist',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#view_respond-' . $model->id,
                            ]);

                        }else {

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#not_view_respond_modal',
                            ]);
                        }

                    },
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'data',
                    'label' => 'Данные респондента',
                    'header' => '<div style="padding: 5px; color: #4F4F4F;">Данные респондента</div>',
                    'value' => function($model){
                        if (!empty($model->name) && !empty($model->info_respond) && !empty($model->date_plan) && !empty($model->place_interview)){
                            return '<div style="padding: 0 5px;">Данные заполнены</div>';
                        }else{
                            return '<div style="padding: 0 5px;">Данные отсутствуют</div>';
                        }

                    },
                    'format' => 'raw',
                ],


            ];

            echo GridView::widget([
                'dataProvider' => $dataProviderResponds,
                'showPageSummary' => false, //whether to display the page summary row for the grid view.
                //'showHeader' => false, // Скрытие header у всех столбцов
                'id' => 'TableRespondsExist',
                'pjax' => false,
                'striped' => false,
                'bordered' => true,
                'condensed' => true,
                'summary' => false,
                'hover' => true,
                'toolbar' => false,
                'columns' => $gridColumnsResponds_1,
                'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],
                'panel' => [
                    'type' => 'default',
                    'heading' => false,
                    'before' => '<div class="col-md-12" style="font-size: 24px; font-weight: 700; color: #F2F2F2;"><span>Проверка заполнения данных о респондентах</span></div>',

                    'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
                    //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                    //'footer' => '{export}',
                    'after' => false,
                    //'footer' => false,
                ],
            ]);

            ?>

            <?php
                Modal::end();
            ?>


            <?php
            // Модальное окно - Проверка стадии проведения интервью
            Modal::begin([
                'options' => [
                    'id' => 'by_date_interview',
                    'class' => 'by_date_interview',
                ],
                'size' => 'modal-lg',
                //'header' => '<h3 class="text-center">Проверка заполнения данных о респондентах</h3>',
            ]);
            ?>

            <?php

            $gridColumnsResponds_2 = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'header' => '',
                ],

                [
                    'attribute' => 'name',
                    'label' => 'Фамилия Имя Отчество',
                    'header' => '<div style="font-size: 12px; font-weight: 500; color: #4F4F4F; padding: 0 5px;">Фамилия Имя Отчество</div>',
                    'value' => function ($model) {
                        if ($model->info_respond && $model->date_plan && $model->place_interview){

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link go_view_respond_by_date_interview',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#view_respond_by_date-' . $model->id,
                            ]);

                        } else {

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#not_view_respond_modal',
                            ]);
                        }

                    },
                    'format' => 'raw',
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

                            return '<div class="text-center" style="padding: 0 25px;">' . date("d.m.yy", $model->date_plan) . '</div>';

                        }else{
                            return '';
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

                            return '<div class="text-center" style="padding: 0 25px;">' . date("d.m.yy", $model->descInterview->updated_at) . '</div>';

                        }else{
                            return '';
                        }
                    },
                    'format' => 'raw',
                ],

            ];

            echo GridView::widget([
                'dataProvider' => $dataProviderResponds,
                'showPageSummary' => false, //whether to display the page summary row for the grid view.
                //'showHeader' => false, // Скрытие header у всех столбцов
                'id' => 'TableByDateInterview',
                'pjax' => false,
                'striped' => false,
                'bordered' => true,
                'condensed' => true,
                'summary' => false,
                'hover' => true,
                'toolbar' => false,
                'columns' => $gridColumnsResponds_2,
                'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],
                'panel' => [
                    'type' => 'default',
                    'heading' => false,
                    'before' => '<div class="col-md-12" style="font-size: 24px; font-weight: 700; color: #F2F2F2;"><span>Проверка стадии проведения интервью</span></div>',

                    'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
                    //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                    //'footer' => '{export}',
                    'after' => false,
                    //'footer' => false,
                ],
                'beforeHeader' => [
                    [
                        'columns' => [
                            ['content' => '', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                            ['content' => 'Респондент', 'options' => ['colspan' => 1, 'class' => 'font-segment-header-table', 'style' => ['padding' => '10px 10px']]],
                            ['content' => 'Дата интервью', 'options' => ['colspan' => 2, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
                        ],

                        'options' => [
                            'class' => 'style-header-table-kartik',
                        ]
                    ]
                ],
            ]);

            ?>

            <?php
            Modal::end();
            ?>


            <?php
            // Модальное окно - поиск представителей сегмента
            Modal::begin([
                'options' => [
                    'id' => 'by_status_responds',
                    'class' => 'by_status_responds',
                ],
                'size' => 'modal-lg',
                //'header' => '<h3 class="text-center">Проверка заполнения данных о респондентах</h3>',
            ]);
            ?>

            <?php

            $gridColumnsResponds_3 = [

                [
                    'class' => 'kartik\grid\SerialColumn',
                    'header' => '',
                ],

                [
                    'attribute' => 'name',
                    'label' => 'Фамилия Имя Отчество',
                    'header' => '<div style="padding: 5px; color: #4F4F4F;">Фамилия Имя Отчество</div>',
                    'value' => function ($model) {
                        if ($model->info_respond && $model->date_plan && $model->place_interview){

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link go_view_respond_for_by_status',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#view_respond_by_status-' . $model->id,
                            ]);

                        }else {

                            return Html::a(Html::encode($model->name), ['#'],[
                                'class' => 'table-kartik-link',
                                'style' => ['padding' => '0 5px'],
                                'data-toggle' => 'modal',
                                'data-target' => '#not_view_respond_modal',
                            ]);
                        }
                    },
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'status',
                    'headerOptions' => ['class' => 'text-center'],
                    'label' => 'Респондент представитель сегмента?',
                    'header' => '<div style="padding: 5px; color: #4F4F4F;">Респондент представитель сегмента?</div>',
                    'value' => function($model){

                        if ($model->descInterview->status == 1){
                            return '<div class="text-center" style="color: green;">Да</div>';

                        } elseif($model->descInterview->status == null){
                            return '';

                        } elseif($model->descInterview->status == 0){
                            return '<div class="text-center" style="color: red;">Нет</div>';
                        }
                    },
                    'format' => 'raw',
                ],


            ];

            echo GridView::widget([
                'dataProvider' => $dataProviderResponds,
                'showPageSummary' => false, //whether to display the page summary row for the grid view.
                //'showHeader' => false, // Скрытие header у всех столбцов
                'id' => 'TableByStatusResponds',
                'pjax' => false,
                'striped' => false,
                'bordered' => true,
                'condensed' => true,
                'summary' => false,
                'hover' => true,
                'toolbar' => false,
                'columns' => $gridColumnsResponds_3,
                'headerRowOptions' => ['class' => 'style-head-table-kartik-bottom'],
                'panel' => [
                    'type' => 'default',
                    'heading' => false,
                    'before' => '<div class="col-md-12" style="font-size: 24px; font-weight: 700; color: #F2F2F2;"><span>Поиск представителей сегмента</span></div>',

                    'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
                    //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                    //'footer' => '{export}',
                    'after' => false,
                    //'footer' => false,
                ],
            ]);

            ?>

            <?php
            Modal::end();
            ?>


            <?php

            foreach ($model->responds as $respond) :

            if ($respond->info_respond && $respond->date_plan && $respond->place_interview) :

            // Модальное окно с информацией о респонденте для проверки данных о респонденте
            Modal::begin([
                'options' => [
                    'id' => 'view_respond-' . $respond->id,
                    'class' => 'view_respond',
                ],
                'size' => 'modal-lg',
                'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_exist_responds']) .'<span style="font-size: 24px;">Информация о респонденте и интервью</span></div>',
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


            // Модальное окно с информацией о респонденте для проверки данных по интервью
            Modal::begin([
                'options' => [
                    'id' => 'view_respond_by_date-' . $respond->id,
                    'class' => 'view_respond_by_date',
                ],
                'size' => 'modal-lg',
                'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_by_date']) .'<span style="font-size: 24px;">Информация о респонденте и интервью</span></div>',
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




            // Модальное окно с информацией о респонденте для поиска представителей сегмента
            Modal::begin([
                'options' => [
                    'id' => 'view_respond_by_status-' . $respond->id,
                    'class' => 'view_respond_by_status',
                ],
                'size' => 'modal-lg',
                'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_by_status']) .'<span style="font-size: 24px;">Информация о респонденте и интервью</span></div>',
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

            <?php

            // Модальное окно - сообщение о том что у выбранного респондента данные отсутствуют
            Modal::begin([
                'options' => [
                    'id' => 'not_view_respond_modal',
                    'class' => 'not_view_respond_modal',
                ],
                'size' => 'modal-md',
                'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Внимание!</h3>',
            ]);
            ?>

            <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
                У выбранного респондента пока отсутствуют<br>заполненные данные для показа.
            </h4>

            <?php

            Modal::end();

            ?>


            <?php

            // Модальное окно - информация о завершении подтверждения
            Modal::begin([
                'options' => [
                    'id' => 'information_confirm_segment_result',
                    'class' => 'information_confirm_segment_result',
                ],
                'size' => 'modal-md',
                'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
            ]);
            ?>

            <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
                Для завершения подтверждения соблюдайте рекомендации.
            </h4>

            <?php

            Modal::end();

            ?>

        </div>

    </div>



    <div id="feedbacks" class="tabcontent row">

    </div>

</div>



<?php

$script = "

    $(document).ready(function() {
    
        //Фон для модального окна информации при заголовке таблицы
        var information_modal_problem_view = $('#information-table-problem-view').find('.modal-content');
        information_modal_problem_view.css('background-color', '#707F99');
        
        //Фон для модального окна информации о том, что у выбранного респондента данные отсутствуют
        var not_view_respond_modal = $('#not_view_respond_modal').find('.modal-content');
        not_view_respond_modal.css('background-color', '#707F99');
        
        // Фон для модального окна - информация о завершении подтверждения
        var information_confirm_segment_result_modal = $('#information_confirm_segment_result').find('.modal-content');
        information_confirm_segment_result_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - некорректное внесение исходных данных в форму редактирования
        var error_update_data_interview_modal = $('#error_update_data_interview').find('.modal-content');
        error_update_data_interview_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - респондент с таким именем уже существует 
        var respondUpdate_modal_error_modal = $('#respondUpdate_modal_error').find('.modal-content');
        respondUpdate_modal_error_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - чтобы добавить интервью, необходимо заполнить инф-ю о всех респондентах
        var descInterviewCreate_modal_error_modal = $('#descInterviewCreate_modal_error').find('.modal-content');
        descInterviewCreate_modal_error_modal.css('background-color', '#707F99');
    
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.tab', this).each(function(){

          var height = $(this).height();
        
           $('.tablinks').css('height', height);
        
        });
        
        //Отмена перехода по ссылке кнопки добавить вопрос
        $('a.add_new_question_button').on('click', false);
        
        
        //Плавное изменение цвета ссылки этапа подтверждения
        $('.tab button').hover(function() {
            $(this).stop().animate({ backgroundColor: '#707f99'}, 300);
        },function() {
            $(this).stop().animate({ backgroundColor: '#828282' }, 300);
        });
        
    
        //Убираем отступ снизу таблицы (Шаг 1)
        $('#table-data-interview-container').find('.panel').css('margin-bottom', '0');
        
        //Вырезаем и вставляем форму добавления вопроса в панель таблицы (Шаг 1) 
        $('.form-newQuestion-panel').append($('.form-newQuestion').first());
        
        //Показываем и скрываем форму добавления вопроса 
        //при нажатии на кнопку добавить вопрос (Шаг 1)
        $('#buttonAddQuestion').on('click', function(){
            $('.form-QuestionsOfGeneralList-panel').hide();
            $('.form-newQuestion-panel').toggle();
        });
        
        //Вырезаем и вставляем форму для выбора вопроса в панель таблицы (Шаг 1)
        $('.form-QuestionsOfGeneralList-panel').append($('.form-QuestionsOfGeneralList').first());
        
        //Показываем и скрываем форму для выбора вопроса 
        //при нажатии на кнопку выбрать из списка (Шаг 1)
        $('#buttonAddQuestionToGeneralList').on('click', function(){
            $('.form-newQuestion-panel').hide();
            $('.form-QuestionsOfGeneralList-panel').toggle();
        });
        
        
        
        //---Переходы по модальным окнам (ШАГ 2)---Начало---
        
        //При переходе из таблицы responds_exist 
        //в модальное окно просмотра инф-и о респонденте
        //закарываем другие модальные окна (Шаг 2)
        $('.interview-view').on('click', '.go_view_respond_for_exist', function(){
            $('.responds_exist').modal('hide');
        });
        
        //При переходе из таблицы by_date_interview 
        //в модальное окно просмотра инф-и о респонденте
        //закарываем другие модальные окна (Шаг 2)
        $('.interview-view').on('click', '.go_view_respond_by_date_interview', function(){
            $('.by_date_interview').modal('hide');
        });
        
        //При переходе из таблицы by_status_responds
        //в модальное окно просмотра инф-и о респонденте
        //закарываем другие модальные окна (Шаг 2)
        $('.interview-view').on('click', '.go_view_respond_for_by_status', function(){
            $('.by_status_responds').modal('hide');
        });
        
        //При клике из окна просмотра инф-и о респонденте на кнопку НАЗАД
        //закрываем окно и показываем таблицу responds_exist (Шаг 2)
        $('.go_view_back_exist_responds').on('click', function(){
            $('.view_respond').modal('hide');
            $('.responds_exist').modal('show');
        });
        
        //При клике из окна просмотра инф-и о респонденте на кнопку НАЗАД
        //закрываем окно и показываем таблицу by_date_interview (Шаг 2)
        $('.go_view_back_by_date').on('click', function(){
            $('.view_respond_by_date').modal('hide');
            $('.by_date_interview').modal('show');
        });
        
        //При клике из окна просмотра инф-и о респонденте на кнопку НАЗАД
        //закрываем окно и показываем таблицу by_status_responds (Шаг 2)
        $('.go_view_back_by_status').on('click', function(){
            $('.view_respond_by_status').modal('hide');
            $('.by_status_responds').modal('show');
        });
        
        //---Переходы по модальным окнам (ШАГ 2)---Конец---
        
        
        //Фон для модального окна информации ШАГ 3
        var information_modal = $('#information-table-responds').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
    });


    //Редактирование исходных даннных интервью (Шаг 1)
    $('#update_data_interview').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){

                if (!response['error']) {
                
                    //Перезагружаем страницу после сохранения данных
                    location.reload();

                } else {
                    // Вызов модального окна, если было некорректное 
                    //внесение данных в форму редактирования 
                    //данных программы интервью (Шаг 1)
                    $('#error_update_data_interview').modal('show');
                }
            }, error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });

    
    //Создание нового вопроса (Шаг 2)
    $('#addNewQuestion').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса (Шаг 2)
                var container = $('#QuestionsTable-container').find('tbody');
                $('.new-string-table-questions').find('tr').attr('data-key', response.model.id);
                $('.new-string-table-questions').find('td[data-col-seq=\"1\"]').html(response.model.title);
                $('.new-string-table-questions').find('.delete-question-interview').attr('id', 'delete_question-' + response.model.id);
                $('.new-string-table-questions').find('.delete-question-interview').attr('href', '/interview/delete-question?id=' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк после удаления (Шаг 2)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Обновляем список вопросов на странице (Шаг 2)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font-weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option id=\"' + index + ' - stringQueryQuestion\" value=\"' + value.title + '\">' + value.title + '<\/option>');
                });
                
                //Скрываем и очищием форму (Шаг 1)
                $('.form-newQuestion-panel').hide();
                $('#addNewQuestion')[0].reset();
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //Добавление нового вопроса из списка предложенных (Шаг 2)
    $('#addNewQuestionOfGeneralList').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса (Шаг 2)
                var container = $('#QuestionsTable-container').find('tbody');
                $('.new-string-table-questions').find('tr').attr('data-key', response.model.id);
                $('.new-string-table-questions').find('td[data-col-seq=\"1\"]').html(response.model.title);
                $('.new-string-table-questions').find('.delete-question-interview').attr('id', 'delete_question-' + response.model.id);
                $('.new-string-table-questions').find('.delete-question-interview').attr('href', '/interview/delete-question?id=' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк (Шаг 1)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Скрываем форму (Шаг 1)
                $('.form-QuestionsOfGeneralList-panel').hide();
                
                //Обновляем список вопросов на странице (Шаг 2)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font-weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option id=\"' + index + ' - stringQueryQuestion\" value=\"' + value.title + '\">' + value.title + '<\/option>');
                });
                
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //Удаление вопроса для интервью в модальном окне.
    //Для того чтобы обрабатывались и старые и новые вопросы
    //указываем контейнер в контором необходимо обрабатывать запросы,
    //а после события указываем по какому элементу оно будет срабатывать. (Шаг 2)
    $('#QuestionsTable-container').on('click', '.delete-question-interview', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/interview/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы (Шаг 2)
        var deleteString = $('#QuestionsTable-container').find('tr[data-key=\"' + id + '\"]');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){

                //Скрываем удаленный вопрос (Шаг 2)
                deleteString.hide();
                
                //Изменение нумерации строк после удаления (Шаг 2)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Обновляем список вопросов на странице (Шаг 2)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font-weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option id=\"' + index + ' - stringQueryQuestion\" value=\"' + value.title + '\">' + value.title + '<\/option>');
                });
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //ШАГ 3 - Заполнение данных респондентов и интервью
    
    $(document).ready(function() {
    
        //Фон для модального окна информации
        var information_modal = $('#information-table-responds').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - респондент с таким именем уже существует
        var respondCreate_modal_error_modal = $('#respondCreate_modal_error').find('.modal-content');
        respondCreate_modal_error_modal.css('background-color', '#707F99');
        
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

foreach ($responds as $i => $respond) :

    $script2 = "

    $(document).ready(function() {

        //Стилизация модального окна для удаления респондента
        var modal_header_delete_respond = $('#delete-respond-modal-".$respond->id."').find('.modal-header');
        modal_header_delete_respond.css('background-color', '#ffb02e');
        modal_header_delete_respond.css('color', '#ffffff');
        modal_header_delete_respond.css('border-radius', '5px 5px 0 0');
        
        var modal_footer_delete_respond = $('#delete-respond-modal-".$respond->id."').find('.modal-footer');
        modal_footer_delete_respond.css('background-color', '#ffb02e');
        modal_footer_delete_respond.css('border-radius', '0 0 5px 5px');
        
    });

    // CONFIRM RESPOND DELETE
    $('#confirm-delete-respond-".$respond->id."').on('click',function(e) {
        
         var url = $(this).attr('href');
         $.ajax({
              url: url,
              method: 'POST',
              cache: false,
              success: function() {
                   
                   //Закрываем окно подтверждения
                   $('#delete-respond-modal-".$respond->id."').modal('hide');
                                
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



    // CANCEL RESPOND DELETE
    $('#cancel-delete-respond-".$respond->id."').on('click',function(e) {
        
         //Закрываем окно подтверждения
         $('#delete-respond-modal-".$respond->id."').modal('hide');
         
         e.preventDefault();
         return false;
    });
    
    
    //Сохранении данных из формы редактирование дынных респондента и 
    //передача новых данных в модальное окно view
    $('#formUpdateRespond-".$respond->id."').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){

                if (!response['error']) {

                    //Закрываем окно редактирования
                    $('.respond_update_modal').modal('hide');
                    
                    //Перезагружаем страницу
                    location.reload();
                      
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
    $('#formCreateDescInterview-".$respond->id."').on('beforeSubmit', function(e){
    
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
    $('#formUpdateDescInterview-".$respond->descInterview->id."').on('beforeSubmit', function(e){
    
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
    $('.interview_file-".$respond->id."').on('click', function(e){
    
        //var url = '/desc-interview/download?id=".$respond->descInterview->id."';
        
        var url = $('.interview_file-".$respond->id."').attr('href');
        
        document.location.href = url;
    
        e.preventDefault();

        return false;
    });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script2, $position);

endforeach;
?>
