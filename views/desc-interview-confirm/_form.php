<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterviewConfirm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="desc-interview-confirm-form">

    <?php $form = ActiveForm::begin(); ?>

    <div  style="margin-top: 30px;margin-bottom: 30px;">

        <h4>Выберите один из вариантов:</h4>

        <?= $form->field($model, 'status')
        ->radioList(
            [0 => 'Проблемы не существует или она малозначимая', 1 => 'Значимая проблема'],
            [
                'item' => function($index, $label, $name, $checked, $value) {

                    $return = '<label style="width: 100%;">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                    $return .= '<i></i>';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label>';

                    return $return;
                }
            ]
        )
        ->label(false);?>
    </div>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
