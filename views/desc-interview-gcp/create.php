<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

?>


<?php $form = ActiveForm::begin([
    'id' => 'formCreateDescInterview',
    'action' => "/desc-interview-gcp/create?id=$respond->id",
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

    <?= $form->field($answer, "[$index]question_id")->label(false)->hiddenInput(); ?>

<?php
endforeach;
?>


<div class="row">

    <div class="col-md-12" style="margin-top: 15px;">

        <p style="padding-left: 5px;"><b>Приложить файл</b> <span style="color: #BDBDBD; padding-left: 20px;">png, jpg, jpeg, pdf, txt, doc, docx, xls</span></p>

        <div style="display:flex; margin-top: -5px;">

            <?= $form->field($model, 'loadFile')
                ->fileInput([
                    'id' => 'descInterviewCreateFile', 'class' => 'sr-only'
                ])->label('Выберите файл',[
                    'class'=>'btn btn-default',
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'color' => '#FFFFFF',
                        'justify-content' => 'center',
                        'background' => '#707F99',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                    ],
                ]); ?>

            <div class='title_file' style="padding-left: 20px; padding-top: 5px;">Файл не выбран</div>

        </div>

    </div>

    <div class="col-md-12">

        <?php
        $selection_list = ['1' => 'Предложение привлекательно', '0' => 'Предложение не интересно'];
        ?>

        <?= $form->field($model, 'status', [
            'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
        ])->label('По результатам интервью сделайте вывод о текущем ценностном предложении')->widget(Select2::class, [
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
