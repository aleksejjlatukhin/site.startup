<?php

use app\models\forms\SearchForm;
use app\models\Projects;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Segments;

$this->title = 'Генерация гипотез целевых сегментов';
$this->registerCssFile('@web/css/segments-index-style.css');

/**
 * @var Projects $project
 * @var Segments[] $models
 * @var SearchForm $searchForm
 */

?>

<div class="segment-index">

    <div class="header-title-index-mobile">
        <div style="overflow: hidden; max-width: 70%;">Проект: <?= $project->getProjectName() ?></div>
        <div class="buttons-project-menu-mobile" style="position: absolute; right: 20px; top: 5px;">
            <?= Html::img('@web/images/icons/icon-four-white-squares.png', ['class' => 'open-project-menu-mobile', 'style' => ['width' => '30px']]) ?>
            <?= Html::img('@web/images/icons/icon-white-cross.png', ['class' => 'close-project-menu-mobile', 'style' => ['width' => '30px', 'display' => 'none']]) ?>
        </div>
    </div>

    <div class="project-menu-mobile">
        <div class="project_buttons_mobile">

            <?= Html::a('Сводная таблица', ['/projects/result-mobile', 'id' => $project->getId()], [
                'class' => 'btn btn-default',
                'style' => [
                    'display' => 'flex',
                    'width' => '47%',
                    'height' => '36px',
                    'background' => '#7F9FC5',
                    'color' => '#FFFFFF',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'border-radius' => '0',
                    'border' => '1px solid #ffffff',
                    'font-size' => '18px',
                    'margin' => '10px 1% 0 2%',
                ],
            ]) ?>

            <?= Html::a('Трэкшн карта', ['/projects/roadmap-mobile', 'id' => $project->getId()], [
                'class' => 'btn btn-default',
                'style' => [
                    'display' => 'flex',
                    'width' => '47%',
                    'height' => '36px',
                    'background' => '#7F9FC5',
                    'color' => '#FFFFFF',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'border-radius' => '0',
                    'border' => '1px solid #ffffff',
                    'font-size' => '18px',
                    'margin' => '10px 2% 0 1%',
                ],
            ]) ?>

        </div>

        <div class="project_buttons_mobile">

            <?= Html::a('Протокол', ['/projects/report-mobile', 'id' => $project->getId()], [
                'class' => 'btn btn-default',
                'style' => [
                    'display' => 'flex',
                    'width' => '47%',
                    'height' => '36px',
                    'background' => '#7F9FC5',
                    'color' => '#FFFFFF',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'border-radius' => '0',
                    'border' => '1px solid #ffffff',
                    'font-size' => '18px',
                    'margin' => '10px 1% 10px 2%',
                ],
            ]) ?>

            <?= Html::a('Презентация', ['/projects/presentation-mobile', 'id' => $project->getId()], [
                'class' => 'btn btn-default',
                'style' => [
                    'display' => 'flex',
                    'width' => '47%',
                    'height' => '36px',
                    'background' => '#7F9FC5',
                    'color' => '#FFFFFF',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'border-radius' => '0',
                    'border' => '1px solid #ffffff',
                    'font-size' => '18px',
                    'margin' => '10px 2% 10px 1%',
                ],
            ]) ?>

        </div>
    </div>

    <div class="arrow_stages_project_mobile">
        <div class="item-stage active"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
        <div class="item-stage passive"></div>
    </div>

    <div class="arrow_links_router_mobile">
        <div class="arrow_link_router_mobile_left">
            <?= Html::a(Html::img('@web/images/icons/arrow_left_active.png'),
                Url::to(['/projects/index', 'id' => $project->getUserId()])) ?>
        </div>
        <div class="text-stage">1/9. Генерация гипотез целевых сегментов</div>
        <div class="arrow_link_router_mobile_right">
            <?= Html::img('@web/images/icons/arrow_left_passive.png') ?>
        </div>
    </div>

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

            <div class="col-md-3 pt-2 pb-2">
                <?= Html::a('Сегменты' . Html::img('/images/icons/icon_report_next.png'), ['/segments/get-instruction'],[
                    'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                ]) ?>
            </div>

            <?php if (!User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                <div class="col-md-6 search_block_desktop">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'search_segments',
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>

                    <?= $form->field($searchForm, 'search', ['template' => '{input}'])
                        ->textInput([
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => 'поиск сегмента',
                            'minlength' => 5,
                            'autocomplete' => 'off'])
                        ->label(false) ?>

                    <?php ActiveForm::end(); ?>

                </div>

            <?php else : ?>

                <div class="col-md-6"></div>

            <?php endif; ?>

            <div class="col-md-3 p-0">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div class="pl-20">Новый сегмент</div></div>', ['/segments/get-hypothesis-to-create', 'id' => $project->getId()],
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

            <div class="col-lg-3 text-right pr-5">
                <?= Html::a(Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]), ['/segments/mpdf-table-segments', 'id' => $project->id], [
                    'target'=>'_blank', 'title'=> 'Экспорт в pdf',
                ]) ?>
            </div>
        </div>

        <div class="row row_header_data_generation_mobile">

            <div class="col-xs-8">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новый сегмент</div></div>', ['/segments/get-hypothesis-to-create', 'id' => $project->getId()],
                        ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus']
                    ) ?>

                <?php endif; ?>
            </div>

            <div class="col-xs-4">

                <?php if (!User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                    <?= Html::a(Html::img('@web/images/icons/icon_red_info.png'),
                        Url::to('/segments/get-instruction'), [
                            'class' => 'link_to_instruction_page_mobile open_modal_instruction_page pull-right',
                            'title' => 'Инструкция', 'style' => ['margin-left' => '10px', 'margin-top' => '5px']
                        ]) ?>

                    <?= Html::a(Html::img('@web/images/icons/icon_green_search.png'), ['#'], [
                        'class' => 'link_show_search_field_mobile show_search_segments pull-right',
                        'title' => 'Поиск сегментов', 'style' => ['margin-top' => '5px']
                    ]) ?>

                <?php else : ?>

                    <?= Html::a(Html::img('@web/images/icons/icon_red_info.png'),
                        Url::to('/segments/get-instruction'), [
                            'class' => 'link_to_instruction_page_mobile open_modal_instruction_page pull-right',
                            'title' => 'Инструкция', 'style' => ['margin-top' => '5px']
                        ]) ?>

                <?php endif; ?>

            </div>
        </div>

        <div class="row search_block_mobile">
            <div class="col-xs-10">
                <?php $form = ActiveForm::begin([
                    'id' => 'search_segments_mobile',
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <?= $form->field($searchForm, 'search', ['template' => '{input}'])
                    ->textInput([
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => 'поиск сегмента',
                        'minlength' => 5,
                        'autocomplete' => 'off'])
                    ->label(false) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-xs-2 pull-right">
                <?= Html::a(Html::img('@web/images/icons/cancel_danger.png'), ['#'], ['class' => 'link_cancel_search_field_mobile show_search_segments']) ?>
            </div>
        </div>

        <div class="block_all_hypothesis row pl-10 pr-10">

            <!--Данные для списка сегментов-->
            <?= $this->render('_index_ajax', ['models' => $models]) ?>

        </div>
    </div>


    <?php if (count($models) > 0) : ?>

        <div class="row information_status_confirm">

            <div>

                <div class="display-flex align-items-center">
                    <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Сегмент подтвержден</div>
                </div>

                <div class="display-flex align-items-center">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Сегмент не подтвержден</div>
                </div>

                <div class="display-flex align-items-center">
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
