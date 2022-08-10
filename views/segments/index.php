<?php

use app\models\Projects;
use app\models\SortForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;
use app\models\Segments;
use kartik\select2\Select2;
use app\models\SegmentSort;

$this->title = 'Генерация гипотез целевых сегментов';
$this->registerCssFile('@web/css/segments-index-style.css');

/**
 * @var Projects $project
 * @var Segments[] $models
 * @var SortForm $sortModel
 */

?>

<div class="segment-index">

    <div class="row project_info_data">

        <div class="col-xs-12 col-md-12 col-lg-4 project_name">
            <span>Проект:</span>
            <?= $project->getProjectName() ?>
        </div>

        <?= Html::a('Данные проекта', ['/projects/show-all-information', 'id' => $project->getId()], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openAllInformationProject link_in_the_header',
        ]) ?>

        <?= Html::a('Протокол проекта', ['/projects/report', 'id' => $project->getId()], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openReportProject link_in_the_header text-center',
        ]) ?>

        <?= Html::a('Трэкшн карта проекта', ['/projects/show-roadmap', 'id' => $project->getId()], [
            'class' => 'col-xs-12 col-sm-3 col-md-3 col-lg-2 openRoadmapProject link_in_the_header text-center',
        ]) ?>

        <?= Html::a('Сводная таблица проекта', ['/projects/result', 'id' => $project->getId()], [
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
            ]); ?>


            <?php

            $listFields = SegmentSort::getListFields();
            $listFields = ArrayHelper::map($listFields,'id', 'name');

            ?>


            <div class="col-md-3" style="padding: 2px 0;">
                <?= Html::a('Сегменты' . Html::img('/images/icons/icon_report_next.png'), ['/segments/get-instruction'],[
                    'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                ]) ?>
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
                    ])
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
                    ])
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>

            <div class="col-md-3" style="padding: 0;">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый сегмент</div></div>', ['/segments/get-hypothesis-to-create', 'id' => $project->getId()],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-right']
                    ) ?>
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
                ]) ?>
            </div>
        </div>

        <div class="block_all_hypothesis row" style="padding-left: 10px; padding-right: 10px;">

            <!--Данные для списка сегментов-->
            <?= $this->render('_index_ajax', ['models' => $models]) ?>

        </div>
    </div>


    <?php if (count($models) > 0) : ?>

        <div class="row information_status_confirm">

            <div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Сегмент подтвержден</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Сегмент не подтвержден</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Сегмент ожидает подтверждения</div>
                </div>

            </div>

        </div>

    <?php endif; ?>

    <!--Модальные окна-->
    <?= $this->render('modal') ?>

</div>

<!--Подключение скриптов-->
<?php
$this->registerJsFile('@web/js/hypothesis_segment_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>
