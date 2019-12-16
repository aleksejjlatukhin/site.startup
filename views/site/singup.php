<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация пользователя';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста заполните необходимые поля:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                    'id' => 'form-signup',
            ]); ?>

            <?php if (!empty($_SESSION['singup_fio'])) : ?>

                <?= $form->field($model, 'fio') ?>

                <?= $form->field($model, 'telephone') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

            <?php else : ?>

                <?= $form->field($model, 'fio')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'telephone') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

            <?php endif; ?>

            <div class="form-group">
                    <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

