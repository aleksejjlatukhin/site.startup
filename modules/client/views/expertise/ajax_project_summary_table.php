<?php

use yii\helpers\Html;

?>


<div class="admin-projects-result">

    <div class="containerHeaderDataOfTableResultProject">
        <div class="headerDataOfTableResultProject">
            <div class="blocks_for_double_header_level">

                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Сегмент</div>

                    <div class="columns_stage">
                        <div class="text-center column_segment_name">Наименование</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Проблемы сегмента</div>

                    <div class="columns_stage">
                        <div class="text-center first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Ценностные предложения</div>

                    <div class="columns_stage">
                        <div class="text-center regular_column first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">MVP (продукт)</div>

                    <div class="columns_stage">
                        <div class="text-center first_regular_column_of_stage">Обознач.</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>

            </div>

            <div class="blocks_for_single_header_level text-center">
                <div class="">Бизнес-модель</div>
            </div>

        </div>
    </div>


    <div class="allContainersDataOfTableResultProject">

        <div class="container-fluid">

            <div class="row ratingOfProject">

                <div class="base_line">
                    <!--Наличие положительного подтверждения у сегментов-->
                    <?php if ($project->segments) : ?>

                        <?php $count_exist_confirm_segment = 0; ?>
                        <?php foreach ($project->segments as $segment) : ?>
                            <?php if ($segment->exist_confirm === 1) $count_exist_confirm_segment++; ?>
                        <?php endforeach; ?>

                        <?php if ($count_exist_confirm_segment > 0) : ?>
                            <div class="segments_line_success"></div>
                        <?php else : ?>
                            <div class="segments_line_default"></div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="segments_line_default"></div>
                    <?php endif; ?>

                    <!--Наличие положительного подтверждения у проблем сегментов-->
                    <?php if ($project->problems) : ?>

                        <?php $count_exist_confirm_problem = 0; ?>
                        <?php foreach ($project->problems as $problem) : ?>
                            <?php if ($problem->exist_confirm === 1) $count_exist_confirm_problem++; ?>
                        <?php endforeach; ?>

                        <?php if ($count_exist_confirm_problem > 0) : ?>
                            <div class="rating_line_success"></div>
                        <?php else : ?>
                            <div class="rating_line_default"></div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="rating_line_default"></div>
                    <?php endif; ?>

                    <!--Наличие положительного подтверждения у ценностных предложений-->
                    <?php if ($project->gcps) : ?>

                        <?php $count_exist_confirm_gcp = 0; ?>
                        <?php foreach ($project->gcps as $gcp) : ?>
                            <?php if ($gcp->exist_confirm === 1) $count_exist_confirm_gcp++; ?>
                        <?php endforeach; ?>

                        <?php if ($count_exist_confirm_gcp > 0) : ?>
                            <div class="rating_line_success"></div>
                        <?php else : ?>
                            <div class="rating_line_default"></div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="rating_line_default"></div>
                    <?php endif; ?>

                    <!--Наличие положительного подтверждения у mvps-->
                    <?php if ($project->mvps) : ?>

                        <?php $count_exist_confirm_mvp = 0; ?>
                        <?php foreach ($project->mvps as $mvp) : ?>
                            <?php if ($mvp->exist_confirm === 1) $count_exist_confirm_mvp++; ?>
                        <?php endforeach; ?>

                        <?php if ($count_exist_confirm_mvp > 0) : ?>
                            <div class="rating_line_success"></div>
                        <?php else : ?>
                            <div class="rating_line_default"></div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="rating_line_default"></div>
                    <?php endif; ?>

                </div>

                <div class="business_models_line">
                    <!--Наличие бизнес-моделей-->
                    <?php if ($project->businessModels) : ?>
                        <div class="business_models_line_success"></div>
                    <?php else : ?>
                        <div class="business_models_line_default"></div>
                    <?php endif; ?>
                </div>

            </div>
        </div>


        <div class="containerDataOfTableResultProject" style="border-bottom: 1px solid #B4B4B4;">

            <div class="dataOfTableResultProject">

                <?php foreach ($project->segments as $number_segment => $segment) : ?>

                    <div class="rowSegmentTableResultProject">

                        <div class="container_all_stage">

                            <div class="segment-blocks" style="display:flex; width: 30.55%;">

                                <!--Наименования сегментов-->
                                <div class="column_segment_name">
                                    <?= Html::a('<span>Сегмент ' . ($number_segment+1) . ': </span>' . $segment->name,
                                        ['/segments/index', 'id' => $segment->projectId], ['class' => 'link_in_column_result_table']); ?>
                                </div>

                                <!--Статусы сегментов-->
                                <?php if ($segment->exist_confirm === 1) : ?>

                                    <div class="text-center regular_column_for_segment">
                                        <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                            ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title'=> 'Посмотреть подтверждение сегмента']);
                                        ?>
                                    </div>

                                <?php elseif ($segment->exist_confirm === 0) : ?>

                                    <div class="text-center regular_column_for_segment">
                                        <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                            ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title'=> 'Посмотреть подтверждение сегмента']);
                                        ?>
                                    </div>

                                <?php elseif ($segment->confirm && $segment->exist_confirm === null) : ?>

                                    <div class="text-center regular_column_for_segment">
                                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                            ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title'=> 'Посмотреть подтверждение сегмента']);
                                        ?>
                                    </div>

                                <?php elseif (empty($segment->confirm) && $segment->exist_confirm === null) : ?>

                                    <div class="text-center regular_column_for_segment_empty">- - -</div>

                                <?php endif; ?>

                                <!--Даты создания сегментов-->
                                <div class="text-center regular_column_for_segment">
                                    <?= date('d.m.y',$segment->created_at); ?>
                                </div>

                                <!--Даты подтверждения сегментов-->
                                <div class="text-center regular_column_for_segment">
                                    <?php if ($segment->time_confirm !== null) echo date('d.m.y', $segment->time_confirm); ?>
                                </div>

                            </div>

                            <!--Параметры проблем-->
                            <div class="" style="display:flex; flex-direction: column; width: 69.45%;">

                                <!--Если у сегмента отсутствуют гипотезы проблем-->
                                <?php if (empty($segment->problems)) : ?>

                                    <div class="" style="display:flex; height: 100%;">

                                        <div class="text-center first_regular_column_of_stage_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>

                                        <div class="text-center first_regular_column_of_stage_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>

                                        <div class="text-center first_regular_column_of_stage_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>
                                        <div class="text-center regular_column_for_problem"></div>

                                    </div>

                                <?php endif;?>

                                <!--Если у сегмента есть гипотезы проблем-->
                                <?php foreach ($segment->problems as $number_problem => $problem) : ?>

                                    <div class="" style="display:flex; height: 100%;">

                                        <div class="problem-blocks" style="display:flex; height: 100%; width: 100%;">

                                            <!--Наименования проблем-->
                                            <?php $problem_title = 'ГПС ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1]; ?>
                                            <div class="text-center first_regular_column_of_stage_for_problem">
                                                <?= Html::a($problem_title, ['/problems/index', 'id' => $problem->confirmSegmentId],
                                                    ['class' => 'link_in_column_result_table', 'title' => $problem->description]); ?>
                                            </div>

                                            <!--Статусы проблем-->
                                            <?php if ($problem->exist_confirm === 1) : ?>

                                                <div class="text-center regular_column_for_problem">
                                                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                        ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title'=> 'Посмотреть подтверждение ГПС']);
                                                    ?>
                                                </div>

                                            <?php elseif ($problem->exist_confirm === 0) : ?>

                                                <div class="text-center regular_column_for_problem">
                                                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                        ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title'=> 'Посмотреть подтверждение ГПС']);
                                                    ?>
                                                </div>

                                            <?php elseif ($problem->confirm && $problem->exist_confirm === null) : ?>

                                                <div class="text-center regular_column_for_problem">
                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                        ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title'=> 'Посмотреть подтверждение ГПС']);
                                                    ?>
                                                </div>

                                            <?php elseif (empty($problem->confirm) && $problem->exist_confirm === null) : ?>

                                                <div class="text-center regular_column_for_problem_empty">- - -</div>

                                            <?php endif; ?>

                                            <!--Даты создания проблем-->
                                            <div class="text-center regular_column_for_problem"><?= date('d.m.y',$problem->created_at); ?></div>

                                            <!--Даты подтверждения проблем-->
                                            <div class="text-center regular_column_for_problem"><?php if ($problem->time_confirm !== null) echo date('d.m.y',$problem->time_confirm); ?></div>


                                            <!--Параметры ценностных предложений-->

                                            <div class="" style="display:flex; flex-direction: column; width: 69.7%;">

                                                <div class="" style="display:flex; height: 100%;">

                                                    <!--Если у проблемы отсутствуют гипотезы ценностных предложений-->
                                                    <?php if (empty($problem->gcps)) : ?>

                                                        <div class="text-center first_regular_column_of_stage_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>

                                                        <div class="text-center first_regular_column_of_stage_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>
                                                        <div class="text-center regular_column_for_gcp"></div>

                                                    <?php endif;?>

                                                </div>

                                                <?php foreach ($problem->gcps as $gcp) : ?>

                                                    <div class="gcp-blocks" style="display:flex; height: 100%;">

                                                        <!--Наименования ценностных предложений-->
                                                        <?php $gcp_title = 'ГЦП ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1] . '.' . explode(' ',$gcp->title)[1]; ?>
                                                        <div class="text-center first_regular_column_of_stage_for_gcp">
                                                            <?= Html::a($gcp_title, ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                                                ['class' => 'link_in_column_result_table', 'title' => $gcp->description]); ?>
                                                        </div>

                                                        <!--Статусы ценностных предложений-->
                                                        <?php if ($gcp->exist_confirm === 1) : ?>

                                                            <div class="text-center regular_column_for_gcp">
                                                                <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title'=> 'Посмотреть подтверждение ГЦП']);
                                                                ?>
                                                            </div>

                                                        <?php elseif ($gcp->exist_confirm === 0) : ?>

                                                            <div class="text-center regular_column_for_gcp">
                                                                <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title'=> 'Посмотреть подтверждение ГЦП']);
                                                                ?>
                                                            </div>

                                                        <?php elseif ($gcp->confirm && $gcp->exist_confirm === null) : ?>

                                                            <div class="text-center regular_column_for_gcp">
                                                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title'=> 'Посмотреть подтверждение ГЦП']);
                                                                ?>
                                                            </div>

                                                        <?php elseif (empty($gcp->confirm) && $gcp->exist_confirm === null) : ?>

                                                            <div class="text-center regular_column_for_gcp_empty">- - -</div>

                                                        <?php endif; ?>

                                                        <!--Даты создания ценностных предложений-->
                                                        <div class="text-center regular_column_for_gcp"><?= date('d.m.y',$gcp->created_at); ?></div>

                                                        <!--Даты подтверждения ценностных предложений-->
                                                        <div class="text-center regular_column_for_gcp"><?php if ($gcp->time_confirm !== null) echo date('d.m.y',$gcp->time_confirm); ?></div>


                                                        <!--Параметры mvps-->
                                                        <div class="" style="display:flex; flex-direction: column; width: 56.3%;">

                                                            <!--Если у ценностного предложения отсутствуют mvp-->
                                                            <?php if (empty($gcp->mvps)) : ?>

                                                                <div class="" style="display:flex; height: 100%;">

                                                                    <div class="text-center first_regular_column_of_stage_for_mvp"></div>
                                                                    <div class="text-center regular_column_for_mvp"></div>
                                                                    <div class="text-center regular_column_for_mvp"></div>
                                                                    <div class="text-center regular_column_for_mvp"></div>

                                                                </div>

                                                            <?php endif;?>

                                                            <?php foreach ($gcp->mvps as $mvp) : ?>

                                                                <div class="" style="display:flex; height: 100%;">

                                                                    <!--Наименования mvps-->
                                                                    <?php
                                                                    $mvp_title = 'MVP ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1]
                                                                        . '.' . explode(' ',$gcp->title)[1] . '.' . explode(' ',$mvp->title)[1];
                                                                    ?>
                                                                    <div class="text-center first_regular_column_of_stage_for_mvp">
                                                                        <?= Html::a($mvp_title, ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                                                            ['class' => 'link_in_column_result_table', 'title' => $mvp->description]); ?>
                                                                    </div>

                                                                    <!--Статусы mvps-->
                                                                    <?php if ($mvp->exist_confirm === 1) : ?>

                                                                        <div class="text-center regular_column_for_mvp">
                                                                            <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                                                ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть подтверждение MVP']);
                                                                            ?>
                                                                        </div>

                                                                    <?php elseif ($mvp->exist_confirm === 0) : ?>

                                                                        <div class="text-center regular_column_for_mvp">
                                                                            <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                                                ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть подтверждение MVP']);
                                                                            ?>
                                                                        </div>

                                                                    <?php elseif ($mvp->confirm && $mvp->exist_confirm === null) : ?>

                                                                        <div class="text-center regular_column_for_mvp">
                                                                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                                ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть подтверждение MVP']);
                                                                            ?>
                                                                        </div>

                                                                    <?php elseif (empty($mvp->confirm) && $mvp->exist_confirm === null) : ?>

                                                                        <div class="text-center regular_column_for_mvp_empty">- - -</div>

                                                                    <?php endif; ?>

                                                                    <!--Даты создания mvps-->
                                                                    <div class="text-center regular_column_for_mvp"><?= date('d.m.y',$mvp->created_at); ?></div>

                                                                    <!--Даты подтверждения mvps-->
                                                                    <div class="text-center regular_column_for_mvp"><?php if ($mvp->time_confirm !== null) echo date('d.m.y',$mvp->time_confirm); ?></div>


                                                                    <!--Бизнес модели-->
                                                                    <?php if ($mvp->businessModel) : ?>

                                                                        <div class="text-center column_business_model_for_mvp">
                                                                            <?= Html::a(Html::img('@web/images/icons/icon-pdf.png', ['style' => ['width' => '20px']]),
                                                                                ['/business-model/index', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть бизнес-модель']);?>
                                                                        </div>

                                                                    <?php else : ?>

                                                                        <div class="text-center column_business_model_for_mvp"></div>

                                                                    <?php endif; ?>


                                                                </div>

                                                            <?endforeach; ?>

                                                        </div>
                                                    </div>

                                                <?php endforeach; ?>

                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>