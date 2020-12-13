<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

?>


<?php $form = ActiveForm::begin([
    'action' => "/desc-interview/create?id=$respond->id",
    'id' => 'formCreateDescInterview',
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
    'errorCssClass' => 'u-has-error-v1',
    'successCssClass' => 'u-has-success-v1-1',
]); ?>



<div class="row" style="margin-bottom: 15px;">

    <div class="col-md-12">

        <?= $form->field($model, 'description', ['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
            'rows' => 1,
            'required' => true,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => 'Ответы на вопросы, инсайды, ценная информация',
        ]); ?>

    </div>

    <div class="col-md-12">

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

    <div class="col-md-12" style="margin-top: -10px;">

        <?= $form->field($model, 'result',['template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>'])->textarea([
            'rows' => 1,
            'maxlength' => true,
            'required' => true,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => 'Опишите краткий вывод по интервью',
        ]); ?>

    </div>

    <div class="col-xs-12 col-md-6">


        <?php
        $selection_list = [ '0' => 'Респондент не является представителем сегмента', '1' => 'Респондент является представителем сегмента', ];
        ?>

        <?= $form->field($model, 'status', [
            'template' => '<div style="padding-left: 5px;">{label}</div><div>{input}</div>',
        ])->label('Этот респондент является представителем сегмента?')->widget(Select2::class, [
            'data' => $selection_list,
            'options' => ['id' => 'descInterview_status'],
            'disabled' => false,  //Сделать поле неактивным
            'hideSearch' => true, //Скрытие поиска
        ]);
        ?>


    </div>

    <div class="form-group col-xs-12 col-md-6">
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
                'margin-top' => '28px'
            ]
        ]) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>