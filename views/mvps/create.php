<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="form-create-mvp">

    <?php
    $form = ActiveForm::begin([
        'id' => 'hypothesisCreateForm',
        'action' => Url::to(['/mvps/create', 'id' => $confirmGcp->id]),
        'options' => ['class' => 'g-py-15 hypothesisCreateForm'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);
    ?>

    <div class="row" style="color: #4F4F4F;">

        <div class="col-md-12" style="margin-top: 10px; padding-left: 20px; padding-right: 20px;">
            Minimum Viable Product(MVP) — минимально жизнеспособный продукт, концепция минимализма программной комплектации выводимого на рынок устройства.
            Минимально жизнеспособный продукт - продукт, обладающий минимальными, но достаточными для удовлетворения первых потребителей функциями.
            Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.
        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <?= $form->field($model, 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label('Описание минимально жизнеспособного продукта')->textarea([
                'rows' => 2,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Примеры: презентация, макет, программное обеспечение, опытный образец, видео и т.д.',
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
