<?php

use app\models\ProjectCommunications;
use yii\helpers\Html;
use app\models\Segments;
use app\models\User;
use app\models\EnableExpertise;
use app\models\StageExpertise;

$this->title = 'Генерация бизнес-модели';
$this->registerCssFile('@web/css/business-model-index-style.css');

?>
<div class="business-model-index">

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

        <?= Html::a('<div class="stage_number">1</div><div>Генерация гипотез целевых сегментов</div>',
            ['/segments/index', 'id' => $project->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">2</div><div>Подтверждение гипотез целевых сегментов</div>',
            ['/confirm-segment/view', 'id' => $confirmSegment->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">3</div><div>Генерация гипотез проблем сегментов</div>',
            ['/problems/index', 'id' => $confirmSegment->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">4</div><div>Подтверждение гипотез проблем сегментов</div>',
            ['/confirm-problem/view', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">5</div><div>Разработка гипотез ценностных предложений</div>',
            ['/gcps/index', 'id' => $confirmProblem->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">6</div><div>Подтверждение гипотез ценностных предложений</div>',
            ['/confirm-gcp/view', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">7</div><div>Разработка MVP</div>',
            ['/mvps/index', 'id' => $confirmGcp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <?= Html::a('<div class="stage_number">8</div><div>Подтверждение MVP</div>',
            ['/confirm-mvp/view', 'id' => $confirmMvp->id],
            ['class' => 'passive_navigation_block navigation_block']
        ) ;?>

        <div class="active_navigation_block navigation_block">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div class="row segment_info_data">

        <div class="col-xs-12 col-md-12 col-lg-8 stage_name_row">

            <?php
            $segment_name = $segment->name;
            if (mb_strlen($segment_name) > 12){
                $segment_name = mb_substr($segment_name, 0, 12) . '...';
            }

            $problem_description = $problem->description;
            if (mb_strlen($problem_description) > 12){
                $problem_description = mb_substr($problem_description, 0, 12) . '...';
            }

            $gcp_description = $gcp->description;
            if (mb_strlen($gcp_description) > 15){
                $gcp_description = mb_substr($gcp_description, 0, 15) . '...';
            }

            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 15){
                $mvp_description = mb_substr($mvp_description, 0, 15) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_max_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

            <?php
            $mvp_description = $mvp->description;
            if (mb_strlen($mvp_description) > 50){
                $mvp_description = mb_substr($mvp_description, 0, 50) . '...';
            }
            ?>

            <?= Html::a('Сегмент: <div>' . $segment_name . '</div> / Проблема: <div>' . $problem_description . '</div> / ЦП: <div>' . $gcp_description . '</div> / MVP: <div>' . $mvp_description . '</div><span class="arrow_link"><span></span><span><span></span>', ['#'], ['id' => 'view_desc_stage_width_min_1900', 'onclick' => 'return false', 'class' => 'view_block_description view_desc_stage']); ?>

        </div>

        <?= Html::a('Данные сегмента', ['/segments/show-all-information', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
        ]) ?>

        <?= Html::a('Трэкшн карта сегмента', ['/segments/show-roadmap', 'id' => $segment->id], [
            'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
        ]) ?>

    </div>


    <div class="row block_description_stage">
        <div>Наименование сегмента:</div>
        <div><?= $segment->name;?></div>
        <div>Формулировка проблемы:</div>
        <div><?= $problem->description;?></div>
        <div>Формулировка ценностного предложения:</div>
        <div><?= $gcp->description;?></div>
        <div>Формулировка минимально жизнеспособного продукта:</div>
        <div><?= $mvp->description;?></div>
    </div>



    <div class="container-fluid container-data row">

        <div class="container-business_model">

            <div class="row" style="margin: 0;">

                <div class="col-lg-3" style="padding-top: 17px; padding-bottom: 17px;">
                    <?= Html::a('Бизнес-модель' . Html::img('/images/icons/icon_report_next.png'), ['/business-model/get-instruction'],[
                        'class' => 'link_to_instruction_page open_modal_instruction_page', 'title' => 'Инструкция'
                    ]); ?>
                </div>

                <div class="col-lg-9" style="padding-top: 17px; padding-bottom: 17px;">

                    <?= Html::a('Скачать', ['/business-model/mpdf-business-model', 'id' => $model->id],[
                        'class' => 'btn btn-default pull-right',
                        'title' => 'Скачать бизнес-модель',
                        'target' => '_blank',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#669999',
                            'padding' => '0 7px',
                            'width' => '190px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                    ]); ?>

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?= Html::a('Редактировать', ['/business-model/get-hypothesis-to-update', 'id' => $model->id], [
                            'class' => 'btn btn-default update-hypothesis pull-right',
                            'title' => 'Редактировать бизнес-модель',
                            'style' => [
                                'color' => '#FFFFFF',
                                'background' => '#C2C2C2',
                                'padding' => '0 7px',
                                'width' => '190px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                        ]); ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?=  Html::a( 'Экспертиза',
                                ['/business-model/enable-expertise', 'id' => $model->id], [
                                'class' => 'btn btn-warning link-enable-expertise pull-right',
                                'title' => 'Разрешить экспертизу',
                                'style' => [
                                    'padding' => '0 7px',
                                    'width' => '190px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ]
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?=  Html::a( 'Экспертиза',
                                ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->id], [
                                'class' => 'btn btn-default link-get-list-expertise pull-right',
                                'title' => 'Смотреть экспертизу',
                                'style' => [
                                    'color' => '#FFFFFF',
                                    'background' => '#52BE7F',
                                    'padding' => '0 7px',
                                    'width' => '190px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ],
                            ]); ?>

                        <?php endif; ?>

                    <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?=  Html::a( 'Экспертиза', ['#'], [
                                'onclick' => 'return false;',
                                'class' => 'btn btn-default pull-right',
                                'title' => 'Экспертиза не разрешена',
                                'style' => [
                                    'color' => '#FFFFFF',
                                    'background' => '#f5a4a4',
                                    'padding' => '0 7px',
                                    'width' => '190px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ]
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON && ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->projectId)) : ?>

                            <?=  Html::a( 'Экспертиза',
                                ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->id], [
                                'class' => 'btn btn-default link-get-list-expertise pull-right',
                                'title' => 'Экспертиза',
                                'style' => [
                                    'color' => '#FFFFFF',
                                    'background' => '#52BE7F',
                                    'padding' => '0 7px',
                                    'width' => '190px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ]
                            ]); ?>

                        <?php endif; ?>

                    <?php else : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?=  Html::a( 'Экспертиза', ['#'], [
                                'onclick' => 'return false;',
                                'class' => 'btn btn-default pull-right',
                                'title' => 'Экспертиза не разрешена',
                                'style' => [
                                    'color' => '#FFFFFF',
                                    'background' => '#f5a4a4',
                                    'padding' => '0 7px',
                                    'width' => '190px',
                                    'height' => '40px',
                                    'font-size' => '24px',
                                    'border-radius' => '8px',
                                ]
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?=  Html::a( 'Экспертиза',
                                ['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::BUSINESS_MODEL], 'stageId' => $model->id], [
                                    'class' => 'btn btn-default link-get-list-expertise pull-right',
                                    'title' => 'Экспертиза',
                                    'style' => [
                                        'color' => '#FFFFFF',
                                        'background' => '#52BE7F',
                                        'padding' => '0 7px',
                                        'width' => '190px',
                                        'height' => '40px',
                                        'font-size' => '24px',
                                        'border-radius' => '8px',
                                    ]
                                ]); ?>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </div>

            <div class="blocks_business_model">

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Ключевые партнеры</h5>
                        <div><?= $model->partners; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">

                        <h5>Ключевые направления</h5>

                        <div class="mini_header_desc_block">Тип взаимодейстивия с рынком:</div>
                        <?php
                        if ($segment->type_of_interaction_between_subjects == Segments::TYPE_B2C) {
                            echo 'В2С (бизнес-клиент)';
                        } else {
                            echo 'B2B (бизнес-бизнес)';
                        }
                        ?>

                        <div class="mini_header_desc_block">Сфера деятельности:</div>
                        <?= $segment->field_of_activity; ?>

                        <div class="mini_header_desc_block">Вид / специализация деятельности:</div>
                        <?= $segment->sort_of_activity; ?>

                    </div>

                    <div class="desc_block_20">
                        <h5>Ключевые ресурсы</h5>
                        <div><?= $model->resources; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Ценностное предложение</h5>
                        <?= $gcp->description; ?>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">
                        <h5>Взаимоотношения с клиентами</h5>
                        <div><?= $model->relations; ?></div>
                    </div>

                    <div class="desc_block_20">
                        <h5>Каналы коммуникации и сбыта</h5>
                        <div><?= $model->distribution_of_sales; ?></div>
                    </div>

                </div>

                <div class="block_20_business_model">

                    <div class="desc_block_20">

                        <h5>Потребительский сегмент</h5>

                        <div class="mini_header_desc_block">Наименование:</div>
                        <?= $segment->name; ?>

                        <div class="mini_header_desc_block">Краткое описание:</div>
                        <?= $segment->description; ?>

                        <div class="mini_header_desc_block">Потенциальное количество потребителей:</div>
                        <?= ' от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') .
                        ' до ' . number_format($segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>

                        <div class="mini_header_desc_block">Объем рынка:</div>
                        <?= number_format($segment->market_volume * 1000000, 0, '', ' ') . ' рублей'; ?>

                    </div>
                </div>
            </div>

            <div class="blocks_business_model">

                <div class="block_50_business_model">

                    <div class="desc_block_50">
                        <h5>Структура издержек</h5>
                        <div><?= $model->cost; ?></div>
                    </div>

                </div>

                <div class="block_50_business_model">

                    <div class="desc_block_50">
                        <h5>Потоки поступления доходов</h5>
                        <div><?= $model->revenue; ?></div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>


<!--Подключение скриптов-->
<?php
$this->registerJsFile('@web/js/business_model_index.js');
$this->registerJsFile('@web/js/main_expertise.js');
?>