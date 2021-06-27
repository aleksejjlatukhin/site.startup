<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Segments;

?>


<style>
    .select2-container--krajee .select2-selection {
        font-size: 16px;
        height: 40px;
        padding-left: 15px;
        padding-top: 8px;
        padding-bottom: 15px;
        border-radius: 12px;
    }
    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        height: 39px;
    }
</style>


<div class="segment-form-create">

    <?php $form = ActiveForm::begin([
        'id' => 'hypothesisCreateForm',
        'action' => Url::to(['/segments/create', 'id' => $project->id]),
        'options' => ['class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <div class="row" style="margin-bottom: 10px;">

        <?= $form->field($model, 'name', [
            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-5">{input}</div>'
        ])->label('Наименование сегмента *')->textInput([
            'maxlength' => true,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => '',
            'autocomplete' => 'off'
        ]);
        ?>

    </div>

    <div class="row" style="margin-bottom: 15px;">

        <?= $form->field($model, 'description', [
            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
        ])->label('Краткое описание сегмента *')->textarea([
            'rows' => 1,
            'maxlength' => true,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => '',
        ]);
        ?>

    </div>

    <div class="row" style="margin-bottom: 10px;">

        <?php
        $list_of_interactions = [
            Segments::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
            Segments::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
        ];
        ?>

        <?= $form->field($model, 'type_of_interaction_between_subjects', [
            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12 type_of_interaction">{input}</div>'
        ])->label('Вид информационного и экономического взаимодействия между субъектами рынка *')->widget(Select2::class, [
            'data' => $list_of_interactions,
            'options' => ['id' => 'type-interaction'],
            'hideSearch' => true, //Скрытие поиска
        ]); ?>

    </div>


    <div class="form-template-b2c">


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'field_of_activity_b2c', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Сфера деятельности потребителя *')->textInput([
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>

        </div>


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'sort_of_activity_b2c', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Вид / специализация деятельности потребителя *')->textInput([
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#age_from").change(function () {
                    var value1 = $("input#age_from").val();
                    var value2 = $("input#age_to").val();
                    var valueMax = 100;
                    var valueMin = 0;

                    if (parseInt(value1) > parseInt(value2)){
                        value1 = value2;
                        $("input#age_from").val(value1);
                    }

                    if (parseInt(value1) > parseInt(valueMax)){
                        value1 = valueMax;
                        $("input#age_from").val(value1);
                    }

                    if (parseInt(value1) < parseInt(valueMin)){
                        value1 = valueMin;
                        $("input#age_from").val(value1);
                    }
                });

                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                $("input#age_to").change(function () {
                    var value1 = $("input#age_from").val();
                    var value2 = $("input#age_to").val();
                    var valueMax = 100;
                    var valueMin = 0;

                    if (parseInt(value1) > parseInt(value2)){
                        value2 = value1;
                        $("input#age_to").val(value2);
                    }

                    if (parseInt(value2) > parseInt(valueMax)){
                        value2 = valueMax;
                        $("input#age_to").val(value2);
                    }

                    if (parseInt(value2) < parseInt(valueMin)){
                        value2 = valueMin;
                        $("input#age_to").val(value2);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

            <?= $form->field($model, 'age_from', [
                'template' => '<div class="col-md-4" style="margin-top: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}</div>'
            ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 0 до 100)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'age_from',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form->field($model, 'age_to', [
                'template' => '<div class="col-md-4">{input}</div>'
            ])->label(false)->textInput([
                'type' => 'number',
                'id' => 'age_to',
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]);
            ?>

        </div>


        <div class="row" style="margin-bottom: 10px;">

            <?php
            $list_gender = [
                Segments::GENDER_ANY => 'Не важно',
                Segments::GENDER_MAN => 'Мужской',
                Segments::GENDER_WOMAN => 'Женский',
            ];
            ?>

            <?= $form->field($model, 'gender_consumer', [
                'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
            ])->label('<div>Пол потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                ->widget(Select2::class, [
                    'data' => $list_gender,

                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'Выберите пол потребителя'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
            ?>

        </div>


        <div class="row" style="margin-bottom: 10px;">

            <?php
            $list_education = [
                Segments::SECONDARY_EDUCATION => 'Среднее образование',
                Segments::SECONDARY_SPECIAL_EDUCATION => 'Среднее образование (специальное)',
                Segments::HIGHER_INCOMPLETE_EDUCATION => 'Высшее образование (незаконченное)',
                Segments::HIGHER_EDUCATION => 'Высшее образование'
            ];
            ?>

            <?= $form->field($model, 'education_of_consumer', [
                'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
            ])->label('<div>Образование потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                ->widget(Select2::class, [
                    'data' => $list_education,
                    'pluginOptions' => ['allowClear' => true],
                    'options' => ['placeholder' => 'Выберите уровень образования потребителя'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]);
            ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#income_from").change(function () {
                    var value1 = $("input#income_from").val();
                    var value2 = $("input#income_to").val();
                    var valueMax = 1000000;
                    var valueMin = 5000;

                    if (parseInt(value1) > parseInt(value2)){
                        value1 = value2;
                        $("input#income_from").val(value1);
                    }

                    if (parseInt(value1) > parseInt(valueMax)){
                        value1 = valueMax;
                        $("input#income_from").val(value1);
                    }

                    if (parseInt(value1) < parseInt(valueMin)){
                        value1 = valueMin;
                        $("input#income_from").val(value1);
                    }
                });

                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                $("input#income_to").change(function () {
                    var value1 = $("input#income_from").val();
                    var value2 = $("input#income_to").val();
                    var valueMax = 1000000;
                    var valueMin = 5000;

                    if (parseInt(value1) > parseInt(value2)){
                        value2 = value1;
                        $("input#income_to").val(value2);
                    }

                    if (parseInt(value2) > parseInt(valueMax)){
                        value2 = valueMax;
                        $("input#income_to").val(value2);
                    }

                    if (parseInt(value2) < parseInt(valueMin)){
                        value2 = valueMin;
                        $("input#income_to").val(value2);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 10px; margin-top: 0;">

            <?= $form->field($model, 'income_from', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
            ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 5 000 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'income_from',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form->field($model, 'income_to', [
                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
            ])->label(false)->textInput([
                'type' => 'number',
                'id' => 'income_to',
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]);
            ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#quantity_from").change(function () {
                    var value1 = $("input#quantity_from").val();
                    var value2 = $("input#quantity_to").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value1 = value2;
                        $("input#quantity_from").val(value1);
                    }

                    if (parseInt(value1) > parseInt(valueMax)){
                        value1 = valueMax;
                        $("input#quantity_from").val(value1);
                    }

                    if (parseInt(value1) < parseInt(valueMin)){
                        value1 = valueMin;
                        $("input#quantity_from").val(value1);
                    }
                });

                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                $("input#quantity_to").change(function () {
                    var value1 = $("input#quantity_from").val();
                    var value2 = $("input#quantity_to").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value2 = value1;
                        $("input#quantity_to").val(value2);
                    }

                    if (parseInt(value2) > parseInt(valueMax)){
                        value2 = valueMax;
                        $("input#quantity_to").val(value2);
                    }

                    if (parseInt(value2) < parseInt(valueMin)){
                        value2 = valueMin;
                        $("input#quantity_to").val(value2);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

            <?= $form->field($model, 'quantity_from', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
            ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'quantity_from',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form->field($model, 'quantity_to', [
                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
            ])->label(false)->textInput([
                'type' => 'number',
                'id' => 'quantity_to',
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]);
            ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#market_volume_b2c").change(function () {
                    var value = $("input#market_volume_b2c").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value) > parseInt(valueMax)){
                        value = valueMax;
                        $("input#market_volume_b2c").val(value);
                    }

                    if (parseInt(value) < parseInt(valueMin)){
                        value = valueMin;
                        $("input#market_volume_b2c").val(value);
                    }
                });
            } );
        </script>


        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

            <?= $form->field($model, 'market_volume_b2c', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div><div class="col-md-4" style="font-size: 14px; color: #999;">Платежеспособность целевого сегмента рассчитывается, как доход потребителя за год умноженный на количество потребителей</div>'
            ])->label('<div>Платежеспособность (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'market_volume_b2c',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

        </div>

    </div>


    <div class="form-template-b2b" style="display: none;">


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'field_of_activity_b2b', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Сфера деятельности предприятия *')->textInput([
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>

        </div>


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'sort_of_activity_b2b', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Вид / специализация деятельности предприятия *')->textInput([
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
                'autocomplete' => 'off'
            ]); ?>

        </div>


        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($model, 'company_products', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Продукция / услуги предприятия *')->textarea([
                'rows' => 1,
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]); ?>

        </div>


        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($model, 'company_partner', [
                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
            ])->label('Партнеры предприятия *')->textarea([
                'rows' => 1,
                'maxlength' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => '',
            ]); ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#quantity_from_b2b").change(function () {
                    var value1 = $("input#quantity_from_b2b").val();
                    var value2 = $("input#quantity_to_b2b").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value1 = value2;
                        $("input#quantity_from_b2b").val(value1);
                    }

                    if (parseInt(value1) > parseInt(valueMax)){
                        value1 = valueMax;
                        $("input#quantity_from_b2b").val(value1);
                    }

                    if (parseInt(value1) < parseInt(valueMin)){
                        value1 = valueMin;
                        $("input#quantity_from_b2b").val(value1);
                    }
                });

                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                $("input#quantity_to_b2b").change(function () {
                    var value1 = $("input#quantity_from_b2b").val();
                    var value2 = $("input#quantity_to_b2b").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value2 = value1;
                        $("input#quantity_to_b2b").val(value2);
                    }

                    if (parseInt(value2) > parseInt(valueMax)){
                        value2 = valueMax;
                        $("input#quantity_to_b2b").val(value2);
                    }

                    if (parseInt(value2) < parseInt(valueMin)){
                        value2 = valueMin;
                        $("input#quantity_to_b2b").val(value2);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($model, 'quantity_from_b2b', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
            ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'quantity_from_b2b',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form->field($model, 'quantity_to_b2b', [
                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
            ])->label(false)->textInput([
                'type' => 'number',
                'id' => 'quantity_to_b2b',
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]);
            ?>

        </div>


        <script>

            $( function() {

                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                $("input#income_from_b2b").change(function () {
                    var value1 = $("input#income_from_b2b").val();
                    var value2 = $("input#income_to_b2b").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value1 = value2;
                        $("input#income_from_b2b").val(value1);
                    }

                    if (parseInt(value1) > parseInt(valueMax)){
                        value1 = valueMax;
                        $("input#income_from_b2b").val(value1);
                    }

                    if (parseInt(value1) < parseInt(valueMin)){
                        value1 = valueMin;
                        $("input#income_from_b2b").val(value1);
                    }
                });

                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                $("input#income_to_b2b").change(function () {
                    var value1 = $("input#income_from_b2b").val();
                    var value2 = $("input#income_to_b2b").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value1) > parseInt(value2)){
                        value2 = value1;
                        $("input#income_to_b2b").val(value2);
                    }

                    if (parseInt(value2) > parseInt(valueMax)){
                        value2 = valueMax;
                        $("input#income_to_b2b").val(value2);
                    }

                    if (parseInt(value2) < parseInt(valueMin)){
                        value2 = valueMin;
                        $("input#income_to_b2b").val(value2);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'income_company_from', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
            ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'income_from_b2b',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

            <?= $form->field($model, 'income_company_to', [
                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
            ])->label(false)->textInput([
                'type' => 'number',
                'id' => 'income_to_b2b',
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]);
            ?>

        </div>


        <script>

            $( function() {

                $("input#market_volume_b2b").change(function () {
                    var value = $("input#market_volume_b2b").val();
                    var valueMax = 1000000;
                    var valueMin = 1;

                    if (parseInt(value) > parseInt(valueMax)){
                        value = valueMax;
                        $("input#market_volume_b2b").val(value);
                    }

                    if (parseInt(value) < parseInt(valueMin)){
                        value = valueMin;
                        $("input#market_volume_b2b").val(value);
                    }
                });

            } );
        </script>


        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'market_volume_b2b', [
                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div><div class="col-md-4" style="font-size: 14px; color: #999;">Платежеспособность целевого сегмента рассчитывается, как доход предприятия за год умноженный на количество представителей сегмента</div>'
            ])->label('<div>Платежеспособность (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                ->textInput([
                    'type' => 'number',
                    'id' => 'market_volume_b2b',
                    'class' => 'style_form_field_respond form-control',
                    'autocomplete' => 'off'
                ]);
            ?>

        </div>

    </div>


    <div class="row" style="margin-bottom: 15px;">

        <?= $form->field($model, 'add_info', [
            'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
        ])->textarea([
            'rows' => 1,
            'class' => 'style_form_field_respond form-control',
            'placeholder' => '',
        ]);
        ?>

    </div>

    <div class="form-group row container-fluid">
        <?= Html::submitButton('Сохранить', [
            'class' => 'btn btn-success pull-right',
            'style' => [
                'color' => '#FFFFFF',
                'background' => '#52BE7F',
                'padding' => '0 7px',
                'width' => '140px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ]
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
