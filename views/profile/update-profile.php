<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Редактирование данных пользователя';
//$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('menu_user', [
    'user' => $user,
]) ?>

<div class="profile-update-profile">

    <div class="row" style="display: flex">
        <div class="col-md-7">

            <div style="margin-bottom: 20px;">

                <h3><?= Html::encode($this->title) ?></h3>

                <p>Пожалуйста заполните необходимые поля:</p>

            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'form-update-profile',
            ]); ?>

            <?= $form->field($model, 'second_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'update-profile-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

    <script>

        $( ".catalog" ).dcAccordion({speed:300});

    </script>

</div>
