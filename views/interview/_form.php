<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Segment;

?>

    <div class="segment-confirm-create">


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

            <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
            ]) ?>

            <?= Html::a('Дорожная карта сегмента', ['/segment/show-roadmap', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
            ]) ?>

        </div>


        <div class="block-link-create-interview row">

            <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 1</div><div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div></div>', [
                'class' => 'link_create_interview link_active_create_interview col-xs-12 col-md-6 col-lg-3',
            ]); ?>

            <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 2</div><div class="link_create_interview-text_right">Сформировать список вопросов</div></div>', [
                'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
                'data-toggle' => 'modal',
                'data-target' => '#next_step_error',
            ]); ?>

            <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 3</div><div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div></div>', [
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

        <div class="row">

            <div class="container-fluid container-data">

                <div class="row row_header_data">

                    <div class="col-md-12" style="padding: 10px 0 0 0;">

                        <span style="color: #4F4F4F;padding-right: 10px;">Текст легенды проблемного интервью</span>

                    </div>

                </div>

                <div class="container-fluid">

                    <?php

                    $form = ActiveForm::begin([
                        'id' => 'new_confirm_segment',
                        'action' => \yii\helpers\Url::to(['/interview/save-interview', 'id' => $segment->id]),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]);

                    ?>


                    <div class="row" style="padding-top: 30px; padding-bottom: 5px;">

                        <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

                        <?= $form->field($model, 'greeting_interview', [
                            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                        ])->textarea([
                            'rows' => 1,
                            'placeholder' => $placeholder,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                        ]);
                        ?>

                    </div>

                    <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                        <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

                        <?= $form->field($model, 'view_interview', [
                            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                        ])->textarea([
                            'rows' => 1,
                            'placeholder' => $placeholder,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                        ]);
                        ?>

                    </div>

                    <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                        <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

                        <?= $form->field($model, 'reason_interview', [
                            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                        ])->textarea([
                            'rows' => 1,
                            'placeholder' => $placeholder,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                        ]);
                        ?>

                    </div>

                    <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

                        <?= $form->field($model, 'count_respond', [
                            'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                        ])->label('<div>Планируемое количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                            ->textInput([
                                'type' => 'number',
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'id' => 'confirm_count_respond',
                            ]);
                        ?>

                    </div>

                    <div class="row">

                        <?= $form->field($model, 'count_positive', [
                            'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                        ])->label('Необходимое количество респондентов, соответствующих сегменту')
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



                    <div class="form-group">
                        <?= Html::submitButton('Далее', [
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#52BE7F',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                                'margin-top' => '30px'
                            ],
                            'class' => 'btn btn-lg btn-success pull-right',
                        ]) ?>
                    </div>

                    <?php
                    ActiveForm::end();
                    ?>

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
            - общее количество респондентов не может быть меньше количества респондентов, соответствующих сегменту;
        </h4>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            - количественные данные респондентов не могут быть меньше 1.
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
          
          //Фон для модального окна - некорректное внесение данных
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