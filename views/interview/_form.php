<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Segment;

?>

<div class="interview-form" style="margin-top: 10px;">

    <?php $form = ActiveForm::begin(); ?>

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

    <div class="row style-header-table-kartik" style="padding: 20px 10px; margin: 0; border-radius: 0 0 3px 3px;">
        <div class="col-md-12">

            <h4 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;margin-top: 20px;">Количественные данные респондентов, которые участвуют в интервью</h4>

            <div class="row">
                <?= $form->field($model, 'count_respond', [
                    'template' => '<div class="col-md-9" style="margin-top: 5px;">{label}</div><div class="col-md-3">{input}</div>'
                ])->textInput(['type' => 'number']);?>
            </div>

            <div class="row">
                <?= $form->field($model, 'count_positive', [
                    'template' => '<div class="col-md-9" style="margin-top: 5px;">{label}</div><div class="col-md-3">{input}</div>'
                ])->textInput(['type' => 'number']);?>
            </div>

            <h4 style="margin: 30px 0 15px 0; border-bottom: 1px solid #ccc; padding-bottom: 10px;">Текст легенды проблемного интервью</h4>

            <div class="row">

                <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

                <?= $form->field($model, 'greeting_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder]) ?>
            </div>

            <div class="row" style="margin-top: 10px;">

                <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

                <?= $form->field($model, 'view_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder]) ?>
            </div>

            <div class="row" style="margin-top: 10px;">

                <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

                <?= $form->field($model, 'reason_interview', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2, 'placeholder' => $placeholder]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-success',
                    'style' => ['margin-top' => '20px'],
                ]) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

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
        
          //Добавляем одинаковую высоту для элементов меню 
          //таблицы - Программа генерации ГПС 
          //равную высоте родителя
          $('.block-link-create-interview', this).each(function(){

              var height = $(this).height();
              $('.link_create_interview').css('height', height);
        
          });
        
     });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>