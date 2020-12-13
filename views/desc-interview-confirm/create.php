<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

?>


<?php $form = ActiveForm::begin([
    'action' => "/desc-interview-confirm/create?id=$respond->id",
    'id' => 'formCreateDescInterview',
    'options' => ['class' => 'g-py-15'],
    'errorCssClass' => 'u-has-error-v1',
    'successCssClass' => 'u-has-success-v1-1',
]); ?>


<?php
foreach ($respond->answers as $index => $answer) :
    ?>

    <?= $form->field($answer, "[$index]answer", ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->label($answer->question->title)
    ->textarea([
        'row' => 2,
        'maxlength' => true,
        'required' => true,
        'class' => 'style_form_field_respond form-control',
    ]);
    ?>

<?php
endforeach;
?>


<div class="row">
    <div class="col-md-12">

        <?php
        $selection_list = [ '0' => 'Проблемы не существует или она малозначимая', '1' => 'Проблема значимая', ];
        ?>

        <?= $form->field($model, 'status', [
            'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
        ])->label('По результатам опроса сделайте вывод о текущей проблеме')->widget(Select2::class, [
            'data' => $selection_list,
            'options' => ['id' => 'descInterview_status'],
            'disabled' => false,  //Сделать поле неактивным
            'hideSearch' => true, //Скрытие поиска
        ]);
        ?>

    </div>
</div>

<div class="row">
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
