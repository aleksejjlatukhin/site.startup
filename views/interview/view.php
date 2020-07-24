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

$this->title = 'Программа генерации ГПС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="interview-view table-project-kartik">


    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-problem-view',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Пройдите три шага генерации гипотез проблем сегмента. Далее переходите к их подтверждению.
    </h4>

    <?php
    Modal::end();
    ?>


    <div class="row d-inline p-2" style="background: #707F99; font-size: 26px; font-weight: 700; color: #F2F2F2; border-radius: 5px 5px 0 0; padding: 0; margin: 0; padding-top: 20px; padding-bottom: 10px;/*height: 80px;*//*padding-top: 12px;padding-left: 20px;margin-top: 10px;*/">

        <div class="col-md-12 col-lg-6" style="padding: 0 20px; text-align: center;">

            <?php
                echo 'Программа генерации ГПС' .

                Html::a('i', ['#'], [
                    'style' => ['margin-left' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-problem-view",
                    'title' => 'Посмотреть описание',
                ])
            ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Данные сегмента', ['#'], [
                'class' => 'btn btn-sm btn-default',
                'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px'],
                'data-toggle' => 'modal',
                'data-target' => '#data_segment_modal',
            ]); ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px']]) ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700', 'color' => '#373737', 'width' => '170px']]) ?>
        </div>

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


    <?= \yii\widgets\DetailView::widget([
        'model' => $segment,
        'attributes' => [

            'name',
            'description:ntext',

            [
                'attribute' => 'type_of_interaction_between_subjects',
                'label' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
                'value' => function ($segment) {
                    if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C){
                        return 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)';
                    }
                    elseif ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B){
                        return 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)';
                    }
                    else{
                        return '';
                    }
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'field_of_activity_b2c',
                'label' => 'Сфера деятельности потребителя',
                'value' => function ($segment) {
                    return $segment->field_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'field_of_activity_b2b',
                'label' => 'Сфера деятельности предприятия',
                'value' => function ($segment) {
                    return $segment->field_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'sort_of_activity_b2c',
                'label' => 'Вид деятельности потребителя',
                'value' => function ($segment) {
                    return $segment->sort_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'sort_of_activity_b2b',
                'label' => 'Вид деятельности предприятия',
                'value' => function ($segment) {
                    return $segment->sort_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'specialization_of_activity_b2c',
                'label' => 'Специализация вида деятельности потребителя',
                'value' => function ($segment) {
                    return $segment->specialization_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'specialization_of_activity_b2b',
                'label' => 'Специализация вида деятельности предприятия',
                'value' => function ($segment) {
                    return $segment->specialization_of_activity;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'company_products',
                'label' => 'Продукция / услуги предприятия',
                'value' => function ($segment) {
                    return $segment->company_products;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'company_partner',
                'label' => 'Партнеры предприятия',
                'value' => function ($segment) {
                    return $segment->company_partner;
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'age',
                'label' => 'Возраст потребителя',
                'value' => function ($segment) {
                    if ($segment->age_from !== null && $segment->age_to !== null){
                        return 'от ' . number_format($segment->age_from, 0, '', ' ') . ' до '
                            . number_format($segment->age_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'gender_consumer',
                'label' => 'Пол потребителя',
                'value' => function ($segment) {
                    if ($segment->gender_consumer == Segment::GENDER_WOMAN) {
                        return 'Женский';
                    }else {
                        return 'Мужской';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'education_of_consumer',
                'label' => 'Образование потребителя',
                'value' => function ($segment) {
                    if ($segment->education_of_consumer == Segment::SECONDARY_EDUCATION) {
                        return 'Среднее образование';
                    }elseif ($segment->education_of_consumer == Segment::SECONDARY_SPECIAL_EDUCATION) {
                        return 'Среднее образование (специальное)';
                    }elseif ($segment->education_of_consumer == Segment::HIGHER_INCOMPLETE_EDUCATION) {
                        return 'Высшее образование (незаконченное)';
                    }elseif ($segment->education_of_consumer == Segment::HIGHER_EDUCATION) {
                        return 'Высшее образование';
                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'income_b2c',
                'label' => 'Доход потребителя (тыс. руб./мес.)',
                'value' => function ($segment) {
                    if ($segment->income_from !== null && $segment->income_to !== null){
                        return 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
                            . number_format($segment->income_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'income_b2b',
                'label' => 'Доход предприятия (млн. руб./год)',
                'value' => function ($segment) {
                    if ($segment->income_from !== null && $segment->income_to !== null){
                        return 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
                            . number_format($segment->income_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],


            [
                'attribute' => 'quantity_b2c',
                'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                'value' => function ($segment) {
                    if ($segment->quantity_from !== null && $segment->quantity_to !== null){
                        return 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
                            . number_format($segment->quantity_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'quantity_b2b',
                'label' => 'Потенциальное количество представителей сегмента (ед.)',
                'value' => function ($segment) {
                    if ($segment->quantity_from !== null && $segment->quantity_to !== null){
                        return 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
                            . number_format($segment->quantity_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],


            [
                'attribute' => 'market_volume',
                'label' => 'Объем рынка (млн. руб./год)',
                'value' => function ($segment) {
                    if ($segment->market_volume !== null){
                        return number_format($segment->market_volume, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],


            [
                'attribute' => 'add_info',
                'visible' => !empty($segment->add_info),
            ],
        ],
    ]) ?>


    <?php
    Modal::end();
    ?>


    <!-- Tab links -->
    <div class="tab">
        <?php if ($model->nextStep === false) : ?>
            <button class="tablinks step_one_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_one')">Шаг 1. Заполнить исходные данные для проведения интервью</button>
            <button class="tablinks step_two_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_two')" id="defaultOpen">Шаг 2. Заполнить информацию о респондентах и интервью</button>
            <button class="tablinks step_three_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_three')">Шаг 3. Сгенерировать гипотезы проблем сегмента</button>
            <button class="tablinks feedbacks_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'feedbacks')">Отзывы экспертов</button>
        <?php else : ?>
            <button class="tablinks step_one_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_one')">Шаг 1. Заполнить исходные данные для проведения интервью</button>
            <button class="tablinks step_two_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_two')">Шаг 2. Заполнить информацию о респондентах и интервью</button>
            <button class="tablinks step_three_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'step_three')" id="defaultOpen">Шаг 3. Сгенерировать гипотезы проблем сегмента</button>
            <button class="tablinks feedbacks_button col-xs-12 col-md-6 col-lg-3" onclick="openCity(event, 'feedbacks')">Отзывы экспертов</button>
        <?php endif; ?>

    </div>

    <!-- Tab content -->

    <!--ПРОГРАММА ГЕНЕРАЦИИ ГПС (ШАГ 1)-->
    <div id="step_one" class="tabcontent">

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
                'updateOptions' => ['label' => 'Редактировать <span class="glyphicon glyphicon-pencil"></span>', 'title' => '', 'class' => 'btn btn-sm btn-default', 'style' => ['font-weight' => '700']],
                'viewOptions' => ['label' => 'Просмотр', 'title' => '', 'class' => 'btn btn-sm btn-default' , 'style' => ['margin-right' => '10px', 'font-weight' => '700']],
                'saveOptions' => ['label' => 'Сохранить', 'title' => '', 'class' => 'btn btn-sm btn-success', 'style' => ['font-weight' => '700']],
                'panel' => [
                    'heading' => '',
                    'type' => DetailView::TYPE_DEFAULT,
                    'before' => false,
                ],
                'formOptions' => [
                    'id' => 'update_data_interview',
                    'action' => Url::to(['/interview/update-data-interview', 'id' => $model->id]),
                ],
                'attributes' => [

                    [
                        //Заголовок для группы
                        'attribute' => 'count_respond',
                        'label' => 'Количественные данные респондентов, которые участвуют в интервью',
                        'group' => true,
                        'groupOptions' => ['class' => 'text-left bg-info', 'style' => ['padding' => '10px']],
                    ],

                    [
                        'attribute' => 'count_data_interview',
                        'columns' => [
                            [
                                'attribute' => 'count_respond',
                                'label' => 'Количество респондентов:',
                                'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                                'valueColOptions' => ['class' => 'text-center', 'id' => 'count_respond-view'],
                                'type' => DetailView::INPUT_HTML5 ,
                            ],

                            [
                                'attribute' => 'count_positive',
                                'label' => 'Количество респондентов, соответствующих сегменту:',
                                'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                                'valueColOptions' => ['class' => 'text-center', 'id' => 'count_positive-view'],
                                'type' => DetailView::INPUT_HTML5 ,
                            ],
                        ],
                    ],

                    [
                        //Заголовок для группы
                        'attribute' => 'greeting_interview',
                        //'label' => 'Предварительные детали встречи с интервьюером',
                        'label' => 'Текст легенды проблемного интервью',
                        'group' => true,
                        'groupOptions' => ['class' => 'text-left bg-info', 'style' => ['padding' => '10px']],
                    ],

                    [
                        'attribute' => 'greeting_interview',
                        'label' => 'Приветствие в начале встречи:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'greeting_interview-view', 'style' => ['padding' => '10px']],
                        'type' => DetailView::INPUT_TEXTAREA,
                    ],

                    [
                        'attribute' => 'view_interview',
                        'label' => 'Представление интервьюера:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'view_interview-view', 'style' => ['padding' => '10px']],
                        'type' => DetailView::INPUT_TEXTAREA,
                    ],

                    [
                        'attribute' => 'reason_interview',
                        'label' => 'Почему мне интересно:',
                        'labelColOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'width' => '40%']],
                        'valueColOptions' => ['id' => 'reason_interview-view', 'style' => ['padding' => '10px']],
                        'type' => DetailView::INPUT_TEXTAREA,
                    ],

                    [
                        //Заголовок для группы
                        'attribute' => 'reason_interview',
                        'label' => '<p><u>Примерный список вопросов для интервью</u> '
                            .Html::a('Редактировать <span class="glyphicon glyphicon-pencil"></span>', ['#'], [
                                'class' => 'btn btn-sm btn-default pull-right',
                                'style' => ['font-weight' => '700', 'margin-right' => '5px'],
                                'title' => '',
                                'data-toggle' => 'modal',
                                'data-target' => '#update_questions_interview',
                            ]) . '</p>'
                            . '<div class="list-questions">'.$model->showListQuestions.'</div>',
                        'group' => true,
                        'groupOptions' => ['class' => 'text-left', 'style' => ['padding' => '10px', 'background-color' => 'rgb(245,246,246)']],
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
            'header' => '<h3 class="text-center">Внимание!</h3>',
        ]);
        ?>

        <h4 class="text-danger text-center">
            Количество респондентов не должно быть меньше количества респондентов, соответствующих сенгменту.
        </h4>

        <?php
        Modal::end();
        ?>


        <?php
        // Модальное окно с формой редактирования вопросов для интервью
        Modal::begin([
            'options' => [
                'id' => 'update_questions_interview',
            ],
            'size' => 'modal-lg',
            //'header' => '<h3 class="text-center">Внимание!</h3>',
        ]);
        ?>

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
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'header' => '',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,[
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
                'before' => '<div class="row" style="margin: 0; font-size: 24px; padding: 10px;"><div class="col-md-12 col-lg-8 text-center" style="margin-bottom: 5px; font-weight: 700; color: #F2F2F2;">Список вопросов для интервью</div>'

                    .   Html::button( 'Добавить вопрос', [
                        'style' => [
                            'font-weight' => '700',
                            'border' => 'solid 5px #707F99',
                            'border-radius' => '8px',
                        ],
                        'class' => 'btn btn-sm btn-default col-xs-12 col-sm-6 col-lg-2',
                        'id' => 'buttonAddQuestion',
                        ])

                    .   Html::button( 'Выбрать из списка', [
                        'style' => [
                            'font-weight' => '700',
                            'border' => 'solid 5px #707F99',
                            'border-radius' => '8px',
                        ],
                        'class' => 'btn btn-sm btn-default col-xs-12 col-sm-6 col-lg-2',
                        'id' => 'buttonAddQuestionToGeneralList',
                        ])

                    .   '</div><div class="row form-newQuestion-panel kv-hide"></div>
                        <div class="row form-QuestionsOfGeneralList-panel kv-hide" style="display: none;"></div>',

                'beforeOptions' => ['class' => 'style-head-table-kartik-top'],
                //'after' => Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']) . '{export}',
                //'footer' => '{export}',
                'after' => false,
                //'footer' => false,
            ],
        ]);

        ?>

        <!--Форма для добаления нового вопроса-->
        <div class="row" style="display: none;">
            <div class="col-md-12 form-newQuestion" style="margin-top: 5px;">

                <? $form = ActiveForm::begin(['id' => 'addNewQuestion', 'action' => Url::to(['/interview/add-question', 'id' => $model->id])]);?>

                <div class="col-md-10">
                    <?= $form->field($newQuestion, 'title', ['template' => '{input}'])->textInput(['maxlength' => true, 'required' => true])->label(false); ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success col-xs-12', 'style' => ['font-weight' => '700', 'margin-bottom' => '15px']]); ?>
                </div>

                <? ActiveForm::end(); ?>

            </div>
        </div>

        <?php
        Modal::end();
        ?>

        <!--Строка нового вопроса-->
        <table style="display:none;">
            <tbody class="new-string-table-questions">
                <tr class="QuestionsTable" data-key="">
                    <td class="kv-align-center kv-align-middle QuestionsTable" style="width: 50px;" data-col-seq="0"></td>
                    <td class="QuestionsTable" data-col-seq="1"></td>
                    <td class="skip-export kv-align-center kv-align-middle QuestionsTable" style="width: 50px;" data-col-seq="2">
                        <a id="" class="delete-question-interview" href="" title="Удалить">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>


        <!--Форма для выбора вопроса из общксписка для  добавления в интервью-->
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
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success col-xs-12', 'style' => ['font-weight' => '700', 'margin-bottom' => '15px']]); ?>
                </div>

                <? ActiveForm::end(); ?>

            </div>
        </div>


    </div>


    <!--ПРОГРАММА ГЕНЕРАЦИИ ГПС (ШАГ 2)-->
    <div id="step_two" class="tabcontent">

        <div class="row step_two">

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

    <!--ПРОГРАММА ГЕНЕРАЦИИ ГПС (ШАГ 3)-->
    <div id="step_three" class="tabcontent">


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

                    if ($model->date_confirm) {

                        return '<div class="text-center" style="padding: 0 5px;">'. date("d.m.y", strtotime($model->date_confirm)) .'</div>';
                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],

        ]

        ?>


        <?php

        echo GridView::widget([
            'dataProvider' => $dataProviderProblems,
            'showPageSummary' => false, //whether to display the page summary row for the grid view.
            'pjax' => false,
            'hashExportConfig' => false,
            'striped' => false,
            'bordered' => true,
            'panel' => [
                'type' => 'default',
                'heading' => false,
                'before' => false,
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
                        ['content' =>  Html::a(Html::img('@web/images/icons/icon-plus.png', ['style' => ['width' => '30px', 'margin-right' => '10px']]), Url::to(['/interview/data-availability-for-next-step', 'id' => $model->id]), ['id' => 'checking_the_possibility']) . 'Гипотеза проблемы сегмента', 'options' => ['colspan' => 3, 'class' => 'font-segment-header-table text-center', 'style' => ['padding-top' => '10px', 'padding-bottom' => '10px']]],
                        ['content' => 'Проблема сегмента', 'options' => ['colspan' => 2, 'class' => 'font-header-table', 'style' => ['padding-top' => '15px', 'padding-bottom' => '15px', 'text-align' => 'center']]],
                    ],
                    'options' => [
                        'class' => 'style-header-table-kartik',
                    ]
                ]
            ],
        ]);

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

            <?php $form = ActiveForm::begin(['id' => 'gpsCreateForm', 'action' => Url::to(['/generation-problem/create', 'id' => $model->id])]); ?>

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
            Вернитесь на «Шаг 2» и следуйте инструкциям.
        </h4>

        <?php
        Modal::end();
        ?>



        <?php
        foreach ($model->responds as $respond) :
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



    <div id="feedbacks" class="tabcontent">

    </div>

    <div style="font-style: italic"><span class="bolder">Программа генерации ГПС</span> - программа генерации гипотез проблем сегмента.</div>

</div>



<?php

$script = "

    $(document).ready(function() {
    
        //Фон для модального окна информации при заголовке таблицы
        var information_modal_problem_view = $('#information-table-problem-view').find('.modal-content');
        information_modal_problem_view.css('background-color', '#707F99');
        
        //Фон для модального окна информации при отказе в добавлении ГПС
        var info_problem_create_modal_error = $('#problem_create_modal_error').find('.modal-content');
        info_problem_create_modal_error.css('background-color', '#707F99');
    
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.tab', this).each(function(){

          var height = $(this).height();
        
           $('.tablinks').css('height', height);
        
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
        
        
        
        //Фон для модального окна информации при создании ГПС (Шаг 3)
        var information_modal = $('#information-table-create-problem').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        
        //Убираем отступ снизу таблицы (Шаг 3)
        $('#step_three').find('.panel').css('margin-bottom', '0');
        
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
                    var viewCountRespond = $('#count_respond-view').find('.kv-attribute');
                    viewCountRespond.html(inputCountRespond);
                    
                    var inputCountPositive = response.model.count_positive;
                    var viewCountPositive = $('#count_positive-view').find('.kv-attribute');
                    viewCountPositive.html(inputCountPositive);
                    
                    var textareaGreetingInterview = response.model.greeting_interview;
                    var viewGreetingInterview = $('#greeting_interview-view').find('.kv-attribute');
                    viewGreetingInterview.html(textareaGreetingInterview);
                    
                    var textareaViewInterview = response.model.view_interview;
                    var viewViewInterview = $('#view_interview-view').find('.kv-attribute');
                    viewViewInterview.html(textareaViewInterview);
                    
                    var textareaReasonInterview = response.model.reason_interview;
                    var viewReasonInterview = $('#reason_interview-view').find('.kv-attribute');
                    viewReasonInterview.html(textareaReasonInterview);
                    
                    
                    //Вызов события клика на кнопку просмотра 
                    //для перхода в режим просмотра (Шаг 1)
                    $('.kv-btn-view').trigger('click');
                    
                    
                    //---Изменяем данные в Шаге 2---
                    //Индикатор с данными респондентов
                    
                    var responds = response.responds;
                    var sumDataExistRespond = 0; //Кол-во респондентов, у кот-х заполнены данные
                    var sumResponds = 0; //Общее кол-во респондентов
                    
                    $.each(responds, function(index, value) {
                        sumResponds++;
                        if(value.name && value.info_respond && value.date_plan && value.place_interview){
                            sumDataExistRespond++;
                        }
                    });
                    
                    if (sumDataExistRespond !== 0) {
                    
                        var valueInfoRespond = (sumDataExistRespond / inputCountRespond) * 100;
                        
                        valueInfoRespond_withoutResidue = valueInfoRespond.toFixed();
                        valueInfoRespond_withTenPart = valueInfoRespond.toFixed(1);
                        valueInfoRespond_withHundredPart = valueInfoRespond.toFixed(2);
                        
                        arr_valueInfoRespond = valueInfoRespond_withHundredPart.split('.');
                        var hundredPart_valueInfoRespond = arr_valueInfoRespond[1];
                        hundredPart_valueInfoRespond = hundredPart_valueInfoRespond.split('');
                        
                        if(hundredPart_valueInfoRespond[1] == 0){
                            
                            valueInfoRespond = valueInfoRespond_withTenPart;
                            
                            if(hundredPart_valueInfoRespond[0] == 0){
                            
                                valueInfoRespond = valueInfoRespond_withoutResidue;
                            }
                            
                        }else {
                            
                            valueInfoRespond = valueInfoRespond_withHundredPart;
                        }
                        
                    } else {
                        var valueInfoRespond = 0;
                    }
                    
                    $('#info-respond').attr('value', valueInfoRespond);
                    $('#info-respond-text-indicator').html(valueInfoRespond + ' %');
                    
                    
                    //Индикатор проведения интервью
                    
                    var descInterviews = response.descInterviews;
                    var sumDataExistDescInterview = 0; //Кол-во проведенных интервью
                    
                    $.each(descInterviews, function(index, value) {
                         if(value.description){
                            sumDataExistDescInterview++;
                         }
                    });
                    
                    if(sumDataExistDescInterview !== 0){
                    
                        var valueInfoDescInterview = (sumDataExistDescInterview / inputCountRespond) * 100;
                        
                        valueInfoDescInterview_withoutResidue = valueInfoDescInterview.toFixed();
                        valueInfoDescInterview_withTenPart = valueInfoDescInterview.toFixed(1);
                        valueInfoDescInterview_withHundredPart = valueInfoDescInterview.toFixed(2);
                        
                        arr_valueInfoDescInterview = valueInfoDescInterview_withHundredPart.split('.');
                        var hundredPart_valueInfoDescInterview = arr_valueInfoDescInterview[1];
                        hundredPart_valueInfoDescInterview = hundredPart_valueInfoDescInterview.split('');
                        
                        if(hundredPart_valueInfoDescInterview[1] == 0){
                            
                            valueInfoDescInterview = valueInfoDescInterview_withTenPart;
                            
                            if(hundredPart_valueInfoDescInterview[0] == 0){
                            
                                valueInfoDescInterview = valueInfoDescInterview_withoutResidue;
                            }
                            
                        }else {
                            
                            valueInfoDescInterview = valueInfoDescInterview_withHundredPart;
                        }
                        
                    } else {
                        var valueInfoDescInterview = 0;
                    }
                    
                    $('#info-interview').attr('value', valueInfoDescInterview);
                    $('#info-interview-text-indicator').html(valueInfoDescInterview + ' %');
                    
                    
                    //Индикатор представителей сегмента, 
                    
                    var sumDataMembersOfSegment = 0; //Кол-во предствителей сегмента
                    
                    $.each(descInterviews, function(index, value) {
                         if(value.status == 1){
                            sumDataMembersOfSegment++;
                         }
                    });
                    
                    if(sumDataMembersOfSegment !== 0){
                    
                        var valueStatusInterview = (sumDataMembersOfSegment / inputCountRespond) * 100;
                        
                        valueStatusInterview_withoutResidue = valueStatusInterview.toFixed();
                        valueStatusInterview_withTenPart = valueStatusInterview.toFixed(1);
                        valueStatusInterview_withHundredPart = valueStatusInterview.toFixed(2);
                        
                        arr_valueStatusInterview = valueStatusInterview_withHundredPart.split('.');
                        var hundredPart_valueStatusInterview = arr_valueStatusInterview[1];
                        hundredPart_valueStatusInterview = hundredPart_valueStatusInterview.split('');
                        
                        if(hundredPart_valueStatusInterview[1] == 0){
                            
                            valueStatusInterview = valueStatusInterview_withTenPart;
                            
                            if(hundredPart_valueStatusInterview[0] == 0){
                            
                                valueStatusInterview = valueStatusInterview_withoutResidue;
                            }
                            
                        }else {
                            
                            valueStatusInterview = valueStatusInterview_withHundredPart;
                        }
                        
                    } else {
                        var valueStatusInterview = 0;
                    }
                    
                    $('#info-status').attr('value', valueStatusInterview);
                    $('#info-status-text-indicator').html(valueStatusInterview + ' %');
                    
                    if (inputCountPositive <= sumDataMembersOfSegment){
                        if ($('#info-status').hasClass('info-red') == true){
                            $('#info-status').removeClass('info-red').addClass('info-green');
                        }
                        
                    }else {
                        if ($('#info-status').hasClass('info-green') == true) {
                            $('#info-status').removeClass('info-green').addClass('info-red');
                        }
                    }
                    
                    
                    
                    //кнопка перехода в таблицу Информация о респондентах и строка сообщения 
                    
                    var problems = response.problems;
                    var sumProblems = 0; //Кол-во ГПС
                    
                    $.each(problems, function(index, value) {
                         if(value.id){
                            sumProblems++;
                         }
                    });
                    
                    
                    if (sumDataExistRespond == 0) {
                    
                        $('#redirect_info_responds_table').html('Начать');
                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
                        }
                        
                        $('#messageAboutTheNextStep').html('Начните заполнять данные о респондентах и интервью');
                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-success');
                        }
                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-success');
                        }
                    
                    } 
                    
                    if (sumDataExistRespond == sumResponds && sumDataExistDescInterview == sumResponds && inputCountPositive <= sumDataMembersOfSegment && sumProblems == 0) {
                        
                        $('#redirect_info_responds_table').html('Редактировать');
                        if ($('#redirect_info_responds_table').hasClass('btn-danger') == true) {
                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
                        } 
                        
                        $('#messageAboutTheNextStep').html('Переходите к генерации ГПС');
                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-success');
                        }
                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-success');
                        }
                        
                    } 
                    
                    if (sumProblems != 0) {
                    
                        $('#redirect_info_responds_table').html('Редактировать');
                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
                        }
                        
                        $('#messageAboutTheNextStep').html('');
                    } 
                    
                    if (sumDataExistRespond == sumResponds && sumDataExistDescInterview == sumResponds && inputCountPositive > sumDataMembersOfSegment) {
                        $('#redirect_info_responds_table').html('Добавить');
                        if ($('#redirect_info_responds_table').hasClass('btn-default')) {
                            $('#redirect_info_responds_table').removeClass('btn-default').addClass('btn-danger');
                        }
                        
                        $('#messageAboutTheNextStep').html('Недостаточное количество представителей сегмента');
                        if ($('#messageAboutTheNextStep').hasClass('text-warning')) {
                            $('#messageAboutTheNextStep').removeClass('text-warning').addClass('text-danger');
                        }
                        if ($('#messageAboutTheNextStep').hasClass('text-success')) {
                            $('#messageAboutTheNextStep').removeClass('text-success').addClass('text-danger');
                        }
                        
                    } 
                    
                    if (sumProblems == 0 && sumDataExistRespond != 0 &&(sumDataExistRespond != sumResponds || sumDataExistDescInterview != sumResponds)) {
                        $('#redirect_info_responds_table').html('Продолжить');
                        if ($('#redirect_info_responds_table').hasClass('btn-danger')) {
                            $('#redirect_info_responds_table').removeClass('btn-danger').addClass('btn-default');
                        }
                        
                        $('#messageAboutTheNextStep').html('Продолжите заполнение данных о респондентах и интервью');
                        if ($('#messageAboutTheNextStep').hasClass('text-danger')) {
                            $('#messageAboutTheNextStep').removeClass('text-danger').addClass('text-warning');
                        }
                        if ($('#messageAboutTheNextStep').hasClass('text-success')) {
                            $('#messageAboutTheNextStep').removeClass('text-success').addClass('text-warning');
                        }
                        
                    }
                    
                    
                    
                    //Изменение данных в модальных окнах с индикаторами данных
                    
                    
                    //Обновление модального окна - проверка данных респондентов
                    
                    var stringTemplateTableRespondsExist = $('#TableRespondsExist').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
                    $('#TableRespondsExist').find('tbody').html(''); //Очищаем таблицу  
                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
                        
                        $('#TableRespondsExist').find('tbody').append('<tr class=\"TableRespondsExist\" id=\"stringTableDataRespond-' + (index + 1) + '\">' + stringTemplateTableRespondsExist + '</tr>');
                        $('#stringTableDataRespond-' + (index + 1)).attr('data-key', value.id);
                        $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(1)').html(index+1);
                        
                        if(value.info_respond && value.date_plan && value.place_interview) {
                            
                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond-' + value.id).html(value.name);
                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('Данные заполнены');
                            
                        }else {
                            
                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_for_exist').html(value.name);
                            $('#stringTableDataRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('Данные отсутствуют');
                        }
                        
                    });
                    
                    
                    //Обновление модального окна - проверка данных по интервью
                    
                    var stringTemplateTableByDateInterview = $('#TableByDateInterview').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
                    $('#TableByDateInterview').find('tbody').html(''); //Очищаем таблицу
                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
                        
                        $('#TableByDateInterview').find('tbody').append('<tr class=\"TableByDateInterview\" id=\"stringTableDataInterview-' + (index + 1) + '\">' + stringTemplateTableByDateInterview + '</tr>');
                        $('#stringTableDataInterview-' + (index + 1)).attr('data-key', value.id);
                        $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(1)').html(index+1);
                        
                        
                        if(value.info_respond && value.date_plan && value.place_interview) {
                            
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond_by_date-' + value.id).html(value.name);
                            var date_plan_respond = new Date(value.date_plan*1000).toLocaleDateString();
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(3)').find('div').html(date_plan_respond);
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html('');
                            
                            for (var j = 0; j < descInterviews.length; j++) {
                                if(value.id == descInterviews[j].respond_id){
                                    
                                    var updated_at_descInterview = new Date(descInterviews[j].updated_at*1000).toLocaleDateString();
                                    $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html(updated_at_descInterview);
                                }
                            }
                        }else {
                            
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_by_date_interview').html(value.name);
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
                            $('#stringTableDataInterview-' + (index + 1)).find('td:nth-child(4)').find('div').html('');
                        }
                    });
                    
                    
                    //Обновление модального окна - таблица представителей сегмента
                    
                    var stringTemplateTableByStatusResponds = $('#TableByStatusResponds').find('tbody').find('tr:first').html(); //Берем в качестве шаблона первую строку таблицы  
                    $('#TableByStatusResponds').find('tbody').html(''); //Очищаем таблицу
                    $.each(responds, function(index, value) { //Добавляем данные в таблицу
                        
                        $('#TableByStatusResponds').find('tbody').append('<tr class=\"TableByStatusResponds\" id=\"stringTableStatusRespond-' + (index + 1) + '\">' + stringTemplateTableByStatusResponds + '</tr>');
                        $('#stringTableStatusRespond-' + (index + 1)).attr('data-key', value.id);
                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(1)').html(index+1);
                        
                        if(value.info_respond && value.date_plan && value.place_interview) {
                            
                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#view_respond_by_status-' + value.id).html(value.name);
                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
                            
                            for (var j = 0; j < descInterviews.length; j++) {
                                if(value.id == descInterviews[j].respond_id){
                                    
                                    var statusRespond = '';
                                    if(descInterviews[j].status == 0){
                                        statusRespond = 'Нет';
                                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').attr('style', 'color:red');
                                    } 
                                    if(descInterviews[j].status == 1){
                                        statusRespond = 'Да';
                                        $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').attr('style', 'color:green');
                                    }
                                    
                                    $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html(statusRespond);
                                }
                            }
                        }else {
                            
                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(2)').find('a').attr('data-target', '#not_view_respond_modal').removeClass('go_view_respond_for_by_status').html(value.name);
                            $('#stringTableStatusRespond-' + (index + 1)).find('td:nth-child(3)').find('div').html('');
                        }
                        
                    });
                    
                    

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

    
    //Создание нового вопроса (Шаг 1)
    $('#addNewQuestion').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса (Шаг 1)
                var container = $('#QuestionsTable-container').find('tbody');
                $('.new-string-table-questions').find('tr').attr('data-key', response.model.id);
                $('.new-string-table-questions').find('td[data-col-seq=\"1\"]').html(response.model.title);
                $('.new-string-table-questions').find('.delete-question-interview').attr('id', 'delete_question-' + response.model.id);
                $('.new-string-table-questions').find('.delete-question-interview').attr('href', '/interview/delete-question?id=' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк после удаления (Шаг 1)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Обновляем список вопросов на странице (Шаг 1)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 1)
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
    
    
    //Добавление нового вопроса из списка предложенных (Шаг 1)
    $('#addNewQuestionOfGeneralList').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса (Шаг 1)
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
                
                //Обновляем список вопросов на странице (Шаг 1)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 1)
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
    //а после события указываем по какому элементу оно будет срабатывать. (Шаг 1)
    $('#QuestionsTable-container').on('click', '.delete-question-interview', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/interview/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы (Шаг 1)
        var deleteString = $('#QuestionsTable-container').find('tr[data-key=\"' + id + '\"]');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){

                //Скрываем удаленный вопрос (Шаг 1)
                deleteString.hide();
                
                //Изменение нумерации строк после удаления (Шаг 1)
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Обновляем список вопросов на странице (Шаг 1)
                $('#table-data-interview').find('.list-questions').html(response.showListQuestions);
                
                //Обновляем список вопросов для добавления (Шаг 1)
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
