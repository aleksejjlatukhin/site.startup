<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



\yii\web\YiiAsset::register($this);
?>

<div class="interview-form">

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

    <?php
    $j = 0;
    foreach ($model->questions as $status => $question){
        $j++;
        echo $form->field($model, 'questions['.$status.']', [
            'template' => '<div class="col-md-8">{label} </div><div class="col-md-4">{input}</div>'
        ])->checkbox(['value' => '1', 'checked ' => true], false)
                ->label($j . '. ' .$question->title);
    }
    ?>

    <p class="col-sm-12 row" style="margin: 10px 0;">
        <div class="btn btn-primary open_fast col-md-1" style="width: 150px;margin-left: 15px;">Добавить вопрос</div>
    </p>

    <div class="popap_fast">

        <div class="col-sm-9">
            <?= $form->field($newQuestions, 'title')->textInput(['maxlength' => true]) ?>
        </div>

        <span class="cross-out glyphicon text-danger glyphicon-remove"></span>

    </div>


    <div class="form-group col-sm-12">
        <hr>
        <?= Html::submitButton('Сохранить данные', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
