<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Gcp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gcp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= '<b>1. Формулировка перспективного продукта (товара / услуги):</b> ' . $form->field($model, 'good')->textInput(['maxlength' => true])->label(false) ?>

    <p><b>2. Для какого сегмента предназначено: <?= Html::a($segment->name, ['segment/view', 'id' => $segment->id]) ?></b></p>

    <p><b>3. Для удовлетворения следующей потребности сегмента: <?= Html::a($generationProblem->title, ['generation-problem/view', 'id' => $generationProblem->id]) ?></b></p>

    <?= '<b>4. Какую выгоду дает использование данного продукта
                потребителю – представителю сегмента. <br>Все выгоды
                формулируются по трем критериям: временной фактор;
                экономический фактор; качественный фактор. <br>Первые два
                параметра выгоды должны быть исчисляемыми. Параметр
                качества(исчисляемый /лаконичный текст):</b> ' . $form->field($model, 'benefit')->textInput(['maxlength' => true])->label(false) ?>

    <?= '<b>5. По сравнению с каким продуктом заявлена выгода (с чем
                сравнивается). <br>Указываются параметры аналога, с которыми
                сравниваются параметры нового продукта:</b> ' . $form->field($model, 'contrast')->textInput(['maxlength' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
