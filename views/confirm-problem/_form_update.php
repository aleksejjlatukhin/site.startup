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




    <div class="form-group">
        <hr>
        <?= Html::submitButton('Сохранить данные', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

