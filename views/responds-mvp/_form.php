<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsMvp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="responds-mvp-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-8">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'info_respond')->textarea(['rows' => 1]) ?>

            <?= $form->field($model, 'email')->textInput() ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
