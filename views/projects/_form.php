<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="projects-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Описание проекта</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'project_name', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-5">{input}</div><div class="col-md-12">{error}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'project_fullname', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'description', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Результат интеллектуальной деятельности</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'rid', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'core_rid', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Данные о патенте</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'patent_name', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <?= $form->field($model, 'patent_number', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'patent_date', [
                    'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-2">{input}</div><div class="col-md-6"></div>'
                ])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'dd.MM.yyyy',
                    //'inline' => true,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.MM.yyyy',
                    ],
                    //'language' => 'ru',
                    'options' => [
                        'class' => 'form-control input-md',
                        'readOnly'=>'readOnly'
                    ],
                ]) ?>
            </div>

        </div>
    </div>


        <div class="row">
            <div class="panel-body col-sm-8">
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
                        'name',
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
                                    'template' => '<div class="col-md-6">{input}</div><div class="col-md-11">{error}</div>'
                                ])->textInput(['maxlength' => true])?>

                            </div><!-- .row -->

                        </div>

                    <?php endforeach; ?>

                </div>

                <p class="col-sm-3"><button type="button" class="add-item btn btn-primary btn-md">Добавить сегмент </i></button></p>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>


        <div class="row">
            <div class="panel-body col-sm-8">
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

                                <?= $form->field($modelsAuthors, "[{$i}]fio", [
                                    'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                                ])->textInput(['maxlength' => true]) ?>

                                <?= $form->field($modelsAuthors, "[{$i}]role", [
                                    'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                                ])->textInput(['maxlength' => true]) ?>

                                <?= $form->field($modelsAuthors, "[{$i}]experience", [
                                    'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                                ])->textarea(['rows' => 1]) ?>

                            </div><!-- .row -->

                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="col-sm-3"><button type="button" class="add-authors btn btn-primary btn-md">Добавить автора</i></button></p>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>



    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Сведения о технологии</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'technology', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'layout_technology', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea(['rows' => 2]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Регистрация юридического лица</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'register_name', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'register_date', [
                    'template' => '<div class="col-md-3" style="padding-top: 5px;">{label}</div><div class="col-md-2">{input}</div><div class="col-md-7"></div>'
                ])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'dd.MM.yyyy',
                    //'inline' => true,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.MM.yyyy',
                    ],
                    //'language' => 'ru',
                    'options' => [
                        'class' => 'form-control input-md',
                        'readOnly'=>'readOnly'
                    ],
                ]) ?>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Адрес сайта</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'site', [
                    'template' => '<div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Инвестиции в проект</h4>
                </div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'invest_name', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'invest_amount', [
                    'template' => '<div class="col-md-4" style="padding-top: 7px;">{label}</div><div class="col-md-3">{input}</div><div class="col-md-5"></div>'
                ])->textInput(['type' => 'number']);?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'invest_date', [
                    'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-2">{input}</div><div class="col-md-6"></div>'
                ])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'dd.MM.yyyy',
                    //'inline' => true,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.MM.yyyy',
                    ],
                    //'language' => 'ru',
                    'options' => [
                        'class' => 'form-control input-md',
                        'readOnly'=>'readOnly'
                    ],
                ]) ?>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Анонс проекта</h4>
                </div>
            </div>


            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'announcement_event', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <?= $form->field($model, 'date_of_announcement', [
                    'template' => '<div class="col-md-4" style="padding-top: 5px;">{label}</div><div class="col-md-2">{input}</div><div class="col-md-6"></div>'
                ])->widget(\yii\jui\DatePicker::class, [
                    'dateFormat' => 'dd.MM.yyyy',
                    //'inline' => true,
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.MM.yyyy',
                    ],
                    //'language' => 'ru',
                    'options' => [
                        'class' => 'form-control input-md',
                        'readOnly'=>'readOnly'
                    ],
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-sm-8">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading">
                    <h4>Презентационные файлы</h4>
                </div>
            </div>

            <div class="container row">
                <div class="pull-left">

                    <div style="font-size: 13px; font-weight: 700;margin-top: -5px;">
                        <p>Загружаемые файлы должны иметь соответствующие расширения:</p>
                        <p style="margin-top: -10px;">"png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls".</p>
                        <p style="margin-top: -10px;">Максимальное  количество - до 5 файлов. Используйте множественную загрузку.</p>
                    </div>

                    <p><?= $form->field($model, 'present_files[]')->fileInput(['multiple' => true,])->label(false) ?></p>

                    <p><?php if (!empty($model->preFiles)){
                            foreach ($model->preFiles as $file){
                                echo Html::a($file->file_name, ['download', 'filename' => $file->file_name], ['class' => 'btn btn-default prefiles']) .
                                    ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-file', 'filename' => $file->file_name], [
                                        'onclick'=>
                                            "$.ajax({
                                                 type:'POST',
                                                 cache: false,
                                                 url: '".Url::to(['delete-file', 'filename' => $file->file_name])."',
                                                 success  : function(response) {
                                                     $('.link-del ' . $file->id).html(response);
                                                     $('.prefiles').remove();
                                                 }
                                              });
                                         return false;
                                         $('.prefiles').remove();
                                         ",
                                        'class' => "link-del $file->id",
                                    ]) . '<br>';
                            }
                        }?></p>

                </div>
            </div>

        </div>
    </div>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
