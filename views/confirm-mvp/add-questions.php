<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Segment;
use kartik\select2\Select2;

$this->title = 'Подтверждение MVP';

$this->registerCssFile('@web/css/confirm-mvp-add_questions-style.css');
?>


<div class="confirm-mvp-add_questions">

    <div class="row project_info_data">


        <div class="col-xs-12 col-md-12 col-lg-4 project_name">
            <span>Проект:</span>
            <?= $project->project_name; ?>
        </div>

        <?= Html::a('Данные проекта', ['/projects/show-all-information', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openAllInformationProject link_in_the_header',
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openReportProject link_in_the_header text-center',
        ]) ?>

        <?= Html::a('Дорожная карта проекта', ['/projects/show-roadmap', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openRoadmapProject link_in_the_header text-center',
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openResultTableProject link_in_the_header text-center',
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

            $problem_description = $problem->description;
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

        <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['/segment/show-roadmap', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->name;?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $problem->description;?></div>
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->description;?></div>
        <div>Формулировка минимально жизнеспособного продукта:</div>
        <div><?= $mvp->description;?></div>
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
                    ])->label('<div>Количество респондентов, подтвердивших ценностное предложение</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')->textInput([
                        'type' => 'number',
                        'readonly' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'id' => 'count_respond-view',
                    ]);?>

                </div>

                <div class="row">

                    <?= $form->field($formUpdateConfirmMvp, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('Необходимое количество респондентов, подтверждающих продукт (MVP)')->textInput([
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
                    ])->label('<div>Количество респондентов, подтвердивших ценностное предложение</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
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

        <?php Modal::end(); ?>


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

        <?php Modal::end(); ?>


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
                        'action' => Url::to(['/confirm-mvp/add-question', 'id' => $confirmMvp->id]),
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


            <div class="col-xs-12">

                <?= Html::a( 'Далее', ['/confirm-mvp/view', 'id' => $confirmMvp->id],[
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
        Пройдите последовательно этапы подтверждения продукта (MVP). Далее переходите к генерации бизнес-модели.
    </h4>

    <?php Modal::end(); ?>



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

    <?php Modal::end(); ?>



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

    <?php Modal::end(); ?>


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
                
                    //Обновление данных в режиме просмотра (Шаг 1)

                    var inputCountRespond = response.model.count_respond;
                    $('#count_respond-view').attr('value', inputCountRespond);
                    
                    var inputCountPositive = response.model.count_positive;
                    $('#count_positive-view').attr('value', inputCountPositive);
                    
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
    
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>
