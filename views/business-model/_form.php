<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-model-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row" style="margin-bottom: 5px">
        <?= '<div class="col-md-8" style="margin-bottom: 10px;"><span style="font-weight: 700;">' . Html::a('Потребительский сегмент: ', Url::to(['segment/view', 'id' => $segment->id])) . '</span>' . mb_strtolower($segment->name) . '</div>' ?>
    </div>

    <div class="row" style="margin-bottom: 5px">
        <?= '<div class="col-md-8" style="margin-bottom: 10px;"><span style="font-weight: 700;">' . Html::a('Ценностное предложение: ', Url::to(['gcp/view', 'id' => $gcp->id])) . '</span>' . mb_strtolower($gcp->description) . '</div>' ?>
    </div>

    <hr>
    <div class="row" style="margin-bottom: 5px">
        <?= $form->field($model, 'quantity', [
            'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-4">{input}</div>'
        ])->textInput(['type' => 'number']); ?>
    </div>
    <br>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'sort_of_activity', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'relations', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'partners', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'distribution_of_sales', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'resources', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'cost', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="row" style="margin-bottom: 15px;">
        <?= $form->field($model, 'revenue', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 1]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>