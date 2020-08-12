<?php

use app\models\TypeOfActivityB2B;
use app\models\TypeOfActivityB2C;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\Segment;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\bootstrap\Modal;

?>

    <div class="segment-form">

        <?php $form = ActiveForm::begin(['id' => 'formCreateSegment', 'action' => Url::to(['/segment/create', 'id' => $project->id])]); ?>

        <div class="row" style="margin-bottom: 10px;">

            <?= $form->field($model, 'name', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-5">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Наименование сегмента *')->textInput(['maxlength' => true]);
            ?>

        </div>

        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($model, 'description', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Краткое описание сегмента *')->textarea(['rows' => 2]);
            ?>

        </div>

        <div class="row" style="margin-bottom: 10px;">

            <?php
            $list_of_interactions = [
                Segment::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
                Segment::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
            ];
            ?>

            <?= $form->field($model, 'type_of_interaction_between_subjects', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12 type_of_interaction">{input}</div><div class="col-md-12">{error}</div>'
            ])->label('Вид информационного и экономического взаимодействия между субъектами рынка *')->widget(Select2::class, [
                'data' => $list_of_interactions,
                'options' => [
                    'id' => 'type-interaction',
                ],
                'disabled' => false,  //Сделать поле неактивным
                'hideSearch' => true, //Скрытие поиска
            ]);
            ?>

        </div>


        <div class="form-template-b2c">

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $listOfAreasOfActivityB2C = TypeOfActivityB2C::getListOfAreasOfActivity();
                $listOfAreasOfActivityB2C = ArrayHelper::map($listOfAreasOfActivityB2C,'id', 'name');
                ?>

                <?= $form->field($model, 'field_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Сфера деятельности потребителя *')->widget(Select2::class, [
                    'data' => $listOfAreasOfActivityB2C,
                    'options' => [
                        'placeholder' => 'Выберите cферу деятельности потребителя',
                        'id' => 'listOfAreasOfActivityB2C',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'sort_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Вид деятельности потребителя *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfActivitiesB2C',
                        'placeholder' => 'Выберите вид деятельности потребителя'
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfAreasOfActivityB2C'],
                        'placeholder' => 'Выберите вид деятельности потребителя',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2c'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'specialization_of_activity_b2c', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Специализация вида деятельности потребителя *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfSpecializationsB2C',
                        'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfActivitiesB2C'],
                        'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2c'])
                    ]
                ]);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#age_from").change(function () {
                        var value1 = $("input#age_from").val();
                        var value2 = $("input#age_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#age_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#age_to").change(function () {
                        var value1 = $("input#age_from").val();
                        var value2 = $("input#age_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#age_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($model, 'age_from', [
                    'template' => '<div class="col-md-4" style="margin-top: 10px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}<div>{error}</div></div>'
                ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 100)</div>')
                    ->textInput(['type' => 'number', 'id' => 'age_from']);
                ?>

                <?= $form->field($model, 'age_to', [
                    'template' => '<div class="col-md-4">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'age_to']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_gender = [
                    Segment::GENDER_ANY => 'Не важно',
                    Segment::GENDER_MAN => 'Мужской',
                    Segment::GENDER_WOMAN => 'Женский',
                ];
                ?>

                <?= $form->field($model, 'gender_consumer', [
                    'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('<div>Пол потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                    ->widget(Select2::class, [
                        'data' => $list_gender,
                        'pluginOptions' => ['allowClear' => true],
                        'options' => [
                            //'id' => 'type-interaction',
                            'placeholder' => 'Выберите пол потребителя',
                        ],
                        'disabled' => false,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_education = [
                    Segment::SECONDARY_EDUCATION => 'Среднее образование',
                    Segment::SECONDARY_SPECIAL_EDUCATION => 'Среднее образование (специальное)',
                    Segment::HIGHER_INCOMPLETE_EDUCATION => 'Высшее образование (незаконченное)',
                    Segment::HIGHER_EDUCATION => 'Высшее образование'
                ];
                ?>

                <?= $form->field($model, 'education_of_consumer', [
                    'template' => '<div class="col-md-4">{label}</div><div class="col-md-8">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('<div>Образование потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                    ->widget(Select2::class, [
                        'data' => $list_education,
                        'pluginOptions' => ['allowClear' => true],
                        'options' => [
                            //'id' => 'type-interaction',
                            'placeholder' => 'Выберите уровень образования потребителя',
                        ],
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
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#income_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#income_to").change(function () {
                        var value1 = $("input#income_from").val();
                        var value2 = $("input#income_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#income_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: 0px;">

                <?= $form->field($model, 'income_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 5 000 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'income_from']);
                ?>

                <?= $form->field($model, 'income_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to']);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#quantity_from").change(function () {
                        var value1 = $("input#quantity_from").val();
                        var value2 = $("input#quantity_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#quantity_from").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#quantity_to").change(function () {
                        var value1 = $("input#quantity_from").val();
                        var value2 = $("input#quantity_to").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#quantity_to").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($model, 'quantity_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'quantity_from']);
                ?>

                <?= $form->field($model, 'quantity_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                <?= $form->field($model, 'market_volume_b2c', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'market_volume_b2c']);
                ?>

            </div>

        </div>


        <div class="form-template-b2b" style="display: none;">

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $listOfAreasOfActivityB2B = TypeOfActivityB2B::getListOfAreasOfActivity();
                $listOfAreasOfActivityB2B = ArrayHelper::map($listOfAreasOfActivityB2B,'id', 'name');
                ?>

                <?= $form->field($model, 'field_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Сфера деятельности предприятия *')->widget(Select2::class, [
                    'data' => $listOfAreasOfActivityB2B,
                    'options' => [
                        'placeholder' => 'Выберите cферу деятельности предприятия',
                        'id' => 'listOfAreasOfActivityB2B',
                    ],
                    'pluginOptions' => ['allowClear' => true]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'sort_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Вид деятельности предприятия *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfActivitiesB2B',
                        'placeholder' => 'Выберите вид деятельности предприятия',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfAreasOfActivityB2B'],
                        'placeholder' => 'Выберите вид деятельности предприятия',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-activities-for-selected-area-b2b'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'specialization_of_activity_b2b', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Специализация вида деятельности предприятия *')->widget(DepDrop::class, [
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'options' => [
                        'id' => 'listOfSpecializationsB2B',
                        'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                    ],
                    'pluginOptions' => [
                        'depends' => ['listOfActivitiesB2B'],
                        'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                        'nameParam' => 'name',
                        'url' => Url::to(['/segment/list-of-specializations-for-selected-activity-b2b'])
                    ]
                ]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($model, 'company_products', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Продукция / услуги предприятия *')->textarea(['rows' => 2]);
                ?>

            </div>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($model, 'company_partner', [
                    'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
                ])->label('Партнеры предприятия *')->textarea(['rows' => 2])
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#quantity_from_b2b").change(function () {
                        var value1 = $("input#quantity_from_b2b").val();
                        var value2 = $("input#quantity_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#quantity_from_b2b").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#quantity_to_b2b").change(function () {
                        var value1 = $("input#quantity_from_b2b").val();
                        var value2 = $("input#quantity_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#quantity_to_b2b").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($model, 'quantity_from_b2b', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'quantity_from_b2b']);
                ?>

                <?= $form->field($model, 'quantity_to_b2b', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to_b2b']);
                ?>

            </div>


            <script>

                $( function() {

                    //Изменение местоположения ползунка при вводе данных в первый элемент Input
                    $("input#income_from_b2b").change(function () {
                        var value1 = $("input#income_from_b2b").val();
                        var value2 = $("input#income_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value1 = value2;
                            $("input#income_from_b2b").val(value1);
                        }
                    });

                    //Изменение местоположения ползунка при вводе данных во второй элемент Input
                    $("input#income_to_b2b").change(function () {
                        var value1 = $("input#income_from_b2b").val();
                        var value2 = $("input#income_to_b2b").val();
                        if (parseInt(value1) > parseInt(value2)){
                            value2 = value1;
                            $("input#income_to_b2b").val(value2);
                        }
                    });

                } );
            </script>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'income_company_from', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'income_from_b2b']);
                ?>

                <?= $form->field($model, 'income_company_to', [
                    'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}<div>{error}</div></div>'
                ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to_b2b']);
                ?>

            </div>


            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($model, 'market_volume_b2b', [
                    'template' => '<div class="col-md-4" style="margin-bottom: 10px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}<div>{error}</div></div>'
                ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 1 до 1 000 000)</div>')
                    ->textInput(['type' => 'number', 'id' => 'market_volume_b2b']);
                ?>

            </div>

        </div>


        <div class="row" style="margin-bottom: 15px;">

            <?= $form->field($model, 'add_info', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div><div class="col-md-12">{error}</div>'
            ])->textarea(['rows' => 2]);
            ?>

        </div>

        <?php


        ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


        <?php
        // Модальное окно - Сегмент с таким именем уже существует
        Modal::begin([
            'options' => [
                'id' => 'segment_already_exists',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Сегмент с таким наименованием уже существует. Отредактируйте данное поле и сохраните форму.
        </h4>

        <?php
        Modal::end();
        ?>


        <?php
        // Модальное окно - Данные не загружены
        Modal::begin([
            'options' => [
                'id' => 'data_not_loaded',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Для создания сегмента необходимо<br>заполнить все поля со знаком *
        </h4>

        <?php
        Modal::end();
        ?>

    </div>


<?php

$script = "
    
    $(document).ready(function() {

        // Проверка установленного значения B2C/B2B
        setInterval(function(){
        
            if($('#select2-type-interaction-container').html() === 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)'){
                
                $('.form-template-b2b').hide();
                $('.form-template-b2c').show();
            }
            
            else {
                
                $('.form-template-b2b').show();
                $('.form-template-b2c').hide();
            }
            
        }, 1000);
        
        //Фон для модального окна информации (сегмент с таким именем уже существует)
        var segment_already_exists_modal = $('#segment_already_exists').find('.modal-content');
        segment_already_exists_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации (данные не загружены)
        var data_not_loaded_modal = $('#data_not_loaded').find('.modal-content');
        data_not_loaded_modal.css('background-color', '#707F99');

    });
    
    
    $('#formCreateSegment').on('beforeSubmit', function(e){
        
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                //Если данные загружены и проверены
                if(response['success']){
                    
                    window.location.href = '/interview/create?id=' + response['new_segment_id'];
                }
                
                //Если сегмент с таким именем уже существует 
                if(response['segment_already_exists']){
                
                    $('#segment_already_exists').modal('show');
                }
                
                //Если данные не загружены
                if(response['data_not_loaded']){
                
                    $('#data_not_loaded').modal('show');
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
$this->registerJs($script, $position);

?>