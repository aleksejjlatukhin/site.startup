<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="form-create-business_model">

    <?php
    $form = ActiveForm::begin([
        'id' => 'hypothesisCreateForm',
        'action' => Url::to(['/business-model/create', 'id' => $confirmMvp->id]),
        'options' => ['class' => 'g-py-15 hypothesisCreateForm'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>

    <div class="row">

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'partners', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'resources', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'relations', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'distribution_of_sales', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'cost', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'revenue', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]);
            ?>

        </div>

    </div>


    <div class="form-group row container-fluid">
        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-success pull-right',
            'style' => [
                'color' => '#FFFFFF',
                'background' => '#52BE7F',
                'padding' => '0 7px',
                'width' => '140px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
