<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterview */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="desc-interview-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?/*= $form->field($model, 'date_fact')->label('Фактическая дата интервью')->widget(\yii\jui\DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-MM-dd',
        ],
        //'language' => 'ru',
    ]) */?>

    <div class="row">
        <div class="col-md-8">

            <?= $form->field($model, 'description')->textarea(['rows' => 2])->label('') ?>

            <div class="container row">
                <div class="pull-left">

                    <p class="feed"><b>Файл (доступные расширения: png, jpg, odt, xlsx, txt, doc, docx, pdf)</b></p>
                    <?php if (!empty($model->interview_file)) : ?>
                        <p><?= $form->field($model, 'loadFile', ['options' => ['class' => 'feed-exp']])->fileInput()->label('') ?></p>
                    <?php endif;?>

                    <?php if (empty($model->interview_file)) : ?>
                        <p><?= $form->field($model, 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput()->label('') ?></p>
                    <?php endif;?>

                    <p>
                        <?php
                        if (!empty($model->interview_file))
                        {
                            echo Html::a($model->interview_file, ['download', 'id' => $model->id], ['class' => 'btn btn-default feedback']) .
                                ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-file', 'id' => $model->id], [
                                    'onclick'=>
                                        "$.ajax({
                                                 type:'POST',
                                                 cache: false,
                                                 url: '".Url::to(['delete-file', 'id' => $model->id])."',
                                                 success  : function(response) {
                                                     $('.link-del').html(response);
                                                     $('.feedback').remove();
                                                 }
                                              });
                                         return false;
                                         $('.feedback').remove();
                                         ",
                                    'class' => "link-del",
                                ]);
                        }
                        ?>
                    </p>

                </div>
            </div>

            <?= $form->field($model, 'result')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'status', ['template' => '<div class="col-md-12" style="padding-left: 0">{label}</div><div class="col-md-12" style="padding-left: 0; margin-bottom: 10px;"><div class="col-md-2" style="padding-left: 0">{input}</div></div>'])->dropDownList([ '0' => 'Нет', '1' => 'Да', ]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
