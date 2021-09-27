<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\expert\models\form\FormCreateCommunicationResponse;
use app\models\CommunicationTypes;
use kartik\select2\Select2;
use yii\helpers\Html;

?>


<style>
    .select2-container--krajee .select2-selection {
        font-size: 16px;
        height: 40px;
        padding-left: 15px;
        padding-top: 8px;
        padding-bottom: 15px;
        border-radius: 12px;
        border: 1px solid #828282;
    }
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        height: 39px;
    }
</style>


<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'formCreateResponseCommunication',
        'action' => Url::to([
            '/expert/communications/send',
            'adressee_id' => $communication->sender_id,
            'project_id' => $communication->project_id,
            'type' => CommunicationTypes::EXPERT_ANSWERS_QUESTION_ABOUT_READINESS_CONDUCT_EXPERTISE,
            'triggered_communication_id' => $communication->id
        ]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

        <div class="col-md-12">
            <?= $form->field($model, 'answer', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>',
            ])->widget(Select2::class, [
                'data' => FormCreateCommunicationResponse::getAnswers(),
                'options' => ['id' => 'communication_response_answer'],
                'disabled' => false,  //Сделать поле неактивным
                'hideSearch' => true, //Скрытие поиска
            ]);
            ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'comment', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
            ])->textInput([
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]) ?>
        </div>

        <div class="col-md-12">

            <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-success pull-right',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '100px',
                    'font-size' => '16px',
                    'border-radius' => '8px',
                    'margin-left' => '20px',
                    'margin-bottom' => '10px'
                ]
            ]); ?>

            <?= Html::button('Отмена', [
                'id' => 'cancel_create_response_communication-'.$communication->project_id,
                'class' => 'btn btn-default pull-right cancel-create-response-communication',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'width' => '100px',
                    'font-size' => '16px',
                    'border-radius' => '8px',
                    'margin-bottom' => '10px'
                ]
            ]); ?>

        </div>

    <?php ActiveForm::end(); ?>

</div>