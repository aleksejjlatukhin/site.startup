<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>


<div class="container-fluid form-view-data-confirm">

    <div class="row row_header_data">

        <div class="col-sm-12 col-md-9" style="padding: 10px 0 0 0;">

            <span style="color: #4F4F4F;padding-right: 10px;">Определение данных, которые необходимо подтвердить</span>

            <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                'data-toggle' => 'modal',
                'data-target' => "#information-add-new-responds",
                'title' => 'Посмотреть описание',
            ]); ?>

        </div>

        <div class="block-buttons-update-data-confirm col-sm-12 col-md-3" style="padding: 0;">

            <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

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
            <div class="col-md-12">Формулировка проблемы, которую проверяем</div>
            <div class="col-md-12"><?= $problem->description;?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Потребность потребителя сегмента, которую проверяем</div>
            <div class="col-md-12"><?= $model->need_consumer;?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Количество респондентов (представителей сегмента):
                <span><?= $model->count_respond; ?></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">Необходимое количество респондентов, подтверждающих проблему:
                <span><?= $model->count_positive; ?></span>
            </div>
        </div>

    </div>

</div>

<div class="container-fluid form-update-data-confirm">

    <?php
    $form = ActiveForm::begin([
        'id' => 'update_data_confirm',
        'action' => Url::to(['/confirm-problem/update', 'id' => $model->id]),
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>

    <div class="row row_header_data">

        <div class="col-sm-12 col-md-6" style="padding: 10px 0 0 0;">

            <span style="color: #4F4F4F;padding-right: 10px;">Определение данных, которые необходимо подтвердить</span>

            <?= Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
                'data-toggle' => 'modal',
                'data-target' => "#information-add-new-responds",
                'title' => 'Посмотреть описание',
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
                Формулировка проблемы, которую проверяем
            </div>

            <div class="col-md-12">
                <?= $problem->description;?>
            </div>

        </div>

        <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

            <?= $form->field($formUpdateConfirmProblem, 'need_consumer', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Какую потребность потребителя сегмента проверяем')
                ->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'placeholder' => '',
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                ]);
            ?>

        </div>

        <div class="row" style="padding-top: 15px; padding-bottom: 5px;">

            <?= $form->field($formUpdateConfirmProblem, 'count_respond', [
                'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
            ])->label('<div>Количество респондентов (представителей сегмента)</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                ->textInput([
                    'type' => 'number',
                    'readonly' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'id' => 'confirm_count_respond',
                ]);
            ?>

        </div>

        <div class="row">

            <?= $form->field($formUpdateConfirmProblem, 'count_positive', [
                'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
            ])->label('Необходимое количество респондентов, подтверждающих проблему')
                ->textInput([
                    'type' => 'number',
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'id' => 'confirm_count_positive',
                ]);
            ?>

        </div>

    </div>

    <?php
    ActiveForm::end();
    ?>

</div>
