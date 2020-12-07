<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
'id' => 'new_respond_form',
'action' => "/respond/create?id=$interview->id",
'options' => ['class' => 'g-py-15'],
'errorCssClass' => 'u-has-error-v1',
'successCssClass' => 'u-has-success-v1-1',
]); ?>

<div class="row">

    <div class="col-md-12">

        <?= $form->field($model, 'name', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
            'maxlength' => true,
            'required' => true,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => 'Иванов Иван Иванович',
        ]) ?>

    </div>

    <div class="form-group col-md-12">

        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-success pull-right',
            'id' => 'save_respond',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#52BE7F',
                'width' => '140px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ]

        ]) ?>

    </div>

</div>

<?php ActiveForm::end(); ?>
