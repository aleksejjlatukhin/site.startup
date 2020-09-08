<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Segment;

$this->title = 'Подтверждение гипотезы целевого сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/interview-add_questions-style.css');
?>

<div class="interview-add-questions">


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



    <div class="block-link-create-interview row tab">


        <button class="tablinks step_one_button link_create_interview" onclick="openCity(event, 'step_one')">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 1</div>
                <div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div>
            </div>
        </button>


        <button class="tablinks step_two_button link_create_interview" onclick="openCity(event, 'step_two')" id="defaultOpen">
            <div class="link_create_interview-block_text">
                <div class="link_create_interview-text_left">Шаг 2</div>
                <div class="link_create_interview-text_right">Сформировать список вопросов</div>
            </div>
        </button>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 3</div><div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 4</div><div class="link_create_interview-text_right">Завершение подтверждения</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text step_five"><div class="link_create_interview-text_left">Шаг 5</div><div class="link_create_interview-text_right">Получить отзывы экспертов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

    </div>



    <!--ШАГ 1-->
    <div id="step_one" class="tabcontent row">

        <?php

        echo kartik\detail\DetailView::widget([
            'model' => $interview,
            'id' => 'table-data-interview',
            'condensed' => true,
            'striped' => false,
            'bordered' => true,
            'hover' => true,
            'enableEditMode' => true,
            'mode' => kartik\detail\DetailView::MODE_VIEW,
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
                'action' => Url::to(['/interview/update', 'id' => $interview->id]),
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
        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">

        <?php
        Modal::end();
        ?>


    </div>



    <!--ШАГ 2-->
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
                <span style="color: #fff; margin-left: 15px; margin-right: 20px;">Список вопросов для интервью</span>'

                    . Html::a('i', ['#'], [
                        'style' => ['margin-rigth' => '20px', 'font-size' => '16px', 'font-weight' => '700', 'padding' => '2px 10px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                        'class' => 'table-kartik-link',
                        'data-toggle' => 'modal',
                        'data-target' => "#information-table-questions",
                        'title' => 'Посмотреть описание',
                    ]) . '</div>'

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
                'footer' => Html::a( 'Далее', ['/interview/view', 'id' => $interview->id],[
                    'style' => [
                        'margin' => '-30px 60px 5px 0',
                        'background' => '#52BE7F',
                        'width' => '130px',
                        'height' => '35px',
                        'padding-top' => '4px',
                        'padding-bottom' => '4px'
                    ],
                    'class' => 'btn btn-lg btn-success pull-right',
                ]),
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

    <?= DetailView::widget([
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
        Пройдите последовательно этапы подтверждения гипотезы целевого сегмента. Далее переходите к генерации гипотез проблем сегмента.
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
        
        //Фон для модального окна о невозможности перехода на следующий этап
        var info_next_step_error_modal = $('#next_step_error').find('.modal-content');
        info_next_step_error_modal.css('background-color', '#707F99');
    
        //Фон для модального окна информации о вопросах
        var information_modal = $('#information-table-questions').find('.modal-content');
        information_modal.css('background-color', '#707F99');
        
        //Фон для модального окна - общее кол-во респондентов 
        //не должно быть меньше кол-ва респондентов, соответствующих сегменту
        var error_update_data_interview_modal = $('#error_update_data_interview').find('.modal-content');
        error_update_data_interview_modal.css('background-color', '#707F99');
        
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
        
        
        //Отмена перехода по ссылке кнопки добавить вопрос
        $('a.add_new_question_button').on('click', false);
    
    
        //Плавное изменение цвета ссылки этапа подтверждения
        $('.tab button').hover(function() {
            $(this).stop().animate({ backgroundColor: '#707f99'}, 300);
        },function() {
            $(this).stop().animate({ backgroundColor: '#828282' }, 300);
        });
        
        
    });
    
    
    
    //Редактирование исходных даннных программы подтверждения сегмента (Шаг 1)
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
