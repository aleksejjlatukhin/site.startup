<?php

use app\models\RequirementWishList;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактировать запроса B2B компании';
$this->registerCssFile('@web/css/wish-list-style.css');

/**
 * @var RequirementWishList $model
 */

?>

<div class="container-fluid">
    <div class="row hi-line-page">
        <div class="col-md-7" style="margin-top: 35px; padding-left: 25px;">
            <?= Html::a($this->title . Html::img('/images/icons/icon_report_next.png'), ['#'],[
                'class' => 'link_to_instruction_page open_modal_instruction_page',
                'title' => 'Инструкция', 'onclick' => 'return false'
            ]) ?>
        </div>
        <div class="col-md-5"></div>
    </div>
</div>

<div class="container-fluid mt-15 pl-20 pr-20">
    <div class="row pl-5">
        <div class="col-md-12">
            <span class="bolder">Размер предприятия по количеству персонала:</span>
            <span><?= $model->wishList->getSizeName() ?></span>
        </div>
        <div class="col-md-12">
            <span class="bolder">Локация предприятия (город):</span>
            <span><?= $model->wishList->location->getName() ?></span>
        </div>
        <div class="col-md-12">
            <span class="bolder">Тип предприятия:</span>
            <span><?= $model->wishList->getTypeCompanyName() ?></span>
        </div>
        <div class="col-md-12">
            <span class="bolder">Тип производства:</span>
            <span><?= $model->wishList->getTypeProductionName() ?></span>
        </div>
        <div class="col-md-12">
            <span class="bolder">Дополнительная информация:</span>
            <span><?= $model->wishList->getAddInfo() ?></span>
        </div>
    </div>
</div>

<div class="container-fluid mt-15 pl-20 pr-20">

    <?php $form = ActiveForm::begin([
        'id' => 'requirementUpdateForm',
        'options' => ['class' => 'g-py-15 requirementUpdateForm'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <div class="row">

        <div class="col-xs-12">

            <?= $form->field($model, 'requirement', ['template' => '<div class="pl-5">{label}</div><div>{input}</div>'])->textarea([
                'rows' => 2,
                'maxlength' => true,
                'required' => true,
                'placeholder' => 'Введите запрос, который соответствует запросам B2B компаний выбранного сегмента',
                'class' => 'style_form_field_respond form-control',
            ]) ?>

        </div>

        <div class="col-xs-12 bolder mb-5 pl-20">Причины запроса:</div>

        <div class="container-requirementReasons">
            <div class="row container-fluid item-requirementReasons item-requirementReasons-<?= $model->getId() ?>">

                <?php foreach ($model->reasons as $i => $reason): ?>

                    <div class="rowRequirementReasons row-requirementReasons-<?= $model->getId() . '_' . $i ?>">

                        <div class="col-xs-12 field-EXR">

                            <?= $form->field($model, "reasons[$i][reason]", ['template' => '{input}'])->textarea([
                                'rows' => 1,
                                'maxlength' => true,
                                'required' => true,
                                'placeholder' => 'Опишите причину',
                                'id' => '_reasons_reason-' . $i,
                                'class' => 'style_form_field_respond form-control',
                            ]) ?>

                        </div>

                        <?php if ($i !== 0) : ?>

                            <div class="col-xs-12" style="margin-bottom: 15px;">

                                <?= Html::button('Удалить запрос', [
                                    'id' => 'remove-requirementReasons-' . $model->getId() . '_' . $i . '-' . $reason->getId(),
                                    'class' => "remove-requirementReasons btn btn-default",
                                    'style' => [
                                        'display' => 'flex',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'width' => '180px',
                                        'height' => '40px',
                                        'font-size' => '16px',
                                        'border-radius' => '8px',
                                        'text-transform' => 'uppercase',
                                        'font-weight' => '700',
                                        'padding-top' => '9px'
                                    ]
                                ]) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>
        </div>

        <div class="col-xs-12">
            <?= Html::button('Добавить причину', [
                'id' => 'add_requirementReasons-' . $model->getId(),
                'class' => "btn btn-success add_requirementReasons",
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'color' => '#FFFFFF',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '180px',
                    'height' => '40px',
                    'font-size' => '16px',
                    'border-radius' => '8px',
                    'text-transform' => 'uppercase',
                    'font-weight' => '700',
                    'padding-top' => '9px'
                ],
            ]) ?>
        </div>

    </div>

    <div class="form-group row container-fluid" style="display: flex; justify-content: center; margin-top: 20px;">
        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-default pull-right',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'margin-bottom' => '15px',
                'background' => '#7F9FC5',
                'width' => '180px',
                'height' => '40px',
                'border-radius' => '8px',
                'text-transform' => 'uppercase',
                'font-size' => '16px',
                'color' => '#FFFFFF',
                'font-weight' => '700',
                'padding-top' => '9px'
            ]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="formRequirementReasons" style="display: none;">

    <?php
    $form = ActiveForm::begin([
        'id' => 'formRequirementReasons'
    ]); ?>

    <div class="formRequirementReasons_inputs">

        <div class="row container-fluid rowRequirementReasons rowRequirementReasons-" style="margin-bottom: 15px;">

            <div class="col-xs-12 field-EXR">

                <?= $form->field($model, "reasons[0][reason]", ['template' => '{input}'])->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'placeholder' => 'Опишите причину',
                    'id' => '_reasons_reason-',
                    'class' => 'style_form_field_respond form-control',
                ]) ?>

            </div>

            <div class="col-xs-12">

                <?= Html::button('Удалить причину', [
                    'id' => 'remove-requirementReasons-',
                    'class' => "remove-requirementReasons btn btn-default",
                    'style' => [
                        'display' => 'flex',
                        'align-items' => 'center',
                        'justify-content' => 'center',
                        'width' => '180px',
                        'height' => '40px',
                        'font-size' => '16px',
                        'border-radius' => '8px',
                        'text-transform' => 'uppercase',
                        'font-weight' => '700',
                        'padding-top' => '9px'
                    ]
                ]) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/wish_list_add_requirement.js'); ?>