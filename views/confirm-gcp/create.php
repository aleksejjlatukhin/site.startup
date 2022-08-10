<?php

use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\forms\FormCreateConfirmGcp;
use app\models\Gcps;
use app\models\Problems;
use app\models\Projects;
use app\models\Segments;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = 'Подтверждение гипотез ценностных предложений';
$this->registerCssFile('@web/css/confirm-gcp-create-style.css');

/**
 * @var FormCreateConfirmGcp $model
 * @var Gcps $gcp
 * @var ConfirmProblem $confirmProblem
 * @var Problems$problem
 * @var ConfirmSegment $confirmSegment
 * @var Segments $segment
 * @var Projects $project
 */

?>


<div class="confirm-gcp-create">

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

        <?= Html::a('<div class="stage_number">3</div><div>Генерация гипотез проблем сегментов</div>',
            ['/problems/index', 'id' => $confirmSegment->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <?= Html::a('<div class="stage_number">4</div><div>Подтверждение гипотез проблем сегментов</div>',
            ['/confirm-problem/view', 'id' => $confirmProblem->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <?= Html::a('<div class="stage_number">5</div><div>Разработка гипотез ценностных предложений</div>',
            ['/gcps/index', 'id' => $confirmProblem->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <div class="active_navigation_block navigation_block">
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

            <?php
            $segment_name = $segment->getName();
            if (mb_strlen($segment_name) > 15){
                $segment_name = mb_substr($segment_name, 0, 15) . '...';
            }

            $problem_description = $problem->getDescription();
            if (mb_strlen($problem_description) > 15){
                $problem_description = mb_substr($problem_description, 0, 15) . '...';
            }

            $gcp_description = $gcp->getDescription();
            if (mb_strlen($gcp_description) > 30){
                $gcp_description = mb_substr($gcp_description, 0, 30) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']) ?>

            <?php
            $gcp_description = $gcp->getDescription();
            if (mb_strlen($gcp_description) > 80){
                $gcp_description = mb_substr($gcp_description, 0, 80) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']) ?>

        </div>

        <?= Html::a('Данные сегмента', ['/segments/show-all-information', 'id' => $segment->getId()], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Трэкшн карта сегмента', ['/segments/show-roadmap', 'id' => $segment->getId()], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->getName() ?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $problem->getDescription() ?></div>
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->getDescription() ?></div>
    </div>


    <div class="block-link-create-interview row">

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 1</div><div class="link_create_interview-text_right">Заполнить исходные данные подтверждения</div></div>', [
            'class' => 'link_create_interview link_active_create_interview col-xs-12 col-lg-4',
        ]) ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 2</div><div class="link_create_interview-text_right">Сформировать список вопросов</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-lg-4 show_modal_next_step_error',
        ]) ?>

        <?= Html::button('<div class="link_create_interview-block_text"><div class="link_create_interview-text_left">Шаг 3</div><div class="link_create_interview-text_right">Заполнить информацию о респондентах и интервью</div></div>', [
            'class' => 'link_create_interview link_passive_create_interview col-xs-12 col-lg-4 show_modal_next_step_error',
        ]) ?>

    </div>


    <div class="row">

        <div class="container-fluid container-data">

            <div class="row row_header_data">

                <div class="col-md-12" style="padding: 5px 0 0 0;">
                    <?= Html::a('Исходные данные подтверждения' . Html::img('/images/icons/icon_report_next.png'), ['/confirm-gcp/get-instruction-step-one'],[
                        'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                    ]) ?>
                </div>

            </div>

            <div class="container-fluid">

                <div class="content-view-data-confirm">

                    <div class="row">
                        <div class="col-md-12">Цель проекта</div>
                        <div class="col-md-12"><?= $project->getPurposeProject() ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Приветствие в начале встречи</div>
                        <div class="col-md-12"><?= $confirmSegment->getGreetingInterview() ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Информация о вас для респондентов</div>
                        <div class="col-md-12"><?= $confirmSegment->getViewInterview() ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Причина и тема (что побудило) для проведения исследования</div>
                        <div class="col-md-12"><?= $confirmSegment->getReasonInterview() ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">Формулировка ценностного предложения, которое проверяем</div>
                        <div class="col-md-12"><?= $gcp->getDescription() ?></div>
                    </div>

                </div>

                <?php

                $form = ActiveForm::begin([
                    'id' => 'new_confirm_gcp',
                    'action' => Url::to(['/confirm-gcp/save-confirm', 'id' => $gcp->getId()]),
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]);

                ?>


                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <?= $form->field($model, 'count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div style="padding-top: 5px;">Количество респондентов, подтвердивших проблему</div>')
                        ->textInput([
                            'type' => 'number',
                            'readonly' => true,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_respond',
                            'autocomplete' => 'off'
                        ])
                    ?>

                </div>

                <div class="row" style="padding-top: 5px; padding-bottom: 5px;">

                    <?php $btnContent = '<div class="changeBtnContent">Добавить новых респондентов</div><div style="font-weight: 400;font-size: 13px;">(Общее количечество респондентов - от 1 до 100)</div>'; ?>

                    <?= $form->field($model, 'add_count_respond', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label(Html::button($btnContent, [
                        'id' => 'switch_add_count_respond',
                        'class' => 'btn btn-default',
                        'style' => [
                            'font-weight' => '700',
                            'line-height' => '18px',
                            'margin-top' => '-10px',
                            'background' => '#E0E0E0',
                            'padding' => '0 7px',
                            'width' => '300px',
                            'height' => '50px',
                            'font-size' => '16px',
                            'border-radius' => '8px',
                        ]
                    ]))->textInput([
                        'type' => 'number',
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                        'id' => 'confirm_add_count_respond',
                        'autocomplete' => 'off'
                    ]) ?>

                </div>

                <div class="row">

                    <?= $form->field($model, 'count_positive', [
                        'template' => '<div class="col-xs-12 col-sm-9 col-md-10" style="padding-left: 20px;">{label}</div><div class="col-xs-12 col-sm-3 col-md-2">{input}</div>'
                    ])->label('<div style="padding-top: 5px;">Необходимое количество респондентов, подтверждающих ценностное предложение</div>')
                        ->textInput([
                            'type' => 'number',
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'id' => 'confirm_count_positive',
                            'autocomplete' => 'off'
                        ])
                    ?>

                </div>

                <div class="form-group">
                    <?= Html::submitButton('Далее', [
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#52BE7F',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-top' => '20px'
                        ],
                        'class' => 'btn btn-lg btn-success pull-right',
                    ]) ?>
                </div>


                <?php
                ActiveForm::end();
                ?>

            </div>

        </div>
    </div>

</div>


<?php
// Модальное окно - Запрет на следующий шаг
Modal::begin([
    'options' => ['id' => 'next_step_error', 'class' => 'next_step_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Данный этап не доступен</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Пройдите последовательно этапы подтверждения гипотезы ценностного предложения. Далее переходите к разработке «минимально жизнеспособных продуктов» (MVP).
</h4>

<?php Modal::end(); ?>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/confirm_gcp_create.js'); ?>