<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="confirm-problem-form">

    <?php $form = ActiveForm::begin(); ?>

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
