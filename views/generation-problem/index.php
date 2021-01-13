<?php

use yii\helpers\Html;
use app\models\User;

$this->title = 'Генерация гипотез проблем сегмента';
$this->registerCssFile('@web/css/problem-index-style.css');
?>


    <div class="generation-problem-index">


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

            <?= Html::a('<div class="stage_number">1</div><div>Генерация гипотез целевых сегментов</div>',
                ['/segment/index', 'id' => $project->id],
                ['class' => 'passive_navigation_block navigation_block']
            ) ;?>

            <?= Html::a('<div class="stage_number">2</div><div>Подтверждение гипотез целевых сегментов</div>',
                ['/interview/view', 'id' => $interview->id],
                ['class' => 'passive_navigation_block navigation_block']
            ) ;?>

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
                <?= $segment->name; ?>
            </div>

            <?= Html::a('Данные сегмента', ['/segment/show-all-information', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openAllInformationSegment link_in_the_header',
            ]) ?>

            <?= Html::a('Дорожная карта сегмента', ['/segment/show-roadmap', 'id' => $segment->id], [
                'class' => 'col-xs-12 col-sm-6 col-md-6 col-lg-2 openRoadmapSegment link_in_the_header text-center',
            ]) ?>

        </div>



        <div class="container-fluid container-data row">

            <div class="container-fluid row">

                <div class="col-md-12" style="padding: 15px 0;">

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Новая проблема</div></div>',
                            ['/interview/data-availability-for-next-step', 'id' => $interview->id],
                            ['id' => 'checking_the_possibility', 'class' => 'new_hypothesis_link_plus pull-right']
                        );
                        ?>

                    <?php endif; ?>

                </div>

            </div>


            <!--Заголовки для списка проблем-->
            <div class="row headers_data_hypothesis" style="margin: 0; padding: 10px; padding-top: 0;">

                <div class="col-md-1 ">
                    <div class="row">
                        <div class="col-md-4" style="padding: 0;"></div>
                        <div class="col-md-8" style="padding: 0;">Обознач.</div>
                    </div>

                </div>

                <div class="col-md-3" style="padding-left: 10px;">Описание гипотезы проблемы сегмента</div>

                <div class="col-md-2">Действие для проверки</div>

                <div class="col-md-2">Метрика результата</div>

                <div class="col-md-1 text-center"><div>Дата создания</div></div>

                <div class="col-md-1 text-center header_date_confirm"><div>Дата подтв.</div></div>

                <div class="col-md-2"></div>

            </div>


            <div class="block_all_hypothesis row" style="padding-left: 10px; padding-right: 10px;">

                <!--Данные для списка проблем-->
                <?php foreach ($models as $model) : ?>

                    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

                        <div class="col-md-1">
                            <div class="row">

                                <div class="col-md-4" style="padding: 0;">

                                    <?php
                                    if ($model->exist_confirm === 1) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                    }elseif ($model->exist_confirm === null && empty($model->confirm)) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                    }elseif ($model->exist_confirm === null && !empty($model->confirm)) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                                    }elseif ($model->exist_confirm === 0) {

                                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                                    }
                                    ?>

                                </div>

                                <div class="col-md-8 hypothesis_title" style="padding: 0 0 0 5px;">

                                    <?= $model->title; ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text_field_problem" title="<?= $model->description; ?>">

                            <?= $model->description; ?>

                        </div>

                        <div class="col-md-2 text_field_problem" title="<?= $model->description; ?>">

                            <?= $model->action_to_check; ?>

                        </div>

                        <div class="col-md-2 text_field_problem" title="<?= $model->description; ?>">

                            <?= $model->result_metric; ?>

                        </div>

                        <div class="col-md-1 text-center">

                            <?= date("d.m.y", $model->created_at); ?>

                        </div>

                        <div class="col-md-1 text-center">

                            <?php if ($model->time_confirm) : ?>
                                <?= date("d.m.y", $model->time_confirm); ?>
                            <?php endif; ?>

                        </div>


                        <div class="col-md-2">

                            <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                                <div style="margin-right: 25px;">

                                    <?php if ($model->confirm) : ?>

                                        <?= Html::a('Далее', ['/confirm-problem/view', 'id' => $model->confirm->id], [
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

                                            <?= Html::a('Подтвердить', ['/confirm-problem/create', 'id' => $model->id], [
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

                                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/generation-problem/get-hypothesis-to-update', 'id' => $model->id], [
                                            'class' => 'update-hypothesis',
                                            'title' => 'Редактировать',
                                        ]); ?>

                                    <?php endif; ?>

                                </div>

                                <div >

                                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                                        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/generation-problem/delete', 'id' => $model->id], [
                                            'class' => 'delete_hypothesis',
                                            'title' => 'Удалить',
                                        ]); ?>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                    </div>

                <?php endforeach; ?>

            </div>

        </div>


        <?php if (count($models) > 0) : ?>

            <div class="row information_status_confirm">

                <div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Проблема подтверждена</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Проблема не подтверждена</div>
                    </div>

                    <div style="display:flex; align-items: center;">
                        <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px', 'margin-right' => '8px']]);?>
                        <div>Проблема ожидает подтверждения</div>
                    </div>

                </div>

            </div>

        <?php endif; ?>


        <!--Модальные окна-->
        <?= $this->render('modal'); ?>

    </div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/hypothesis_problem_index.js'); ?>