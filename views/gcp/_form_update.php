<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Gcp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gcp-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-8">

    <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
