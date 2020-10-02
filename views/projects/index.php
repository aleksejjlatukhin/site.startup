<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\ProjectSort;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проекты';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/projects-index-style.css');

?>
<div class="projects-index">


    <div class="container-fluid container-data row">


        <div class="row row_header_data_generation" style="margin-top: 10px;">

            <?php
            $form = ActiveForm::begin([
                'id' => 'sorting_projects',
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]);
            ?>


            <?php

            $listFields = ProjectSort::getListFields();
            $listFields = ArrayHelper::map($listFields,'id', 'name');

            ?>


            <div class="col-md-3" style="font-size: 32px; font-weight: 700;">ПРОЕКТЫ</div>


            <div class="col-md-3">

                <?= $form->field($sortModel, 'field',
                    ['template' => '<div>{input}</div>'])
                    ->widget(Select2::class, [
                        'data' => $listFields,
                        'options' => [
                            'id' => 'listFields',
                            'placeholder' => 'Выберите данные для сортировки'
                        ],
                        'hideSearch' => true, //Скрытие поиска
                    ]);
                ?>

            </div>

            <div class="col-md-3">

                <?= $form->field($sortModel, 'type',
                    ['template' => '<div>{input}</div>'])
                    ->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => [
                            'pluginOptions' => ['allowClear' => false],
                            'hideSearch' => true,
                        ],
                        'options' => ['id' => 'listType', 'placeholder' => 'Выберите тип сортировки'],
                        'pluginOptions' => [
                            'placeholder' => false,
                            'hideSearch' => true,
                            'depends' => ['listFields'],
                            'nameParam' => 'name',
                            'url' => Url::to(['/projects/list-type-sort'])
                        ]
                    ]);
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>

            <div class="col-md-3">

                <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить проект</div></div>', ['#'],
                    ['data-toggle' => 'modal', 'data-target' => "#data_project_create_modal", 'class' => 'new_segment_link_plus']
                );
                ?>

            </div>
        </div>


        <!--Заголовки для списка сегментов-->
        <div class="row" style="margin: 0 0 10px 0; padding: 10px;">

            <div class="col-md-3 headers_data_respond_hi">
                <div class="">Проект</div>
            </div>

            <div class="col-md-3 headers_data_respond_hi text-center" style="padding-left: 0; padding-right: 0;">
                Результат интеллектуальной деятельности
            </div>

            <div class="col-md-2 headers_data_respond_hi">
                Базовая технология
            </div>

            <div class="col-md-1 headers_data_respond_hi text-center">
                Создан
            </div>

            <div class="col-md-1 headers_data_respond_hi text-center">
                Изменен
            </div>

        </div>


        <!--Данные для списка сегментов-->
        <?php foreach ($models as $model) : ?>


            <div class="row container-one_respond" style="margin: 3px 0; padding: 0;">

                <div class="col-md-3">

                    <div>
                        <?= Html::a(Html::encode($model->project_name), Url::to(['/segment/index', 'id' => $model->id]),[
                            'class' => 'project_name_table_link'
                        ]);?>
                    </div>
                    <div class="project_description_text">
                        <?php

                        $description = $model->description;
                        if (mb_strlen($description) > 50) {
                            $description = mb_substr($description, 0, 50) . '...';
                        }

                        echo '<div title="'.$model->description.'">' . $description . '</div>';

                        ?>
                    </div>

                </div>


                <div class="col-md-3">

                    <?php

                    $rid = $model->rid;

                    if (mb_strlen($rid) > 80) {
                        $rid = mb_substr($rid, 0, 80)  . ' ...';
                    }

                    echo '<div class="text_14_table_project" title="' . $model->rid . '">' . $rid . '</div>';

                    ?>

                </div>

                <div class="col-md-2">

                    <?php

                    $technology = $model->technology;

                    if (mb_strlen($technology) > 50) {
                        $technology = mb_substr($technology, 0, 50) . ' ...';
                    }

                    echo '<div class="text_14_table_project" title="' . $model->technology . '">' . $technology . '</div>';

                    ?>

                </div>

                <div class="col-md-1">

                    <?= date('d.m.Y', $model->created_at); ?>

                </div>

                <div class="col-md-1">

                    <?= date('d.m.Y', $model->updated_at); ?>

                </div>

                <div class="col-md-2" style="padding-left: 20px; padding-right: 20px;">

                    <div class="row" style="display:flex; align-items: center;">


                        <div class="col-md-4">

                            <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['#'], [
                                'class' => '',
                                'title' => 'Смотреть',
                                'data-toggle' => 'modal',
                                'data-target' => "#data_project_modal-$model->id",
                            ]); ?>

                        </div>

                        <div class="col-md-4">

                            <?= Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['#'], [
                                'class' => '',
                                'title' => 'Редактировать',
                                'data-toggle' => 'modal',
                                'data-target' => "#data_project_update_modal-$model->id",
                            ]); ?>

                        </div>

                        <div class="col-md-4">

                            <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['#'], [
                                'class' => '',
                                'title' => 'Удалить',
                                'onclick' => 'return false',
                            ]); ?>

                        </div>

                    </div>
                </div>


            </div>

        <?php endforeach;?>

    </div>



    <?php
    // Модальное окно - создание проекта
    Modal::begin([
        'options' => [
            'id' => 'data_project_create_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Создание проекта</h3>',
    ]);
    ?>


    <?php $form = ActiveForm::begin([
        'id' => 'project_create_form',
        'action' => Url::to(['projects/create', 'id' => $newModel->user_id]),
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
                <?= $form->field($newModel, 'project_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-5">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'project_fullname', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'description', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
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
                <?= $form->field($newModel, 'rid', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'core_rid', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

        </div>
    </div>


    <div class="row">
        <div class="panel-body col-md-12">
            <div class="panel panel-default"><!-- widgetBody -->
                <div class="panel-heading" style="font-size: 24px;">Данные о патенте</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'patent_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]); ?>
            </div>

            <div class="row" style="margin-bottom: 20px;">
                <?= $form->field($newModel, 'patent_number', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>


            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата получения патента</label>';?>
                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[patent_date]',
                        'value' => $newModel->patent_date == null ? null : date('d.m.yy', $newModel->patent_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ],
                        'options' => [
                            'id' => "patent_date",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ]
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

                <div class="item-authors item-authors-form-create panel-body" style="padding: 0;"><!-- widgetBody -->

                        <div class="row row-author row-author-form-create-0" style="margin-bottom: 15px;">

                            <?= $form->field($new_author, "[0]fio", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'id' => 'author_fio-0',
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]); ?>

                            <?= $form->field($new_author, "[0]role", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'id' => 'author_role-0',
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]); ?>

                            <?= $form->field($new_author, "[0]experience", [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                            ])->textarea([
                                'rows' => 2,
                                'id' => 'author_experience-0',
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]) ?>


                        </div><!-- .row -->

                </div>

            </div>


            <?= Html::button('Добавить автора', [
                'id' => 'add_author_create_form',
                'class' => "btn btn-default add_author_create_form",
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
                <?= $form->field($newModel, 'technology', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'layout_technology', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textarea([
                    'rows' => 2,
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
                <?= $form->field($newModel, 'register_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-3">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата регистрации</label>';?>
                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[register_date]',
                        'value' => $newModel->register_date == null ? null : date('d.m.yy', $newModel->register_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "register_date",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ]
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
                <?= $form->field($newModel, 'site', [
                    'template' => '<div class="col-md-12">{input}</div>'
                ])->textInput([
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
                <div class="panel-heading" style="font-size: 24px;">Инвестиции в проект</div>
            </div>

            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'invest_name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>
            </div>


            <script>

                $( function() {

                    var invest_amount_create = 'input#invest_amount_create';

                    $(invest_amount_create).change(function () {
                        var value = $(invest_amount_create).val();
                        var valueMax = 100000000;
                        var valueMin = 50000;

                        if (parseInt(value) > parseInt(valueMax)){
                            value = valueMax;
                            $(invest_amount_create).val(value);
                        }

                        if (parseInt(value) < parseInt(valueMin)){
                            value = valueMin;
                            $(invest_amount_create).val(value);
                        }
                    });
                } );
            </script>


            <div class="row" style="margin-bottom: 15px;">
                <?= $form->field($newModel, 'invest_amount', [
                    'template' => '<div class="col-md-12" style="padding-top: 7px; padding-left: 20px;">{label}<div style="font-weight: 400;font-size: 13px; margin-top: -5px; margin-bottom: 5px;">(укажите значение от 50 000 до 100 млн.)</div></div><div class="col-md-3">{input}</div><div class="col-md-5"></div>'
                ])->textInput([
                    'type' => 'number',
                    'id' => 'invest_amount_create',
                    'class' => 'style_form_field_respond form-control',
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
                        'value' => $newModel->invest_date == null ? null : date('d.m.yy', $newModel->invest_date),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "invest_date",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ]
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
                <?= $form->field($newModel, 'announcement_event', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
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
                        'value' => $newModel->date_of_announcement == null ? null : date('d.m.yy', $newModel->date_of_announcement),
                        'readonly' => true,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy',
                        ],
                        'options' => [
                            'id' => "date_of_announcement",
                            'class' => 'text-center style_form_field_respond form-control',
                            'style' => ['padding-right' => '20px'],
                            'placeholder' => 'Выберите дату',
                        ]
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

                    <div class="add_files">

                        <div style="margin-top: -5px; padding-left: 5px;">
                            <label>Максимальное  количество - до 5 файлов. Используйте множественную загрузку.</label>
                            <p style="margin-top: -5px; color: #BDBDBD;">Загружаемые файлы должны иметь соответствующие расширения: png, jpg, jpeg, pdf, txt, doc, docx, xls</p>
                        </div>

                        <div class="error_files_count text-danger" style="display: none; margin-top: -5px; padding-left: 5px;">
                            Превышено максимальное количество файлов для загрузки.
                        </div>

                        <div style="padding-left: 5px;"><?= $form->field($newModel, 'present_files[]')->fileInput(['multiple' => true,])->label(false) ?></div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">

            <?= Html::submitButton('Сохранить', [
                'id' => 'save_create_form',
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


    <?php
    Modal::end();
    ?>





    <?php foreach ($models as $key => $model) : ?>

        <?php
        // Модальное окно - данные проекта
        Modal::begin([
            'options' => [
                'id' => 'data_project_modal-' . $model->id,
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Исходные данные по проекту</h3>',
        ]);
        ?>


        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'table table-bordered detail-view'], //Стилизация таблицы
            'attributes' => [

                'project_name',
                'project_fullname:ntext',
                'description:ntext',
                'rid',
                'core_rid:ntext',
                'patent_number',

                [
                    'attribute' => 'patent_date',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                'patent_name:ntext',

                [
                    'attribute'=>'Команда проекта',
                    'value' => $model->getAuthorInfo($model),
                    'format' => 'html',
                ],

                'technology',
                'layout_technology:ntext',
                'register_name',

                [
                    'attribute' => 'register_date',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                'site',
                'invest_name',

                [
                    'attribute' => 'invest_date',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                [
                    'attribute' => 'invest_amount',
                    'value' => function($model){
                        if($model->invest_amount !== null){
                            return number_format($model->invest_amount, 0, '', ' ');
                        }
                    },
                ],

                [
                    'attribute' => 'date_of_announcement',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                'announcement_event',

                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                [
                    'attribute' => 'updated_at',
                    'format' => ['date', 'dd.MM.yyyy'],
                ],

                [
                    'attribute' => 'pre_files',
                    'label' => 'Презентационные файлы',
                    'value' => function($model){
                        $string = '';
                        foreach ($model->preFiles as $file){
                            $string .= Html::a($file->file_name, ['/projects/download', 'id' => $file->id], ['class' => '']) . '<br>';
                        }
                        return $string;
                    },
                    'format' => 'html',
                ]

            ],
        ]) ?>


        <?php
        Modal::end();
        ?>




        <?php
        // Модальное окно - редактирование проекта
        Modal::begin([
            'options' => [
                'id' => 'data_project_update_modal-' . $model->id,
                'class' => 'data_project_update_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Редактирование исходных данных проекта</h3>',
        ]);
        ?>


        <?php $form = ActiveForm::begin([
            'id' => 'project_update_form-' . $model->id,
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
                        ]) ?>
                </div>

                <div class="row" style="margin-bottom: 15px;">
                    <?= $form->field($model, 'description', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 2,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
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
                        ]) ?>
                </div>

                <div class="row" style="margin-bottom: 15px;">
                    <?= $form->field($model, 'core_rid', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 2,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]) ?>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="panel-body col-md-12">
                <div class="panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading" style="font-size: 24px;">Данные о патенте</div>
                </div>

                <div class="row" style="margin-bottom: 15px;">
                    <?= $form->field($model, 'patent_name', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                        ]); ?>
                </div>

                <div class="row" style="margin-bottom: 20px;">
                    <?= $form->field($model, 'patent_number', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                        ]) ?>
                </div>


                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-3">

                    <?= '<label class="control-label" style="padding-left: 5px;">Дата получения патента</label>';?>
                    <?= \kartik\date\DatePicker::widget([
                        'type' => 2,
                        'removeButton' => false,
                        'name' => 'Projects[patent_date]',
                        'value' => $model->patent_date == null ? null : date('d.m.yy', $model->patent_date),
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
                        ]
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

                            <?php foreach ($workers[$key] as $i => $worker): ?>

                            <div class="row row-author row-author-<?= $model->id . '_' . $i;?>" style="margin-bottom: 15px;">

                                <?= $form->field($worker, "[$i]fio", [
                                    'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                                ])->textInput([
                                    'maxlength' => true,
                                    'required' => true,
                                    'id' => 'author_fio-' . $i,
                                    'class' => 'style_form_field_respond form-control',
                                    'placeholder' => '',
                                    ]); ?>

                                <?= $form->field($worker, "[$i]role", [
                                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                                ])->textInput([
                                    'maxlength' => true,
                                    'required' => true,
                                    'id' => 'author_role-' . $i,
                                    'class' => 'style_form_field_respond form-control',
                                    'placeholder' => '',
                                    ]); ?>

                                <?= $form->field($worker, "[$i]experience", [
                                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                                ])->textarea([
                                    'rows' => 2,
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
                        ]) ?>
                </div>

                <div class="row" style="margin-bottom: 15px;">
                    <?= $form->field($model, 'layout_technology', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 2,
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
                        ]) ?>
                </div>

                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-md-3">

                        <?= '<label class="control-label" style="padding-left: 5px;">Дата регистрации</label>';?>
                        <?= \kartik\date\DatePicker::widget([
                            'type' => 2,
                            'removeButton' => false,
                            'name' => 'Projects[register_date]',
                            'value' => $model->register_date == null ? null : date('d.m.yy', $model->register_date),
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
                            ]
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
                            'value' => $model->invest_date == null ? null : date('d.m.yy', $model->invest_date),
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
                            ]
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
                            'value' => $model->date_of_announcement == null ? null : date('d.m.yy', $model->date_of_announcement),
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
                            ]
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

                                <div style="padding-left: 5px;"><?= $form->field($model, 'present_files[]')->fileInput(['multiple' => true])->label(false) ?></div>

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

                                <div style="padding-left: 5px;"><?= $form->field($model, 'present_files[]')->fileInput(['multiple' => true])->label(false) ?></div>

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
                                            'class' => 'btn btn-default prefiles',
                                            'style' => [
                                                'display' => 'flex',
                                                'align-items' => 'center',
                                                //'color' => '#FFFFFF',
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


        <?php
        Modal::end();
        ?>


    <?php endforeach; ?>



    <?php
    // Модальное окно - Проект с таким именем уже существует
    Modal::begin([
        'options' => [
            'id' => 'project_already_exists',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Проект с таким наименованием уже существует. Отредактируйте данное поле и сохраните форму.
    </h4>

    <?php
    Modal::end();
    ?>



    <div class="form_authors" style="display: none;">

    <?php

    $form = ActiveForm::begin([
            'id' => 'form_authors'
    ]);

    ?>

        <div class="form_authors_inputs">

            <div class="row row-author row-author-" style="margin-bottom: 15px;">



                <?= $form->field($new_author, "[0]fio", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px; margin-top: 15px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_fio-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]); ?>

                <?= $form->field($new_author, "[0]role", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'id' => 'author_role-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]); ?>

                <?= $form->field($new_author, "[0]experience", [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12" style="margin-bottom: 15px;">{input}</div>'
                ])->textarea([
                    'rows' => 2,
                    'id' => 'author_experience-',
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]) ?>



                <div class="col-md-12">

                    <?= Html::button('Удалить автора', [
                        'id' => 'remove-author-',
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

            </div>


        </div>

    <?php
    ActiveForm::end();
    ?>

    </div>


</div>

<?php


$script = "

    $(document).ready(function() {

        //Фон для модального окна информации (проект с таким именем уже существует)
        var project_already_exists_modal = $('#project_already_exists').find('.modal-content');
        project_already_exists_modal.css('background-color', '#707F99');

    });
    
    
    $('#data_project_create_modal').on('change', 'input[type=file]',function(){
    
        for (var i = 0; i < this.files.length; i++) {
            console.log(this.files[i].name);
        }
        
        //Количество добавленных файлов
        var add_count = this.files.length;
        
        if(add_count > 5) {
            //Сделать кнопку отправки формы не активной
            $('#data_project_create_modal').find('#save_create_form').attr('disabled', true);
            $('#data_project_create_modal').find('.error_files_count').show();
        }else {
            //Сделать кнопку отправки формы активной
            $('#data_project_create_modal').find('#save_create_form').attr('disabled', false);
            $('#data_project_create_modal').find('.error_files_count').hide();
        }
        
    });
    
    
    //Возвращение скролла первого модального окна после закрытия 
    //модального окна информации об ошибке
    $( '#project_already_exists' ).on( 'hidden.bs.modal' , function() {
        $( 'body' ).addClass( 'modal-open' );
    } );
    
    
    //Удаление файла из проекта
    $('body').on('click', '.delete_file', function(e){
    
        var deleteFileId = $(this).attr('id');
        deleteFileId = deleteFileId.split('-');
        deleteFileId = deleteFileId[1];
        var url = $(this).attr('href');
    
        $.ajax({
        
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){

                if (response['success']) {
                
                    //Удаляем блок с файлом
                    $('#data_project_update_modal-' + response['project_id']).find('.one_block_file-' + deleteFileId).remove();
                    
                    if (response['count_files'] == 4){
                        $('#data_project_update_modal-' + response['project_id']).find('.add_files').show();
                        $('#data_project_update_modal-' + response['project_id']).find('.add_max_files_text').hide();
                    }
                }
                 
            }, error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();

        return false;
    });
    
    
    //Создание проекта
    $('#project_create_form').on('beforeSubmit', function(e){
    
        var form = $(this);
		var url = form.attr('action');	
		var formData = new FormData(form[0]);
        
        $.ajax({
        
            url: url,
            method: 'POST',
            processData: false,
	        contentType: false,
            data:  formData,
            cache: false,
            success: function(response){
                
                //Если данные загружены и проверены
                if(response['success']){
                
                    //Закрываем модальное окно и делаем перезагрузку 
                    $('#data_project_create_modal').modal('hide');
                    location.reload();
                }
                
                //Если сегмент с таким именем уже существует 
                if(response['project_already_exists']){
                
                    $('#project_already_exists').modal('show');
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });
    
    
    //Удаление формы автора проекта при создании
    $('body').on('click', '.remove_author_for_create', function(){
    
        var clickId = $(this).attr('id');
        var arrId = clickId.split('-');
        var numberId = arrId[4];
        
        $('.row-author-form-create-' + numberId).remove();
        
    });
    
    
    //Добавление формы автора проекта при создании
    $('#add_author_create_form').on('click', function(){
    
        var numberName = $('.item-authors').children('.row-author').last();
        numberName = $(numberName).children('.form-group').last();
        numberName = $(numberName).children('div').last();
        numberName = $(numberName).find('textarea');
        numberName = $(numberName).attr('id');
        var lastNumberItem = numberName.toString().slice(-1);
        lastNumberItem = Number.parseInt(lastNumberItem);
        var id = lastNumberItem + 1;
        
        var fio_id = 'author_fio_create-' + id;
        $('#author_fio-').attr('name', 'Authors['+id+'][fio]');
        $('#author_fio-').attr('id', fio_id);
        
        var role_id = 'author_role_create-' + id;
        $('#author_role-').attr('name', 'Authors['+id+'][role]');
        $('#author_role-').attr('id', role_id);
        
        var experience_id = 'author_experience_create-' + id;
        $('#author_experience-').attr('name', 'Authors['+id+'][experience]');
        $('#author_experience-').attr('id', experience_id);
        
        var buttonRemoveId = 'remove-author-form-create-' + id;
        $('#remove-author-').addClass('remove_author_for_create');
        $('#remove-author-').attr('id', buttonRemoveId);
        
        $('#form_authors').find('.form_authors_inputs').find('.row-author').toggleClass('row-author-').toggleClass('row-author-form-create-' + id);
        var str = $('#form_authors').find('.form_authors_inputs').html();
        $(str).find('.row-author').toggleClass('row-author-').toggleClass('row-author-form-create-' + id);
        $('.item-authors').append(str);
        
        $('#form_authors').find('.form_authors_inputs').find('.row-author').toggleClass('row-author-form-create-' + id).toggleClass('row-author-');
        $('#form_authors').find('#author_fio_create-' + id).attr('name', 'Authors[0][fio]');
        $('#form_authors').find('#author_role_create-' + id).attr('name', 'Authors[0][role]');
        $('#form_authors').find('#author_experience_create-' + id).attr('name', 'Authors[0][experience]');
        
        $('#form_authors').find('#author_fio_create-' + id).attr('id', 'author_fio-');
        $('#form_authors').find('#author_role_create-' + id).attr('id', 'author_role-');
        $('#form_authors').find('#author_experience_create-' + id).attr('id', 'author_experience-');
        $('#form_authors').find('#remove-author-form-create-' + id).removeClass('remove_author_for_create');
        $('#form_authors').find('#remove-author-form-create-' + id).attr('id', 'remove-author-');
        
    });
    

    //Удаление формы автора проекта в редактировании
    $('body').on('click', '.remove-author', function(){
    
        var clickId = $(this).attr('id');
        var arrId = clickId.split('-');
        var numberId = arrId[2];
        
        if(arrId[3]) {
        
            var worker_id = arrId[3];
            var url = '/projects/delete-author?id=' + worker_id;
            
        $.ajax({
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){},
            error: function(){alert('Ошибка');}
            });
        }
        
        $('.row-author-' + numberId).remove();
        
    });
    
    //Добавление формы автора проекта в редактировании
    $('.add_author').on('click', function(){
    
        var clickId = $(this).attr('id');
        var arrId = clickId.split('-');
        var numberId = arrId[1];
        
        var numberName = $('.item-authors-' + numberId).children('.row-author').last();
        numberName = $(numberName).children('.form-group').last();
        numberName = $(numberName).children('div').last();
        numberName = $(numberName).find('textarea');
        numberName = $(numberName).attr('id');
        var lastNumberItem = numberName.toString().slice(-1);
        lastNumberItem = Number.parseInt(lastNumberItem);
        var id = lastNumberItem + 1;
        
        var fio_id = 'author_fio-' + id;
        $('#author_fio-').attr('name', 'Authors['+id+'][fio]');
        $('#author_fio-').attr('id', fio_id);
        
        var role_id = 'author_role-' + id;
        $('#author_role-').attr('name', 'Authors['+id+'][role]');
        $('#author_role-').attr('id', role_id);
        
        var experience_id = 'author_experience-' + id;
        $('#author_experience-').attr('name', 'Authors['+id+'][experience]');
        $('#author_experience-').attr('id', experience_id);
        
        var buttonRemoveId = 'remove-author-' + numberId + '_' + id;
        $('#remove-author-').attr('id', buttonRemoveId);
        
        $('.item-authors-' + numberId).find('.row-author').toggleClass('row-author-').toggleClass('row-author-' + numberId + '_' + id);
    
        var str = $('#form_authors').find('.form_authors_inputs').html();
    
        $('.item-authors-' + numberId).append(str);
        
        $('#form_authors').find('#author_fio-' + id).attr('name', 'Authors[0][fio]');
        $('#form_authors').find('#author_role-' + id).attr('name', 'Authors[0][role]');
        $('#form_authors').find('#author_experience-' + id).attr('name', 'Authors[0][experience]');
        
        $('#form_authors').find('#author_fio-' + id).attr('id', 'author_fio-');
        $('#form_authors').find('#author_role-' + id).attr('id', 'author_role-');
        $('#form_authors').find('#author_experience-' + id).attr('id', 'author_experience-');
        $('#form_authors').find('#remove-author-' + numberId + '_' + id).attr('id', 'remove-author-');
        $('.item-authors-' + numberId).find('.row-author').toggleClass('row-author-' + numberId + '_' + id).toggleClass('row-author-');
    });
    

";
    $position = \yii\web\View::POS_READY;
    $this->registerJs($script, $position);

?>


<?php

foreach ($models as $model) :

$script2 = "

   

    $('#data_project_update_modal-" . $model->id. "').on('change', 'input[type=file]',function(){
    
        for (var i = 0; i < this.files.length; i++) {
            console.log(this.files[i].name);
        }
        
        //Количество добавленных файлов
        var add_count = this.files.length;
        //Количество файлов уже загруженных
        var count_exist_files = $('#data_project_update_modal-" . $model->id. "').find('.block_all_files').children('div').length;
        //Общее количество файлов
        var countAllFiles = this.files.length + count_exist_files;
        
        if(countAllFiles > 5) {
            //Сделать кнопку отправки формы не активной
            $('#project_update_form-" . $model->id . "').find('#save_update_form').attr('disabled', true);
            $('#data_project_update_modal-" . $model->id. "').find('.error_files_count').show();
        }else {
            //Сделать кнопку отправки формы активной
            $('#project_update_form-" . $model->id . "').find('#save_update_form').attr('disabled', false);
            $('#data_project_update_modal-" . $model->id. "').find('.error_files_count').hide();
        }
        
    });
    

    //Редактирование проекта
    $('#project_update_form-" . $model->id . "').on('beforeSubmit', function(e){
    
        var form = $(this);
        var url = form.attr('action');	
        var formData = new FormData(form[0]);
            
        $.ajax({
            
            url: url,
            method: 'POST',
            processData: false,
            contentType: false,
            data:  formData,
            cache: false,
            success: function(response){
                    
                //Если данные загружены и проверены
                if(response['success']){
                    
                    //Закрываем модальное окно и делаем перезагрузку 
                    $('#data_project_update_modal-' + response['model_id']).modal('hide');
                    location.reload();
                }
                    
                //Если сегмент с таким именем уже существует 
                if(response['project_already_exists']){
                
                    $('#project_already_exists').modal('show');
                }
                    
            },
            error: function(){
                alert('Ошибка');
            }
        });
        
        e.preventDefault();

        return false;
    });

    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script2, $position);

endforeach;
?>