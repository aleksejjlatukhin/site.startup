<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Segment;

?>

<div class="segment-confirm-create">


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


    <?php $form = ActiveForm::begin(['id' => 'new_confirm_segment', 'action' => \yii\helpers\Url::to(['/interview/save-interview', 'id' => $segment->id])]); ?>


    <div class="block-link-create-interview row">

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 1</div><div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div></div>', [
            'class' => 'link_create_interview link_active_create_interview',
        ]); ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 2</div><div class="link_create_interview-text_right">Сформировать список вопросов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

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

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 5</div><div class="link_create_interview-text_right">Получить отзывы экспертов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

    </div>


    <div class="d-inline-block header-table row" style="font-size: 24px; color: #ffffff; border-top: 1px solid #fff;">
        <div class="col-md-12">Текст легенды проблемного интервью</div>
    </div>


    <div class="row style-header-table-kartik" style="padding: 20px 10px; border-radius: 0 0 3px 3px;">
        <div class="col-md-12">

            <div class="row">

                <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

                <?= $form->field($model, 'greeting_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder, 'required' => true,]) ?>
            </div>

            <div class="row" style="margin-top: 15px;">

                <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

                <?= $form->field($model, 'view_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder, 'required' => true,]) ?>
            </div>

            <div class="row" style="margin-top: 15px;">

                <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

                <?= $form->field($model, 'reason_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder, 'required' => true,]) ?>
            </div>



            <div class="row" style="margin-top: 30px;">

                <div class="col-md-5 row">

                    <?= $form->field($model, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-8" style="margin-top: 5px; ">{label}</div><div class="col-xs-12 col-sm-3 col-md-4">{input}</div>'
                    ])->label('Планируемое количество респондентов')->textInput(['type' => 'number', 'required' => true,]);?>

                </div>

                <div class="col-md-7 row">

                    <?= $form->field($model, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9" style="margin-top: 5px;">{label}</div><div class="col-xs-12 col-sm-3">{input}</div>'
                    ])->label('Необходимое количество респондентов, соответствующих сегменту')->textInput(['type' => 'number', 'required' => true,]);?>

                </div>

            </div>



            <div class="form-group">
                <?= Html::submitButton('Далее', [
                    'class' => 'btn btn-lg btn-success pull-right',
                    'style' => [
                        'margin-top' => '20px',
                        'margin-right' => '60px',
                        'background' => '#52BE7F',
                        'width' => '130px',
                        'height' => '35px',
                        'padding-top' => '4px',
                        'padding-bottom' => '4px'
                    ],
                ]) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>


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
        'header' => '<h3 class="text-center">Просмотр данных сегмента</h3>',
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
    // Модальное окно - некорректное внесение данных
    Modal::begin([
        'options' => [
            'id' => 'error_form',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        - общее количество респондентов не может быть меньше количества респондентов, соответствующих сегменту.
    </h4>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        - количественные данные респондентов не могут быть равны нулю;
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
          
          //
          var error_form_modal = $('#error_form').find('.modal-content');
          error_form_modal.css('background-color', '#707F99');
        
          //Добавляем одинаковую высоту для элементов меню 
          //таблицы - Программа генерации ГПС 
          //равную высоте родителя
          $('.block-link-create-interview', this).each(function(){

              var height = $(this).height();
              $('.link_create_interview').css('height', height);
        
          });
          
          
          //Плавное изменение цвета ссылки этапа подтверждения
          $('.link_passive_create_interview').hover(function() {
             $(this).stop().animate({ backgroundColor: '#707f99'}, 300);
          },function() {
             $(this).stop().animate({ backgroundColor: '#828282' }, 300);
          });
        
     });
     
     
     //Форма создания модели подтверждения сегмента
     $('#new_confirm_segment').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                if (!response['error']) {
                
                    window.location.href = '/interview/add-questions?id=' + response['id'];
                }
                else {
                
                    $('#error_form').modal('show');
                }
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