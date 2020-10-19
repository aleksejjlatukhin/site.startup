<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\User;
use app\models\Segment;
use yii\bootstrap\Modal;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Подтверждение гипотезы проблемы сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerCssFile('@web/css/confirm-problem-view-style.css');
?>
<div class="confirm-problem-view">


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

        <?= Html::a('<div class="stage_number">3</div><div>Генерация гипотез проблем сегментов</div>',
            ['/generation-problem/index', 'id' => $interview->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
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



            <span style="padding-left: 30px; padding-right: 10px; font-weight: 400; font-size: 20px;">Проблема:</span>

            <?php
            $problem = $generationProblem->description;
            if (mb_strlen($problem) > 25){
                $problem = mb_substr($problem, 0, 25) . '...';
            }
            ?>

            <?= '<span title="'.$generationProblem->description.'">' . $problem . '</span>'; ?>

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



    <!-- Tab links -->
    <div class="tab row">


        <button class="tablinks step_one_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_one')">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 1</div>
                <div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div>
            </div>
        </button>

        <button class="tablinks step_two_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_two')">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 2</div>
                <div class="link_create_interview-text_right">Сформировать список вопросов</div>
            </div>
        </button>

        <button class="tablinks step_two_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_three')" id="defaultOpen">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 3</div>
                <div class="link_create_interview-text_right">Заполнить анкетные данные респондентов</div>
            </div>
        </button>

        <button class="tablinks step_four_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'feedbacks')">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 4</div>
                <div class="link_create_interview-text_right">Получить отзывы экспертов</div>
            </div>
        </button>

    </div>


    <!-- Tab content -->

    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 1)-->
    <div id="step_one" class="tabcontent row">


        <div class="container-fluid form-view-data-confirm">

            <div class="row row_header_data">

                <div class="col-sm-12 col-md-9" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Определение данных, которые необходимо подтвердить</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'data-toggle' => 'modal',
                        'data-target' => "#information-add-new-responds",
                        'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-3">

                    <?= Html::button('Редактировать', [
                        'id' => 'show_form_update_data',
                        'class' => 'btn btn-default',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#707F99',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ])?>

                </div>

            </div>


            <?php
            $form = ActiveForm::begin([
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>

            <div class="container-fluid">

                <div class="row" style="padding-top: 30px; padding-bottom: 10px; padding-left: 5px;">

                    <div class="col-md-12" style="font-weight: 700;">
                        Формулировка проблемы, которую проверяем
                    </div>

                    <div class="col-md-12" style="padding-top: 10px;">
                        <?= $generationProblem->description;?>
                    </div>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'need_consumer', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Какую потребность потребителя сегмента проверяем')
                        ->textarea([
                            'rows' => 1,
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов (представителей сегмента)</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmProblem, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих проблему')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                </div>

            </div>

            <?php
            ActiveForm::end();
            ?>

        </div>

        <div class="container-fluid form-update-data-confirm">

            <?php
                $form = ActiveForm::begin([
                    'id' => 'update_data_interview',
                    'action' => Url::to(['/confirm-problem/update-data-interview', 'id' => $formUpdateConfirmProblem->id]),
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]);
            ?>


            <div class="row row_header_data">

                <div class="col-sm-12 col-md-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Определение данных, которые необходимо подтвердить</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'data-toggle' => 'modal',
                        'data-target' => "#information-add-new-responds",
                        'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-6">

                    <?= Html::button('Просмотр', [
                        'id' => 'show_form_view_data',
                        'class' => 'btn btn-default',
                        'style' => [
                            'background' => '#E0E0E0',
                            'padding' => '0 7px',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ])?>

                    <?= Html::submitButton('Сохранить', [
                        'class' => 'btn btn-success',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#52BE7F',
                            'padding' => '0 7px',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>

                </div>

            </div>


            <div class="container-fluid">


                <div class="row" style="padding-top: 30px; padding-bottom: 10px; padding-left: 5px;">

                    <div class="col-md-12" style="font-weight: 700;">
                        Формулировка проблемы, которую проверяем
                    </div>

                    <div class="col-md-12" style="padding-top: 10px;">
                        <?= $generationProblem->description;?>
                    </div>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'need_consumer', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Какую потребность потребителя сегмента проверяем')
                        ->textarea([
                            'rows' => 1,
                            'placeholder' => '',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов (представителей сегмента)</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_respond',
                            ]);
                    ?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmProblem, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих проблему')
                        ->textInput([
                            'type' => 'number',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_positive',
                            ]);
                    ?>

                </div>


                <script>

                    $( function() {

                        //Если задано, что count_respond < count_positive, то count_respond = count_positive
                        $("input#confirm_count_respond").change(function () {
                            var value1 = $("input#confirm_count_positive").val();
                            var value2 = $("input#confirm_count_respond").val();
                            var valueMax = 100;
                            var valueMin = 1;

                            if (parseInt(value2) < parseInt(value1)){
                                value2 = value1;
                                $("input#confirm_count_respond").val(value2);
                            }

                            if (parseInt(value2) > parseInt(valueMax)){
                                value2 = valueMax;
                                $("input#confirm_count_respond").val(value2);
                            }

                            if (parseInt(value2) < parseInt(valueMin)){
                                value2 = valueMin;
                                $("input#confirm_count_respond").val(value2);
                            }
                        });

                        //Если задано, что count_positive > count_respond, то count_positive = count_respond
                        $("input#confirm_count_positive").change(function () {
                            var value1 = $("input#confirm_count_positive").val();
                            var value2 = $("input#confirm_count_respond").val();
                            var valueMax = 100;
                            var valueMin = 1;

                            if (parseInt(value1) > parseInt(value2)){
                                value1 = value2;
                                $("input#confirm_count_positive").val(value1);
                            }

                            if (parseInt(value1) > parseInt(valueMax)){
                                value1 = valueMax;
                                $("input#confirm_count_positive").val(value1);
                            }

                            if (parseInt(value1) < parseInt(valueMin)){
                                value1 = valueMin;
                                $("input#confirm_count_positive").val(value1);
                            }
                        });

                    } );
                </script>


            </div>

            <?php
                ActiveForm::end();
            ?>

        </div>


        <?php
        // Некорректное внесение данных в форму редактирования данных
        Modal::begin([
            'options' => [
                'id' => 'error_update_data_interview',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            - общее количество респондентов не может быть меньше количества респондентов, подтверждающих проблему;
        </h4>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            - количественные данные респондентов не могут быть меньше 1.
        </h4>

        <?php
        Modal::end();
        ?>



    </div>




    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 2)-->
    <div id="step_two" class="tabcontent row">


        <div class="container-fluid container-data">

            <!--Заголовок для списка вопросов-->

            <div class="row row_header_data">

                <div class="col-xs-12 col-md-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Список вопросов для интервью</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'data-toggle' => 'modal',
                        'data-target' => "#information-table-questions",
                        'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="col-xs-12 col-md-6" style="padding: 0;">

                    <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить вопрос</div></div>', ['#'],
                        ['class' => 'add_new_question_button pull-right', 'id' => 'buttonAddQuestion']
                    );
                    ?>

                </div>

            </div>


            <!--Сюда помещаем форму для создания нового вопроса-->
            <div class="form-newQuestion-panel" style="display: none;"></div>

            <!--Список вопросов-->
            <div id="QuestionsTable-container" class="row" style="padding-top: 30px; padding-bottom: 30px;">

                <?php foreach ($questions as $q => $question) : ?>

                    <div class="col-xs-12 string_question string_question-<?= $question->id; ?>">

                        <div class="row style_form_field_questions">
                            <div class="col-xs-11">
                                <div style="display:flex;">
                                    <div class="number_question" style="padding-right: 15px;"><?= ($q+1) . '. '; ?></div>
                                    <div class="title_question"><?= $question->title; ?></div>
                                </div>
                            </div>
                            <div class="col-xs-1 delete_question_link">
                            <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]), [
                                Url::to(['/confirm-problem/delete-question', 'id' => $question->id])],[
                                'title' => Yii::t('yii', 'Delete'),
                                'class' => 'delete-question-confirm-problem pull-right',
                                'id' => 'delete_question-'.$question->id,
                            ]); ?>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>


            <!--Форма для добаления нового вопроса-->
            <div style="display: none;">
                <div class="col-md-12 form-newQuestion" style="margin-top: 20px; padding: 0;">

                    <? $form = ActiveForm::begin([
                        'id' => 'addNewQuestion',
                        'action' => Url::to(['/confirm-problem/add-question', 'id' => $model->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);
                    ?>

                    <div class="col-xs-12 col-sm-9 col-lg-10">

                        <?= $form->field($newQuestion, 'title', ['template' => '{input}', 'options' => ['style' => ['position' => 'absolute', 'top' => '0', 'z-index' => '20', 'left' => '15px', 'right' => '15px']]])
                            ->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'placeholder' => 'Введите свой вопрос или выберите готовый из выпадающего списка',
                                'id' => 'add_text_question_confirm',
                                'class' => 'style_form_field_respond',
                                'autocomplete' => 'off'])
                            ->label(false);
                        ?>

                        <?= Html::a('<span class="triangle-bottom"></span>', ['#'], [
                            'id' => 'button_add_text_question_confirm',
                            'class' => 'btn'
                        ]); ?>

                        <?= $form->field($newQuestion, 'list_questions', ['template' => '{input}', 'options' => ['style' => ['position' => 'absolute', 'top' => '0', 'z-index' => '10', 'left' => '15px', 'right' => '15px']]])
                            ->widget(Select2::class, [
                                'data' => $queryQuestions,
                                'options' => [
                                    'id' => 'add_new_question_confirm',
                                    'placeholder' => 'Выберите вариант из списка готовых вопросов',
                                ],
                                'pluginEvents' => [
                                    "select2:open" => 'function() { 
                                        $(".select2-container--krajee .select2-dropdown").css("border-color","#828282");
                                        $(".select2-container--krajee.select2-container--open .select2-selection, .select2-container--krajee .select2-selection:focus").css("border-color","#828282");
                                        $(".select2-container--krajee.select2-container--open .select2-selection, .select2-container--krajee .select2-selection:focus").css("box-shadow","none"); 
                                    }',
                                ],
                                'disabled' => false,  //Сделать поле неактивным
                                'hideSearch' => false, //Скрытие поиска
                            ]);
                        ?>

                    </div>

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <?= Html::submitButton('Сохранить', [
                            'class' => 'btn btn-lg btn-success',
                            'id' => 'submit_addNewQuestion',
                            'style' => [
                                'margin-bottom' => '15px',
                                'background' => '#52BE7F',
                                'width' => '100%',
                                'height' => '40px',
                                'padding-top' => '4px',
                                'padding-bottom' => '4px',
                                'border-radius' => '8px',
                            ]
                        ]); ?>
                    </div>

                    <? ActiveForm::end(); ?>

                </div>
            </div>


            <!--Строка нового вопроса-->
            <div style="display:none;">
                <div class="new-string-table-questions">
                    <div class="col-xs-12 string_question">
                        <div class="row style_form_field_questions">
                            <div class="col-xs-11">
                                <div style="display:flex;">
                                    <div class="number_question" style="padding-right: 15px;"></div>
                                    <div class="title_question"></div>
                                </div>
                            </div>
                            <div class="col-xs-1 delete_question_link">
                                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]), [
                                    Url::to(['/confirm-problem/delete-question', 'id' => ''])],[
                                    'title' => Yii::t('yii', 'Delete'),
                                    'class' => 'delete-question-confirm-problem pull-right',
                                    'id' => '',
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>



    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-questions',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Сформулируйте собственный список вопросов для анкеты или отредактируйте список «по-умолчанию».
    </h4>

    <?php
    Modal::end();
    ?>




    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 3)-->
    <div id="step_three" class="tabcontent style-header-table-kartik">

        <?php

        //echo $model->pointerOnThirdStep();

        ?>

    <!--</div>-->




    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 4)-->
   <!-- <div id="step_four" class="tabcontent">-->

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
                Проведение опроса
            </div>

            <div class="col-md-9">
                <?= Html::a($model->dataDescInterviewsOfModel, ['#'], [
                    'data-toggle' => 'modal',
                    'data-target' => '#by_date_interview',
                ]); ?>
            </div>

            <div class="col-md-3" style="margin-bottom: 10px; font-weight: 700;">
                Подтверждение гипотезы
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
                        if ($model->info_respond){

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
                        if (!empty($model->name) && !empty($model->info_respond)){
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
                        if ($model->info_respond){

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
                    'attribute' => 'fact',
                    'label' => 'Дата изменения',
                    'header' => '<div class="font-header-table" style="font-size: 12px; font-weight: 500;">Дата изменения</div>',
                    'groupOddCssClass' => 'kv',
                    'groupEvenCssClass' => 'kv',
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
                    'before' => '<div class="col-md-12" style="font-size: 24px; font-weight: 700; color: #F2F2F2;"><span>Проверка проведения опроса респондентов</span></div>',

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
                            ['content' => 'Дата опроса', 'options' => ['colspan' => 1, 'class' => 'font-header-table', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px', 'text-align' => 'center']]],
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
                        if ($model->info_respond){

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
                    'label' => 'Вывод о текущей проблеме',
                    'header' => '<div style="padding: 5px; color: #4F4F4F;">Вывод о текущей проблеме</div>',
                    'value' => function($model){

                        if ($model->descInterview->status == 1){
                            return '<div class="text-center" style="color:green">Значимая проблема</div>';

                        } elseif($model->descInterview->status == null){
                            return '';

                        } elseif($model->descInterview->status == 0){
                            return '<div class="text-center" style="color:red">Проблемы не существует или она малозначимая</div>';
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
                    'before' => '<div class="col-md-12" style="font-size: 24px; font-weight: 700; color: #F2F2F2;"><span>Подтверждение проблемы</span></div>',

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

                if ($respond->info_respond) :

                    // Модальное окно с информацией о респонденте для проверки данных о респонденте
                    Modal::begin([
                        'options' => [
                            'id' => 'view_respond-' . $respond->id,
                            'class' => 'view_respond',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_exist_responds']) .'<span style="font-size: 24px;">Информация о респонденте и анкете</span></div>',
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

                        [
                            'attribute' => 'created_descInterview',
                            'label' => 'Дата создания анкеты',
                            'value' => function($model){
                                return $model->descInterview->created_at;
                            },
                            'contentOptions' => ['id' => "created_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],
                        ],

                        [
                            'attribute' => 'updated_descInterview',
                            'label' => 'Дата изменения индикатора анкеты',
                            'value' => function($model){
                                return $model->descInterview->updated_at;
                            },
                            'contentOptions' => ['id' => "updated_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],

                        ],


                        [
                            'attribute' => 'respond_status',
                            'label' => 'Вывод о текущей проблеме',
                            'value' => function($model){
                                if ($model->descInterview){
                                    return !$model->descInterview->status ? '<span style="color:red">Проблемы не существует или она малозначимая</span>' : '<span style="color:green">Значимая проблема</span>';
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
                        'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_by_date']) .'<span style="font-size: 24px;">Информация о респонденте и анкете</span></div>',
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

                        [
                            'attribute' => 'created_descInterview',
                            'label' => 'Дата создания анкеты',
                            'value' => function($model){
                                return $model->descInterview->created_at;
                            },
                            'contentOptions' => ['id' => "created_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],
                        ],

                        [
                            'attribute' => 'updated_descInterview',
                            'label' => 'Дата изменения индикатора анкеты',
                            'value' => function($model){
                                return $model->descInterview->updated_at;
                            },
                            'contentOptions' => ['id' => "updated_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],

                        ],

                        [
                            'attribute' => 'questions',
                            'label' => 'Результаты анкеты (вопрос-ответ)',
                            'value' => function($model){
                                return $model->listQuestions();
                            },
                            'format' => 'html',
                        ],

                        [
                            'attribute' => 'respond_status',
                            'label' => 'Вывод о текущей проблеме',
                            'value' => function($model){
                                if ($model->descInterview){
                                    return !$model->descInterview->status ? '<span style="color:red">Проблемы не существует или она малозначимая</span>' : '<span style="color:green">Значимая проблема</span>';
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
                        'header' => '<div class="text-center">'. Html::button('Назад', ['class' => 'btn btn-default pull-left go_view_back_by_status']) .'<span style="font-size: 24px;">Информация о респонденте и анкете</span></div>',
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

                        [
                            'attribute' => 'created_descInterview',
                            'label' => 'Дата создания анкеты',
                            'value' => function($model){
                                return $model->descInterview->created_at;
                            },
                            'contentOptions' => ['id' => "created_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],
                        ],

                        [
                            'attribute' => 'updated_descInterview',
                            'label' => 'Дата изменения индикатора анкеты',
                            'value' => function($model){
                                return $model->descInterview->updated_at;
                            },
                            'contentOptions' => ['id' => "updated_at_interview_$model->id"],
                            'format' => ['date', 'dd.MM.yyyy'],

                        ],

                        [
                            'attribute' => 'questions',
                            'label' => 'Результаты анкеты (вопрос-ответ)',
                            'value' => function($model){
                                return $model->listQuestions();
                            },
                            'format' => 'html',
                        ],

                        [
                            'attribute' => 'respond_status',
                            'label' => 'Вывод о текущей проблеме',
                            'value' => function($model){
                                if ($model->descInterview){
                                    return !$model->descInterview->status ? '<span style="color:red">Проблемы не существует или она малозначимая</span>' : '<span style="color:green">Значимая проблема</span>';
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
                'header' => '<h3 class="text-center">Внимание!</h3>',
            ]);
            ?>

            <h4 class="text-center text-danger">
                У выбранного респондента пока отсутствуют<br>заполненные данные для показа.
            </h4>

            <?php

            Modal::end();

            ?>

        </div>

    </div>






    <div id="feedbacks" class="tabcontent"></div>





    <?php
    // Информация о месте добавления новых респондентов
    Modal::begin([
        'options' => [
            'id' => 'information-add-new-responds',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Добавить новых респондентов возможно на этапе заполнения анкетных данных.
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




</div>



<?php

$script = "

    $(document).ready(function() {
        
        //Фон для модального окна информации о месте добавления новых респондентов
        var information_add_new_responds = $('#information-add-new-responds').find('.modal-content');
        information_add_new_responds.css('background-color', '#707F99');
        
        //Фон для модального окна информации о некорректном внесении данных в форму (Шаг 1)
        var error_update_data_interview_modal = $('#error_update_data_interview').find('.modal-content');
        error_update_data_interview_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99')
        
    
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.tab', this).each(function(){

          var height = $(this).height();
        
           $('.tablinks').css('height', height);
        
        });
        
        
        //Плавное изменение цвета ссылки этапа подтверждения
        $('.tab button').hover(function() {
            $(this).stop().animate({ backgroundColor: '#707f99'}, 300);
        },function() {
            $(this).stop().animate({ backgroundColor: '#828282' }, 300);
        });
        
    
        //Вырезаем и вставляем форму добавления вопроса (Шаг 2)
        $('.form-newQuestion-panel').append($('.form-newQuestion').first());
            
        //Показываем и скрываем форму добавления вопроса 
        //при нажатии на кнопку добавить вопрос (Шаг 2)
        $('#buttonAddQuestion').on('click', function(e){
            
            $('.form-newQuestion-panel').toggle();
            e.preventDefault();
            return false;
        });
        
        
        
        //---Переходы по модальным окнам (ШАГ 4)---Начало---
        
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
        
        //---Переходы по модальным окнам (ШАГ 4)---Конец---
    });
    
    
    //При нажатии на кнопку редактировать(Шаг 1)
    //показываем форму редактирования и скрываем вид просмотра
    $('#show_form_update_data').on('click', function(){
        $('.form-view-data-confirm').hide();
        $('.form-update-data-confirm').show();
    });
    
    //При нажатии на кнопку просмотр(Шаг 1)
    //скрываем форму редактирования и показываем вид просмотра
    $('#show_form_view_data').on('click', function(){
        $('.form-update-data-confirm').hide();
        $('.form-view-data-confirm').show();
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
                
                    location.reload();
                
//                    //Обновление данных в режиме просмотра (Шаг 1)
//                    
//                    var inputCountRespond = response.model.count_respond;
//                    var viewCountRespond = $('#count_respond-view').find('.kv-attribute');
//                    viewCountRespond.html(inputCountRespond);
//                    
//                    var inputCountPositive = response.model.count_positive;
//                    var viewCountPositive = $('#count_positive-view').find('.kv-attribute');
//                    viewCountPositive.html(inputCountPositive);
//                    
//                    var textareaNeedConsumer = response.model.need_consumer;
//                    var viewNeedConsumer = $('#need_consumer-view').find('.kv-attribute');
//                    viewNeedConsumer.html(textareaNeedConsumer);
//                    
//                    
//                    //Вызов события клика на кнопку просмотра 
//                    //для перхода в режим просмотра (Шаг 1)
//                    $('.kv-btn-view').trigger('click');
//                    
//                    
//                    //---Изменяем данные в Шаге 2---
//                    //Индикатор с данными респондентов
//                    
//                    var responds = response.responds;
//                    var sumDataExistRespond = 0; //Кол-во респондентов, у кот-х заполнены данные
//                    var sumResponds = 0; //Общее кол-во респондентов
//                    
//                    $.each(responds, function(index, value) {
//                        sumResponds++;
//                        if(value.name && value.info_respond){
//                            sumDataExistRespond++;
//                        }
//                    });
//                    
//                    if (sumDataExistRespond !== 0) {
//                    
//                        var valueInfoRespond = (sumDataExistRespond / inputCountRespond) * 100;
//                        
//                        valueInfoRespond_withoutResidue = valueInfoRespond.toFixed();
//                        valueInfoRespond_withTenPart = valueInfoRespond.toFixed(1);
//                        valueInfoRespond_withHundredPart = valueInfoRespond.toFixed(2);
//                        
//                        arr_valueInfoRespond = valueInfoRespond_withHundredPart.split('.');
//                        var hundredPart_valueInfoRespond = arr_valueInfoRespond[1];
//                        hundredPart_valueInfoRespond = hundredPart_valueInfoRespond.split('');
//                        
//                        if(hundredPart_valueInfoRespond[1] == 0){
//                            
//                            valueInfoRespond = valueInfoRespond_withTenPart;
//                            
//                            if(hundredPart_valueInfoRespond[0] == 0){
//                            
//                                valueInfoRespond = valueInfoRespond_withoutResidue;
//                            }
//                            
//                        }else {
//                            
//                            valueInfoRespond = valueInfoRespond_withHundredPart;
//                        }
//                        
//                    } else {
//                        var valueInfoRespond = 0;
//                    }
//                    
//                    $('#info-respond').attr('value', valueInfoRespond);
//                    $('#info-respond-text-indicator').html(valueInfoRespond + ' %');
//                    
//                    
//                    //Индикатор проведения интервью
//                    
//                    var descInterviews = response.descInterviews;
//                    var sumDataExistDescInterview = 0; //Кол-во проведенных интервью
//                    
//                    $.each(descInterviews, function(index, value) {
//                         if(value.updated_at){
//                            sumDataExistDescInterview++;
//                         }
//                    });
//                    
//                    if(sumDataExistDescInterview !== 0){
//                    
//                        var valueInfoDescInterview = (sumDataExistDescInterview / inputCountRespond) * 100;
//                        
//                        valueInfoDescInterview_withoutResidue = valueInfoDescInterview.toFixed();
//                        valueInfoDescInterview_withTenPart = valueInfoDescInterview.toFixed(1);
//                        valueInfoDescInterview_withHundredPart = valueInfoDescInterview.toFixed(2);
//                        
//                        arr_valueInfoDescInterview = valueInfoDescInterview_withHundredPart.split('.');
//                        var hundredPart_valueInfoDescInterview = arr_valueInfoDescInterview[1];
//                        hundredPart_valueInfoDescInterview = hundredPart_valueInfoDescInterview.split('');
//                        
//                        if(hundredPart_valueInfoDescInterview[1] == 0){
//                            
//                            valueInfoDescInterview = valueInfoDescInterview_withTenPart;
//                            
//                            if(hundredPart_valueInfoDescInterview[0] == 0){
//                            
//                                valueInfoDescInterview = valueInfoDescInterview_withoutResidue;
//                            }
//                            
//                        }else {
//                            
//                            valueInfoDescInterview = valueInfoDescInterview_withHundredPart;
//                        }
//                        
//                    } else {
//                        var valueInfoDescInterview = 0;
//                    }
//                    
//                    $('#info-interview').attr('value', valueInfoDescInterview);
//                    $('#info-interview-text-indicator').html(valueInfoDescInterview + ' %');
//                    
//                    
//                    //Индикатор представителей сегмента, 
//                    
//                    var sumDataMembersOfSegment = 0; //Кол-во предствителей сегмента
//                    
//                    $.each(descInterviews, function(index, value) {
//                         if(value.status == 1){
//                            sumDataMembersOfSegment++;
//                         }
//                    });
//                    
//                    if(sumDataMembersOfSegment !== 0){
//                    
//                        var valueStatusInterview = (sumDataMembersOfSegment / inputCountRespond) * 100;
//                        
//                        valueStatusInterview_withoutResidue = valueStatusInterview.toFixed();
//                        valueStatusInterview_withTenPart = valueStatusInterview.toFixed(1);
//                        valueStatusInterview_withHundredPart = valueStatusInterview.toFixed(2);
//                        
//                        arr_valueStatusInterview = valueStatusInterview_withHundredPart.split('.');
//                        var hundredPart_valueStatusInterview = arr_valueStatusInterview[1];
//                        hundredPart_valueStatusInterview = hundredPart_valueStatusInterview.split('');
//                        
//                        if(hundredPart_valueStatusInterview[1] == 0){
//                            
//                            valueStatusInterview = valueStatusInterview_withTenPart;
//                            
//                            if(hundredPart_valueStatusInterview[0] == 0){
//                            
//                                valueStatusInterview = valueStatusInterview_withoutResidue;
//                            }
//                            
//                        }else {
//                            
//                            valueStatusInterview = valueStatusInterview_withHundredPart;
//                        }
//                        
//                    } else {
//                        var valueStatusInterview = 0;
//                    }
//                    
//                    $('#info-status').attr('value', valueStatusInterview);
//                    $('#info-status-text-indicator').html(valueStatusInterview + ' %');
//                    
//                    if (inputCountPositive <= sumDataMembersOfSegment){
//                        if ($('#info-status').hasClass('info-red') == true){
//                            $('#info-status').removeClass('info-red').addClass('info-green');
//                        }
//                        
//                    }else {
//                        if ($('#info-status').hasClass('info-green') == true) {
//                            $('#info-status').removeClass('info-green').addClass('info-red');
//                        }
//                    }
//                    
//                    
//                    
//                    //кнопка перехода в таблицу Информация о респондентах и строка сообщения 
//                    
//                    var problems = response.gcps;
//                    var sumProblems = 0; //Кол-во ГЦП
//                    
//                    $.each(problems, function(index, value) {
//                         if(value.id){
//                            sumProblems++;
//                         }
//                    });
//                    
//                    
//                    if (sumDataExistRespond == 0) {
//                    
//                        $('#redirect_info_responds_table').html('Начать');
//                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
//                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
//                        }
//                        
//                        $('#messageAboutTheNextStep').html('Начните заполнять данные о респондентах и интервью');
//                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
//                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-success');
//                        }
//                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
//                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-success');
//                        }
//                    
//                    } 
//                    
//                    if (sumDataExistRespond == sumResponds && sumDataExistDescInterview == sumResponds && inputCountPositive <= sumDataMembersOfSegment && sumProblems == 0) {
//                        
//                        $('#redirect_info_responds_table').html('Редактировать');
//                        if ($('#redirect_info_responds_table').hasClass('btn-danger') == true) {
//                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
//                        }
//                        
//                        //Скрыть кнопку завершить
//                        $('.finish_program').hide(); 
//                        
//                        $('#messageAboutTheNextStep').html('Переходите к генерации ГЦП');
//                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
//                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-success');
//                        }
//                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
//                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-success');
//                        }
//                        
//                        //Обновление данных Шаг 3.
//                        $('.not_next_step').hide();
//                        $('.finish_program_success').show();
//                        
//                        
//                    } 
//                    
//                    if (sumProblems != 0) {
//                    
//                        $('#redirect_info_responds_table').html('Редактировать');
//                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
//                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
//                        }
//                        
//                        $('#messageAboutTheNextStep').html('');
//                    } 
//                    
//                    if (sumDataExistRespond == sumResponds && sumDataExistDescInterview == sumResponds && inputCountPositive > sumDataMembersOfSegment) {
//                        $('#redirect_info_responds_table').html('Добавить');
//                        if ($('#redirect_info_responds_table').hasClass('btn-default')) {
//                            $('#redirect_info_responds_table').removeClass('btn-default').addClass('btn-danger');
//                        }
//                        
//                        $('#messageAboutTheNextStep').html('Недостаточное количество представителей сегмента');
//                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
//                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-danger');
//                        }
//                        if ($('#messageAboutTheNextStep').hasClass('text-success')) {
//                            $('#messageAboutTheNextStep').removeClass('text-success').addClass('text-danger');
//                        }
//                        
//                        //Обновление данных Шаг 3.
//                        $('.not_next_step').show();
//                        $('.finish_program_success').hide();
//                        
//                    } 
//                    
//                    if (sumDataExistRespond == sumResponds && sumDataExistDescInterview == sumResponds && inputCountPositive > sumDataMembersOfSegment && response.problem.exist_confirm === null) {
//                        $('#redirect_info_responds_table').html('Добавить');
//                        if ($('#redirect_info_responds_table').hasClass('btn-default')) {
//                            $('#redirect_info_responds_table').removeClass('btn-default').addClass('btn-danger');
//                        }
//                        
//                        //Показать кнопку завершить
//                        $('.finish_program').show();
//                        
//                        $('#messageAboutTheNextStep').html('Недостаточное количество представителей сегмента');
//                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
//                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-danger');
//                        }
//                        if ($('#messageAboutTheNextStep').hasClass('text-success')) {
//                            $('#messageAboutTheNextStep').removeClass('text-success').addClass('text-danger');
//                        }
//                        
//                        //Обновление данных Шаг 3.
//                        $('.not_next_step').show();
//                        $('.finish_program_success').hide();
//                        
//                    } 
//                    
//                    if (sumProblems == 0 && sumDataExistRespond != 0 &&(sumDataExistRespond != sumResponds || sumDataExistDescInterview != sumResponds)) {
//                        $('#redirect_info_responds_table').html('Продолжить');
//                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
//                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
//                        }
//                        
//                        $('#messageAboutTheNextStep').html('Продолжите заполнение данных о респондентах и интервью');
//                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
//                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-warning');
//                        }
//                        if ($('#messageAboutTheNextStep').hasClass('text-success')) {
//                            $('#messageAboutTheNextStep').removeClass('text-success').addClass('text-warning');
//                        }
//                        
//                    }
//                    
//                    
//                    
//                    //Изменение данных в модальных окнах с индикаторами данных
//                    
//                    
//                    //Обновление модального окна - проверка данных респондентов
//                    
//                    var stringTemplateTableRespondsExist = $('#TableRespondsExist').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
//                    $('#TableRespondsExist').find('tbody').html(''); //Очищаем таблицу  
//                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
//                        
//                        $('#TableRespondsExist').find('tbody').append('<tr class=\"TableRespondsExist\" id=\"stringTableDataRespond-' + (index + 1) + '\">' + stringTemplateTableRespondsExist + '</tr>');
//                        $('#stringTableDataRespond-' + (index + 1)).attr('data-key', value.id);
//                        $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(1)').html(index+1);
//                        
//                        if(value.info_respond && value.date_plan && value.place_interview) {
//                            
//                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond-' + value.id).html(value.name);
//                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('Данные заполнены');
//                            
//                        }else {
//                            
//                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_for_exist').html(value.name);
//                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('Данные отсутствуют');
//                        }
//                        
//                    });
//                    
//                    
//                    //Обновление модального окна - проверка данных по интервью
//                    
//                    var stringTemplateTableByDateInterview = $('#TableByDateInterview').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
//                    $('#TableByDateInterview').find('tbody').html(''); //Очищаем таблицу
//                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
//                        
//                        $('#TableByDateInterview').find('tbody').append('<tr class=\"TableByDateInterview\" id=\"stringTableDataInterview-' + (index + 1) + '\">' + stringTemplateTableByDateInterview + '</tr>');
//                        $('#stringTableDataInterview-' + (index + 1)).attr('data-key', value.id);
//                        $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(1)').html(index+1);
//                        
//                        
//                        if(value.info_respond && value.date_plan && value.place_interview) {
//                            
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond_by_date-' + value.id).html(value.name);
//                            var date_plan_respond = new Date(value.date_plan*1000).toLocaleDateString();
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(3)').find('div').html(date_plan_respond);
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html('');
//                            
//                            for (var j = 0; j < descInterviews.length; j++) {
//                                if(value.id == descInterviews[j].respond_id){
//                                    
//                                    var updated_at_descInterview = new Date(descInterviews[j].updated_at*1000).toLocaleDateString();
//                                    $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html(updated_at_descInterview);
//                                }
//                            }
//                        }else {
//                            
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_by_date_interview').html(value.name);
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
//                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html('');
//                        }
//                    });
//                    
//                    
//                    //Обновление модального окна - таблица представителей сегмента
//                    
//                    var stringTemplateTableByStatusResponds = $('#TableByStatusResponds').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
//                    $('#TableByStatusResponds').find('tbody').html(''); //Очищаем таблицу
//                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
//                        
//                        $('#TableByStatusResponds').find('tbody').append('<tr class=\"TableByStatusResponds\" id=\"stringTableStatusRespond-' + (index + 1) + '\">' + stringTemplateTableByStatusResponds + '</tr>');
//                        $('#stringTableStatusRespond-' + (index + 1)).attr('data-key', value.id);
//                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(1)').html(index+1);
//                        
//                        if(value.info_respond && value.date_plan && value.place_interview) {
//                            
//                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond_by_status-' + value.id).html(value.name);
//                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
//                            
//                            for (var j = 0; j < descInterviews.length; j++) {
//                                if(value.id == descInterviews[j].respond_id){
//                                    
//                                    var statusRespond = '';
//                                    if(descInterviews[j].status == 0){
//                                        statusRespond = 'Нет';
//                                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').attr('style', 'color:red');
//                                    } 
//                                    if(descInterviews[j].status == 1){
//                                        statusRespond = 'Да';
//                                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').attr('style', 'color:green');
//                                    }
//                                    
//                                    $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html(statusRespond);
//                                }
//                            }
//                        }else {
//                            
//                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_for_by_status').html(value.name);
//                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
//                        }
//                        
//                    });
                    
                    

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
                var container = $('#QuestionsTable-container');
                $('.new-string-table-questions').find('.string_question').addClass('string_question-' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.title_question').html(response.model.title);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('href', '/confirm-problem/delete-question?id=' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('id', 'delete_question-' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк (Шаг 2)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('.string_question-' + response.model.id).find('.number_question').html((index+1) + '.');
                });
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestion').find('select').html('');
                $('#addNewQuestion').find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestion').find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
                });    
                
                //Скрываем и очищием форму (Шаг 2)
                $('.form-newQuestion-panel').hide();
                $('#addNewQuestion')[0].reset();
                
                //Удаляем добавленный класс из шаблона строки вопроса
                $('.new-string-table-questions').find('.string_question').removeClass('string_question-' + response.model.id);
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    

    
    
    
    //события для select2 https://select2.org/programmatic-control/events
    //Открытие и закрытие списка вопросов для добавления в анкету
    $('body').on('click', '#button_add_text_question_confirm', function(e){

        if(!$('#button_add_text_question_confirm').hasClass('openDropDownList')){
            
            $('#add_new_question_confirm').select2('open');
            $(this).addClass('openDropDownList');
            $(this).css('border-width', '0');
            $(this).find('.triangle-bottom').css('transform', 'rotate(180deg)');
            
            var position_button = $('#button_add_text_question_confirm').offset().top;
            var position_select = $('.select2-container--krajee .select2-dropdown').offset().top;
            
            if (position_button < position_select) {
                
                $('#add_text_question_confirm').css('border-bottom-width', '0');
                $('#add_text_question_confirm').css('border-radius', '12px 12px 0 0');
            } else {
            
                $('#add_text_question_confirm').css('border-top-width', '0');
                $('#add_text_question_confirm').css('border-radius', '0 0 12px 12px');
            }

        }else {
            
            $('#add_new_question_confirm').select2('close');
            $(this).removeClass('openDropDownList');
            $(this).css('border-width', '0 0 0 1px');
            $(this).find('.triangle-bottom').css('transform', 'rotate(0deg)');
            $('#add_text_question_confirm').css('border-width', '1px');
			$('#add_text_question_confirm').css('border-radius', '12px');
        }

        e.preventDefault();

        return false;
    });
    
    //Проверяем позицию кнопки и select при скролле страницы и задаем стили для поля ввода
    $(window).on('scroll', function() {
    
        var position_button = $('#button_add_text_question_confirm').offset().top;
        var position_select = $('.select2-container--krajee .select2-dropdown').offset().top;
            
        if (position_button < position_select) {
            
            $('#add_text_question_confirm').css('border-top-width', '1px');    
            $('#add_text_question_confirm').css('border-bottom-width', '0');
            $('#add_text_question_confirm').css('border-radius', '12px 12px 0 0');
        } else {
            
            $('#add_text_question_confirm').css('border-bottom-width', '1px');
            $('#add_text_question_confirm').css('border-top-width', '0');
            $('#add_text_question_confirm').css('border-radius', '0 0 12px 12px');
        }
    });
    
    // Отслеживаем клик вне поля Select
    $(document).mouseup(function (e){ // событие клика по веб-документу
    
		var search = $('.select2-container--krajee .select2-search--dropdown .select2-search__field'); // поле поиска в select
		var button = $('#button_add_text_question_confirm'); // кнопка открытия и закрытия списка select
		
		if (!search.is(e.target) && !button.is(e.target) // если клик был не полю поиска и не по кнопке
		    && search.has(e.target).length === 0 && button.has(e.target).length === 0) { // и не их по его дочерним элементам
			
			$('#add_new_question_confirm').select2('close'); // скрываем список select
			$('#button_add_text_question_confirm').removeClass('openDropDownList'); // убираем класс открытового списка у кнопки открытия и закрытия списка select
			
			$('#button_add_text_question_confirm').css('border-width', '0 0 0 1px'); // возвращаем стили кнопке
			$(this).find('.triangle-bottom').css('transform', 'rotate(0deg)'); // возвращаем стили кнопке
			
			$('#add_text_question_confirm').css('border-width', '1px'); // возвращаем стили для поля ввода
			$('#add_text_question_confirm').css('border-radius', '12px'); // возвращаем стили для поля ввода
		}
	});
    
    //Передаем выбранное значение из select в поле ввода
    $('#add_new_question_confirm').on('select2:select', function(){
        $('#add_text_question_confirm').val($(this).val());
        $(this).val('');
    });
    
      
    
    
    
    //Удаление вопроса для интервью в модальном окне.
    //Для того чтобы обрабатывались и старые и новые вопросы
    //указываем контейнер в контором необходимо обрабатывать запросы,
    //а после события указываем по какому элементу оно будет срабатывать.
    $('#QuestionsTable-container').on('click', '.delete-question-confirm-problem', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/confirm-problem/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы
        var deleteString = $('#QuestionsTable-container').find('.string_question-' + id);
        
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){

                //Скрываем удаленный вопрос
                deleteString.hide();
                
                //Изменение нумерации строк после удаления
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('.string_question-' + value['id']).find('.number_question').html((index+1) + '.');
                });
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestion').find('select').html('');
                $('#addNewQuestion').find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestion').find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
                });
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
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
