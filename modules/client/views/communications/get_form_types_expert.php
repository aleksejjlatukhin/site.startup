<?php

use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\ExpertType;
use yii\helpers\Url;
use app\models\CommunicationTypes;
use yii\helpers\Html;

?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'form_types_expert',
        'action' => Url::to(['/client/communications/send',
            'adressee_id' => $communicationExpert->sender_id,
            'project_id' => $communicationExpert->project_id,
            'type' => CommunicationTypes::MAIN_ADMIN_APPOINTS_EXPERT_PROJECT,
            'triggered_communication_id' => $communicationExpert->id
        ]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

        <div class="col-md-12">
            <?= $form->field($formExpertTypes, 'expert_types', [
                'template' => '<div>{input}</div>',
            ])->widget(Select2::class, [
                'data' => ExpertType::getListTypes(null, $communicationExpert->communicationResponse->expert_types),
                'options' => [
                    'id' => 'communication_response_expert_types',
                    'multiple' => true,
                    'required' => true
                ],
                'toggleAllSettings' => [
                    'selectLabel' => '<i class="fas fa-check-circle"></i> Выбрать все',
                    'unselectLabel' => '<i class="fas fa-times-circle"></i> Убрать все',
                    'selectOptions' => ['class' => 'text-success'],
                    'unselectOptions' => ['class' => 'text-danger'],
                ]
            ]); ?>
        </div>

        <div class="col-md-12">
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

    <?php ActiveForm::end(); ?>

</div>
