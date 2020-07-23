<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Segment;

$this->title = 'Список вопросов для интервью';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="interview-add-questions table-project-kartik">

    <div class="row d-inline p-2" style="background: #707F99; font-size: 26px; font-weight: 700; color: #F2F2F2; border-radius: 5px 5px 0 0; padding: 10px; margin: 0; padding-top: 20px; padding-bottom: 10px;/*height: 80px;*//*padding-top: 12px;padding-left: 20px;margin-top: 10px;*/">

        <div class="col-md-12 col-lg-6" style="padding: 0 20px; text-align: center;">

            <?php
            echo 'Программа генерации ГПС' .

                Html::a('i', ['#'], [
                    'style' => ['margin-left' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-interview",
                    'title' => 'Посмотреть описание'
                ])
            ?>
        </div>


            <?= Html::a('Данные сегмента', ['#'], [
                'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                'style' => [
                    'font-weight' => '700',
                    'color' => '#373737',
                    'border' => 'solid 5px #707F99',
                    'border-radius' => '8px',
                ],
                'data-toggle' => 'modal',
                'data-target' => '#data_segment_modal',
            ]); ?>



            <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], [
                'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                'style' => [
                    'font-weight' => '700',
                    'color' => '#373737',
                    'border' => 'solid 5px #707F99',
                    'border-radius' => '8px',
                ],
            ]) ?>



            <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], [
                'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                'style' => [
                    'font-weight' => '700',
                    'color' => '#373737',
                    'border' => 'solid 5px #707F99',
                    'border-radius' => '8px',
                ],
            ]) ?>


    </div>

    <div class="block-link-create-interview row">

        <?= Html::button('Шаг 1. Заполнить исходные данные для проведения интервью', [
            'class' => 'link_create_interview link_active_create_interview col-xs-12 col-md-6 col-lg-3',
        ]); ?>

        <?= Html::button('Шаг 2. Заполнить информацию о респондентах и интервью', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('Шаг 3. Сгенерировать гипотезы проблем сегмента', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('Отзывы экспертов', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

    </div>

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
            'before' => '<div class="row" style="margin: 0; font-size: 20px; padding: 10px;"><div class="col-md-12 col-lg-6" style="margin-bottom: 5px;">
                <span style="color: #4F4F4F; padding-left: 10px;">Список вопросов для интервью</span>'

                . Html::a('i', ['#'], [
                    'style' => [
                        'margin-left' => '20px',
                        'font-size' => '13px',
                        'font-weight' => '700',
                        'padding' => '2px 8px',
                        'background-color' => '#707F99',
                        'border-radius' => '50%',
                        'text-decoration' => 'none',
                        'color' => '#F2F2F2',
                    ],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-questions",
                    'title' => 'Посмотреть описание',
                ]) . '</div>'

                .   Html::a( 'Далее', ['/interview/view', 'id' => $interview->id],[
                    'style' => [
                        'font-weight' => '700',
                        'border' => 'solid 5px #F2F2F2',
                        'border-radius' => '8px',
                        'box-shadow' => '0px 0px 0px 1px rgba(204, 204, 204, 1) inset'
                    ],
                    'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                    ])

                .   Html::button( 'Добавить вопрос', [
                    'style' => [
                        'font-weight' => '700',
                        'border' => 'solid 5px #F2F2F2',
                        'border-radius' => '8px',
                        'box-shadow' => '0px 0px 0px 1px rgba(204, 204, 204, 1) inset'
                    ],
                    'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                    'id' => 'buttonAddQuestion',
                    ])

                .   Html::button( 'Выбрать из списка', [
                    'style' => [
                        'font-weight' => '700',
                        'border' => 'solid 5px #F2F2F2',
                        'border-radius' => '8px',
                        'box-shadow' => '0px 0px 0px 1px rgba(204, 204, 204, 1) inset'
                    ],
                    'class' => 'btn btn-sm btn-default col-xs-12 col-sm-4 col-lg-2',
                    'id' => 'buttonAddQuestionToGeneralList',
                    ])

                .   '</div><div class="row form-newQuestion-panel kv-hide" style="display: none;"></div>
                    <div class="row form-QuestionsOfGeneralList-panel kv-hide" style="display: none;"></div>',

            'beforeOptions' => ['class' => 'style-header-table-kartik'],
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

            <? $form = ActiveForm::begin(['id' => 'addNewQuestion', 'action' => Url::to(['/interview/add-question', 'id' => $interview->id])]);?>

            <div class="col-xs-12 col-md-10 col-lg-10">
                <?= $form->field($newQuestion, 'title', ['template' => '{input}'])->textInput(['maxlength' => true, 'required' => true])->label(false); ?>
            </div>
            <div class="col-xs-12 col-md-2 col-lg-2">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success col-xs-12', 'style' => ['font-weight' => '700', 'margin-bottom' => '15px']]); ?>
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
                    <span class="glyphicon glyphicon-trash"></span>
                </a>
            </td>
        </tr>
        </tbody>
    </table>

    <!--Форма для выбора вопроса из общксписка для  добавления в интервью-->
    <div class="row" style="display: none;">
        <div class="col-md-12 form-QuestionsOfGeneralList" style="margin-top: 5px;">

            <? $form = ActiveForm::begin(['id' => 'addNewQuestionOfGeneralList', 'action' => Url::to(['/interview/add-question', 'id' => $interview->id])]);?>

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


    <?= DetailView::widget([
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
                'attribute' => 'main_problems_consumer',
                'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C),
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

    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-interview',
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
        Для перехода на следующий этап Вам необходимо заполнить данные и нажать кнопку «Сохранить».
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
        'header' => '<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">1. Сформулируйте собственный список вопросов для интервью или отредактируйте список «по-умолчанию».</h4>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        2. Когда список будет готов переходите по ссылке «Далее».
    </h4>

    <?php
    Modal::end();
    ?>

</div>

<?php

$script = "

    $(document).ready(function() {
    
        //Фон для модального окна информации
        var information_modal = $('#information-table-interview').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //Фон для модального окна о невозможности перехода на следующий этап
        var info_next_step_error_modal = $('#next_step_error').find('.modal-content');
        info_next_step_error_modal.css('background-color', '#707F99');
    
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //Добавляем одинаковую высоту для элементов меню 
        //таблицы - Программа генерации ГПС 
        //равную высоте родителя
        $('.block-link-create-interview', this).each(function(){
            var height = $(this).height();
            $('.link_create_interview').css('height', height);
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
    
    });
    
    //Создание нового вопроса
    $('#addNewQuestion').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса
                var container = $('#QuestionsTable-container').find('tbody');
                $('.new-string-table-questions').find('tr').attr('data-key', response.model.id);
                $('.new-string-table-questions').find('td[data-col-seq=\"1\"]').html(response.model.title);
                $('.new-string-table-questions').find('.delete-question-interview').attr('id', 'delete_question-' + response.model.id);
                $('.new-string-table-questions').find('.delete-question-interview').attr('href', '/interview/delete-question?id=' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Скрываем и очищием форму
                $('.form-newQuestion-panel').hide();
                $('#addNewQuestion')[0].reset();
                
                //Обновляем список вопросов для добавления
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
    
    
    
    //Добавление нового вопроса из списка предложенных
    $('#addNewQuestionOfGeneralList').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Добавление строки для нового вопроса
                var container = $('#QuestionsTable-container').find('tbody');
                $('.new-string-table-questions').find('tr').attr('data-key', response.model.id);
                $('.new-string-table-questions').find('td[data-col-seq=\"1\"]').html(response.model.title);
                $('.new-string-table-questions').find('.delete-question-interview').attr('id', 'delete_question-' + response.model.id);
                $('.new-string-table-questions').find('.delete-question-interview').attr('href', '/interview/delete-question?id=' + response.model.id);
                var newString = $('.new-string-table-questions').html();
                container.append(newString);
                
                //Изменение нумерации строк
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Скрываем форму
                $('.form-QuestionsOfGeneralList-panel').hide();
                
                //Обновляем список вопросов для добавления
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
    //а после события указываем по какому элементу оно будет срабатывать.
    $('#QuestionsTable-container').on('click', '.delete-question-interview', function(e){
        
        var id = $(this).attr('id');
        id = id.split('-');
        id = id[1];
        
        var url = '/interview/delete-question?id=';
        url += id;
        
        //Сторока, которая будет удалена из таблицы
        var deleteString = $('#QuestionsTable-container').find('tr[data-key=\"' + id + '\"]');
        
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
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Обновляем список вопросов для добавления
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
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>
