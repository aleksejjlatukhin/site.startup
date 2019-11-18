<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'update_at') ?>

    <?= $form->field($model, 'project_fullname') ?>

    <?php // echo $form->field($model, 'project_name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'rid') ?>

    <?php // echo $form->field($model, 'patent_number') ?>

    <?php // echo $form->field($model, 'patent_date') ?>

    <?php // echo $form->field($model, 'patent_name') ?>

    <?php // echo $form->field($model, 'core_rid') ?>

    <?php // echo $form->field($model, 'technology') ?>

    <?php // echo $form->field($model, 'layout_technology') ?>

    <?php // echo $form->field($model, 'register_name') ?>

    <?php // echo $form->field($model, 'register_date') ?>

    <?php // echo $form->field($model, 'site') ?>

    <?php // echo $form->field($model, 'invest_name') ?>

    <?php // echo $form->field($model, 'invest_date') ?>

    <?php // echo $form->field($model, 'invest_amount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
