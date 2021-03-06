<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

?>


<div class="col-md-12 form_update_question" style="padding: 0;">

    <? $form = ActiveForm::begin([
        'id' => 'updateQuestionForm',
        'action' => Url::to(['/questions/update', 'stage' => $model->confirm->stage, 'id' => $model->id]),
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]);?>

    <div class="col-md-12">

        <?= $form->field($model, 'title', ['template' => '{input}'])
            ->textInput([
                'maxlength' => true,
                'required' => true,
                'placeholder' => 'Отредактируйте вопрос',
                'id' => 'update_text_question_confirm',
                'class' => 'style_form_field_respond',
                'autocomplete' => 'off'])
            ->label(false);
        ?>

    </div>

    <div class="col-xs-12" style="display: flex; justify-content: flex-end;">

        <?= Html::a('Отмена', ['/questions/get-query-questions', 'stage' => $model->confirm->stage, 'id' => $model->confirm->id],[
            'class' => 'btn btn-lg btn-default col-xs-6 col-sm-2 col-lg-1 submit_update_question_cancel',
            'style' => [
                'margin-bottom' => '15px',
                'margin-right' => '5px',
                'height' => '40px',
                'padding-top' => '7px',
                'padding-bottom' => '4px',
                'border-radius' => '8px',
            ]
        ]); ?>

        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-lg btn-success col-xs-6 col-sm-2 col-lg-1',
            'style' => [
                'margin-bottom' => '15px',
                'margin-left' => '5px',
                'background' => '#52BE7F',
                'height' => '40px',
                'padding-top' => '4px',
                'padding-bottom' => '4px',
                'border-radius' => '8px',
            ]
        ]); ?>

    </div>

    <? ActiveForm::end(); ?>

</div>
