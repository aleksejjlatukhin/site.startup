<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;

$this->title = 'Список вопросов для интервью';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="interview-add-questions table-project-kartik">

    <div class="row d-inline p-2" style="background: #707F99; font-size: 26px; font-weight: 700; color: #F2F2F2; border-radius: 5px 5px 0 0; padding: 0; margin: 0; padding-top: 20px; padding-bottom: 10px;/*height: 80px;*//*padding-top: 12px;padding-left: 20px;margin-top: 10px;*/">

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
            'before' => '<div class="row" style="margin: 0; font-size: 20px; padding: 10px;"><span style="color: #4F4F4F; padding-left: 10px;">Список вопросов для интервью</span>'

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
                ])



                . Html::button( 'Добавить вопрос', [
                    'style' => ['font-weight' => '700'],
                    'class' => 'btn btn-sm btn-default pull-right col-xs-5 col-md-2',
                    'id' => 'buttonAddQuestion'])

                . Html::a( 'Далее', ['/interview/view', 'id' => $interview->id],[
                    'style' => ['font-weight' => '700', 'margin-right' => '5px'],
                    'class' => 'btn btn-sm btn-default pull-right col-xs-5 col-md-2',
                    ])

                . '</div><div class="row form-newQuestion-panel kv-hide" style="display: none;"></div>',

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
        <div class="col-md-12 form-newQuestion" style="margin-top: 15px;">

            <? $form = ActiveForm::begin(['id' => 'addNewQuestion', 'action' => Url::to(['/interview/add-question', 'id' => $interview->id])]);?>

            <div class="col-md-10">
                <?= $form->field($newQuestion, 'title')->textInput(['maxlength' => true, 'required' => true])->label(false); ?>
            </div>
            <div class="col-md-2">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success pull-right', 'style' => ['font-weight' => '700', 'margin' => '2px 5px', 'width' => '120px']]); ?>
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
            'field_of_activity:ntext',
            'sort_of_activity:ntext',

            [
                'attribute' => 'age',
                'label' => 'Возраст потребителя',
                'value' => function ($model) {
                    if ($model->age_from !== null && $model->age_to !== null){
                        return 'от ' . number_format($model->age_from, 0, '', ' ') . ' до '
                            . number_format($model->age_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'income',
                'label' => 'Доход потребителя (тыс. руб./мес.)',
                'value' => function ($model) {
                    if ($model->income_from !== null && $model->income_to !== null){
                        return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                            . number_format($model->income_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'quantity',
                'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                'value' => function ($model) {
                    if ($model->quantity_from !== null && $model->quantity_to !== null){
                        return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                            . number_format($model->quantity_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'market_volume',
                'label' => 'Объем рынка (млн. руб./год)',
                'value' => function ($model) {
                    if ($model->market_volume_from !== null && $model->market_volume_to !== null){
                        return 'от ' . number_format($model->market_volume_from, 0, '', ' ') . ' до '
                            . number_format($model->market_volume_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'add_info',
                'visible' => !empty($model->add_info),
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
            $('.form-newQuestion-panel').toggle();
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
                
                //Изменение нумерации строк после удаления
                var questions = response.questions;
                $.each(questions, function(index, value) {
                    $('#QuestionsTable-container').find('tr[data-key=\"' + value['id'] + '\"]').find('td:first').html(index+1);
                });
                
                //Скрываем и очищием форму
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
