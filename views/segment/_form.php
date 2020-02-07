<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="segment-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'name', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'field_of_activity', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 2]) ?>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'sort_of_activity', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 2]) ?>
    </div>

    <div class="row" style="margin-bottom: 5px;">
        <?= $form->field($model, 'age', [
            'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-4">{input}</div>'
        ])->textInput(['type' => 'number']);?>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <?= $form->field($model, 'income', [
            'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-4">{input}</div>'
        ])->textInput(['type' => 'number']); ?>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <?= $form->field($model, 'quantity', [
            'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-4">{input}</div>'
        ])->textInput(['type' => 'number']); ?>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <?= $form->field($model, 'market_volume', [
            'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-4">{input}</div>'
        ])->textInput(['type' => 'number']); ?>
    </div>

    <br>
    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'add_info', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 4]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
