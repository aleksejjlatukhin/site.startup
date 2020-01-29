<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\BusinessModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= '<div style="margin-bottom: 10px;"><span style="font-weight: 700;">' . Html::a('Потребительский сегмент: ', Url::to(['segment/view', 'id' => $segment->id])) . '</span>' . mb_strtolower($segment->name) . '</div>' ?>

    <?= '<div style="margin-bottom: 10px;"><span style="font-weight: 700;">' . Html::a('Ценностное предложение: ', Url::to(['gcp/view', 'id' => $gcp->id])) . '</span>' . mb_strtolower($gcp->description) . '</div>' ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_of_activity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relations')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partners')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'distribution_of_sales')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'resources')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'revenue')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
