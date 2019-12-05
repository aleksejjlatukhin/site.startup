<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackExpert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="feedback-expert-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

    <div class="container row">
        <div class="pull-left">

            <p class="feed"><b>Отзыв(файл)</b></p>
            <?php if (!empty($model->feedback_file)) : ?>
                <p><?= $form->field($model, 'loadFile', ['options' => ['class' => 'feed-exp']])->fileInput()->label('') ?></p>
            <?php endif;?>

            <?php if (empty($model->feedback_file)) : ?>
                <p><?= $form->field($model, 'loadFile', ['options' => ['class' => 'feed-exp active']])->fileInput()->label('') ?></p>
            <?php endif;?>

            <p>
                <?php
                if (!empty($model->feedback_file))
                {
                    echo Html::a($model->feedback_file, ['download', 'filename' => $model->feedback_file], ['class' => 'btn btn-default feedback']) .
                        ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-file', 'filename' => $model->feedback_file], [
                            'onclick'=>
                                "$.ajax({
                                         type:'POST',
                                         cache: false,
                                         url: '".Url::to(['delete-file', 'filename' => $model->feedback_file])."',
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

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
