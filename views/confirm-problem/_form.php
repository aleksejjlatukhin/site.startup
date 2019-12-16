<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="confirm-problem-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= $form->field($model, 'count_respond', [
            'template' => '<div class="col-md-3">{label}</div><div class="col-md-2">{input}</div>'
        ])->textInput(['type' => 'number']);?>
    </div>

    <div class="row">
        <?= $form->field($model, 'count_positive', [
            'template' => '<div class="col-md-3">{label}</div><div class="col-md-2">{input}</div>'
        ])->textInput(['type' => 'number']);?>
    </div>

    <h4><u>Текст легенды</u></h4>

    <div class="row">
        <?= $form->field($model, 'greeting_interview', [
            'template' => '<div class="col-md-3">{label}</div><div class="col-md-9">{input}</div>'
        ])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'view_interview', [
            'template' => '<div class="col-md-3">{label}</div><div class="col-md-9">{input}</div>'
        ])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($model, 'reason_interview', [
            'template' => '<div class="col-md-3">{label}</div><div class="col-md-9">{input}</div>'
        ])->textInput(['maxlength' => true]) ?>
    </div>

    <h3>Вопросы</h3>

    <div class="d-inline p-2 bg-success text-center" style="font-size: 18px;border-radius: 5px;height: 50px;padding-top: 12px;">Примерный список вопросов для проведения интервью</div>

    <br>
    <?= $form->field($model, 'question_1', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("1. Как и посредством какого инструмента / процесса вы справляетесь с задачей?") ?>

    <?= $form->field($model, 'question_2', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("2. Что нравится / не нравится в текущем положении вещей?") ?>

    <?= $form->field($model, 'question_3', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("3. Вас беспокоит данная ситуация?") ?>

    <?= $form->field($model, 'question_4', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("4. Что вы пытались с этим сделать?") ?>

    <?= $form->field($model, 'question_5', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("5. Что вы делали с этим в последний раз, какие шаги предпринимали?") ?>

    <?= $form->field($model, 'question_6', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("6. Если ничего не делали, то почему?") ?>

    <?= $form->field($model, 'question_7', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("7. Сколько денег / времени на это тратится сейчас?") ?>

    <?= $form->field($model, 'question_8', [
        'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
    ])->checkbox(['value' => '1', 'checked ' => true])
        ->label("8. Есть ли деньги на решение сложившейся ситуации сейчас?") ?>




    <p class="col-sm-12" style="margin: 10px 0;">
    <div class="btn btn-primary open_fast col-md-1" style="width: 150px;margin-left: 15px;">Добавить вопрос</div>
    </p>

    <div class="popap_fast">

        <div class="col-sm-9">
            <?= $form->field($newQuestions, 'title')->textInput(['maxlength' => true])->label('Напишите новый вопрос') ?>
        </div>

        <span class="cross-out glyphicon text-danger glyphicon-remove"></span>

    </div>

    <div class="form-group col-sm-12">
        <hr>
        <?= Html::submitButton('Сохранить данные', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
