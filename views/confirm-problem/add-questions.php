<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use app\models\User;

$this->title = 'Подтверждение гипотезы проблемы сегмента';
$this->registerCssFile('@web/css/confirm-problem-add_questions-style.css');
?>


<div class="confirm-problem-add-questions">


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
            <div>Разработка MVP</div>
        </div>

        <div class="no_transition_navigation_block navigation_block">
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
            if (mb_strlen($segment_name) > 15){
                $segment_name = mb_substr($segment_name, 0, 15) . '...';
            }

            $problem_description = $problem->description;
            if (mb_strlen($problem_description) > 50){
                $problem_description = mb_substr($problem_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $problem_description = $problem->description;
            if (mb_strlen($problem_description) > 100){
                $problem_description = mb_substr($problem_description, 0, 100) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

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
    </div>


    <div class="block-link-create-interview row tab">

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 1</div><div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div></div>', [
            'class' => 'tablinks link_create_interview col-xs-12 col-md-6 col-lg-3',
            'onclick' => "openCity(event, 'step_one')"
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 2</div><div class="link_create_interview-text_right">Сформировать список вопросов</div></div>', [
            'class' => 'tablinks link_create_interview col-xs-12 col-md-6 col-lg-3',
            'onclick' => "openCity(event, 'step_two')",
            'id' => "defaultOpen",
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 3</div><div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3 show_modal_next_step_error',
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 4</div><div class="link_create_interview-text_right">Получить отзывы экспертов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3 show_modal_next_step_error',
        ]); ?>

    </div>



    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 1)-->
    <div id="step_one" class="tabcontent row">


        <div class="container-fluid form-view-data-confirm">

            <div class="row row_header_data">

                <div class="col-sm-12 col-md-9" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Исходные данные подтверждения</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'class' => 'show_modal_information_add_new_responds', 'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-3" style="padding: 0;">

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

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

                    <?php endif; ?>

                </div>

            </div>


            <div class="container-fluid content-view-data-confirm">

                <div class="row">
                    <div class="col-md-12">Цель проекта</div>
                    <div class="col-md-12"><?= $project->purpose_project;?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Приветствие в начале встречи</div>
                    <div class="col-md-12"><?= $interview->greeting_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Информация о вас для респондентов</div>
                    <div class="col-md-12"><?= $interview->view_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Причина и тема (что побудило) для проведения исследования</div>
                    <div class="col-md-12"><?= $interview->reason_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Формулировка проблемы, которую проверяем</div>
                    <div class="col-md-12"><?= $problem->description;?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Действие для проверки</div>
                    <div class="col-md-12"><?= $problem->action_to_check;?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Метрика результата</div>
                    <div class="col-md-12"><?= $problem->result_metric;?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Потребность потребителя сегмента, которую проверяем</div>
                    <div class="col-md-12"><?= $model->need_consumer;?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Количество респондентов (представителей сегмента):
                        <span><?= $model->count_respond; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">Необходимое количество респондентов, подтверждающих проблему:
                        <span><?= $model->count_positive; ?></span>
                    </div>
                </div>

            </div>

        </div>

        <div class="container-fluid form-update-data-confirm">

            <?php
            $form = ActiveForm::begin([
                'id' => 'update_data_confirm',
                'action' => Url::to(['/confirm-problem/update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>

            <div class="row row_header_data">

                <div class="col-sm-12 col-md-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Исходные данные подтверждения</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'class' => 'show_modal_information_add_new_responds', 'title' => 'Посмотреть описание',
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

                <div class="content-view-data-confirm">

                    <div class="row">
                        <div class="col-md-12">Цель проекта</div>
                        <div class="col-md-12"><?= $project->purpose_project;?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Приветствие в начале встречи</div>
                        <div class="col-md-12"><?= $interview->greeting_interview; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Информация о вас для респондентов</div>
                        <div class="col-md-12"><?= $interview->view_interview; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Причина и тема (что побудило) для проведения исследования</div>
                        <div class="col-md-12"><?= $interview->reason_interview; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Формулировка проблемы, которую проверяем</div>
                        <div class="col-md-12"><?= $problem->description;?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Действие для проверки</div>
                        <div class="col-md-12"><?= $problem->action_to_check;?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Метрика результата</div>
                        <div class="col-md-12"><?= $problem->result_metric;?></div>
                    </div>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'need_consumer', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Какую потребность потребителя сегмента проверяем')
                        ->textarea([
                            'rows' => 1,
                            'maxlength' => true,
                            'placeholder' => '',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                        ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmProblem, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Количество респондентов (представителей сегмента)</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_respond',
                            'autocomplete' => 'off'
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
                            'autocomplete' => 'off'
                        ]);
                    ?>

                </div>

            </div>

            <?php
            ActiveForm::end();
            ?>

        </div>

    </div>






    <!--ПРОГРАММА ПОДТВЕРЖДЕНИЯ ГПС (ШАГ 2)-->
    <div id="step_two" class="tabcontent row">


        <div class="container-fluid container-data">

            <!--Заголовок для списка вопросов-->

            <div class="row row_header_data">

                <div class="col-xs-12 col-md-6" style="padding: 10px 0 0 0;">

                    <span style="color: #4F4F4F;padding-right: 10px;">Список вопросов для интервью</span>

                    <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                        'class' => 'show_modal_information_table_questions', 'title' => 'Посмотреть описание',
                    ]); ?>

                </div>

                <div class="col-xs-12 col-md-6" style="padding: 0;">

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить вопрос</div></div>', ['#'],
                        ['class' => 'add_new_question_button pull-right', 'id' => 'buttonAddQuestion']
                    );
                    ?>

                    <?php endif; ?>

                </div>

            </div>


            <!--Сюда помещаем форму для создания нового вопроса-->
            <div class="form-newQuestion-panel" style="display: none;"></div>

            <!--Список вопросов-->
            <div id="QuestionsTable-container" class="row" style="padding-top: 30px; padding-bottom: 30px;">

                <?php foreach ($questions as $q => $question) : ?>

                    <div class="col-xs-12 string_question string_question-<?= $question->id; ?>">

                        <div class="row style_form_field_questions">
                            <div class="col-xs-8 col-sm-9 col-md-9 col-lg-10">
                                <div style="display:flex;">
                                    <div class="number_question" style="padding-right: 15px;"><?= ($q+1) . '. '; ?></div>
                                    <div class="title_question"><?= $question->title; ?></div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2 delete_question_link">

                                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]), [
                                        Url::to(['/confirm-problem/delete-question', 'id' => $question->id])],[
                                        'title' => Yii::t('yii', 'Delete'),
                                        'class' => 'delete-question-confirm-problem pull-right',
                                        'id' => 'delete_question-'.$question->id,
                                    ]); ?>

                                    <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px', 'margin-top' => '3px', ]]), [
                                        Url::to(['/confirm-problem/get-question-update-form', 'id' => $question->id])], [
                                        'class' => 'showQuestionUpdateForm pull-right',
                                        'title' => 'Редактировать вопрос',
                                    ]); ?>

                                <?php endif; ?>

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


            <div class="col-xs-12">

                <?= Html::a( 'Далее', ['/confirm-problem/view', 'id' => $model->id],[
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
</div>


<?php
// Модальное окно - Запрет на следующий шаг
Modal::begin([
    'options' => ['id' => 'next_step_error', 'class' => 'next_step_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Данный этап не доступен</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Пройдите последовательно этапы подтверждения гипотезы проблемы сегмента. Далее переходите к разработке гипотез ценностных предложений.
</h4>

<?php Modal::end(); ?>

<?php
// Описание выполнения задачи на данной странице
Modal::begin([
    'options' => ['id' => 'information-table-questions', 'class' => 'information-table-questions'],
    'size' => 'modal-md',
    'header' => '<h4 style="color: #F2F2F2; padding: 0 30px;">1. Сформулируйте собственный список вопросов для интервью или отредактируйте список «по-умолчанию».</h4>',
]);
?>

<h4 style="color: #F2F2F2; padding: 0 30px;">
    2. Когда список будет готов переходите по ссылке «Далее».
</h4>

<?php Modal::end(); ?>


<?php
// Информация о месте добавления новых респондентов
Modal::begin([
    'options' => ['id' => 'information-add-new-responds', 'class' => 'information-table-questions'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Добавить новых респондентов возможно на этапе заполнения информации о респондентах и интервью.
</h4>

<?php Modal::end(); ?>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/confirm_problem_add_questions.js'); ?>
