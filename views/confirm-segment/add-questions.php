<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use app\models\User;
use app\models\QuestionStatus;

$this->title = 'Подтверждение гипотезы целевого сегмента';
$this->registerCssFile('@web/css/interview-add_questions-style.css');
?>

<div class="interview-add-questions">


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
            ['/segments/index', 'id' => $project->id],
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
            <span>Сегмент:</span>
            <?= $segment->name; ?>
        </div>

        <?= Html::a('Данные сегмента', ['/segments/show-all-information', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Дорожная карта сегмента', ['/segments/show-roadmap', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

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



    <!--ШАГ 1-->
    <div id="step_one" class="tabcontent row">


        <div class="container-fluid form-view-data-confirm">

            <div class="row row_header_data">

                <div class="col-sm-12 col-md-9" style="padding: 5px 0 0 0;">
                    <?= Html::a('Исходные данные подтверждения' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-segment/get-instruction-step-one'],[
                        'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                    ]); ?>
                </div>

                <div class="block-buttons-update-data-confirm col-sm-12 col-md-3" style="padding: 0;">

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $segment->exist_confirm === null) : ?>

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
                    <div class="col-md-12"><?= $model->greeting_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Информация о вас для респондентов</div>
                    <div class="col-md-12"><?= $model->view_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Причина и тема (что побудило) для проведения исследования</div>
                    <div class="col-md-12"><?= $model->reason_interview; ?></div>
                </div>

                <div class="row">
                    <div class="col-md-12">Планируемое количество респондентов:
                        <span><?= $model->count_respond; ?></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">Необходимое количество респондентов, соответствующих сегменту:
                        <span><?= $model->count_positive; ?></span>
                    </div>
                </div>

            </div>

        </div>

        <div class="container-fluid form-update-data-confirm">

            <?php
            $form = ActiveForm::begin([
                'id' => 'update_data_interview',
                'action' => Url::to(['/confirm-segment/update', 'id' => $model->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>


            <div class="row row_header_data">

                <div class="col-sm-12 col-md-6" style="padding: 5px 0 0 0;">
                    <?= Html::a('Исходные данные подтверждения' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-segment/get-instruction-step-one'],[
                        'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
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


                <div class="row" style="padding-top: 20px; padding-bottom: 5px; padding-left: 5px;">

                    <div class="col-md-12" style="font-weight: 700;">
                        Цель проекта
                    </div>

                    <div class="col-md-12">
                        <?= $project->purpose_project;?>
                    </div>

                </div>


                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'greeting_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'view_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

                    <?= $form->field($formUpdateConfirmSegment, 'reason_interview', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'placeholder' => $placeholder,
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]);
                    ?>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <?= $form->field($formUpdateConfirmSegment, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div>Планируемое количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_respond',
                            'autocomplete' => 'off'
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



    <!--ШАГ 2-->
    <div id="step_two" class="tabcontent row">

        <div class="container-fluid container-data">

            <!--Заголовок для списка вопросов-->

            <div class="row row_header_data">

                <div class="col-xs-12 col-md-6" style="padding: 5px 0 0 0;">
                    <?= Html::a('Список вопросов для интервью' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-segment/get-instruction-step-two'],[
                        'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                    ]); ?>
                </div>

                <div class="col-xs-12 col-md-6" style="padding: 0;">
                    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $segment->exist_confirm === null) : ?>
                        <?=  Html::a( '<div style="display:flex; align-items: center; padding: 5px 0;"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить вопрос</div></div>', ['#'],
                            ['class' => 'add_new_question_button pull-right', 'id' => 'buttonAddQuestion']
                        ); ?>
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

                                <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $segment->exist_confirm === null) : ?>

                                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),
                                        Url::to(['/questions/delete', 'stage' => $model->stage, 'id' => $question->id]),[
                                        'title' => Yii::t('yii', 'Delete'),
                                        'class' => 'delete-question-confirm-hypothesis pull-right',
                                        'id' => 'delete_question-'.$question->id,
                                    ]); ?>

                                    <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-top' => '3px', ]]),
                                        Url::to(['/questions/get-form-update', 'stage' => $model->stage, 'id' => $question->id]), [
                                        'class' => 'showQuestionUpdateForm pull-right',
                                        'style' => ['margin-right' => '20px'],
                                        'title' => 'Редактировать вопрос',
                                    ]); ?>

                                    <?php if ($question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                                        <?= Html::a('<div class="star"></div>', Url::to(['/questions/change-status', 'stage' => $model->stage, 'id' => $question->id]), [
                                            'class' => 'star-link', 'title' => 'Значимость вопроса'
                                        ]); ?>
                                    <?php elseif ($question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                                        <?= Html::a('<div class="star active"></div>', Url::to(['/questions/change-status', 'stage' => $model->stage, 'id' => $question->id]), [
                                            'class' => 'star-link', 'title' => 'Значимость вопроса'
                                        ]); ?>
                                    <?php endif; ?>

                                <?php else : ?>

                                    <?php if ($question->status === QuestionStatus::STATUS_NOT_STAR) : ?>
                                        <div class="star-passive" title="Значимость вопроса">
                                            <div class="star"></div>
                                        </div>
                                    <?php elseif ($question->status === QuestionStatus::STATUS_ONE_STAR) : ?>
                                        <div class="star-passive" title="Значимость вопроса">
                                            <div class="star active"></div>
                                        </div>
                                    <?php endif; ?>

                                <? endif; ?>

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
                        'action' => Url::to(['/questions/create', 'stage' => $model->stage, 'id' => $model->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);?>

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

                <?= Html::a( 'Далее', ['/confirm-segment/view', 'id' => $model->id],[
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
    Пройдите последовательно этапы подтверждения гипотезы целевого сегмента. Далее переходите к генерации гипотез проблем сегмента.
</h4>

<?php
Modal::end();
?>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/confirm_segment_add_questions.js'); ?>
