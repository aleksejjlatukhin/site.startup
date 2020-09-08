<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SegmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="segment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'type_of_interaction_between_subjects') ?>

    <?php // echo $form->field($model, 'field_of_activity') ?>

    <?php // echo $form->field($model, 'sort_of_activity') ?>

    <?php // echo $form->field($model, 'specialization_of_activity') ?>

    <?php // echo $form->field($model, 'age_from') ?>

    <?php // echo $form->field($model, 'age_to') ?>

    <?php // echo $form->field($model, 'gender_consumer') ?>

    <?php // echo $form->field($model, 'education_of_consumer') ?>

    <?php // echo $form->field($model, 'income_from') ?>

    <?php // echo $form->field($model, 'income_to') ?>

    <?php // echo $form->field($model, 'quantity_from') ?>

    <?php // echo $form->field($model, 'quantity_to') ?>

    <?php // echo $form->field($model, 'market_volume') ?>

    <?php // echo $form->field($model, 'company_products') ?>

    <?php // echo $form->field($model, 'company_partner') ?>

    <?php // echo $form->field($model, 'add_info') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'creat_date') ?>

    <?php // echo $form->field($model, 'plan_gps') ?>

    <?php // echo $form->field($model, 'fact_gps') ?>

    <?php // echo $form->field($model, 'plan_ps') ?>

    <?php // echo $form->field($model, 'fact_ps') ?>

    <?php // echo $form->field($model, 'plan_dev_gcp') ?>

    <?php // echo $form->field($model, 'fact_dev_gcp') ?>

    <?php // echo $form->field($model, 'plan_gcp') ?>

    <?php // echo $form->field($model, 'fact_gcp') ?>

    <?php // echo $form->field($model, 'plan_dev_gmvp') ?>

    <?php // echo $form->field($model, 'fact_dev_gmvp') ?>

    <?php // echo $form->field($model, 'plan_gmvp') ?>

    <?php // echo $form->field($model, 'fact_gmvp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
