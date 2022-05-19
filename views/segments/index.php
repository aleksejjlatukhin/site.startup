<?php

use app\models\ProjectCommunications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\Segments;
use kartik\select2\Select2;
use app\models\SegmentSort;
use app\models\EnableExpertise;
use app\models\StageExpertise;

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

        <?= Html::a('Трэкшн карта проекта', ['/projects/show-roadmap', 'id' => $project->id], [
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


            <div class="col-md-3" style="padding: 2px 0;">
                <?= Html::a('Сегменты' . Html::img('/images/icons/icon_report_next.png'), ['/segments/get-instruction'],[
                    'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                ]); ?>
            </div>


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
                            'url' => Url::to(['/segments/list-type-sort'])
                        ]
                    ]);
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>

            <div class="col-md-3" style="padding: 0;">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый сегмент</div></div>', ['/segments/get-hypothesis-to-create', 'id' => $project->id],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-right']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>


        <!--Заголовки для списка сегментов-->
        <div class="row all_headers_data_hypothesis">

            <div class="col-lg-3 headers_data_hypothesis_hi">
                <div class="row">
                    <div class="col-md-1" style="padding: 0;"></div>
                    <div class="col-md-11">Наименование сегмента</div>
                </div>
            </div>

            <div class="col-lg-1 headers_data_hypothesis_hi text-center">Тип</div>
            <div class="col-lg-2 headers_data_hypothesis_hi text-center">Сфера деятельности</div>
            <div class="col-lg-2 headers_data_hypothesis_hi text-center">Вид / специализация деятельности</div>

            <div class="col-lg-1 text-center">

                <div class="headers_data_hypothesis_hi">
                    Платеже&shy;способность
                </div>
                <div class="headers_data_hypothesis_low">
                    млн. руб./год
                </div>
            </div>

            <div class="col-lg-3 text-right" style="padding-right: 5px;">
                <?= Html::a(Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]), ['/segments/mpdf-table-segments', 'id' => $project->id], [
                    'target'=>'_blank', 'title'=> 'Экспорт в pdf',
                ]);?>
            </div>
        </div>

        <div class="block_all_hypothesis row" style="padding-left: 10px; padding-right: 10px;">

            <!--Данные для списка сегментов-->
            <?php foreach ($models as $model) : ?>

                <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

                    <div class="col-lg-3" style="padding-left: 5px; padding-right: 5px;">

                        <div class="row" style="display:flex; align-items: center;">

                            <div class="col-lg-1" style="padding-bottom: 3px;">

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

                            <div class="col-lg-11">

                                <div class="hypothesis_title" style="padding-left: 15px;">
                                    <?= $model->name;?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-1 text_description_segment text-center" style="padding-left: 25px;">
                        <?php
                        if ($model->type_of_interaction_between_subjects === Segments::TYPE_B2C)
                            echo '<div class="">B2C</div>';
                        elseif ($model->type_of_interaction_between_subjects === Segments::TYPE_B2B)
                            echo '<div class="">B2B</div>';
                        ?>
                    </div>

                    <div class="col-lg-2 text_description_segment text-center" title="<?= $model->field_of_activity; ?>">
                        <?= $model->field_of_activity; ?>
                    </div>

                    <div class="col-lg-2 text_description_segment text-center" title="<?= $model->sort_of_activity; ?>">
                        <?= $model->sort_of_activity; ?>
                    </div>

                    <div class="col-lg-1 text-center">
                        <?= number_format($model->market_volume, 0, '', ' '); ?>
                    </div>

                    <div class="col-lg-3">
                        <div class="row pull-right" style="display:flex; align-items: center; padding-right: 10px;">
                            <div style="margin-right: 25px;">

                                <?php if ($model->confirm) : ?>

                                    <?= Html::a('Далее', ['/confirm-segment/view', 'id' => $model->confirm->id], [
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

                                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                                            <?= Html::a('Подтвердить', ['#'], [
                                                'disabled' => true,
                                                'onclick' => 'return false;',
                                                'title' => 'Необходимо разрешить экспертизу',
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

                                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                                            <?= Html::a('Подтвердить', ['/confirm-segment/create', 'id' => $model->id], [
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

                                    <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/segments/enable-expertise', 'id' => $model->id], [
                                            'class' => 'link-enable-expertise',
                                            'title' => 'Разрешить экспертизу',
                                        ]); ?>

                                    <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->id], [
                                            'class' => 'link-get-list-expertise',
                                            'title' => 'Смотреть экспертизу',
                                        ]); ?>

                                    <?php endif; ?>

                                    <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segments/get-hypothesis-to-update', 'id' => $model->id], [
                                        'class' => 'update-hypothesis',
                                        'title' => 'Редактировать',
                                    ]); ?>

                                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segments/delete', 'id' => $model->id], [
                                        'class' => 'delete_hypothesis',
                                        'title' => 'Удалить',
                                    ]); ?>

                                <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                                    <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                            'onclick' => 'return false;',
                                            'class' => 'no-get-list-expertise',
                                            'style' => ['margin-left' => '20px'],
                                            'title' => 'Экспертиза не разрешена',
                                        ]); ?>

                                    <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON && ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->projectId)) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->id], [
                                            'class' => 'link-get-list-expertise',
                                            'style' => ['margin-left' => '20px'],
                                            'title' => 'Экспертиза',
                                        ]); ?>

                                    <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON && !ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->projectId)) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                            'onclick' => 'return false;',
                                            'style' => ['margin-left' => '20px'],
                                            'title' => 'Экспертиза не доступна',
                                        ]); ?>

                                    <?php endif; ?>

                                    <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segments/show-all-information', 'id' => $model->id], [
                                        'class' => 'openAllInformationSegment', 'title' => 'Смотреть описание сегмента',
                                    ]); ?>

                                <?php else : ?>

                                    <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                            'onclick' => 'return false;',
                                            'class' => 'no-get-list-expertise',
                                            'style' => ['margin-left' => '20px'],
                                            'title' => 'Экспертиза не разрешена',
                                        ]); ?>

                                    <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->id], [
                                            'class' => 'link-get-list-expertise',
                                            'style' => ['margin-left' => '20px'],
                                            'title' => 'Смотреть экспертизу',
                                        ]); ?>

                                    <?php endif; ?>

                                    <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segments/show-all-information', 'id' => $model->id], [
                                        'class' => 'openAllInformationSegment', 'title' => 'Смотреть описание сегмента',
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
<?php
$this->registerJsFile('@web/js/hypothesis_segment_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>
