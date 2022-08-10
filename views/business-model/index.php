<?php

use app\models\BusinessModel;
use app\models\ConfirmGcp;
use app\models\ConfirmMvp;
use app\models\ConfirmProblem;
use app\models\ConfirmSegment;
use app\models\Gcps;
use app\models\Mvps;
use app\models\Problems;
use app\models\Projects;
use yii\helpers\Html;
use app\models\Segments;

$this->title = 'Генерация бизнес-модели';
$this->registerCssFile('@web/css/business-model-index-style.css');

/**
 * @var BusinessModel $model
 * @var ConfirmMvp $confirmMvp
 * @var Mvps $mvp
 * @var ConfirmGcp $confirmGcp
 * @var Gcps $gcp
 * @var ConfirmProblem $confirmProblem
 * @var Problems $problem
 * @var ConfirmSegment $confirmSegment
 * @var Segments $segment
 * @var Projects $project
 */

?>
<div class="business-model-index">

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

        <?= Html::a('<div class="stage_number">6</div><div>Подтверждение гипотез ценностных предложений</div>',
            ['/confirm-gcp/view', 'id' => $confirmGcp->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <?= Html::a('<div class="stage_number">7</div><div>Разработка MVP</div>',
            ['/mvps/index', 'id' => $confirmGcp->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <?= Html::a('<div class="stage_number">8</div><div>Подтверждение MVP</div>',
            ['/confirm-mvp/view', 'id' => $confirmMvp->getId()],
            ['class' => 'passive_navigation_block navigation_block']
        ) ?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">

            <?php
            $segment_name = $segment->getName();
            if (mb_strlen($segment_name) > 12){
                $segment_name = mb_substr($segment_name, 0, 12) . '...';
            }

            $problem_description = $problem->getDescription();
            if (mb_strlen($problem_description) > 12){
                $problem_description = mb_substr($problem_description, 0, 12) . '...';
            }

            $gcp_description = $gcp->getDescription();
            if (mb_strlen($gcp_description) > 15){
                $gcp_description = mb_substr($gcp_description, 0, 15) . '...';
            }

            $mvp_description = $mvp->getDescription();
            if (mb_strlen($mvp_description) > 15){
                $mvp_description = mb_substr($mvp_description, 0, 15) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']) ?>

            <?php
            $mvp_description = $mvp->getDescription();
            if (mb_strlen($mvp_description) > 50){
                $mvp_description = mb_substr($mvp_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']) ?>

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
        <div>Формулировка минимально жизнеспособного продукта:</div>
        <div><?= $mvp->getDescription() ?></div>
    </div>


    <div class="container-fluid container-data row">
        <div class="container-business_model">
            <?= $this->render('_index_ajax', [
                'model' => $model,
                'segment' => $segment,
                'gcp' => $gcp
            ]) ?>
        </div>
    </div>


    <!--Модальные окна-->
    <?= $this->render('modal') ?>

</div>


<!--Подключение скриптов-->
<?php
$this->registerJsFile('@web/js/business_model_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>