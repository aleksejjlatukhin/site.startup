<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="text-center">
    <?= Html::a('Скачать исходные данные по проекту', ['/projects/mpdf-project', 'id' => $model->id], [
        'class' => 'export_link_hypothesis_for_user', 'target' => '_blank', 'title' => 'Скачать в pdf',
    ]); ?>
</div>

<div class="form-update-project" style="overflow: hidden;">

    <?php $form = ActiveForm::begin([
        'id' => 'project_update_form',
        'action' => Url::to(['projects/update', 'id' => $model->id]),
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Описание проекта</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'project_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-5">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'project_fullname', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'description', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'required' => true,
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'purpose_project', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'required' => true,
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Примеры: разработать продукт, найти целевой сегмент, найти рекламный слоган, разработать упаковку и т.д.',
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Результат интеллектуальной деятельности</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'rid', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'core_rid', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'required' => true,
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Сведения о патенте</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'patent_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]); ?>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <?= $form->field($model, 'patent_number', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>


            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата получения патента</label>';?>
                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[patent_date]',
                        'value' => $model->patent_date == null ? null : date('d.m.Y', $model->patent_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ],
                        'options' => [
                            'id' => "patent_date-$model->id",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ],
                        'pluginEvents' => [
                                "hide" => "function(e) {e.preventDefault(); e.stopPropagation();}",
                        ],
                    ]);?>

                </div>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Команда проекта</div>
            </div>

            <div class="container-authors"><!-- widgetContainer -->

                <div class="item-authors item-authors-<?=$model->id;?> panel-body" style="padding: 0;"><!-- widgetBody -->

                    <?php foreach ($workers as $i => $worker): ?>

                        <div class="row row-author row-author-<?= $model->id . '_' . $i;?>" style="margin-bottom: 15px;">

                            <?= $form->field($worker, "[$i]fio", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'id' => 'author_fio-' . $i,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                                'autocomplete' => 'off'
                            ]); ?>

                            <?= $form->field($worker, "[$i]role", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'id' => 'author_role-' . $i,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                                'autocomplete' => 'off'
                            ]); ?>

                            <?= $form->field($worker, "[$i]experience", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textarea([
                                'rows' => 2,
                                'maxlength' => true,
                                'id' => 'author_experience-' . $i,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]) ?>

                            <?php if ($i != 0) : ?>

                                <div class="col-md-12">

                                    <?= Html::button('Удалить автора', [
                                        'id' => 'remove-author-' . $model->id . '_' . $i . '-' . $worker->id,
                                        'class' => "remove-author btn btn-default",
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'background' => '#E0E0E0',
                                            'color' => '#FFFFFF',
                                            'width' => '200px',
                                            'height' => '40px',
                                            'font-size' => '24px',
                                            'border-radius' => '8px',
                                        ]
                                    ]); ?>
                                </div>

                            <?php endif; ?>

                        </div><!-- .row -->

                    <?php endforeach; ?>

                </div>

            </div>


            <?= Html::button('Добавить автора', [
                'id' => 'add_author-' . $model->id,
                'class' => "btn btn-default add_author",
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'color' => '#FFFFFF',
                    'justify-content' => 'center',
                    'background' => '#707F99',
                    'width' => '200px',
                    'height' => '40px',
                    'text-align' => 'left',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                    'margin-right' => '5px',
                ]
            ]);?>

        </div>
    </div>



    <div class="row">
        <div class="panel-body col-sm-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Сведения о технологии</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'technology', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'layout_technology', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Регистрация юридического лица</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'register_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата регистрации</label>';?>
                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[register_date]',
                        'value' => $model->register_date == null ? null : date('d.m.Y', $model->register_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "register_date-$model->id",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ],
                        'pluginEvents' => [
                            "hide" => "function(e) {e.preventDefault(); e.stopPropagation();}",
                        ],
                    ]);?>

                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Адрес сайта</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'site', [
                    'template' => '<div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Инвестиции в проект</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'invest_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>


            <script>

                $( function() {

                    var invest_amount = 'input#invest_amount-<?= $model->id; ?>';

                    $(invest_amount).change(function () {
                        var value = $(invest_amount).val();
                        var valueMax = 100000000;
                        var valueMin = 50000;

                        if (parseInt(value) > parseInt(valueMax)){
                            value = valueMax;
                            $(invest_amount).val(value);
                        }

                        if (parseInt(value) < parseInt(valueMin)){
                            value = valueMin;
                            $(invest_amount).val(value);
                        }
                    });
                } );
            </script>


            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'invest_amount', [
                    'template' => '<div class="col-md-12" style="padding-top: 7px; padding-left: 20px;">{label}<div style="font-weight: 400;font-size: 13px; margin-top: -5px; margin-bottom: 5px;">(укажите значение от 50 000 до 100 млн.)</div></div><div class="col-md-3">{input}</div><div class="col-md-9"></div>'
                ])->textInput([
                    'type' => 'number',
                    'id' => 'invest_amount-' . $model->id,
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-12">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата получения инвестиций</label>';?>

                </div>
                <div class="col-md-3">

                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[invest_date]',
                        'value' => $model->invest_date == null ? null : date('d.m.Y', $model->invest_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "invest_date-$model->id",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ],
                        'pluginEvents' => [
                            "hide" => "function(e) {e.preventDefault(); e.stopPropagation();}",
                        ],
                    ]);?>

                </div>

                <div class="col-md-5"></div>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Анонс проекта</div>
            </div>


            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($model, 'announcement_event', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-12">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата анонсирования проекта</label>';?>

                </div>
                <div class="col-md-3">

                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[date_of_announcement]',
                        'value' => $model->date_of_announcement == null ? null : date('d.m.Y', $model->date_of_announcement),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "date_of_announcement-$model->id",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ],
                        'pluginEvents' => [
                            "hide" => "function(e) {e.preventDefault(); e.stopPropagation();}",
                        ],
                    ]);?>

                </div>

                <div class="col-md-5"></div>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Презентационные файлы</div>
            </div>

            <div class="container row">
                <div class="pull-left">

                    <?php if (count($model->preFiles) < 5) : ?>

                        <div class="add_files">

                            <div style="margin-top: -5px; padding-left: 5px;">
                                <label>Максимальное  количество - 5 файлов.</label>
                                <p style="margin-top: -5px; color: #BDBDBD;">png, jpg, jpeg, pdf, txt, doc, docx, xls</p>
                            </div>

                            <div class="error_files_count text-danger" style="display: none; margin-top: -5px; padding-left: 5px;">
                                Превышено максимальное количество файлов для загрузки.
                            </div>

                            <div style="padding-left: 5px;"><?= $form->field($model, 'present_files[]', ['template' => "{label}\n{input}"])->fileInput(['multiple' => true])->label(false) ?></div>

                        </div>

                        <div class="add_max_files_text" style="display: none; margin-top: -5px; padding-left: 5px;">
                            <label>Добавлено максимальное количество файлов.</label>
                            <p style="margin-top: -5px; color: #BDBDBD;">Чтобы загрузить новые файлы, удалите уже загруженные.</p>
                        </div>

                    <?php else : ?>

                        <div class="add_files" style="display: none;">

                            <div style="margin-top: -5px; padding-left: 5px;">
                                <label>Максимальное  количество - 5 файлов.</label>
                                <p style="margin-top: -5px; color: #BDBDBD;">png, jpg, jpeg, pdf, txt, doc, docx, xls</p>
                            </div>

                            <div class="error_files_count text-danger" style="display: none; margin-top: -5px; padding-left: 5px;">
                                Превышено максимальное количество файлов для загрузки.
                            </div>

                            <div style="padding-left: 5px;"><?= $form->field($model, 'present_files[]', ['template' => "{label}\n{input}"])->fileInput(['multiple' => true])->label(false) ?></div>

                        </div>

                        <div class="add_max_files_text" style="margin-top: -5px; padding-left: 5px;">
                            <label>Добавлено максимальное количество файлов.</label>
                            <p style="margin-top: -5px; color: #BDBDBD;">Чтобы загрузить новые файлы, удалите уже загруженные.</p>
                        </div>

                    <?php endif; ?>

                    <div class="block_all_files" style="padding-left: 5px;">
                        <?php if (!empty($model->preFiles)){
                            foreach ($model->preFiles as $file){
                                $filename = $file->file_name;
                                if(mb_strlen($filename) > 35){ $filename = mb_substr($file->file_name, 0, 35) . '...'; }
                                echo '<div style="display: flex; margin: 2px 0; align-items: center;" class="one_block_file-'.$file->id.'">' .
                                    Html::a('<div style="display:flex; width: 100%; justify-content: space-between;"><div>' . $filename . '</div><div>'. Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]) .'</div></div>', ['download', 'id' => $file->id], [
                                        'title' => 'Скачать файл',
                                        'target' => '_blank',
                                        'class' => 'btn btn-default prefiles',
                                        'style' => [
                                            'display' => 'flex',
                                            'align-items' => 'center',
                                            'justify-content' => 'center',
                                            'background' => '#E0E0E0',
                                            'width' => '320px',
                                            'height' => '40px',
                                            'text-align' => 'left',
                                            'font-size' => '14px',
                                            'border-radius' => '8px',
                                            'margin-right' => '5px',
                                        ]
                                    ]) . ' ' .
                                    Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px', 'height' => '29px']]), ['delete-file', 'id' => $file->id], [
                                        'title' => 'Удалить файл',
                                        'class' => 'delete_file',
                                        'id' => 'delete_file-' . $file->id,
                                        'style' => ['display' => 'flex', 'margin-left' => '15px'],
                                    ])
                                    . '</div>';
                            }
                        }?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">

            <?= Html::submitButton('Сохранить', [
                'id' => 'save_update_form',
                'class' => 'btn btn-success pull-right',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'background' => '#52BE7F',
                    'width' => '140px',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                    'margin-top' => '28px'
                ]
            ]) ?>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
