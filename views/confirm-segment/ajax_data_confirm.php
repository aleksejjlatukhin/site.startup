<?php

use yii\widgets\ActiveForm;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="container-fluid form-view-data-confirm">

    <div class="row row_header_data">

        <div class="col-sm-12 col-md-9" style="padding: 5px 0 0 0;">
            <?= Html::a('Исходные данные подтверждения' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-segment/get-instruction-step-one'],[
                'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
            ]); ?>
        </div>

        <div class="block-buttons-update-data-confirm col-sm-12 col-md-3" style="padding: 0;">

            <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $segment->exist_confirm === null) : ?>

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
            <div class="col-md-12">Цель проекта</div>
            <div class="col-md-12"><?= $project->purpose_project;?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Приветствие в начале встречи</div>
            <div class="col-md-12"><?= $model->greeting_interview; ?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Информация о вас для респондентов</div>
            <div class="col-md-12"><?= $model->view_interview; ?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Причина и тема (что побудило) для проведения исследования</div>
            <div class="col-md-12"><?= $model->reason_interview; ?></div>
        </div>

        <div class="row">
            <div class="col-md-12">Планируемое количество респондентов:
                <span><?= $model->count_respond; ?></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">Необходимое количество респондентов, соответствующих сегменту:
                <span><?= $model->count_positive; ?></span>
            </div>
        </div>

    </div>

</div>

<div class="container-fluid form-update-data-confirm">

    <?php
    $form = ActiveForm::begin([
        'id' => 'update_data_interview',
        'action' => Url::to(['/confirm-segment/update', 'id' => $model->id]),
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>


    <div class="row row_header_data">

        <div class="col-sm-12 col-md-6" style="padding: 5px 0 0 0;">
            <?= Html::a('Исходные данные подтверждения' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-segment/get-instruction-step-one'],[
                'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
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
                Цель проекта
            </div>

            <div class="col-md-12">
                <?= $project->purpose_project;?>
            </div>

        </div>


        <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

            <? $placeholder = 'Написать разумное обоснование, почему вы проводите это интервью, чтобы респондент поверил вам и начал говорить с вами открыто, не зажато.' ?>

            <?= $form->field($formUpdateConfirmSegment, 'greeting_interview', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textarea([
                'rows' => 1,
                'placeholder' => $placeholder,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
            ]);
            ?>

        </div>

        <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

            <? $placeholder = 'Фраза, которая соответствует статусу респондента и настраивает на нужную волну сотрудничества.' ?>

            <?= $form->field($formUpdateConfirmSegment, 'view_interview', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textarea([
                'rows' => 1,
                'placeholder' => $placeholder,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
            ]);
            ?>

        </div>

        <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

            <? $placeholder = 'Фраза, которая описывает, чем занимается интервьюер' ?>

            <?= $form->field($formUpdateConfirmSegment, 'reason_interview', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textarea([
                'rows' => 1,
                'placeholder' => $placeholder,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
            ]);
            ?>

        </div>

        <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

            <?= $form->field($formUpdateConfirmSegment, 'count_respond', [
                'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
            ])->label('<div>Планируемое количество респондентов</div><div style="font-weight: 400;font-size: 13px;">(укажите значение в диапазоне от 1 до 100)</div>')
                ->textInput([
                    'type' => 'number',
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'id' => 'confirm_count_respond',
                    'autocomplete' => 'off'
                ]);
            ?>

        </div>

        <div class="row">

            <?= $form->field($formUpdateConfirmSegment, 'count_positive', [
                'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
            ])->label('Необходимое количество респондентов, соответствующих сегменту')
                ->textInput([
                    'type' => 'number',
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'id' => 'confirm_count_positive',
                    'autocomplete' => 'off'
                ]);
            ?>

        </div>

    </div>

    <?php
    ActiveForm::end();
    ?>

</div>