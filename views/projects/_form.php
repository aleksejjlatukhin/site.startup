<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projects-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?//= $form->field($model, 'user_id')->textInput() ?>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'update_at')->textInput() ?>

    <?= $form->field($model, 'project_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'rid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'core_rid')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'patent_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patent_date')->widget(\yii\jui\DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-MM-dd',
        ],
        //'language' => 'ru',
    ]) ?>

    <?= $form->field($model, 'patent_name')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="panel-body">
                <div class="panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h4>Целевые сегменты</h4>
                    </div>
                </div>

                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $modelsConcept[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'description',
                    ],
                ]); ?>

                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($modelsConcept as $i => $modelsConcept): ?>

                        <div class="item panel-body"><!-- widgetBody -->
                            <?php
                            // necessary for update action.
                            if (! $modelsConcept->isNewRecord) {
                                echo Html::activeHiddenInput($modelsConcept, "[{$i}]id");
                            }
                            ?>

                            <div class="row">
                                <div class="pull-right">
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>

                                <?= $form->field($modelsConcept, "[{$i}]name", [
                                    'template' => '<div class="col-md-3">{label}</div><div class="col-md-8">{input}</div><div class="col-md-1">{error}</div>'
                                ])->textInput(['maxlength' => true])?>

                            </div><!-- .row -->

                        </div>
                    <?php endforeach; ?>

                </div>

                <p><button type="button" class="add-item btn btn-primary btn-md">Добавить сегмент </i></button></p>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>


        <div class="row">
            <div class="panel-body">
                <div class="panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h4>Команда проекта</h4>
                    </div>
                </div>

                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_inner', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-authors', // required: css class selector
                    'widgetItem' => '.item-authors', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-authors', // css class
                    'deleteButton' => '.remove-authors', // css class
                    'model' => $modelsAuthors[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'fio',
                        'role',
                        'experience',
                    ],
                ]); ?>

                <div class="container-authors"><!-- widgetContainer -->

                    <?php foreach ($modelsAuthors as $i => $modelsAuthors): ?>

                        <div class="item-authors panel-body"><!-- widgetBody -->
                            <?php
                            // necessary for update action.
                            if (! $modelsAuthors->isNewRecord) {
                                echo Html::activeHiddenInput($modelsAuthors, "[{$i}]id");
                            }
                            ?>

                            <div class="row">
                                <div class="pull-right">
                                    <button type="button" class="remove-authors btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>

                                <?= $form->field($modelsAuthors, "[{$i}]fio")->textInput(['maxlength' => true]) ?>
                                <?= $form->field($modelsAuthors, "[{$i}]role")->textInput(['maxlength' => true]) ?>
                                <?= $form->field($modelsAuthors, "[{$i}]experience")->textarea(['rows' => 3]) ?>
                            </div><!-- .row -->

                        </div>
                    <?php endforeach; ?>
                </div>

                <p><button type="button" class="add-authors btn btn-primary btn-md">Добавить автора</i></button></p>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>

    <hr>
    <?= $form->field($model, 'technology')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'layout_technology')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'register_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'register_date')->widget(\yii\jui\DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-MM-dd'
        ],
        //'language' => 'ru',
    ]) ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invest_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invest_date')->widget(\yii\jui\DatePicker::class, [
        'dateFormat' => 'yyyy-MM-dd',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-MM-dd'
        ],
        //'language' => 'ru',
    ]) ?>

    <?= $form->field($model, 'invest_amount')->textInput() ?>

    <div class="container row">
        <div class="pull-left">
            <p><?= $form->field($model, 'present_files[]')->label('Презентационные материалы')->fileInput(['multiple' => true,]) ?>

                <?php if (!empty($model->files)) : ?>
                    <?php foreach ($model->getFiles($model) as $file) : ?>
<!--                        <a href="--><?//= \yii\helpers\Url::to(['/../web/upload/files/' . $file])  ; ?><!--">-->
                            <?= $file . ' | ';?>
<!--                        </a>-->
                    <?php endforeach; ?>
                <?php endif;?>

            </p>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
