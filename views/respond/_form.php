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


            <?= $form->field($model, 'date_plan', [
                'template' => '<div style="padding-top: 5px;">{label}</div><div class="row"><div class="col-md-2">{input}</div></div>'
            ])->label('Запланированная дата интервью')->widget(\yii\jui\DatePicker::class, [
                'dateFormat' => 'dd.MM.yyyy',
                //'inline' => true,
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.MM.yyyy',
                ],
                //'language' => 'ru',
                'options' => [
                    'class' => 'form-control input-md',
                    'readOnly'=>'readOnly'
                ],
            ]) ?>


            <?= $form->field($model, 'place_interview')->textInput(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
