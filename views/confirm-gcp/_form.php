<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="confirm-gcp-form">

    <div class="row d-inline p-2" style="background: #707F99; font-size: 26px; font-weight: 700; color: #F2F2F2; border-radius: 5px 5px 0 0; padding: 0; margin: 0; padding-top: 20px; padding-bottom: 10px;/*height: 80px;*//*padding-top: 12px;padding-left: 20px;margin-top: 10px;*/">

        <div class="col-md-12 col-lg-6" style="padding: 0 20px; text-align: center;">

            <?php
            echo 'Программа подтверждения ' . $gcp->title .

                Html::a('i', ['#'], [
                    'style' => ['margin-left' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                    'class' => 'table-kartik-link',
                    'data-toggle' => 'modal',
                    'data-target' => "#information-table-confirm-gcp",
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
            <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], [
                'class' => 'btn btn-sm btn-default',
                'style' => [
                    'font-weight' => '700',
                    'color' => '#373737',
                    'width' => '170px'
                ]
            ]) ?>
        </div>

        <div class="col-md-12 col-lg-2" style="padding: 0 10px 10px 10px; text-align: center;">
            <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], [
                'class' => 'btn btn-sm btn-default',
                'style' => [
                    'font-weight' => '700',
                    'color' => '#373737',
                    'width' => '170px'
                ],
                'onclick' => 'return false',
            ]) ?>
        </div>

    </div>

    <div class="block-link-create-interview row">

        <?= Html::button('Шаг 1. Заполнить исходные данные для подтверждения ГЦП', [
            'class' => 'link_create_interview link_active_create_interview col-xs-12 col-md-6 col-lg-3',
        ]); ?>

        <?= Html::button('Шаг 2. Заполнить анкетные данные респондентов', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-md-6 col-lg-3',
            'data-toggle' => 'modal',
            'data-target' => '#next_step_error',
        ]); ?>

        <?= Html::button('Шаг 3. Переход к генерации Minimum Viable Product', [
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

            <h4 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;margin-top: 20px;">Определение данных, которые необходимо подтвердить</h4>

            <?php $form = ActiveForm::begin(); ?>

            <div class="row" style="margin-top: 20px;">

                <?= $form->field($gcp, 'description', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->label('Формулировка ценностного предложения, которое проверяем')->textarea(['rows' => 4, 'readonly' => true]) ?>

            </div>

            <?php ActiveForm::end(); ?>


            <?php $form = ActiveForm::begin(); ?>

            <h4 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;margin-top: 20px;">Количественные данные респондентов, которые участвуют в опросе

                <?= Html::a('i', ['#'], [
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
                    'data-target' => "#information-add-new-responds",
                    'title' => 'Посмотреть описание',
                ])?>

            </h4>

            <div class="row" style="margin-top: 20px;">

                <?= $form->field($model, 'count_respond', [
                    'template' => '<div class="col-md-9">{label}</div><div class="col-md-3">{input}</div>'
                ])->label('Количество респондентов (подтвердивших проблему сегмента)')->textInput(['type' => 'number', 'readonly' => true]);?>

            </div>

            <div class="row">

                <?= $form->field($model, 'count_positive', [
                    'template' => '<div class="col-md-9">{label}</div><div class="col-md-3">{input}</div>'
                ])->label('Необходимое количество респондентов, подтверждающих ценностное предложение')->textInput(['type' => 'number']);?>

            </div>

            <div class="form-group" style="margin-top: 10px">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>


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

    <?= $segment->allInformation; ?>

    <?php
    Modal::end();
    ?>

    <?php
    // Описание выполнения задачи на данной странице
    Modal::begin([
        'options' => [
            'id' => 'information-table-confirm-gcp',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Пройдите три шага подтверждения ценностного предложения. Далее переходите к генерации MVP.
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
        Добавить новых респондентов можно в «Шаге 2» на этапе заполнения анкетных данных.
    </h4>

    <?php
    Modal::end();
    ?>

</div>


<?php
$script = "

     $(document).ready(function() {
    
          //Фон для модального окна информации
          var information_modal = $('#information-table-confirm-gcp').find('.modal-content');
          information_modal.css('background-color', '#707F99');
        
          //Фон для модального окна о невозможности перехода на следующий этап
          var info_next_step_error_modal = $('#next_step_error').find('.modal-content');
          info_next_step_error_modal.css('background-color', '#707F99');
          
          //Фон для модального окна информации о месте добавления новых респондентов
          var information_add_new_responds = $('#information-add-new-responds').find('.modal-content');
          information_add_new_responds.css('background-color', '#707F99');
        
          //Добавляем одинаковую высоту для элементов меню 
          //таблицы равную высоте родителя
          $('.block-link-create-interview', this).each(function(){

              var height = $(this).height();
              $('.link_create_interview').css('height', height);
        
          });
        
     });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>
