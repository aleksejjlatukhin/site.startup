<?php

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php if (User::isUserSimple(Yii::$app->user->identity['username'])) :?>


    <?php $form = ActiveForm::begin([
        'action' => "/responds-confirm/update?id=$model->id",
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
                'autocomplete' => 'off'
            ]) ?>

        </div>

        <div class="col-md-6">

            <?= $form->field($model, 'email', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                'type' => 'email',
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'ivanov@gmail.com',
                'autocomplete' => 'off'
            ]); ?>

        </div>

        <div class="col-md-12">

            <?= $form->field($model, 'info_respond', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 1,
                'required' => true,
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Кто? Откуда? Чем занимается?',
            ]); ?>

            <?= $form->field($model, 'place_interview', ['template' => '<div style="padding-left: 15px;">{label}</div><div>{input}</div>'])->textInput([
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Организация, адрес',
                'autocomplete' => 'off'
            ]); ?>

        </div>

        <div class="col-xs-12 col-sm-6 col-md-4">

            <?= '<label class="control-label" style="padding-left: 15px;">Плановая дата интервью</label>';?>
            <?= \kartik\date\DatePicker::widget([
                'type' => 2,
                'removeButton' => false,
                'name' => 'UpdateRespondConfirmForm[date_plan]',
                'value' => $model->date_plan == null ? date('d.m.Y') : date('d.m.Y', $model->date_plan),
                'readonly' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ],
                'options' => [
                    'id' => 'datePlan',
                    'class' => 'style_form_field_respond form-control'
                ]
            ]);?>

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
            <div class="bolder">Место проведения интервью</div>
            <?= $model->place_interview; ?>
            <div class="bolder">Плановая дата интервью:</div>
            <?php if ($model->date_plan != null) : ?>
                <?= date('d.m.yy', $model->date_plan); ?>
            <?php endif; ?>
        </div>
    </div>


<?php endif; ?>
