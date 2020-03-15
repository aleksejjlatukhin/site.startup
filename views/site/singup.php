<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация пользователя';
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-signup">

    <div class="row" style="display: flex">
        <div class="col-md-5" style="margin: auto">

            <div style="text-align: center;margin-bottom: 20px;">

                <h2><?= Html::encode($this->title) ?></h2>

                <p>Пожалуйста, заполните необходимые поля:</p>

            </div>

            <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
            ]); ?>

                <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group" style="display:flex;">
                <div style="margin: auto;">
                    <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

