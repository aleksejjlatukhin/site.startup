<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmMvp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="confirm-mvp-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <?= $form->field($model, 'count_positive', [
                    'template' => '<div class="col-md-9">{label}</div><div class="col-md-3">{input}</div>'
                ])->textInput(['type' => 'number']) ?>
            </div>

        </div>
    </div>

    <div class="form-group" style="margin-top: 10px">
        <?= Html::submitButton('Сохранить данные', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
