<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ResetPasswordForm */
/* @var $form ActiveForm */
$this->title = 'Востановление пароля';

?>
<div class="site-resetPassword">

    <div class="row" style="display: flex">
        <div class="col-md-4" style="margin: auto">

            <h3 style="text-align: center;">Введите новый пароль:</h3>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'password')->label(false)->passwordInput() ?>

            <div class="form-group" style="display:flex;">
                <div style="margin: auto;">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div><!-- site-resetPassword -->
