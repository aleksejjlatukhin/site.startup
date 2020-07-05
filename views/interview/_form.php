<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

?>

<div class="interview-form" style="margin-top: 10px;">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row" style="font-size: 24px; font-weight: 700; color: #F2F2F2; background: #707F99; margin: 0; padding: 10px; border-radius: 3px 3px 0 0">
        <div class="col-md-12" style="margin: 5px 0;">

            <?= $this->title

            . Html::a('i', ['#'], [
                'style' => ['margin-left' => '20px', 'font-size' => '13px', 'font-weight' => '700', 'padding' => '2px 8px', 'background-color' => '#F2F2F2', 'border-radius' => '50%', 'text-decoration' => 'none'],
                'class' => 'table-kartik-link',
                'data-toggle' => 'modal',
                'data-target' => "#information-table-interview",
                'title' => 'Посмотреть описание',])

            . Html::a('Данные сегмента', ['#'], [
                'class' => 'btn btn-sm btn-default pull-right col-xs-12 col-sm-2',
                'style' => ['font-weight' => '700', 'color' => '#373737', 'margin-top' => '3px'],
                'data-toggle' => 'modal',
                'data-target' => '#data_segment_modal',
            ]);  ?>

        </div>
    </div>

    <div class="row style-header-table-kartik" style="padding: 20px 10px; margin: 0; border-radius: 0 0 3px 3px;">
        <div class="col-md-12">

            <h4 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">Количественные данные респондентов, которые участвуют в интервью</h4>

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
         Необходимо заполнить все поля данной формы.
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
        
    });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>