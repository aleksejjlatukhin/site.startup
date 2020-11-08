<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use app\models\TypeOfActivityB2B;
use app\models\TypeOfActivityB2C;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\Segment;
use kartik\select2\Select2;
use app\models\SegmentSort;

$this->title = 'Генерация гипотез целевых сегментов';

$this->registerCssFile('@web/css/segments-index-style.css');

?>
    <div class="segment-index">


        <div class="row project_info_data">

            <div class="col-xs-12 col-md-12 col-lg-4 project_name">
                <span>Проект:</span>
                <?= $project->project_name; ?>
            </div>

            <?= Html::a('Данные проекта', ['/projects/show-all-information', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openAllInformationProject link_in_the_header',
            ]) ?>

            <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
                'onclick' => 'return false',
            ]) ?>

            <?= Html::a('Дорожная карта проекта', ['/projects/show-roadmap', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openRoadmapProject link_in_the_header text-center',
            ]) ?>

            <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 link_in_the_header text-center',
                'onclick' => 'return false',
            ]) ?>

        </div>


        <div class="row navigation_blocks">

            <div class="active_navigation_block navigation_block">
                <div class="stage_number">1</div>
                <div>Генерация гипотез целевых сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">2</div>
                <div>Подтверждение гипотез целевых сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">3</div>
                <div>Генерация гипотез проблем сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">4</div>
                <div>Подтверждение гипотез проблем сегментов</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">5</div>
                <div>Разработка гипотез ценностных предложений</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">6</div>
                <div>Подтверждение гипотез ценностных предложений</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">7</div>
                <div>Разработка MVP</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">8</div>
                <div>Подтверждение MVP</div>
            </div>

            <div class="no_transition_navigation_block navigation_block">
                <div class="stage_number">9</div>
                <div>Генерация бизнес-модели</div>
            </div>

        </div>


        <div class="container-fluid container-data row">

            <div class="row row_header_data_generation">

                <?php
                $form = ActiveForm::begin([
                    'id' => 'sorting_segments',
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]);
                ?>


                <?php

                $listFields = SegmentSort::getListFields();
                $listFields = ArrayHelper::map($listFields,'id', 'name');

                ?>


                <div class="col-md-1"></div>


                <div class="col-md-4">

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

                <div class="col-md-4">

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
                                'url' => Url::to(['/segment/list-type-sort'])
                            ]
                        ]);
                    ?>
                </div>

                <?php
                ActiveForm::end();
                ?>

                <div class="col-md-3" style="padding: 0;">

                    <?=  Html::a( '<div class="new_segment_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый сегмент</div></div>', ['#'],
                        ['data-toggle' => 'modal',
                            'data-target' => "#create_segment_modal",
                            'class' => 'new_segment_link_plus pull-right']
                    );
                    ?>

                </div>
            </div>


            <!--Заголовки для списка сегментов-->
            <div class="row" style="margin: 0; padding: 10px;">

                <div class="col-md-3 headers_data_respond_hi">
                    <div class="row">
                        <div class="col-md-1" style="padding: 0;"></div>
                        <div class="col-md-8">Наименование сегмента</div>
                        <div class="col-md-3 text-center">Тип</div>
                    </div>
                </div>

                <div class="col-md-2 headers_data_respond_hi">
                    Сфера деятельности
                </div>

                <div class="col-md-2 headers_data_respond_hi">
                    Вид деятельности
                </div>

                <div class="col-md-2 headers_data_respond_hi">
                    Специализация
                </div>

                <div class="col-md-2 text-center" style="padding-right: 100px;">

                    <div class="headers_data_respond_hi">
                        Объем рынка
                    </div>
                    <div class="headers_data_respond_low" style="padding-left: 10px;">
                        млн. руб./год
                    </div>
                </div>

                <div class="col-md-1"></div>

            </div>


            <div class="block_all_segments_project row" style="padding-left: 10px; padding-right: 10px;">

                <!--Данные для списка сегментов-->
                <?php foreach ($models as $model) : ?>


                    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>" style="margin: 3px 0;">

                        <div class="col-md-3" style="padding-left: 5px; padding-right: 5px;">

                            <div class="row" style="display:flex; align-items: center;">

                                <div class="col-md-1" style="padding-bottom: 3px;">

                                    <?php
                                    if ($model->exist_confirm === 1) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                    }elseif ($model->exist_confirm === null && empty($model->interview)) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                    }elseif ($model->exist_confirm === null && !empty($model->interview)) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                    }elseif ($model->exist_confirm === 0) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                    }
                                    ?>

                                </div>

                                <div class="col-md-8">

                                    <div class="hypothesis_title" style="padding-left: 15px;">
                                        <?= $model->name;?>
                                    </div>

                                </div>

                                <div class="col-md-3 text-center">

                                    <?php

                                    if ($model->type_of_interaction_between_subjects === Segment::TYPE_B2C) {
                                        echo '<div class="">B2C</div>';
                                    }
                                    elseif ($model->type_of_interaction_between_subjects === Segment::TYPE_B2B) {
                                        echo '<div class="">B2B</div>';
                                    }

                                    ?>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-2">

                            <?php

                            $field_of_activity = $model->field_of_activity;

                            if (mb_strlen($field_of_activity) > 50) {
                                $field_of_activity = mb_substr($field_of_activity, 0, 50);
                                $field_of_activity = $field_of_activity . ' ...';
                            }

                            echo '<div title="' . $model->field_of_activity . '">' . $field_of_activity . '</div>';

                            ?>

                        </div>

                        <div class="col-md-2">

                            <?php

                            $sort_of_activity = $model->sort_of_activity;

                            if (mb_strlen($sort_of_activity) > 50) {
                                $sort_of_activity = mb_substr($sort_of_activity, 0, 50);
                                $sort_of_activity = $sort_of_activity . ' ...';
                            }

                            echo '<div title="' . $model->sort_of_activity . '">' . $sort_of_activity . '</div>';

                            ?>

                        </div>

                        <div class="col-md-2">

                            <?php

                            $specialization_of_activity = $model->specialization_of_activity;

                            if (mb_strlen($specialization_of_activity) > 50) {
                                $specialization_of_activity = mb_substr($specialization_of_activity, 0, 50);
                                $specialization_of_activity = $specialization_of_activity . ' ...';
                            }

                            echo '<div title="' . $model->specialization_of_activity . '">' . $specialization_of_activity . '</div>';

                            ?>

                        </div>


                        <div class="col-md-1">

                            <?php

                            echo '<div class="text-right">' . number_format($model->market_volume, 0, '', ' ') . '</div>';

                            ?>

                        </div>


                        <div class="col-md-2">

                            <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                                <div style="margin-right: 25px;">

                                    <?php if ($model->interview) : ?>

                                        <?= Html::a('Далее', ['/interview/view', 'id' => $model->interview->id], [
                                            'class' => 'btn btn-default',
                                            'style' => [
                                                'display' => 'flex',
                                                'align-items' => 'center',
                                                'justify-content' => 'center',
                                                'color' => '#FFFFFF',
                                                'background' => '#52BE7F',
                                                'width' => '120px',
                                                'height' => '40px',
                                                'font-size' => '18px',
                                                'border-radius' => '8px',
                                            ]
                                        ]);
                                        ?>

                                    <?php else : ?>

                                        <?= Html::a('Подтвердить', ['/interview/create', 'id' => $model->id], [
                                            'class' => 'btn btn-default',
                                            'style' => [
                                                'display' => 'flex',
                                                'align-items' => 'center',
                                                'justify-content' => 'center',
                                                'color' => '#FFFFFF',
                                                'background' => '#707F99',
                                                'width' => '120px',
                                                'height' => '40px',
                                                'font-size' => '18px',
                                                'border-radius' => '8px',
                                            ]
                                        ]);
                                        ?>

                                    <?php endif; ?>

                                </div>

                                <div>

                                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segment/update', 'id' => $model->id], [
                                            'class' => '',
                                            'title' => 'Редактировать',
                                            'data-toggle' => 'modal',
                                            'data-target' => "#update_segment_modal-$model->id",
                                        ]); ?>

                                    <?php else : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segment/show-all-information', 'id' => $model->id], [
                                            'class' => 'openAllInformationSegment', 'title' => 'Смотреть',
                                        ]); ?>

                                    <?php endif; ?>

                                </div>

                                <div >

                                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segment/delete', 'id' => $model->id], [
                                        'class' => 'delete_hypothesis',
                                        'title' => 'Удалить',
                                    ]); ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach;?>

            </div>

        </div>


        <?php if (count($models) > 0) : ?>

            <div class="row information_status_confirm">

                <div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Сегмент подтвержден</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Сегмент не подтвержден</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Сегмент ожидает подтверждения</div>
                    </div>

                </div>

            </div>

        <?php endif; ?>


        <?php
        // Модальное окно - создание нового сегмента
        Modal::begin([
            'options' => [
                'id' => 'create_segment_modal',
            ],
            'size' => 'modal-lg',
            'header' => '<h3 class="text-center">Создание нового сегмента</h3>',
        ]);
        ?>

        <div class="segment-form-create">

            <?php $form = ActiveForm::begin([
                'id' => 'formCreateSegment',
                'action' => Url::to(['/segment/create', 'id' => $project->id]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="row" style="margin-bottom: 10px;">

                <?= $form->field($newSegment, 'name', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-5">{input}</div>'
                ])->label('Наименование сегмента *')->textInput([
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($newSegment, 'description', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                ])->label('Краткое описание сегмента *')->textarea([
                    'rows' => 1,
                    'maxlength' => true,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => '',
                ]);
                ?>

            </div>

            <div class="row" style="margin-bottom: 10px;">

                <?php
                $list_of_interactions = [
                    Segment::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
                    Segment::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
                ];
                ?>

                <?= $form->field($newSegment, 'type_of_interaction_between_subjects', [
                    'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12 type_of_interaction">{input}</div>'
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

                    <?= $form->field($newSegment, 'field_of_activity_b2c', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'sort_of_activity_b2c', [
                        'template' => '<div class="col-md-12"style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'specialization_of_activity_b2c', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'age_from', [
                        'template' => '<div class="col-md-4" style="margin-top: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}</div>'
                    ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 0 до 100)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'age_from',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                    <?= $form->field($newSegment, 'age_to', [
                        'template' => '<div class="col-md-4">{input}</div>'
                    ])->label(false)->textInput([
                        'type' => 'number',
                        'id' => 'age_to',
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control'
                    ]);
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

                    <?= $form->field($newSegment, 'gender_consumer', [
                        'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
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

                    <?= $form->field($newSegment, 'education_of_consumer', [
                        'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
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


                <div class="row" style="margin-bottom: 10px; margin-top: 0px;">

                    <?= $form->field($newSegment, 'income_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 5 000 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'income_from',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                    <?= $form->field($newSegment, 'income_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                    ])->label(false)->textInput([
                        'type' => 'number',
                        'id' => 'income_to',
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control'
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

                    <?= $form->field($newSegment, 'quantity_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'quantity_from',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                    <?= $form->field($newSegment, 'quantity_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                    ])->label(false)->textInput([
                        'type' => 'number',
                        'id' => 'quantity_to',
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control'
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

                    <?= $form->field($newSegment, 'market_volume_b2c', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'market_volume_b2c',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                </div>

            </div>


            <div class="form-template-b2b" style="display: none;">

                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $listOfAreasOfActivityB2B = TypeOfActivityB2B::getListOfAreasOfActivity();
                    $listOfAreasOfActivityB2B = ArrayHelper::map($listOfAreasOfActivityB2B,'id', 'name');
                    ?>

                    <?= $form->field($newSegment, 'field_of_activity_b2b', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'sort_of_activity_b2b', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'specialization_of_activity_b2b', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
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

                    <?= $form->field($newSegment, 'company_products', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Продукция / услуги предприятия *')->textarea([
                        'rows' => 1,
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>


                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($newSegment, 'company_partner', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Партнеры предприятия *')->textarea([
                        'rows' => 1,
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

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

                    <?= $form->field($newSegment, 'quantity_from_b2b', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'quantity_from_b2b',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                    <?= $form->field($newSegment, 'quantity_to_b2b', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                    ])->label(false)->textInput([
                        'type' => 'number',
                        'id' => 'quantity_to_b2b',
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control'
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

                    <?= $form->field($newSegment, 'income_company_from', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'income_from_b2b',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                    <?= $form->field($newSegment, 'income_company_to', [
                        'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                    ])->label(false)->textInput([
                        'type' => 'number',
                        'id' => 'income_to_b2b',
                        //'required' => true,
                        'class' => 'style_form_field_respond form-control'
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

                    <?= $form->field($newSegment, 'market_volume_b2b', [
                        'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                    ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                        ->textInput([
                            'type' => 'number',
                            'id' => 'market_volume_b2b',
                            //'required' => true,
                            'class' => 'style_form_field_respond form-control'
                        ]);
                    ?>

                </div>

            </div>


            <div class="row" style="margin-bottom: 15px;">

                <?= $form->field($newSegment, 'add_info', [
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

        <?php
        Modal::end();
        ?>



        <?php

        foreach ($models as $i => $model) :

            // Модальное окно - Редактирование сегмента
            Modal::begin([
                'options' => [
                    'id' => 'update_segment_modal-' . $model->id,
                ],
                'size' => 'modal-lg',
                'header' => '<h3 class="text-center">Редактирование данных сегмента</h3>',
            ]);
            ?>


            <div class="segment-update-form">

                <?php $form = ActiveForm::begin([
                    'id' => 'formUpdateSegment-' .$model->id,
                    'action' => Url::to(['/segment/update', 'id' => $model->id]),
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <div class="row" style="margin-bottom: 10px;">

                    <?= $form->field($updateSegments[$i], 'name', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-5">{input}</div>'
                    ])->label('Наименование сегмента *')->textInput([
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>

                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($updateSegments[$i], 'description', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->label('Краткое описание сегмента *')->textarea([
                        'rows' => 1,
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>

                <div class="row" style="margin-bottom: 10px;">

                    <?php
                    $list_of_interactions = [
                        Segment::TYPE_B2C => 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)',
                        Segment::TYPE_B2B => 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)'
                    ];
                    ?>

                    <?= $form->field($updateSegments[$i], 'type_of_interaction_between_subjects', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12 type_of_interaction">{input}</div>'
                    ])->label('Вид информационного и экономического взаимодействия между субъектами рынка *')->widget(Select2::class, [
                        'data' => $list_of_interactions,
                        'options' => [
                            'id' => 'type-interaction-' . $model->id,
                        ],
                        'disabled' => true,  //Сделать поле неактивным
                        'hideSearch' => true, //Скрытие поиска
                    ]);
                    ?>

                </div>


                <?php if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C) : ?>


                    <div class="form-update-template-b2c-<?= $model->id; ?>">

                        <div class="row" style="margin-bottom: 10px;">

                            <?php
                            $listOfAreasOfActivityB2C = TypeOfActivityB2C::getListOfAreasOfActivity();
                            $listOfAreasOfActivityB2C = ArrayHelper::map($listOfAreasOfActivityB2C,'id', 'name');
                            ?>

                            <?= $form->field($updateSegments[$i], 'field_of_activity_b2c', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Сфера деятельности потребителя *')->widget(Select2::class, [
                                'data' => $listOfAreasOfActivityB2C,
                                'options' => [
                                    'placeholder' => 'Выберите cферу деятельности потребителя',
                                    'id' => 'listOfAreasOfActivityB2C-' . $model->id,
                                ],
                                'disabled' => true,  //Сделать поле неактивным
                                'pluginOptions' => ['allowClear' => true]
                            ]);
                            ?>

                        </div>


                        <div class="row" style="margin-bottom: 10px;">

                            <?= $form->field($updateSegments[$i], 'sort_of_activity_b2c', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Вид деятельности потребителя *')->widget(DepDrop::class, [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                'options' => [
                                    'id' => 'listOfActivitiesB2C-' . $model->id,
                                    'placeholder' => 'Выберите вид деятельности потребителя'
                                ],
                                'disabled' => true,  //Сделать поле неактивным
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

                            <?= $form->field($updateSegments[$i], 'specialization_of_activity_b2c', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Специализация вида деятельности потребителя *')->widget(DepDrop::class, [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                'options' => [
                                    'id' => 'listOfSpecializationsB2C-' . $model->id,
                                    'placeholder' => 'Выберите cпециализацию вида деятельности потребителя',
                                ],
                                'disabled' => true,  //Сделать поле неактивным
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

                                var age_from = 'input#age_from-<?= $model->id; ?>';
                                var age_to = 'input#age_to-<?= $model->id; ?>';

                                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                                $(age_from).change(function () {
                                    var value1 = $(age_from).val();
                                    var value2 = $(age_to).val();
                                    var valueMax = 100;
                                    var valueMin = 0;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value1 = value2;
                                        $(age_from).val(value1);
                                    }

                                    if (parseInt(value1) > parseInt(valueMax)){
                                        value1 = valueMax;
                                        $(age_from).val(value1);
                                    }

                                    if (parseInt(value1) < parseInt(valueMin)){
                                        value1 = valueMin;
                                        $(age_from).val(value1);
                                    }
                                });

                                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                                $(age_to).change(function () {
                                    var value1 = $(age_from).val();
                                    var value2 = $(age_to).val();
                                    var valueMax = 100;
                                    var valueMin = 0;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value2 = value1;
                                        $(age_to).val(value2);
                                    }

                                    if (parseInt(value2) > parseInt(valueMax)){
                                        value2 = valueMax;
                                        $(age_to).val(value2);
                                    }

                                    if (parseInt(value2) < parseInt(valueMin)){
                                        value2 = valueMin;
                                        $(age_to).val(value2);
                                    }
                                });

                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                            <?= $form->field($updateSegments[$i], 'age_from', [
                                'template' => '<div class="col-md-4" style="margin-top: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-top: 15px;">{input}</div>'
                            ])->label('<div>Возраст потребителя *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 0 до 100)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'age_from-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                            <?= $form->field($updateSegments[$i], 'age_to', [
                                'template' => '<div class="col-md-4">{input}</div>'
                            ])->label(false)->textInput([
                                'type' => 'number',
                                'id' => 'age_to-' . $model->id,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control'
                            ]);
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

                            <?= $form->field($updateSegments[$i], 'gender_consumer', [
                                'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
                            ])->label('<div>Пол потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                                ->widget(Select2::class, [
                                    'data' => $list_gender,
                                    'pluginOptions' => ['allowClear' => true],
                                    'options' => [
                                        'id' => 'gender_consumer-' . $model->id,
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

                            <?= $form->field($updateSegments[$i], 'education_of_consumer', [
                                'template' => '<div class="col-md-4" style="padding-left: 20px;">{label}</div><div class="col-md-8">{input}</div>'
                            ])->label('<div>Образование потребителя *</div><div style="font-weight: 400;font-size: 13px;padding-bottom: 10px;">(укажите нужное значение)</div>')
                                ->widget(Select2::class, [
                                    'data' => $list_education,
                                    'pluginOptions' => ['allowClear' => true],
                                    'options' => [
                                        'id' => 'education_of_consumer-' . $model->id,
                                        'placeholder' => 'Выберите уровень образования потребителя',
                                    ],
                                    'disabled' => false,  //Сделать поле неактивным
                                    'hideSearch' => true, //Скрытие поиска
                                ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var income_from = 'input#income_from-<?= $model->id; ?>';
                                var income_to = 'input#income_to-<?= $model->id; ?>';

                                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                                $(income_from).change(function () {
                                    var value1 = $(income_from).val();
                                    var value2 = $(income_to).val();
                                    var valueMax = 1000000;
                                    var valueMin = 5000;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value1 = value2;
                                        $(income_from).val(value1);
                                    }

                                    if (parseInt(value1) > parseInt(valueMax)){
                                        value1 = valueMax;
                                        $(income_from).val(value1);
                                    }

                                    if (parseInt(value1) < parseInt(valueMin)){
                                        value1 = valueMin;
                                        $(income_from).val(value1);
                                    }
                                });

                                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                                $(income_to).change(function () {
                                    var value1 = $(income_from).val();
                                    var value2 = $(income_to).val();
                                    var valueMax = 1000000;
                                    var valueMin = 5000;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value2 = value1;
                                        $(income_to).val(value2);
                                    }

                                    if (parseInt(value2) > parseInt(valueMax)){
                                        value2 = valueMax;
                                        $(income_to).val(value2);
                                    }

                                    if (parseInt(value2) < parseInt(valueMin)){
                                        value2 = valueMin;
                                        $(income_to).val(value2);
                                    }
                                });

                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px; margin-top: 0px;">

                            <?= $form->field($updateSegments[$i], 'income_from', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Доход потребителя (руб./мес.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 5 000 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'income_from-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                            <?= $form->field($updateSegments[$i], 'income_to', [
                                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                            ])->label(false)->textInput([
                                'type' => 'number',
                                'id' => 'income_to-' . $model->id,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control'
                            ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var quantity_from = 'input#quantity_from-<?= $model->id; ?>';
                                var quantity_to = 'input#quantity_to-<?= $model->id; ?>';

                                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                                $(quantity_from).change(function () {
                                    var value1 = $(quantity_from).val();
                                    var value2 = $(quantity_to).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value1 = value2;
                                        $(quantity_from).val(value1);
                                    }

                                    if (parseInt(value1) > parseInt(valueMax)){
                                        value1 = valueMax;
                                        $(quantity_from).val(value1);
                                    }

                                    if (parseInt(value1) < parseInt(valueMin)){
                                        value1 = valueMin;
                                        $(quantity_from).val(value1);
                                    }
                                });

                                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                                $(quantity_to).change(function () {
                                    var value1 = $(quantity_from).val();
                                    var value2 = $(quantity_to).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value2 = value1;
                                        $(quantity_to).val(value2);
                                    }

                                    if (parseInt(value2) > parseInt(valueMax)){
                                        value2 = valueMax;
                                        $(quantity_to).val(value2);
                                    }

                                    if (parseInt(value2) < parseInt(valueMin)){
                                        value2 = valueMin;
                                        $(quantity_to).val(value2);
                                    }
                                });

                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                            <?= $form->field($updateSegments[$i], 'quantity_from', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Потенциальное количество<br>потребителей (тыс. чел.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'quantity_from-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                            <?= $form->field($updateSegments[$i], 'quantity_to', [
                                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                            ])->label(false)->textInput([
                                'type' => 'number',
                                'id' => 'quantity_to-' . $model->id,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control'
                            ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var market_volume_b2c = 'input#market_volume_b2c-<?= $model->id; ?>';

                                $(market_volume_b2c).change(function () {
                                    var value = $(market_volume_b2c).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;


                                    if (parseInt(value) > parseInt(valueMax)){
                                        value = valueMax;
                                        $(market_volume_b2c).val(value);
                                    }

                                    if (parseInt(value) < parseInt(valueMin)){
                                        value = valueMin;
                                        $(market_volume_b2c).val(value);
                                    }
                                });
                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px; margin-top: -10px;">

                            <?= $form->field($updateSegments[$i], 'market_volume_b2c', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'market_volume_b2c-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                        </div>

                    </div>


                <?php else : ?>

                    <div class="form-update-template-b2b-<?= $model->id; ?>">

                        <div class="row" style="margin-bottom: 10px;">

                            <?php
                            $listOfAreasOfActivityB2B = TypeOfActivityB2B::getListOfAreasOfActivity();
                            $listOfAreasOfActivityB2B = ArrayHelper::map($listOfAreasOfActivityB2B,'id', 'name');
                            ?>

                            <?= $form->field($updateSegments[$i], 'field_of_activity_b2b', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Сфера деятельности предприятия *')->widget(Select2::class, [
                                'data' => $listOfAreasOfActivityB2B,
                                'options' => [
                                    'placeholder' => 'Выберите cферу деятельности предприятия',
                                    'id' => 'listOfAreasOfActivityB2B-' . $model->id,
                                ],
                                'disabled' => true,  //Сделать поле неактивным
                                'pluginOptions' => ['allowClear' => true]
                            ]);
                            ?>

                        </div>


                        <div class="row" style="margin-bottom: 10px;">

                            <?= $form->field($updateSegments[$i], 'sort_of_activity_b2b', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Вид деятельности предприятия *')->widget(DepDrop::class, [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                'options' => [
                                    'id' => 'listOfActivitiesB2B-' . $model->id,
                                    'placeholder' => 'Выберите вид деятельности предприятия',
                                ],
                                'disabled' => true,  //Сделать поле неактивным
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

                            <?= $form->field($updateSegments[$i], 'specialization_of_activity_b2b', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Специализация вида деятельности предприятия *')->widget(DepDrop::class, [
                                'type' => DepDrop::TYPE_SELECT2,
                                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                                'options' => [
                                    'id' => 'listOfSpecializationsB2B-' . $model->id,
                                    'placeholder' => 'Выберите cпециализацию вида деятельности предприятия',
                                ],
                                'disabled' => true,  //Сделать поле неактивным
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

                            <?= $form->field($updateSegments[$i], 'company_products', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Продукция / услуги предприятия *')->textarea([
                                'rows' => 1,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]);
                            ?>

                        </div>


                        <div class="row" style="margin-bottom: 15px;">

                            <?= $form->field($updateSegments[$i], 'company_partner', [
                                'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                            ])->label('Партнеры предприятия *')->textarea([
                                'rows' => 1,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => '',
                            ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var quantity_from_b2b = 'input#quantity_from_b2b-<?= $model->id; ?>';
                                var quantity_to_b2b = 'input#quantity_to_b2b-<?= $model->id; ?>';

                                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                                $(quantity_from_b2b).change(function () {
                                    var value1 = $(quantity_from_b2b).val();
                                    var value2 = $(quantity_to_b2b).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value1 = value2;
                                        $(quantity_from_b2b).val(value1);
                                    }

                                    if (parseInt(value1) > parseInt(valueMax)){
                                        value1 = valueMax;
                                        $(quantity_from_b2b).val(value1);
                                    }

                                    if (parseInt(value1) < parseInt(valueMin)){
                                        value1 = valueMin;
                                        $(quantity_from_b2b).val(value1);
                                    }
                                });

                                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                                $(quantity_to_b2b).change(function () {
                                    var value1 = $(quantity_from_b2b).val();
                                    var value2 = $(quantity_to_b2b).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value2 = value1;
                                        $(quantity_to_b2b).val(value2);
                                    }

                                    if (parseInt(value2) > parseInt(valueMax)){
                                        value2 = valueMax;
                                        $(quantity_to_b2b).val(value2);
                                    }

                                    if (parseInt(value2) < parseInt(valueMin)){
                                        value2 = valueMin;
                                        $(quantity_to_b2b).val(value2);
                                    }
                                });

                            } );
                        </script>


                        <div class="row" style="margin-bottom: 15px;">

                            <?= $form->field($updateSegments[$i], 'quantity_from_b2b', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Потенциальное количество<br>представителей сегмента (ед.) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'quantity_from_b2b-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                            <?= $form->field($updateSegments[$i], 'quantity_to_b2b', [
                                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                            ])->label(false)->textInput([
                                'type' => 'number',
                                'id' => 'quantity_to_b2b-' . $model->id,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control'
                            ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var income_from_b2b = 'input#income_from_b2b-<?= $model->id; ?>';
                                var income_to_b2b = 'input#income_to_b2b-<?= $model->id; ?>';

                                //Изменение местоположения ползунка при вводе данных в первый элемент Input
                                $(income_from_b2b).change(function () {
                                    var value1 = $(income_from_b2b).val();
                                    var value2 = $(income_to_b2b).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value1 = value2;
                                        $(income_from_b2b).val(value1);
                                    }

                                    if (parseInt(value1) > parseInt(valueMax)){
                                        value1 = valueMax;
                                        $(income_from_b2b).val(value1);
                                    }

                                    if (parseInt(value1) < parseInt(valueMin)){
                                        value1 = valueMin;
                                        $(income_from_b2b).val(value1);
                                    }
                                });

                                //Изменение местоположения ползунка при вводе данных во второй элемент Input
                                $(income_to_b2b).change(function () {
                                    var value1 = $(income_from_b2b).val();
                                    var value2 = $(income_to_b2b).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value1) > parseInt(value2)){
                                        value2 = value1;
                                        $(income_to_b2b).val(value2);
                                    }

                                    if (parseInt(value2) > parseInt(valueMax)){
                                        value2 = valueMax;
                                        $(income_to_b2b).val(value2);
                                    }

                                    if (parseInt(value2) < parseInt(valueMin)){
                                        value2 = valueMin;
                                        $(income_to_b2b).val(value2);
                                    }
                                });

                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px;">

                            <?= $form->field($updateSegments[$i], 'income_company_from', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Доход предприятия (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'income_from_b2b-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                            <?= $form->field($updateSegments[$i], 'income_company_to', [
                                'template' => '<div class="col-md-4" style="margin-top: -15px;">{input}</div>'
                            ])->label(false)->textInput([
                                'type' => 'number',
                                'id' => 'income_to_b2b-' . $model->id,
                                //'required' => true,
                                'class' => 'style_form_field_respond form-control'
                            ]);
                            ?>

                        </div>


                        <script>

                            $( function() {

                                var market_volume_b2b = 'input#market_volume_b2b-<?= $model->id; ?>';

                                $(market_volume_b2b).change(function () {
                                    var value = $(market_volume_b2b).val();
                                    var valueMax = 1000000;
                                    var valueMin = 1;

                                    if (parseInt(value) > parseInt(valueMax)){
                                        value = valueMax;
                                        $(market_volume_b2b).val(value);
                                    }

                                    if (parseInt(value) < parseInt(valueMin)){
                                        value = valueMin;
                                        $(market_volume_b2b).val(value);
                                    }
                                });
                            } );
                        </script>


                        <div class="row" style="margin-bottom: 10px;">

                            <?= $form->field($updateSegments[$i], 'market_volume_b2b', [
                                'template' => '<div class="col-md-4" style="margin-bottom: 10px; padding-left: 20px;">{label}</div>
                <div class="col-md-4" style="margin-bottom: 30px;">{input}</div>'
                            ])->label('<div>Объем рынка (млн. руб./год) *</div><div style="font-weight: 400;font-size: 13px;">(укажите значения от 1 до 1 000 000)</div>')
                                ->textInput([
                                    'type' => 'number',
                                    'id' => 'market_volume_b2b-' . $model->id,
                                    //'required' => true,
                                    'class' => 'style_form_field_respond form-control'
                                ]);
                            ?>

                        </div>

                    </div>

                <?php endif; ?>



                <div class="row" style="margin-bottom: 15px;">

                    <?= $form->field($updateSegments[$i], 'add_info', [
                        'template' => '<div class="col-md-12" style="padding-left: 20px;">{label}</div><div class="col-md-12">{input}</div>'
                    ])->textarea([
                        'rows' => 1,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]);
                    ?>

                </div>

                <?php


                ?>

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


            <?php Modal::end(); ?>

        <?php endforeach; ?>


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
            Для сохранения формы сегмента необходимо<br>заполнить все поля со знаком *
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
        
        
        //Возвращение скролла первого модального окна после закрытия второго
        $('.modal').on('hidden.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
                $('body').addClass('modal-open');
            }
        }).on('show.bs.modal', function (e) {
            if($('.modal:visible').length)
            {
                $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
                $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
            }
        });
    
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
                
                    //Закрываем модальное окно и делаем перезагрузку 
                    $('#create_segment_modal').modal('hide');
                    location.reload();
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
    
    
    
    //Сортировка сегментов
    $('#listType').change(function(){
    
        var current_url = window.location.href;
        current_url = current_url.split('=');
        var current_id = current_url[1];
    
        var select_value = $('#listType').val();
        
        if (select_value !== null) {
        
            var url = '/segment/sorting-models?current_id=' + current_id + '&type_sort_id=' + select_value;
            
            $.ajax({
                url: url,
                method: 'POST',
                cache: false,
                success: function(response){
                
                    $('.block_all_segments_project').html('');
                    $('.block_all_segments_project').html(response.content);
                    
                },
                error: function(){
                    alert('Ошибка')
                ;}
            });
        }
        
    });

";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>



<?php

foreach ($models as $model) :

    $script2 = "
    
    $('#formUpdateSegment-".$model->id."').on('beforeSubmit', function(e){
        
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
                    
                    //Закрываем модальное окно и делаем перезагрузку        
                    $('#update_segment_modal-".$model->id."').modal('hide');
                    location.reload();
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
    $this->registerJs($script2, $position);

endforeach;
?>