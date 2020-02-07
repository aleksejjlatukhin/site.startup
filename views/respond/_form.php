<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Respond */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="respond-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-8">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'info_respond')->textarea(['rows' => 1]) ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'date_plan')->label('Запланированная дата интервью')->widget(\yii\jui\DatePicker::class, [
                'dateFormat' => 'yyyy-MM-dd',
                //'inline' => true,
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-MM-dd',
                ],
                //'language' => 'ru',
            ]) ?>

            <?= $form->field($model, 'place_interview')->textInput(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
