<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
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

<div class="interview-view">


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


        <div class="active_navigation_block navigation_block">
            <div class="stage_number">2</div>
            <div>Подтверждение гипотез целевых сегментов</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
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
                <div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div>
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

    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 1)-->
    <div id="step_one" class="tabcontent row">


        <div class="container-fluid form-view-data-confirm">

            <div class="row row_header_data">

                <div class="col-sm-12 col-md-9" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Текст легенды проблемного интервью</span>

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

                <div class="row" style="padding-top: 30px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'greeting_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                        ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'view_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'reason_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Планируемое количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmSegment, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, соответствующих сегменту')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);?>

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
                'action' => Url::to(['/interview/update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>


            <div class="row row_header_data">

                <div class="col-sm-12 col-md-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Текст легенды проблемного интервью</span>

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


                <div class="row" style="padding-top: 30px; padding-bottom: 5px;">

                    <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'greeting_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'view_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'reason_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Планируемое количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_respond',
                            ]);
                    ?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmSegment, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, соответствующих сегменту')
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
        // Некорректное внесение данных в форму редактирования данных программы интервью
        Modal::begin([
            'options' => [
                'id' => 'error_update_data_interview',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            - общее количество респондентов не может быть меньше количества респондентов, соответствующих сегменту;
        </h4>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            - количественные данные респондентов не могут быть меньше 1.
        </h4>

        <?php
        Modal::end();
        ?>


    </div>



    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 2)-->
    <div id="step_two" class="tabcontent row">

        <div class="container-fluid container-data">

            <!--Заголовок для списка вопросов-->

            <div class="row row_header_data">

                <div class="col-md-12 col-lg-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Список вопросов для интервью</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'data-toggle' => 'modal',
                        'data-target' => "#information-table-questions",
                        'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить вопрос</div></div>', ['#'],
                    ['class' => 'add_new_question_button col-xs-12 col-md-6 col-lg-3', 'id' => 'buttonAddQuestion']
                );
                ?>

                <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Выбрать вопрос</div></div>', ['#'],
                    ['class' => 'add_new_question_button col-xs-12 col-md-6 col-lg-3', 'id' => 'buttonAddQuestionToGeneralList']
                );
                ?>

            </div>



            <!--Сюда помещаем форму для создания нового вопроса-->
            <div class="form-newQuestion-panel" style="display: none;"></div>
            <!--Сюда помещаем форму для добавления вопроса из списка-->
            <div class="form-QuestionsOfGeneralList-panel" style="display: none;"></div>


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
                                    Url::to(['/interview/delete-question', 'id' => $question->id])],[
                                    'title' => Yii::t('yii', 'Delete'),
                                    'class' => 'delete-question-confirm-segment pull-right',
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
                        'action' => Url::to(['/interview/add-question', 'id' => $model->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);?>

                    <div class="col-xs-12 col-sm-9 col-lg-10">

                        <?= $form->field($newQuestion, 'title', ['template' => '{input}'])
                            ->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'placeholder' => 'Добавьте новый вопрос для интервью',
                                'class' => 'style_form_field_respond'])
                            ->label(false);
                        ?>

                    </div>

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <?= Html::submitButton('Сохранить', [
                            'class' => 'btn btn-lg btn-success',
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
                                    Url::to(['/interview/delete-question', 'id' => ''])],[
                                    'title' => Yii::t('yii', 'Delete'),
                                    'class' => 'delete-question-confirm-segment pull-right',
                                    'id' => '',
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--Форма для выбора вопроса из общего списка-->
            <div style="display: none;">
                <div class="col-md-12 form-QuestionsOfGeneralList" style="margin-top: 20px; padding: 0;">

                    <? $form = ActiveForm::begin([
                        'id' => 'addNewQuestionOfGeneralList',
                        'action' => Url::to(['/interview/add-question', 'id' => $model->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);?>

                    <div class="col-xs-12 col-sm-9 col-lg-10">

                        <?= $form->field($newQuestion, 'title', ['template' => '{input}',])
                            ->widget(Select2::class, [
                                'data' => $queryQuestions,
                                'options' => [
                                    'id' => 'add_new_question_confirm',
                                    'placeholder' => 'Выберите вариант из списка готовых вопросов',
                                ],
                                'disabled' => false,  //Сделать поле неактивным
                                'hideSearch' => false, //Скрытие поиска
                            ]);
                        ?>

                    </div>

                    <div class="col-xs-12 col-sm-3 col-lg-2">
                        <?= Html::submitButton('Сохранить', [
                            'class' => 'btn btn-lg btn-success',
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
                Сформулируйте собственный список вопросов для интервью или отредактируйте список «по-умолчанию».
            </h4>

            <?php
            Modal::end();
            ?>

        </div>

    </div>





    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ СЕГМЕНТА (ШАГ 3)-->
    <div id="step_three" class="tabcontent row">


        <div class="">

            <?php
/*
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

            */?><!--


            --><?php
/*
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

//                'toggleDataOptions' => [
//                    'all' => [
//                        //'icon' => 'resize-full',
//                        'label' => '<span class="font-header-table" style="font-weight: 700;">Все страницы</span>',
//                        'class' => 'btn btn-default',
//                        'title' => 'Show all data'
//                    ],
//                    'page' => [
//                        //'icon' => 'resize-small',
//                        'label' => '<span class="font-header-table" style="font-weight: 700;">Одна страница</span>',
//                        'class' => 'btn btn-default',
//                        'title' => 'Show first page data'
//                    ],
//                ],

                'export' => [
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK,
                    'label' => '<span class="font-header-table" style="font-weight: 700;">Экспорт таблицы</span>',
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

            */?>


            <?php

            // Форма добавления нового респондента
            Modal::begin([
                'options' => ['id' => 'respondCreate_modal'],
                'size' => 'modal-md',
                'header' => '<h3 class="text-center">Добавить респондента</h3>',
                'headerOptions' => ['class' => 'style_header_modal_form'],
            ]);

            $form = ActiveForm::begin([
                'id' => 'new_respond_form',
                'action' => "/respond/create?id=$model->id",
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row">

                <div class="col-md-12">

                    <?= $form->field($newRespond, 'name', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => 'Иванов Иван Иванович',
                    ]) ?>

                </div>

                <div class="form-group col-md-12">

                    <?= Html::submitButton('Сохранить', [
                        'class' => 'btn btn-success pull-right',
                        'id' => 'save_respond',
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#52BE7F',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ]

                    ]) ?>

                </div>

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


                    // Форма редактирование информации о респонденте
                    Modal::begin([
                        'options' => [
                            'id' => "respond_update_modal-$respond->id",
                            'class' => 'respond_update_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center">Сведения о респонденте</h3>',
                        'headerOptions' => ['class' => 'style_header_modal_form'],
                    ]);

                    // Контент страницы редактирования информации о респонденте
                    ?>

                    <div class="respond-form">


                        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) :?>


                            <?php $form = ActiveForm::begin([
                                'action' => "/respond/update?id=$respond->id",
                                'id' => "formUpdateRespond-$respond->id",
                                'options' => ['class' => 'g-py-15'],
                                'errorCssClass' => 'u-has-error-v1',
                                'successCssClass' => 'u-has-success-v1-1',
                            ]); ?>

                            <div class="row">
                                <div class="col-md-6">

                                    <?= $form->field($updateRespondForms[$i], 'name', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'maxlength' => true,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Иванов Иван Иванович',
                                    ]) ?>

                                </div>

                                <div class="col-md-6">

                                    <?= $form->field($updateRespondForms[$i], 'email', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'type' => 'email',
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'ivanov@gmail.com',
                                    ]); ?>

                                </div>

                                <div class="col-md-12">

                                    <?= $form->field($updateRespondForms[$i], 'info_respond', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Кто? Откуда? Чем занимается?',
                                    ]); ?>

                                    <?= $form->field($updateRespondForms[$i], 'place_interview', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'maxlength' => true,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Организация, адрес',
                                    ]); ?>

                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-4">

                                    <?= '<label class="control-label" style="padding-left: 15px;">Плановая дата интервью</label>';?>
                                    <?= \kartik\date\DatePicker::widget([
                                        'type' => 2,
                                        'removeButton' => false,
                                        'name' => 'UpdateRespondForm[date_plan]',
                                        'value' => $updateRespondForms[$i]->date_plan == null ? date('d.m.yy') : date('d.m.yy', $updateRespondForms[$i]->date_plan),
                                        'readonly' => true,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd.mm.yyyy'
                                        ],
                                        'options' => [
                                            'id' => "datePlan_-$respond->id",
                                            'class' => 'style_form_field_respond form-control'
                                        ]
                                    ]);?>

                                </div>

                                <div class="form-group col-xs-12 col-sm-6 col-md-8" style="margin-top: 30px;">
                                    <?= Html::submitButton('Сохранить', [
                                        'class' => 'btn btn-success pull-right',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'background' => '#52BE7F',
                                            'width' => '140px',
                                            'height' => '40px',
                                            'font-size' => '24px',
                                            'border-radius' => '8px',
                                        ]

                                    ]) ?>
                                </div>

                            </div>

                            <?php ActiveForm::end(); ?>


                        <?php else : ?>


                            <?php $form = ActiveForm::begin([
                                'action' => "/respond/update?id=$respond->id",
                                'id' => "formUpdateRespond-$respond->id",
                                'options' => ['class' => 'g-py-15'],
                                'errorCssClass' => 'u-has-error-v1',
                                'successCssClass' => 'u-has-success-v1-1',
                            ]); ?>

                            <div class="row">
                                <div class="col-md-6">

                                    <?= $form->field($updateRespondForms[$i], 'name', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'maxlength' => true,
                                        'required' => true,
                                        'readOnly' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Иванов Иван Иванович',
                                    ]) ?>

                                </div>

                                <div class="col-md-6">

                                    <?= $form->field($updateRespondForms[$i], 'email', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'type' => 'email',
                                        'readOnly' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'ivanov@gmail.com',
                                    ]); ?>

                                </div>

                                <div class="col-md-12">

                                    <?= $form->field($updateRespondForms[$i], 'info_respond', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'readOnly' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Кто? Откуда? Чем занимается?',
                                    ]); ?>

                                    <?= $form->field($updateRespondForms[$i], 'place_interview', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                                        'maxlength' => true,
                                        'required' => true,
                                        'readOnly' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Организация, адрес',
                                    ]); ?>

                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-4">

                                    <?= '<label class="control-label" style="padding-left: 15px;">Плановая дата интервью</label>';?>
                                    <?= \kartik\date\DatePicker::widget([
                                        'type' => 2,
                                        'removeButton' => false,
                                        'disabled' => true,
                                        'name' => 'UpdateRespondForm[date_plan]',
                                        'value' => $updateRespondForms[$i]->date_plan == null ? date('d.m.yy') : date('d.m.yy', $updateRespondForms[$i]->date_plan),
                                        'readonly' => true,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd.mm.yyyy'
                                        ],
                                        'options' => [
                                            'id' => "datePlan-$respond->id",
                                            'class' => 'style_form_field_respond form-control'
                                        ],
                                    ]);?>

                                </div>

                            </div>

                            <?php ActiveForm::end(); ?>


                        <?php endif; ?>

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
                            'header' => '<h3 class="text-center">Внесите результаты интервью</h3>',
                            'headerOptions' => ['class' => 'style_header_modal_form'],
                        ]);

                        // Контент страницы создания интервью для респондента
                        ?>

                        <div class="desc-interview-create-form">

                            <?php $form = ActiveForm::begin([
                                'action' => "/desc-interview/create?id=$respond->id",
                                'id' => "formCreateDescInterview-$respond->id",
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
                                'errorCssClass' => 'u-has-error-v1',
                                'successCssClass' => 'u-has-success-v1-1',
                            ]); ?>



                            <div class="row" style="margin-bottom: 15px;">

                                <div class="col-md-12">

                                    <?= $form->field($createDescInterviewForms[$i], 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Ответы на вопросы, инсайды, ценная информация',
                                    ]); ?>

                                </div>

                                <div class="col-md-12">

                                    <p style="padding-left: 5px;"><b>Приложить файл</b> <span style="color: #BDBDBD; padding-left: 20px;">png, jpg, jpeg, pdf, txt, doc, docx, xls</span></p>

                                    <div style="display:flex; margin-top: -5px;">

                                        <?= $form->field($createDescInterviewForms[$i], 'loadFile')
                                            ->fileInput([
                                                'id' => "descInterviewCreateFile-$respond->id", 'class' => 'sr-only'
                                            ])->label('Выберите файл',[
                                                'class'=>'btn btn-default',
                                                'style' => [
                                                    'display' => 'flex',
                                                    'align-items' => 'center',
                                                    'color' => '#FFFFFF',
                                                    'justify-content' => 'center',
                                                    'background' => '#707F99',
                                                    'width' => '180px',
                                                    'height' => '40px',
                                                    'font-size' => '24px',
                                                    'border-radius' => '8px',
                                                ],
                                            ]); ?>

                                        <div class="title_file-<?= $respond->id;?>" style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

                                    </div>

                                </div>

                                <div class="col-md-12" style="margin-top: -10px;">

                                    <?= $form->field($createDescInterviewForms[$i], 'result',['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Опишите краткий вывод по интервью',
                                    ]); ?>

                                </div>

                                <div class="col-xs-12 col-md-6">


                                    <?php
                                    $selection_list = [ '0' => 'Респондент не является представителем сегмента', '1' => 'Респондент является представителем сегмента', ];
                                    ?>

                                    <?= $form->field($createDescInterviewForms[$i], 'status', [
                                        'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                                    ])->label('Этот респондент является представителем сегмента?')->widget(Select2::class, [
                                        'data' => $selection_list,
                                        'options' => [
                                            'id' => "descInterview_status-$respond->id",
                                        ],
                                        'disabled' => false,  //Сделать поле неактивным
                                        'hideSearch' => true, //Скрытие поиска
                                    ]);
                                    ?>


                                </div>

                                <div class="form-group col-xs-12 col-md-6">
                                    <?= Html::submitButton('Сохранить', [
                                        'class' => 'btn btn-success pull-right',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'background' => '#52BE7F',
                                            'width' => '140px',
                                            'height' => '40px',
                                            'font-size' => '24px',
                                            'border-radius' => '8px',
                                            'margin-top' => '28px'
                                        ]
                                    ]) ?>
                                </div>

                            </div>

                            <?php ActiveForm::end(); ?>

                        </div>

                        <?php

                        Modal::end();

                    endif; ?>

                    <?php

                    // Форма редактирование информации о интервью
                    Modal::begin([
                        'options' => [
                            'id' => "interview_update_modal-$respond->id",
                            'class' => 'interview_update_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center">Внесите результаты интервью</h3>',
                        'headerOptions' => ['class' => 'style_header_modal_form'],
                    ]);

                    // Контент страницы редактирования информации о интервью
                    ?>

                    <div class="desc-interview-update-form">

                        <?php if ($respond->descInterview) : ?>

                            <?php $form = ActiveForm::begin([
                                'action' => "/desc-interview/update?id=".$respond->descInterview->id ,
                                'id' => "formUpdateDescInterview-".$respond->id ,
                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
                                'errorCssClass' => 'u-has-error-v1',
                                'successCssClass' => 'u-has-success-v1-1',
                            ]); ?>


                            <div class="row" style="margin-bottom: 15px;">

                                <div class="col-md-12">

                                    <?//= $form->field($updateDescInterviewForms[$i], 'description')->textarea(['rows' => 2])->label('Материалы, полученные во время интервью') ?>

                                    <?= $form->field($updateDescInterviewForms[$i], 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Ответы на вопросы, инсайды, ценная информация',
                                    ]); ?>

                                </div>

                                <div class="col-md-12">

                                    <p style="padding-left: 5px;"><b>Приложить файл</b> <span style="color: #BDBDBD; padding-left: 20px;">png, jpg, jpeg, pdf, txt, doc, docx, xls</span></p>


                                    <?php if (!empty($updateDescInterviewForms[$i]->interview_file)) : ?>


                                        <div class="feed-exp">

                                            <div style="display:flex; margin-top: -5px;margin-bottom: -30px;">

                                                <?= $form->field($updateDescInterviewForms[$i], 'loadFile')
                                                    ->fileInput([
                                                        'id' => "descInterviewUpdateFile-$respond->id", 'class' => 'sr-only'
                                                    ])->label('Выберите файл',[
                                                        'class'=>'btn btn-default',
                                                        'style' => [
                                                            'display' => 'flex',
                                                            'align-items' => 'center',
                                                            'color' => '#FFFFFF',
                                                            'justify-content' => 'center',
                                                            'background' => '#707F99',
                                                            'width' => '180px',
                                                            'height' => '40px',
                                                            'font-size' => '24px',
                                                            'border-radius' => '8px',
                                                        ],
                                                    ]); ?>

                                                <div class="file_name_update_form-<?= $respond->id;?>" style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

                                            </div>

                                        </div>


                                    <div style="margin-top: -5px; margin-bottom: 30px;">

                                        <div style="display: flex; align-items: center;">


                                            <?= Html::a('Скачать файл', ['/desc-interview/download', 'id' => $updateDescInterviewForms[$i]->id], [
                                                'class' => "btn btn-default interview_file_update-$respond->id",
                                                'style' => [
                                                    'display' => 'flex',
                                                    'align-items' => 'center',
                                                    'color' => '#FFFFFF',
                                                    'justify-content' => 'center',
                                                    'background' => '#707F99',
                                                    'width' => '170px',
                                                    'height' => '40px',
                                                    'text-align' => 'left',
                                                    'font-size' => '24px',
                                                    'border-radius' => '8px',
                                                    'margin-right' => '5px',
                                                ]

                                            ]) . ' ' . Html::a('Удалить файл', ['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id], [
                                                    'onclick'=>
                                                        "$.ajax({
                                                            type:'POST',
                                                            cache: false,
                                                            url: '".Url::to(['/desc-interview/delete-file', 'id' => $updateDescInterviewForms[$i]->id])."',
                                                            success  : function(response) {
                                                                $('.interview_file_update-".$respond->id."').hide();
                                                                $('#formUpdateDescInterview-".$respond->id."').find('.link-delete').hide();
                                                                $('#formUpdateDescInterview-".$respond->id."').find('.title_name_update_form').hide();
                                                                $('#formUpdateDescInterview-".$respond->id."').find('.feed-exp').show();
                                                            }
                                                        });
                                                    return false;
                                                    
                                                    ",
                                                    'class' => "btn btn-default link-delete",
                                                    'style' => [
                                                        'display' => 'flex',
                                                        'align-items' => 'center',
                                                        'justify-content' => 'center',
                                                        'background' => '#E0E0E0',
                                                        'color' => '#FFFFFF',
                                                        'width' => '170px',
                                                        'height' => '40px',
                                                        'font-size' => '24px',
                                                        'border-radius' => '8px',
                                                    ]
                                                ]);
                                            ?>


                                        </div>

                                        <div class="title_name_update_form" style="padding-left: 5px; padding-top: 5px; margin-bottom: -10px;"><?= $updateDescInterviewForms[$i]->interview_file;?></div>

                                    </div>


                                    <?php endif;?>


                                    <?php if (empty($updateDescInterviewForms[$i]->interview_file)) : ?>

                                        <div style="display:flex; margin-top: -5px;">

                                            <?= $form->field($updateDescInterviewForms[$i], 'loadFile')
                                                ->fileInput([
                                                    'id' => "descInterviewUpdateFile-$respond->id", 'class' => 'sr-only'
                                                ])->label('Выберите файл',[
                                                    'class'=>'btn btn-default',
                                                    'style' => [
                                                        'display' => 'flex',
                                                        'align-items' => 'center',
                                                        'color' => '#FFFFFF',
                                                        'justify-content' => 'center',
                                                        'background' => '#707F99',
                                                        'width' => '180px',
                                                        'height' => '40px',
                                                        'font-size' => '24px',
                                                        'border-radius' => '8px',
                                                    ],
                                                ]); ?>

                                            <div class="file_name_update_form-<?= $respond->id;?>" style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

                                        </div>

                                    <?php endif;?>


                                </div>

                                <div class="col-md-12" style="margin-top: -10px;">

                                    <?= $form->field($updateDescInterviewForms[$i], 'result',['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                                        'rows' => 1,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Опишите краткий вывод по интервью',
                                    ]); ?>

                                </div>

                                <div class="col-xs-12 col-md-6">

                                    <?php
                                    $selection_list = [ '0' => 'Респондент не является представителем сегмента', '1' => 'Респондент является представителем сегмента', ];
                                    ?>

                                    <?= $form->field($updateDescInterviewForms[$i], 'status', [
                                        'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                                    ])->label('Этот респондент является представителем сегмента?')->widget(Select2::class, [
                                        'data' => $selection_list,
                                        'options' => [
                                            'id' => "descInterview_status_update-$respond->id",
                                        ],
                                        'disabled' => false,  //Сделать поле неактивным
                                        'hideSearch' => true, //Скрытие поиска
                                    ]);
                                    ?>

                                </div>

                                <div class="form-group col-xs-12 col-md-6">
                                    <?= Html::submitButton('Сохранить', [
                                        'class' => 'btn btn-success pull-right',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'background' => '#52BE7F',
                                            'width' => '140px',
                                            'height' => '40px',
                                            'font-size' => '24px',
                                            'border-radius' => '8px',
                                            'margin-top' => '28px'
                                        ]
                                    ]) ?>
                                </div>

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
                        'header' => '<h3 class="text-center header-update-modal">Выберите действие</h3>',
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


        <!--Список респондентов-->
        <div class="container-fluid container-data">

            <div class="row row_header_data">

                <div class="col-md-9" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Информация о респондентах и интервью</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'data-toggle' => 'modal',
                        'data-target' => "#information-table-responds",
                        'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="col-md-3" style="padding: 0;">

                    <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить респондента</div></div>', ['#'],
                        ['data-toggle' => 'modal',
                        'data-target' => '#respondCreate_modal',
                        'class' => 'link_add_respond_text pull-right']
                    );
                    ?>

                </div>

            </div>

            <!--Заголовки для списка респондентов-->
            <div class="row" style="margin: 0; padding: 10px;">

                <div class="col-md-3 headers_data_respond_hi">
                    Фамилия, имя, отчество
                </div>

                <div class="col-md-2" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        Данные респондента
                    </div>
                    <div class="headers_data_respond_low">
                        Кто? Откуда? Чем занят?
                    </div>
                </div>

                <div class="col-md-2" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        Место проведения
                    </div>
                    <div class="headers_data_respond_low">
                        Организация, адрес
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="row headers_data_respond_hi" style="text-align: center;">
                        Интервью
                    </div>
                    <div class="row headers_data_respond_low">
                        <div class="col-md-6" style="text-align: center;">
                            План
                        </div>
                        <div class="col-md-6" style="text-align: center;">
                            Факт
                        </div>
                    </div>
                </div>

                <div class="col-md-2" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        Варианты проблем
                    </div>
                    <div class="headers_data_respond_low">
                        Заключение по интервью
                    </div>
                </div>

                <div class="col-md-1" style="text-align: right; padding: 10px 7px 10px 0;">
                    <?= Html::a(Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]), ['/interview/mpdf-data-responds', 'id' => $model->id], [
                        'target'=>'_blank',
                        //'data-toggle'=>'tooltip',
                        'title'=> 'Скачать',
                    ]);?>
                </div>

            </div>

            <?php foreach ($responds as $respond): ?>

            <div class="row container-one_respond" style="margin: 3px 0; padding: 0;">

                <div class="col-md-3" style="display:flex; align-items: center;">

                    <div style="padding-right: 10px; padding-bottom: 3px;">

                        <?php
                        if ($respond->descInterview->status == 1) {
                            echo  Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]);
                        }
                        elseif ($respond->descInterview->status === null) {
                            echo  Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);
                        }
                        elseif ($respond->descInterview->status == 0) {
                            echo  Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);
                        }
                        else {
                            echo '';
                        }
                        ?>

                    </div>

                    <div class="">

                    <?=  Html::a($respond->name, ['#'], [
                            'id' => "fio-$respond->id",
                            'class' => 'container-respond_name_link',
                            'data-toggle' => 'modal',
                            'data-target' => "#respond_update_modal-$respond->id",
                        ]);
                    ?>

                    </div>

                </div>

                <div class="col-md-2" style="font-size: 14px; padding: 0;">

                    <?php
                    if (!empty($respond->info_respond)){

                        if(mb_strlen($respond->info_respond) > 50) {
                            echo '<div title="'.$respond->info_respond.'">' . mb_substr($respond->info_respond, 0, 50) . '...</div>';
                        }else {
                            echo $respond->info_respond;
                        }
                    }
                    ?>

                </div>

                <div class="col-md-2" style="font-size: 14px; padding: 0;">

                    <?php
                    if (!empty($respond->place_interview)){

                        if(mb_strlen($respond->place_interview) > 50) {
                            echo '<div title="'.$respond->place_interview.'">' . mb_substr($respond->place_interview, 0, 50) . '...</div>';
                        }else {
                            echo $respond->place_interview;
                        }
                    }
                    ?>

                </div>

                <div class="col-md-1">

                    <?php
                        if (!empty($respond->date_plan)){

                            echo '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $respond->date_plan) . '</div>';
                        }
                    ?>

                </div>

                <div class="col-md-1">

                    <?php
                    if (!empty($respond->descInterview->updated_at)){

                        $date_fact = date("d.m.y", $respond->descInterview->updated_at);
                        echo '<div class="text-center">' . Html::a(Html::encode($date_fact), Url::to(['#']), [
                                'class' => 'container-respond_data_link',
                                'data-toggle' => 'modal',
                                'data-target' => "#interview_update_modal-$respond->id",
                                'style' => ['padding' => '0 5px']
                            ]) . '</div>';

                    }elseif (!empty($respond->info_respond) && !empty($respond->place_interview) && !empty($respond->date_plan) && empty($respond->descInterview->updated_at)){

                        echo '<div class="text-center">' . Html::a(
                                Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]),
                                ['/respond/data-availability', 'id' => Yii::$app->request->get('id')],
                                ['onclick'=>
                                    "$.ajax({
        
                                        url: '".Url::to(['/respond/data-availability', 'id' => Yii::$app->request->get('id')])."',
                                        method: 'POST',
                                        cache: false,
                                        success: function(response){
                                            if (!response['error']) {
                                            
                                                //Показываем окно создания интервью
                                                $('#create_descInterview_modal-".$respond->id."').modal('show');
                                                
                                            } else {
                                                
                                                //Показываем окно о невозможности создания интервью
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
                    ?>

                </div>

                <div class="col-md-2" style="font-size: 14px; padding: 0;">

                    <?php
                    if (!empty($respond->descInterview)){

                        if(mb_strlen($respond->descInterview->result) > 50) {
                            echo '<div title="'.$respond->descInterview->result.'">' . mb_substr($respond->descInterview->result, 0, 50) . '...</div>';
                        }else {
                            echo $respond->descInterview->result;
                        }
                    }
                    ?>

                </div>

                <div class="col-md-1" style="text-align: right;">
                    <?php

                        echo Html::a(Html::img('/images/icons/icon_delete.png',
                            ['style' => ['width' => '24px']]), ['#'], [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-toggle' => 'modal',
                            'data-target' => "#delete-respond-modal-$respond->id",
                            ]);

                    ?>
                </div>

            </div>

            <?php  endforeach;?>


            <div class="col-md-12" style="color: #4F4F4F; font-size: 16px; display: flex; justify-content: space-between; padding: 10px 20px; border-radius: 12px; border: 2px solid #707F99; align-items: center; margin-top: 30px;">

                <div class="" style="padding: 0;">
                    Необходимо респондентов: <?= $model->count_positive;?>
                </div>

                <div class="" style="padding: 0;">
                    Внесено респондентов: <?= $model->dataRespondsOfModel;?>
                </div>

                <div class="" style="padding: 0;">
                    <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]);?>
                    Соответствуют сегменту: <?= $model->dataMembersOfSegment;?>
                </div>

                <div class="" style="padding: 0;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);?>
                    Не соответствуют сегменту: <?= ($model->dataDescInterviewsOfModel - $model->dataMembersOfSegment);?>
                </div>

                <div class="" style="padding: 0;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);?>
                    Не опрошены: <?= ($model->count_respond - $model->dataDescInterviewsOfModel);?>
                </div>

                <div class="" style="padding: 0;">

                    <?php
                    if ($model->buttonMovingNextStage === true) :
                    ?>

                        <?= Html::a( 'Далее', ['/interview/moving-next-stage', 'id' => $model->id],[
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#52BE7F',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                            'class' => 'btn btn-lg btn-success',
                            'id' => 'button_MovingNextStage',
                        ]);?>

                    <?php
                    else :
                    ?>

                        <?= Html::a( 'Далее', ['/interview/moving-next-stage', 'id' => $model->id],[
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#E0E0E0',
                                'color' => '#FFFFFF',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                            'class' => 'btn btn-lg btn-default',
                            'id' => 'button_MovingNextStage',
                        ]);?>

                    <?php
                    endif;
                    ?>


                </div>

            </div>


        </div>


        <?php

        // Сообщение о том, что в подтверждении недостаточно представителей сегмента
        //и необходимо выбрать (вернуться или продолжить)
        Modal::begin([
            'options' => [
                'id' => "not_exist-confirm-modal",
                'class' => 'not_exist_confirm_modal',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center">Выберите действие</h3>',
            'footer' => '<div class="text-center">'.

                Html::a('Отмена', ['#'],[
                    'class' => 'btn btn-default',
                    'style' => ['width' => '120px'],
                    'id' => "cancel-not_exist-confirm",
                ]).

                Html::a('Ок', ['/interview/not-exist-confirm', 'id' => $model->id],[
                    'class' => 'btn btn-default',
                    'style' => ['width' => '120px'],
                    'id' => "not_exist-confirm",
                ]).

                '</div>'
        ]);

        ?>

        <h4 class="text-center">Вы не набрали достаточное количество представителей сегмента. Следующий этап будет не доступен. Завершить данное подтверждение?</h4>

        <?php

        Modal::end();

        ?>


        <?php

        // Модальное окно - нельльзя удалить респондента,
        // т.к. общее кол-во респондентов не может быть меньше необходимого кол-ва респондентов соответствующих сегменту
        Modal::begin([
            'options' => [
                'id' => 'not_delete_respond_invalid_value',
                'class' => 'not_delete_respond_invalid_value',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Удаление респондента отклонено.</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Общее количество респондентов не должно быть меньше необходимого количества респондентов соответствующих сегменту.
        </h4>

        <?php

        Modal::end();

        ?>


        <?php

        // Модальное окно - нельльзя удалить респондента,
        // Запрет на удаление последнего респондента
        Modal::begin([
            'options' => [
                'id' => 'not_delete_respond_last_child',
                'class' => 'not_delete_respond_last_child',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Удаление респондента отклонено.</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Удаление последнего респондента запрещено.
        </h4>

        <?php

        Modal::end();

        ?>


        <?php

        // Модальное окно - лимит на создание новых респондентов
        Modal::begin([
            'options' => [
                'id' => 'limit_count_respond_modal',
                'class' => 'limit_count_respond_modal',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Создание нового респондента заблокировано.</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Действует ограничение. Вы не можете добавить больше существующего количества респондентов.
        </h4>

        <?php

        Modal::end();

        ?>


    </div>



    <div id="feedbacks" class="tabcontent row">

    </div>

</div>



<?php

$script = "

    $(document).ready(function() {
    
        //Установка шрифта для модальных окон
        $('.modal').css('font-family', 'RobotoCondensed-Light');
    
        //Модальное окно выбора завершения подтверждения
        var not_exist_confirm_modal_header = $('#not_exist-confirm-modal').find('.modal-header');
        not_exist_confirm_modal_header.css('background-color', '#707F99');
        not_exist_confirm_modal_header.css('color', '#ffffff');
        not_exist_confirm_modal_header.css('border-radius', '5px 5px 0 0');
        var not_exist_confirm_modal_body = $('#not_exist-confirm-modal').find('.modal-body');
        not_exist_confirm_modal_body.css('background-color', '#F2F2F2');
        not_exist_confirm_modal_body.css('color', '#4F4F4F');
        var not_exist_confirm_modal_footer = $('#not_exist-confirm-modal').find('.modal-footer');
        not_exist_confirm_modal_footer.css('background-color', '#707F99');
        not_exist_confirm_modal_footer.css('border-radius', '0 0 5px 5px');
    
    
        //Фон для модального окна информации при заголовке таблицы
        var information_modal_problem_view = $('#information-table-problem-view').find('.modal-content');
        information_modal_problem_view.css('background-color', '#707F99');
        
        // Модальное окно - нельльзя удалить респондента,
        // т.к. общее кол-во респондентов не может быть меньше необходимого кол-ва респондентов соответствующих сегменту
        var not_delete_respond_invalid_value_modal = $('#not_delete_respond_invalid_value').find('.modal-content');
        not_delete_respond_invalid_value_modal.css('background-color', '#707F99');
        
        // Модальное окно - лимит на создание новых респондентов
        var limit_count_respond_modal = $('#limit_count_respond_modal').find('.modal-content');
        limit_count_respond_modal.css('background-color', '#707F99');
        
        // Модальное окно - нельльзя удалить респондента,
        // Запрет на удаление последнего респондента
        var not_delete_respond_last_child_modal = $('#not_delete_respond_last_child').find('.modal-content');
        not_delete_respond_last_child_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - некорректное внесение исходных данных в форму редактирования
        var error_update_data_interview_modal = $('#error_update_data_interview').find('.modal-content');
        error_update_data_interview_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - респондент с таким именем уже существует 
        var respondUpdate_modal_error_modal = $('#respondUpdate_modal_error').find('.modal-content');
        respondUpdate_modal_error_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - чтобы добавить интервью, необходимо заполнить инф-ю о всех респондентах
        var descInterviewCreate_modal_error_modal = $('#descInterviewCreate_modal_error').find('.modal-content');
        descInterviewCreate_modal_error_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99');
    
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
        
        
        //Вырезаем и вставляем форму добавления вопроса в панель таблицы (Шаг 2) 
        $('.form-newQuestion-panel').append($('.form-newQuestion').first());
        
        //Показываем и скрываем форму добавления вопроса 
        //при нажатии на кнопку добавить вопрос (Шаг 2)
        $('#buttonAddQuestion').on('click', function(){
            $('.form-QuestionsOfGeneralList-panel').hide();
            $('.form-newQuestion-panel').toggle();
        });
        
        //Вырезаем и вставляем форму для выбора вопроса в панель таблицы (Шаг 2)
        $('.form-QuestionsOfGeneralList-panel').append($('.form-QuestionsOfGeneralList').first());
        
        //Показываем и скрываем форму для выбора вопроса 
        //при нажатии на кнопку выбрать из списка (Шаг 2)
        $('#buttonAddQuestionToGeneralList').on('click', function(){
            $('.form-newQuestion-panel').hide();
            $('.form-QuestionsOfGeneralList-panel').toggle();
        });

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
                var container = $('#QuestionsTable-container');
                $('.new-string-table-questions').find('.string_question').addClass('string_question-' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.title_question').html(response.model.title);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('href', '/interview/delete-question?id=' + response.model.id);
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
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
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
                var container = $('#QuestionsTable-container');
                $('.new-string-table-questions').find('.string_question').addClass('string_question-' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.title_question').html(response.model.title);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('href', '/interview/delete-question?id=' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('id', 'delete_question-' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк (Шаг 2)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('.string_question-' + response.model.id).find('.number_question').html((index+1) + '.');
                });
                
                //Скрываем форму (Шаг 2)
                $('.form-QuestionsOfGeneralList-panel').hide();
                
                //Обновляем список вопросов для добавления (Шаг 2)
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
                });
                
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
    
    
    //Удаление вопроса для интервью в модальном окне.
    //Для того чтобы обрабатывались и старые и новые вопросы
    //указываем контейнер в контором необходимо обрабатывать запросы,
    //а после события указываем по какому элементу оно будет срабатывать. (Шаг 2)
    $('#QuestionsTable-container').on('click', '.delete-question-confirm-segment', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/interview/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы (Шаг 2)
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
                
                //Обновляем список вопросов для добавления
                var queryQuestions = response.queryQuestions;
                $('#addNewQuestionOfGeneralList').find('select').html('');
                $('#addNewQuestionOfGeneralList').find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
                $.each(queryQuestions, function(index, value) {
                    $('#addNewQuestionOfGeneralList').find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
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
            
                if (!response['limit_count_respond']) {
                
                    if (!response['error']) {
                    
                    //Закрываем окно создания нового респондента
                    $('#respondCreate_modal').modal('hide');
                    
                    //Перезагружаем страницу
                    location.reload();
                    
                    } else {
                        $('#respondCreate_modal_error').modal('show');
                    }
                
                }else {
                    
                    //Закрываем окно создания нового респондента
                    $('#respondCreate_modal').modal('hide');
                    
                    //Показываем окно с информацией
                    $('#limit_count_respond_modal').modal('show');
                }
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //Переход к генерации ГПС по кнопке Далее
    $('#button_MovingNextStage').on('click', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('href');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                if (!response['error']) {
                    
                    if (response['exist_confirm'] === 1) {
                        window.location.href = '/generation-problem/index?id=".$model->id."';
                    }
                    
                    if (response['exist_confirm'] === null) {
                        window.location.href = '/interview/exist-confirm?id=".$model->id."';
                    }
                    
                    if (response['exist_confirm'] === 0) {
                        window.location.href = '/interview/exist-confirm?id=".$model->id."';
                    }
                    
                    
                } else {
                
                    //Показываем окно выбора
                    $('#not_exist-confirm-modal').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();
        
        return false;
    });
    
    
    // Отмена завершения неудачного подтверждения сегмента
    $('#cancel-not_exist-confirm').on('click',function(e) {
        
         //Закрываем окно
         $('#not_exist-confirm-modal').modal('hide');
         
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
        
        $('#delete-respond-modal-".$respond->id."').css('font-family', 'RobotoCondensed-Light');
        
        var modal_header_delete_respond = $('#delete-respond-modal-".$respond->id."').find('.modal-header');
        modal_header_delete_respond.css('background-color', '#707F99');
        modal_header_delete_respond.css('color', '#ffffff');
        modal_header_delete_respond.css('border-radius', '5px 5px 0 0');
        
        var modal_body_delete_respond = $('#delete-respond-modal-".$respond->id."').find('.modal-body');
        modal_body_delete_respond.css('background-color', '#F2F2F2');
        modal_body_delete_respond.css('color', '#4F4F4F');
        
        var modal_footer_delete_respond = $('#delete-respond-modal-".$respond->id."').find('.modal-footer');
        modal_footer_delete_respond.css('background-color', '#707F99');
        modal_footer_delete_respond.css('border-radius', '0 0 5px 5px');
        
    });

    // CONFIRM RESPOND DELETE
    $('#confirm-delete-respond-".$respond->id."').on('click',function(e) {
        
         var url = $(this).attr('href');
         $.ajax({
              url: url,
              method: 'POST',
              cache: false,
              success: function(response) {
              
                   if (!response['success']) {
                   
                       if (response['zero_value_responds']) {
                       
                           //Закрываем окно подтверждения
                           $('#delete-respond-modal-".$respond->id."').modal('hide');
                       
                           //Показываем окно с ошибкой 
                           $('#not_delete_respond_last_child').modal('show'); 
                       } 
                       
                       if (response['number_less_than_allowed']) {
                       
                           //Закрываем окно подтверждения
                           $('#delete-respond-modal-".$respond->id."').modal('hide');
                           //Показываем окно с ошибкой 
                           $('#not_delete_respond_invalid_value').modal('show');
                       } 
                   } 
                   
                   if (response['success']) {
                   
                       //Закрываем окно подтверждения
                       $('#delete-respond-modal-".$respond->id."').modal('hide');
                                    
                       //Перезагружаем страницу
                       location.reload();
                   
                   }    
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
    
    
    //Сохранении данных из формы редактирование дынных респондента
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
    
    

    //После выбора файла в форме создания интервью выводим его имя на экран
    $('#formCreateDescInterview-".$respond->id."').on('change', 'input[type=file]',function(){

        var filename = $(this).val().split('\\\\').pop();
        $('.title_file-".$respond->id."').html(filename)
    });
    
    //После выбора файла в форме редактирования интервью выводим его имя на экран
    $('#formUpdateDescInterview-".$respond->id."').on('change', 'input[type=file]',function(){

        var filename = $(this).val().split('\\\\').pop();
        $('.file_name_update_form-".$respond->id."').html(filename)
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
    $('#formUpdateDescInterview-".$respond->id."').on('beforeSubmit', function(e){
    
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
