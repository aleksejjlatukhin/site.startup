<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Изменение пароля';
//$this->params['breadcrumbs'][] = $this->title;

?>



<div class="profile-change-password">

    <div class="row" style="display: flex">
        <div class="col-md-4">

            <div style="margin-bottom: 20px;">

                <h3><?= Html::encode($this->title) ?></h3>

                <p>Пожалуйста, заполните необходимые поля:</p>

            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'form-update-profile',
            ]); ?>

            <?= $form->field($model, 'currentPassword')->passwordInput() ?>

            <?= $form->field($model, 'newPassword')->passwordInput() ?>

            <?= $form->field($model, 'newPasswordRepeat')->passwordInput() ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'change-password-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>

