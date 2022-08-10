<?php

use app\models\ConfirmSegment;
use app\models\forms\FormCreateProblem;
use app\models\Problems;
use app\models\Projects;
use app\models\Segments;
use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'Генерация гипотез проблем сегмента';
$this->registerCssFile('@web/css/problem-index-style.css');

/**
 * @var Problems[] $models
 * @var ConfirmSegment $confirmSegment
 * @var Segments $segment
 * @var Projects $project
 * @var FormCreateProblem $formModel
 */

?>

<div class="generation-problem-index">

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

        <?= Html::a('<div class="stage_number">1</div><div>Генерация гипотез целевых сегментов</div>',
            ['/segments/index', 'id' => $project->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <?= Html::a('<div class="stage_number">2</div><div>Подтверждение гипотез целевых сегментов</div>',
            ['/confirm-segment/view', 'id' => $confirmSegment->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <div class="active_navigation_block navigation_block">
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


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">
            <span>Сегмент:</span>
            <?= $segment->getName() ?>
        </div>

        <?= Html::a('Данные сегмента', ['/segments/show-all-information', 'id' => $segment->getId()], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Трэкшн карта сегмента', ['/segments/show-roadmap', 'id' => $segment->getId()], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

    </div>


    <div class="container-fluid container-data row">

        <div class="row" style="margin-left: 10px; margin-right: 10px; border-bottom: 1px solid #ccc;">

            <div class="col-md-9" style="padding-top: 17px; padding-bottom: 17px;">
                <?= Html::a('Проблемы' . Html::img('/images/icons/icon_report_next.png'), ['/problems/get-instruction'],[
                    'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                ]) ?>
            </div>

            <div class="col-md-3" style="padding-top: 15px; padding-bottom: 15px;">
                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                    <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новая проблема</div></div>',
                        ['/confirm-segment/data-availability-for-next-step', 'id' => $confirmSegment->getId()],
                        ['id' => 'checking_the_possibility', 'class' => 'new_hypothesis_link_plus pull-right']
                    ) ?>
                <?php endif; ?>
            </div>

        </div>

        <!--Заголовки для списка проблем-->
        <div class="row headers_data_hypothesis" style="margin: 0; padding: 10px;">

            <div class="col-lg-1 ">
                <div class="row">
                    <div class="col-md-4" style="padding: 0;"></div>
                    <div class="col-md-8" style="padding: 0;">Обознач.</div>
                </div>
            </div>

            <div class="col-lg-5 headers_data_hypothesis">
                Описание гипотезы проблемы сегмента
            </div>

            <div class="col-lg-3">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-lg-6 text-center">Показатель положительного прохождения теста</div>
                    <div class="col-lg-3 text-center"><div>Дата создания</div></div>
                    <div class="col-lg-3 text-center header_date_confirm"><div>Дата подтв.</div></div>
                </div>
            </div>

            <div class="col-lg-3 text-right" style="padding-right: 8px;">
                <?= Html::a(Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px']]), ['/problems/mpdf-table-problems', 'id' => $confirmSegment->getId()], [
                    'target'=>'_blank', 'title'=> 'Экспорт в pdf',
                ]) ?>
            </div>

        </div>


        <div class="block_all_hypothesis row" style="padding-left: 10px; padding-right: 10px;">

            <!--Данные для списка проблем-->
            <?= $this->render('_index_ajax', ['models' => $models]) ?>

        </div>
    </div>


    <?php if (count($models) > 0) : ?>

        <div class="row information_status_confirm">

            <div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Проблема подтверждена</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Проблема не подтверждена</div>
                </div>

                <div style="display:flex; align-items: center;">
                    <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]) ?>
                    <div>Проблема ожидает подтверждения</div>
                </div>

            </div>

        </div>

    <?php endif; ?>


    <div class="formExpectedResults" style="display: none;">

        <?php
        $form = ActiveForm::begin([
            'id' => 'formExpectedResults'
        ]); ?>

        <div class="formExpectedResults_inputs">

            <div class="row container-fluid rowExpectedResults rowExpectedResults-" style="margin-bottom: 15px;">

                <div class="col-md-6 field-EXR">

                    <?= $form->field($formModel, "_expectedResultsInterview[0][question]", ['template' => '{input}'])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => 'Напишите вопрос',
                        'id' => '_expectedResults_question-',
                        'class' => 'style_form_field_respond form-control',
                    ]) ?>

                </div>

                <div class="col-md-6 field-EXR">

                    <?= $form->field($formModel, "_expectedResultsInterview[0][answer]", ['template' => '{input}'])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'required' => true,
                        'placeholder' => 'Напишите ответ',
                        'id' => '_expectedResults_answer-',
                        'class' => 'style_form_field_respond form-control',
                    ]) ?>

                </div>

                <div class="col-md-12">

                    <?= Html::button('Удалить вопрос/ответ', [
                        'id' => 'remove-expectedResults-',
                        'class' => "remove-expectedResults btn btn-default",
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'width' => '170px',
                            'height' => '40px',
                            'font-size' => '16px',
                            'border-radius' => '8px',
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>


    <!--Модальные окна-->
    <?= $this->render('modal') ?>

</div>

<!--Подключение скриптов-->
<?php
$this->registerJsFile('@web/js/hypothesis_problem_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>