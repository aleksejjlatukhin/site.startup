<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SendEmailForm */
/* @var $form ActiveForm */

$this->title = 'Востановление пароля'
?>
<div class="site-sendEmail">

    <div class="row" style="display: flex">
        <div class="col-md-4" style="margin: auto">

            <h3 style="text-align: center;"><?=$this->title?></h3>

            <p style="font-weight: 700;text-align: center;">Укажите адрес электронной почты  <br><span style="font-weight: 400;font-style: italic">(который был указан при регистрации)</span></p>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email', ['options' => ['autocomplete' => 'off']])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>



</div><!-- site-sendEmail -->
