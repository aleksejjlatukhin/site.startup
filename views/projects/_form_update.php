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
                    <h4>Команда проекта</h4>
                </div>
            </div>


            <div class="container-authors"><!-- widgetContainer -->

                <?php foreach ($workers as $i => $worker): ?>

                    <div class="item-authors panel-body"><!-- widgetBody -->


                        <div class="row row-author">
                            <div class="pull-right">
                                <button type="button" class="remove-authors btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>

                            <?= $form->field($worker, "[$i]fio", [
                                'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                            ])->textInput(['maxlength' => true, 'required' => true, 'id' => 'author_fio-' . $i,]) ?>

                            <?= $form->field($worker, "[$i]role", [
                                'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                            ])->textInput(['maxlength' => true, 'required' => true, 'id' => 'author_role-' . $i,]) ?>

                            <?= $form->field($worker, "[$i]experience", [
                                'template' => '<div class="col-md-11">{label}</div><div class="col-md-11">{input}</div><div class="col-md-11">{error}</div>'
                            ])->textarea(['rows' => 1, 'id' => 'author_experience-' . $i,]) ?>

                        </div><!-- .row -->

                    </div>
                <?php endforeach; ?>
            </div>

            <p class="col-sm-3"><button type="button" id="add_author" class="add-authors btn btn-primary btn-md">Добавить автора</i></button></p>

        </div>
    </div>

    <script>
        $(function () {
            $('#add_author').click(function(){

                var id = $('.row-author').length;

                var str = '<div class="row row-author"><li>';
                str+= '<label>ФИО</label><input name="Authors['+id+'][fio]" class="style_form_field_respond form-control fio_author-'+id+'" type="text" value=""/> ';
                str+= '<input type="button" value="Удалить" class="remove"/>';
                str+= '</li></div>';

                $('.item-authors').append(str);
            });
        })
    </script>


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

                    <?php if (count($model->preFiles) < 4) : ?>

                    <div style="font-size: 13px; font-weight: 700;margin-top: -5px;">
                        <p>Загружаемые файлы должны иметь соответствующие расширения:</p>
                        <p style="margin-top: -10px;">"png, jpg, odt, xlsx, txt, doc, docx, pdf, otf, odp, pps, ppsx, ppt, pptx, opf, csv, xls".</p>
                        <p style="margin-top: -10px;">Максимальное  количество - до 5 файлов. Используйте множественную загрузку.</p>
                    </div>

                    <p><?= $form->field($model, 'present_files[]')->fileInput(['multiple' => true,])->label(false) ?></p>

                    <?php else : ?>

                    <div style="font-size: 13px; font-weight: 700;margin-top: -5px;">
                        <p>Добавлено максимальное количество файлов.</p>
                        <p style="margin-top: -10px;">Чтобы загрузить новые файлы, удалите уже загруженные.</p>
                    </div>

                    <?php endif; ?>

                    <p><?php if (!empty($model->preFiles)){
                            foreach ($model->preFiles as $file){
                                echo Html::a($file->file_name, ['download', 'id' => $file->id], ['class' => 'btn btn-default prefiles']) .
                                    ' ' . Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-file', 'id' => $file->id], [
                                        'onclick'=>
                                            "$.ajax({
                                                 type:'POST',
                                                 cache: false,
                                                 url: '".Url::to(['delete-file', 'id' => $file->id])."',
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
                        }?>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
