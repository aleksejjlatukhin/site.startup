<?php

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>


<?php if (User::isUserSimple(Yii::$app->user->identity['username'])) :?>


    <?php $form = ActiveForm::begin([
        'action' => "/responds-gcp/update?id=$model->id",
        'id' => 'formUpdateRespond',
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'name', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Иванов Иван Иванович',
            ]) ?>

        </div>

        <div class="col-md-6">

            <?= $form->field($model, 'email', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                'type' => 'email',
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'ivanov@gmail.com',
            ]); ?>

        </div>

        <div class="col-md-12">

            <?= $form->field($model, 'info_respond', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 2,
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Кто? Откуда? Чем занимается?',
            ]); ?>

        </div>


        <div class="form-group col-md-12">
            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success pull-right',
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


<?php else : ?>


    <div class="row" style="margin-top: -20px;">
        <div class="col-md-8">
            <div style="font-size: 24px;">
                <?= $model->name; ?>
            </div>
            <?= $model->info_respond; ?>
        </div>
        <div class="col-md-4" style="padding-top: 5px;">
            <div class="bolder">E-mail:</div>
            <?= $model->email; ?>
        </div>
    </div>


<?php endif; ?>
