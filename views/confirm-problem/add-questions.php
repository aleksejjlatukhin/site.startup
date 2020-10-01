<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
//use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use app\models\Segment;
use kartik\select2\Select2;

$this->title = 'Подтверждение гипотезы проблемы сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $problem->title, 'url' => ['generation-problem/view', 'id' => $problem->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/confirm-problem-add_questions-style.css');
?>


<div class="confirm-problem-add-questions">


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


    <div class="block-link-create-interview row tab">

        <button class="tablinks step_one_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_one')">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 1</div>
                <div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div>
            </div>
        </button>


        <button class="tablinks step_two_button link_create_interview col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_two')" id="defaultOpen">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 2</div>
                <div class="link_create_interview-text_right">Сформировать список вопросов</div>
            </div>
        </button>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 3</div><div class="link_create_interview-text_right">Заполнить анкетные данные респондентов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 4</div><div class="link_create_interview-text_right">Получить отзывы экспертов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

    </div>



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

                <div class="row" style="padding-top: 30px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'problem_description', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Формулировка проблемы, которую проверяем')
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

                    <?= $form->field($formUpdateConfirmProblem, 'need_consumer', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Какую потребность потребителя сегмента проверяем')->textarea([
                        'rows' => 1,
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                        'id' => 'need_consumer-view',
                    ]) ?>

                </div>

                <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов (представителей сегмента)</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')->textInput([
                        'type' => 'number',
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'id' => 'count_respond-view',
                    ]);?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmProblem, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих проблему')->textInput([
                        'type' => 'number',
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'id' => 'count_positive-view',
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

                <div class="row" style="padding-top: 30px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'problem_description', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Формулировка проблемы, которую проверяем')
                        ->textarea([
                            'rows' => 1,
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                        ])
                    ?>

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

                <div class="col-md-12 col-lg-6" style="padding: 10px 0 0 0">

                    <span style="color: #4F4F4F;padding-right: 10px;">Список вопросов для анкеты</span>

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
                        'action' => Url::to(['/confirm-problem/add-question', 'id' => $confirmProblem->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);
                    ?>

                    <div class="col-xs-12 col-sm-9 col-lg-10">

                        <?= $form->field($newQuestion, 'title', ['template' => '{input}'])
                            ->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'placeholder' => 'Добавьте новый вопрос для анкеты',
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



            <!--Форма для выбора вопроса из общего списка-->
            <div style="display: none;">
                <div class="col-md-12 form-QuestionsOfGeneralList" style="margin-top: 20px; padding: 0;">

                    <? $form = ActiveForm::begin([
                        'id' => 'addNewQuestionOfGeneralList',
                        'action' => Url::to(['/confirm-problem/add-question', 'id' => $confirmProblem->id]),
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


            <div class="col-xs-12">

                <?= Html::a( 'Далее', ['/confirm-problem/view', 'id' => $confirmProblem->id],[
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
                    'class' => 'btn btn-lg btn-success pull-right',
                ]);?>

            </div>

        </div>
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
    // Модальное окно - Запрет на следующий шаг
    Modal::begin([
        'options' => [
            'id' => 'next_step_error',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Данный этап не доступен</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Пройдите последовательно этапы подтверждения гипотезы проблемы сегмента. Далее переходите к генерации гипотез ценностных предложений.
    </h4>

    <?php
    Modal::end();
    ?>

    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-questions',
        ],
        'size' => 'modal-md',
        'header' => '<h4 style="color: #F2F2F2; padding: 0 30px;">1. Сформулируйте собственный список вопросов для анкеты или отредактируйте список «по-умолчанию».</h4>',
    ]);
    ?>

    <h4 style="color: #F2F2F2; padding: 0 30px;">
        2. Когда список будет готов переходите по ссылке «Далее».
    </h4>

    <?php
    Modal::end();
    ?>


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

</div>

<?php

$script = "

    $(document).ready(function() {
        
        //Фон для модального окна о невозможности перехода на следующий этап
        var info_next_step_error_modal = $('#next_step_error').find('.modal-content');
        info_next_step_error_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - некорректное внесение данных в форму редактирования исходных данных
        var error_update_data_interview_modal = $('#error_update_data_interview').find('.modal-content');
        error_update_data_interview_modal.css('background-color', '#707F99');
    
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации о месте добавления новых респондентов
        var information_add_new_responds = $('#information-add-new-responds').find('.modal-content');
        information_add_new_responds.css('background-color', '#707F99');
        
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.block-link-create-interview', this).each(function(){
            var height = $(this).height();
            $('.link_create_interview').css('height', height);
        });
        
        //Плавное изменение цвета ссылки этапа подтверждения
        $('.tab button').hover(function() {
            $(this).stop().animate({ backgroundColor: '#707f99'}, 300);
        },function() {
            $(this).stop().animate({ backgroundColor: '#828282' }, 300);
        });

        //Вырезаем и вставляем форму добавления вопроса в панель таблицы
        $('.form-newQuestion-panel').append($('.form-newQuestion').first());
            
        //Показываем и скрываем форму добавления вопроса 
        //при нажатии на кнопку добавить вопрос
        $('#buttonAddQuestion').on('click', function(){
            $('.form-QuestionsOfGeneralList-panel').hide();
            $('.form-newQuestion-panel').toggle();
        });
        
        //Вырезаем и вставляем форму для выбора вопроса в панель таблицы
        $('.form-QuestionsOfGeneralList-panel').append($('.form-QuestionsOfGeneralList').first());
        
        //Показываем и скрываем форму для выбора вопроса 
        //при нажатии на кнопку выбрать из списка
        $('#buttonAddQuestionToGeneralList').on('click', function(){
            $('.form-newQuestion-panel').hide();
            $('.form-QuestionsOfGeneralList-panel').toggle();
        });
        
        //Отмена перехода по ссылке кнопки добавить вопрос
        $('a.add_new_question_button').on('click', false);
    
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
                $('.new-string-table-questions').find('.string_question-' + response.model.id).find('.delete_question_link > a').attr('href', '/confirm-problem/delete-question?id=' + response.model.id);
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
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>
