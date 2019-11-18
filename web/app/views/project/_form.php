<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'project_fullname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'rid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patent_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patent_date')->textInput() ?>

    <?= $form->field($model, 'patent_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'core_rid')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'technology')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'layout_technology')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'register_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'register_date')->textInput() ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invest_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invest_date')->textInput() ?>

    <?= $form->field($model, 'invest_amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
