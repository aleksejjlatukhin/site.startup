<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\User;
use app\models\Segment;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmMvp */

$this->title = 'Подтверждение MVP';

$this->registerCssFile('@web/css/confirm-mvp-view-style.css');
?>
<div class="confirm-mvp-view">

    <div class="row project_info_data">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name">
            <span>Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header',
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'onclick' => 'return false',
        ]) ?>

        <?= Html::a('Дорожная карта проекта', ['#'], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapProject",
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
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

        <?= Html::a('<div class="stage_number">4</div><div>Подтверждение гипотез проблем сегментов</div>',
            ['/confirm-problem/view', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">5</div><div>Разработка гипотез ценностных предложений</div>',
            ['/gcp/index', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">6</div><div>Подтверждение гипотез ценностных предложений</div>',
            ['/confirm-gcp/view', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">7</div><div>Разработка MVP</div>',
            ['/mvp/index', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">8</div>
            <div>Подтверждение MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">

            <?php
            $segment_name = $segment->name;
            if (mb_strlen($segment_name) > 12){
                $segment_name = mb_substr($segment_name, 0, 12) . '...';
            }

            $problem_description = $generationProblem->description;
            if (mb_strlen($problem_description) > 12){
                $problem_description = mb_substr($problem_description, 0, 12) . '...';
            }

            $gcp_description = $gcp->description;
            if (mb_strlen($gcp_description) > 15){
                $gcp_description = mb_substr($gcp_description, 0, 15) . '...';
            }

            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 15){
                $mvp_description = mb_substr($mvp_description, 0, 15) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 50){
                $mvp_description = mb_substr($mvp_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

        </div>

        <?= Html::a('Данные сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 link_in_the_header',
            'data-toggle' => 'modal',
            'data-target' => '#data_segment_modal',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['#'], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 link_in_the_header text-center',
            'data-toggle' => 'modal',
            'data-target' => "#showRoadmapSegment",
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->name;?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $generationProblem->description;?></div>
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->description;?></div>
        <div>Формулировка минимально жизнеспособного продукта:</div>
        <div><?= $mvp->description;?></div>
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

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-3" style="padding: 0;">

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
                        Формулировка минимально жизнеспособного продукта, который проверяем
                    </div>

                    <div class="col-md-12" style="padding-top: 10px;">
                        <?= $mvp->description;?>
                    </div>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmMvp, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'id' => 'count_respond-view',
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmMvp, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих продукт (MVP)')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'id' => 'count_positive-view',
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
                'action' => Url::to(['/confirm-mvp/update', 'id' => $formUpdateConfirmMvp->id]),
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

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-6" style="padding: 0;">

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
                        Формулировка минимально жизнеспособного продукта, который проверяем
                    </div>

                    <div class="col-md-12" style="padding-top: 10px;">
                        <?= $mvp->description;?>
                    </div>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmMvp, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
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

                    <?= $form->field($formUpdateConfirmMvp, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих продукт (MVP)')
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
            - общее количество респондентов не может быть меньше количества респондентов, подтверждающих продукт (MVP);
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

                    <span style="color: #4F4F4F;padding-right: 10px;">Список вопросов для анкеты</span>

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
                                    Url::to(['/confirm-mvp/delete-question', 'id' => $question->id])],[
                                    'title' => Yii::t('yii', 'Delete'),
                                    'class' => 'delete-question-confirm-mvp pull-right',
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
                        'action' => Url::to(['/confirm-mvp/add-question', 'id' => $model->id]),
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
                                    Url::to(['/confirm-mvp/delete-question', 'id' => ''])],[
                                    'title' => Yii::t('yii', 'Delete'),
                                    'class' => 'delete-question-confirm-mvp pull-right',
                                    'id' => '',
                                ]); ?>
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

    </div>



    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 3)-->
    <div id="step_three" class="tabcontent row">


        <div class="modal-windows">


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
                'action' => "/responds-mvp/create?id=$model->id",
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
                                'action' => "/responds-mvp/update?id=$respond->id",
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
                                        'rows' => 2,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Кто? Откуда? Чем занимается?',
                                    ]); ?>

                                </div>


                                <div class="form-group col-md-12">
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
                                'action' => "/responds-mvp/update?id=$respond->id",
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
                                        'rows' => 2,
                                        'required' => true,
                                        'readOnly' => true,
                                        'class' => 'style_form_field_respond form-control',
                                        'placeholder' => 'Кто? Откуда? Чем занимается?',
                                    ]); ?>

                                </div>

                            </div>

                            <?php ActiveForm::end(); ?>


                        <?php endif; ?>

                    </div>

                    <?php Modal::end(); ?>



                    <?php if (empty($respond->descInterview)) : ?>

                    <?php
                    // Форма создания интервью для респондента
                    Modal::begin([
                        'options' => [
                            'id' => "create_descInterview_modal-$respond->id",
                            'class' => 'create_descInterview_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center">Внесите данные опроса</h3>',
                        'headerOptions' => ['class' => 'style_header_modal_form'],
                    ]);
                    // Контент страницы создания интервью для респондента
                    ?>


                    <div class="desc-interview-create-form">


                        <?php $form = ActiveForm::begin([
                            'action' => "/desc-interview-mvp/create?id=$respond->id",
                            'id' => "formCreateDescInterview-$respond->id",
                            'options' => ['class' => 'g-py-15'],
                            'errorCssClass' => 'u-has-error-v1',
                            'successCssClass' => 'u-has-success-v1-1',
                        ]); ?>


                        <?php
                        foreach ($respond->answers as $index => $answer) :
                            ?>

                            <?= $form->field($answer, "[$index]answer", ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label($answer->question->title)
                            ->textarea([
                                'row' => 2,
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                            ]);
                            ?>

                        <?php
                        endforeach;
                        ?>


                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                $selection_list = [ '0' => 'Не хочу приобретать данный продукт (MVP)', '1' => 'Хочу приобрести данный продукт (MVP)', ];
                                ?>

                                <?= $form->field($createDescInterviewForms[$i], 'status', [
                                    'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                                ])->label('По результатам опроса сделайте вывод о текущем продукте (MVP)')->widget(Select2::class, [
                                    'data' => $selection_list,
                                    'options' => [
                                        'id' => "descInterview_status-$respond->id",
                                    ],
                                    'disabled' => false,  //Сделать поле неактивным
                                    'hideSearch' => true, //Скрытие поиска
                                ]);
                                ?>

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
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

                    </div>

                    <?php Modal::end(); ?>

                <?php endif; ?>


                    <?php

                    // Форма редактирование информации о интервью
                    Modal::begin([
                        'options' => [
                            'id' => "interview_update_modal-$respond->id",
                            'class' => 'interview_update_modal',
                        ],
                        'size' => 'modal-lg',
                        'header' => '<h3 class="text-center">Внесите данные опроса</h3>',
                        'headerOptions' => ['class' => 'style_header_modal_form'],
                    ]);

                    // Контент страницы редактирования информации о интервью
                    ?>


                    <div class="desc-interview-update-form">

                        <?php if ($respond->descInterview) : ?>

                            <!--Если пользователь является проектантом-->
                            <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                <?php $form = ActiveForm::begin([
                                    'action' => "/desc-interview-mvp/update?id=".$respond->descInterview->id ,
                                    'id' => "formUpdateDescInterview-".$respond->id ,
                                    'options' => ['class' => 'g-py-15'],
                                    'errorCssClass' => 'u-has-error-v1',
                                    'successCssClass' => 'u-has-success-v1-1',
                                ]); ?>


                                <?php
                                foreach ($respond->answers as $index => $answer) :
                                    ?>

                                    <?= $form->field($answer, "[$index]answer", ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label($answer->question->title)
                                    ->textarea([
                                        'row' => 2,
                                        'required' => true,
                                        'class' => 'style_form_field_respond form-control',
                                    ]);
                                    ?>

                                <?php
                                endforeach;
                                ?>


                                <div class="row">
                                    <div class="col-md-12">

                                        <?php
                                        $selection_list = [ '0' => 'Не хочу приобретать данный продукт (MVP)', '1' => 'Хочу приобрести данный продукт (MVP)', ];
                                        ?>

                                        <?= $form->field($updateDescInterviewForms[$i], 'status', [
                                            'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
                                        ])->label('По результатам опроса сделайте вывод о текущем продукте (MVP)')->widget(Select2::class, [
                                            'data' => $selection_list,
                                            'options' => [
                                                'id' => "descInterview_status-$respond->id",
                                            ],
                                            'disabled' => false,  //Сделать поле неактивным
                                            'hideSearch' => true, //Скрытие поиска
                                        ]);
                                        ?>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
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

                                <!--Если пользователь не является проектантом-->
                            <?php else : ?>

                                <div class="row" style="margin-bottom: 15px; color: #4F4F4F;">

                                    <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                                        <div style="font-weight: 700;">Респондент</div>
                                        <div><?= $respond->name; ?></div>
                                    </div>

                                    <?php foreach ($respond->answers as $answer) : ?>

                                        <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                                            <div style="font-weight: 700;"><?= $answer->question->title; ?></div>
                                            <div><?= $answer->answer; ?></div>
                                        </div>

                                    <?php endforeach; ?>

                                    <div class="col-md-12" style="padding: 0 20px; margin-bottom: 15px;">
                                        <div style="font-weight: 700;">По результатам опроса сделайте вывод о текущем продукте (MVP)</div>
                                        <div>
                                            <?php
                                            if ($updateDescInterviewForms[$i]->status == 1) {
                                                echo 'Хочу приобрести данный продукт (MVP)';
                                            } else {
                                                echo 'Не хочу приобретать данный продукт (MVP)';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>

                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                    <?php Modal::end(); ?>



                    <?php
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

                            Html::a('Ок', ['/responds-mvp/delete', 'id' => $respond->id],[
                                'class' => 'btn btn-default',
                                'style' => ['width' => '120px'],
                                'id' => "confirm-delete-respond-$respond->id",
                            ]).

                            '</div>'
                    ]);

                    // Контент страницы - подтверждение удаления респондента
                    ?>

                    <h4 class="text-center">Вы уверены, что хотите удалить все данные<br>о респонденте «<?= $respond->name ?>»?</h4>

                    <?php Modal::end(); ?>



                <?php endforeach; ?>

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
                Для перехода к созданию анкеты,<br> необходимо заполнить вводные данные<br>по всем заданным респондентам.
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
                2. Затем переходите к заполнению данных опроса, при необходимости добавляйте новых респондентов.
            </h4>

            <?php
            Modal::end();
            ?>


        </div>



        <!--Список респондентов-->
        <div class="container-fluid container-data">

            <div class="row row_header_data">

                <div class="col-md-9" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Информация о респондентах и данные опроса</span>

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

                <div class="col-md-3" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        Данные респондента
                    </div>
                    <div class="headers_data_respond_low">
                        Кто? Откуда? Чем занят?
                    </div>
                </div>

                <div class="col-md-3" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        E-mail
                    </div>
                    <div class="headers_data_respond_low">
                        Адрес электронной почты
                    </div>
                </div>

                <div class="col-md-2" style="padding: 0;">
                    <div class="headers_data_respond_hi">
                        Дата опроса
                    </div>
                    <div class="headers_data_respond_low">
                        Заполнение анкетных данных
                    </div>
                </div>

                <div class="col-md-1" style="text-align: right; padding: 10px 7px 10px 0;">
                    <?= Html::a(Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]), ['/confirm-mvp/mpdf-data-responds', 'id' => $model->id], [
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
                                'title' => 'Редактировать данные респондента',
                                'data-toggle' => 'modal',
                                'data-target' => "#respond_update_modal-$respond->id",
                            ]);
                            ?>

                        </div>

                    </div>

                    <div class="col-md-3" style="font-size: 14px; padding: 0 10px 0 0;">

                        <?php
                        if (!empty($respond->info_respond)){

                            if(mb_strlen($respond->info_respond) > 80) {
                                echo '<div title="'.$respond->info_respond.'">' . mb_substr($respond->info_respond, 0, 80) . '...</div>';
                            }else {
                                echo $respond->info_respond;
                            }
                        }
                        ?>

                    </div>

                    <div class="col-md-3" style="padding: 0 10px 0 0;">

                        <?php
                        if (!empty($respond->email)){

                            if(mb_strlen($respond->email) > 40) {
                                echo '<div title="'.$respond->email.'">' . mb_substr($respond->email, 0, 40) . '...</div>';
                            }else {
                                echo $respond->email;
                            }
                        }
                        ?>

                    </div>

                    <div class="col-md-2" style="padding: 0;">

                        <?php
                        if (!empty($respond->descInterview->updated_at)){

                            $date_fact = date("d.m.y", $respond->descInterview->updated_at);
                            echo '<div>' . Html::encode($date_fact) . '</div>';

                        }elseif (!empty($respond->info_respond) && empty($respond->descInterview->updated_at) && User::isUserSimple(Yii::$app->user->identity['username'])){

                            echo '<div>' . Html::a(
                                    Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]),
                                    ['/responds-mvp/data-availability', 'id' => Yii::$app->request->get('id')],
                                    ['title' => 'Заполнить анкетные данные',
                                        'onclick'=> "$.ajax({
        
                                        url: '".Url::to(['/responds-mvp/data-availability', 'id' => Yii::$app->request->get('id')])."',
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

                    <div class="col-md-1" style="text-align: right;">
                        <?php

                        if ($respond->descInterview) {

                            echo Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]), ['#'], [
                                'class' => '',
                                'title' => 'Редактировать результаты опроса',
                                'data-toggle' => 'modal',
                                'data-target' => "#interview_update_modal-$respond->id",
                            ]);
                        }

                        echo Html::a(Html::img('/images/icons/icon_delete.png',
                            ['style' => ['width' => '24px']]), ['#'], [
                            'title' => 'Удалить респондента',
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
                    Подтверждают MVP: <?= $model->dataMembersOfMvp;?>
                </div>

                <div class="" style="padding: 0;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);?>
                    Не подтверждают MVP: <?= ($model->dataDescInterviewsOfModel - $model->dataMembersOfMvp);?>
                </div>

                <div class="" style="padding: 0;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);?>
                    Не опрошены: <?= ($model->count_respond - $model->dataDescInterviewsOfModel);?>
                </div>

                <div class="" style="padding: 0;">

                    <?php
                    if ($model->buttonMovingNextStage === true) :
                        ?>

                        <?= Html::a( 'Далее', ['/confirm-mvp/moving-next-stage', 'id' => $model->id],[
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

                        <?= Html::a( 'Далее', ['/confirm-mvp/moving-next-stage', 'id' => $model->id],[
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
    </div>



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



    <?php

    // Сообщение о том, что в подтверждении недостаточно респондентов подтвердивших продукт (MVP)
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

            Html::a('Ок', ['/confirm-mvp/not-exist-confirm', 'id' => $model->id],[
                'class' => 'btn btn-default',
                'style' => ['width' => '120px'],
                'id' => "not_exist-confirm",
            ]).

            '</div>'
    ]);

    ?>

    <h4 class="text-center">Вы не набрали достаточное количество респондентов, которые подтвердили продукт (MVP). Следующий этап будет не доступен. Завершить данное подтверждение?</h4>

    <?php Modal::end(); ?>


    <?php

    // Модальное окно - проведены не все интервью
    Modal::begin([
        'options' => [
            'id' => 'not_completed_descInterviews',
            'class' => 'not_completed_descInterviews',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Для продолжения Вам необходимо опросить всех заданных респондентов.
    </h4>

    <?php Modal::end(); ?>


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
        Общее количество респондентов не должно быть меньше необходимого количества респондентов, подтверждающих продукт (MVP).
    </h4>

    <?php Modal::end(); ?>


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

    <?php Modal::end(); ?>


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

    <?php Modal::end(); ?>




    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ MVP (ОТЗЫВЫ ЭКСПЕРТОВ)-->
    <div id="feedbacks" class="tabcontent">

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
        
        
        //Фон для модального окна информации о месте добавления новых респондентов
        var information_add_new_responds = $('#information-add-new-responds').find('.modal-content');
        information_add_new_responds.css('background-color', '#707F99');
        
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
        
        //Фон для модального окна - для завершения подтверждения необходимо опросить всех заданных респондентов
        var not_completed_descInterviews_modal = $('#not_completed_descInterviews').find('.modal-content');
        not_completed_descInterviews_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
    
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа подтверждения MVP 
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
                
                    //Назначаем перезагрузку страницы при переходе на Шаг 3
                    $('.tab').find('#defaultOpen').attr('onclick', 'location.reload()');
                
                    //Обновление данных в режиме просмотра (Шаг 1)

                    var inputCountRespond = response.model.count_respond;
                    $('#count_respond-view').attr('value', inputCountRespond);
                    
                    var inputCountPositive = response.model.count_positive;
                    $('#count_positive-view').attr('value', inputCountPositive);
                    
                    var textareaNeedConsumer = response.model.need_consumer;
                    $('#need_consumer-view').html(textareaNeedConsumer);
                    
                    //скрываем форму редактирования и показываем вид просмотра
                    $('.form-update-data-confirm').hide();
                    $('.form-view-data-confirm').show();    
                    
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
            
                //Назначаем перезагрузку страницы при переходе на Шаг 3
                $('.tab').find('#defaultOpen').attr('onclick', 'location.reload()');
                
                //Добавление строки для нового вопроса (Шаг 2)
                var container = $('#QuestionsTable-container');
                $('.new-string-table-questions').find('.string_question').addClass('string_question-' + response.model.id);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.title_question').html(response.model.title);
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('href', '/confirm-mvp/delete-question?id=' + response.model.id);
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
    $('#QuestionsTable-container').on('click', '.delete-question-confirm-mvp', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/confirm-mvp/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы
        var deleteString = $('#QuestionsTable-container').find('.string_question-' + id);
        
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){
            
                //Назначаем перезагрузку страницы при переходе на Шаг 3
                $('.tab').find('#defaultOpen').attr('onclick', 'location.reload()');

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
    
    
    //Переход к генерации Бизнес-модели по кнопке Далее
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
                
                    if (!response['not_completed_descInterviews']) {
                        
                        if (response['exist_confirm'] === 1) {
                            window.location.href = '/business-model/index?id=".$model->id."';
                        }
                        
                        if (response['exist_confirm'] === null) {
                            window.location.href = '/confirm-mvp/exist-confirm?id=".$model->id."';
                        }
                        
                        if (response['exist_confirm'] === 0) {
                            window.location.href = '/confirm-mvp/exist-confirm?id=".$model->id."';
                        }
                    
                    } else {
                        
                        //Показываем окно (проведены не все интервью)
                        $('#not_completed_descInterviews').modal('show');
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
    
    
    // Отмена завершения неудачного подтверждения проблемы
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
    
    
";
    $position = \yii\web\View::POS_READY;
    $this->registerJs($script2, $position);

endforeach;
?>