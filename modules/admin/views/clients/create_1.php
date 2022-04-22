<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создание новой организации';
?>

<div class="row container-fluid block-form-create-client">

    <div class="col-md-12" style="margin-top: 35px; margin-bottom: 35px; padding-left: 25px;">
        <?= Html::a('Шаг 1. Заполните форму создания администратора' . Html::img('/images/icons/icon_report_next.png'), ['#'],[
            'class' => 'link_to_instruction_page open_modal_instruction_page',
            'title' => 'Инструкция', 'onclick' => 'return false'
        ]); ?>
    </div>

    <!--Вывод ошибок валидации собственных правил формы-->
    <?php if ($errors = $formCreateAdminCompany->errors) : ?>
        <?php foreach ($errors as $k => $error) : ?>
            <div class="text-center text-danger">
                <?= implode('\n', (array)$error); ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'adminCompanyCreateForm',
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

        <div class="row" style="margin-bottom: 10px;">
            <?= $form->field($formCreateAdminCompany, 'second_name', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'maxlength' => 50,
                'minlength' => 2,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <?= $form->field($formCreateAdminCompany, 'first_name', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'maxlength' => 50,
                'minlength' => 2,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <?= $form->field($formCreateAdminCompany, 'middle_name', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'maxlength' => 50,
                'minlength' => 2,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="row" style="margin-bottom: 15px;">
            <?= $form->field($formCreateAdminCompany, 'username', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'maxlength' => 32,
                'minlength' => 3,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Введите от 3 до 32 символов',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="row" style="margin-bottom: 15px;">
            <?= $form->field($formCreateAdminCompany, 'email', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'type' => 'email',
                'required' => true,
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="row" style="margin-bottom: 15px;">
            <?= $form->field($formCreateAdminCompany, 'telephone', [
                'template' => '<div class="col-md-12" style="padding-left: 30px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->textInput([
                'maxlength' => 50,
                'minlength' => 6,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>
        </div>

        <div class="form-group row container-fluid">
            <?= Html::submitButton('Далее', [
                'class' => 'btn btn-success pull-right',
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

    <?php ActiveForm::end(); ?>

</div>