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
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openReportProject link_in_the_header text-center',
            ]) ?>

            <?= Html::a('Дорожная карта проекта', ['/projects/show-roadmap', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openRoadmapProject link_in_the_header text-center',
            ]) ?>

            <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->id], [
                'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openResultTableProject link_in_the_header text-center',
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

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый сегмент</div></div>', ['/segment/get-hypothesis-to-create', 'id' => $project->id],
                            ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-right']
                        );
                        ?>

                    <?php endif; ?>

                </div>
            </div>


            <!--Заголовки для списка сегментов-->
            <div class="row all_headers_data_hypothesis">

                <div class="col-md-3 headers_data_hypothesis_hi">
                    <div class="row">
                        <div class="col-md-1" style="padding: 0;"></div>
                        <div class="col-md-8">Наименование сегмента</div>
                        <div class="col-md-3 text-center">Тип</div>
                    </div>
                </div>

                <div class="col-md-2 headers_data_hypothesis_hi" style="padding-left: 10px;">
                    Сфера деятельности
                </div>

                <div class="col-md-2 headers_data_hypothesis_hi">
                    Вид деятельности
                </div>

                <div class="col-md-2 headers_data_hypothesis_hi">
                    Специализация
                </div>

                <div class="col-md-2 text-center" style="padding-right: 100px;">

                    <div class="headers_data_hypothesis_hi">
                        Платеже&shy;способность
                    </div>
                    <div class="headers_data_hypothesis_low" style="padding-left: 10px;">
                        млн. руб./год
                    </div>
                </div>

                <div class="col-md-1"></div>

            </div>


            <div class="block_all_hypothesis row" style="padding-left: 10px; padding-right: 10px;">

                <!--Данные для списка сегментов-->
                <?php foreach ($models as $model) : ?>


                    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

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


                        <div class="col-md-2 text_description_segment" title="<?= $model->field_of_activity; ?>">

                            <?= $model->field_of_activity; ?>

                        </div>

                        <div class="col-md-2 text_description_segment" title="<?= $model->sort_of_activity; ?>">

                            <?= $model->sort_of_activity; ?>

                        </div>

                        <div class="col-md-2 text_description_segment" title="<?= $model->specialization_of_activity; ?>">

                            <?= $model->specialization_of_activity; ?>

                        </div>


                        <div class="col-md-1 text-right">

                            <?= number_format($model->market_volume, 0, '', ' '); ?>

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

                                        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

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
                                        ]); ?>

                                        <?php else: ?>

                                            <?= Html::a('Подтвердить', ['#'], [
                                                'onclick' => 'return false',
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
                                            ]); ?>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                </div>

                                <div>

                                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segment/get-hypothesis-to-update', 'id' => $model->id], [
                                            'class' => 'update-hypothesis',
                                            'title' => 'Редактировать',
                                        ]); ?>

                                    <?php else : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segment/show-all-information', 'id' => $model->id], [
                                            'class' => 'openAllInformationSegment', 'title' => 'Смотреть',
                                        ]); ?>

                                    <?php endif; ?>

                                </div>

                                <div >

                                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segment/delete', 'id' => $model->id], [
                                            'class' => 'delete_hypothesis',
                                            'title' => 'Удалить',
                                        ]); ?>

                                    <?php endif; ?>

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


        <!--Модальные окна-->
        <?= $this->render('modal'); ?>

    </div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/hypothesis_segment_index.js'); ?>