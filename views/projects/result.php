<?php
use yii\helpers\Html;

?>

<div class="TableResultProject">


    <div class="containerHeaderTableResultProject">
        <div class="headerTableResultProject">
            <div class="text-center"><span>Проект:</span> <?= $project->project_name; ?></div>
        </div>
    </div>


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
                        <div class="text-center first_regular_column_of_stage">Номер</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">Ценностные предложения</div>

                    <div class="columns_stage">
                        <div class="text-center regular_column first_regular_column_of_stage">Номер</div>
                        <div class="text-center regular_column">Статус</div>
                        <div class="text-center regular_column">Дата генер.</div>
                        <div class="text-center regular_column">Дата подтв.</div>
                    </div>

                </div>


                <div class="one_block_for_double_header_level">

                    <div class="text-center stage">MVP (продукт)</div>

                    <div class="columns_stage">
                        <div class="text-center first_regular_column_of_stage">Номер</div>
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




    <div class="containerDataOfTableResultProject">

        <div class="dataOfTableResultProject">

            <?php foreach ($segments as $number_segment => $segment) : ?>

                <div class="rowSegmentTableResultProject">

                    <div class="container_all_stage">

                        <!--Наименования сегментов-->
                        <div class="column_segment_name">
                            <?= Html::a('<span>Сегмент ' . ($number_segment+1) . ': </span>' . $segment->name,
                                ['/segment/index', 'id' => $segment->project_id], ['class' => 'link_in_column_result_table']); ?>
                        </div>

                        <!--Статусы сегментов-->
                        <?php if ($segment->exist_confirm === 1) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                    ['/interview/view', 'id' => $segment->interview->id], ['title'=> 'Посмотреть подтверждение сегмента']);
                                ?>
                            </div>

                        <?php elseif ($segment->exist_confirm === 0) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                    ['/interview/view', 'id' => $segment->interview->id], ['title'=> 'Посмотреть подтверждение сегмента']);
                                ?>
                            </div>

                        <?php elseif ($segment->exist_confirm === null) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/interview/create', 'id' => $segment->id], ['title'=> 'Подтвердить сегмент']);
                                ?>
                            </div>

                        <?php endif; ?>

                        <!--Даты создания сегментов-->
                        <div class="text-center regular_column">
                            <?= date('d.m.y',$segment->created_at); ?>
                        </div>

                        <!--Даты подтверждения сегментов-->
                        <div class="text-center regular_column">
                            <?php if ($segment->time_confirm !== null) echo date('d.m.y', $segment->time_confirm); ?>
                        </div>

                        <!--Параметры проблем-->
                        <div class="" style="display:flex; flex-direction: column;">

                            <!--Если у сегмента отсутствуют гипотезы проблем-->
                            <?php if (empty($segment->problems)) : ?>

                                <div class="" style="display:flex; height: 100%;">

                                    <?php if ($segment->exist_confirm === 1) : ?>

                                        <div class="text-center first_regular_column_of_stage">
                                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                ['/generation-problem/index', 'id' => $segment->interview->id], ['title'=> 'Создать ГПС']);
                                            ?>
                                        </div>

                                    <?php else : ?>

                                        <div class="text-center first_regular_column_of_stage"></div>

                                    <?php endif; ?>

                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>

                                    <div class="text-center first_regular_column_of_stage"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>

                                    <div class="text-center first_regular_column_of_stage"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center regular_column"></div>
                                    <div class="text-center column_business_model"></div>

                                </div>

                            <?php endif;?>

                            <!--Если у сегмента есть гипотезы проблем-->
                            <?php foreach ($segment->problems as $number_problem => $problem) : ?>

                                <div class="" style="display:flex; height: 100%;">

                                    <!--Наименования проблем-->
                                    <?php $problem_title = 'ГПС ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1]; ?>
                                    <div class="text-center first_regular_column_of_stage">
                                        <?= Html::a($problem_title, ['/generation-problem/index', 'id' => $problem->interview_id],
                                            ['class' => 'link_in_column_result_table', 'title' => $problem->description]); ?>
                                    </div>

                                    <!--Статусы проблем-->
                                    <?php if ($problem->exist_confirm === 1) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title'=> 'Посмотреть подтверждение ГПС']);
                                            ?>
                                        </div>

                                    <?php elseif ($problem->exist_confirm === 0) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title'=> 'Посмотреть подтверждение ГПС']);
                                            ?>
                                        </div>

                                    <?php elseif ($problem->exist_confirm === null) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/create', 'id' => $problem->id], ['title'=> 'Подтвердить ГПС']);
                                            ?>
                                        </div>

                                    <?php endif; ?>

                                    <!--Даты создания проблем-->
                                    <div class="text-center regular_column"><?= date('d.m.y',$problem->created_at); ?></div>

                                    <!--Даты подтверждения проблем-->
                                    <div class="text-center regular_column"><?php if ($problem->time_confirm !== null) echo date('d.m.y',$problem->time_confirm); ?></div>

                                    <!--Параметры ценностных предложений-->
                                    <div class="" style="display:flex; flex-direction: column;">

                                        <!--Если у проблемы отсутствуют гипотезы ценностных предложений-->
                                        <?php if (empty($problem->gcps)) : ?>

                                            <div class="" style="display:flex; height: 100%;">

                                                <?php if ($problem->exist_confirm === 1) : ?>

                                                    <div class="text-center first_regular_column_of_stage">
                                                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                            ['/gcp/index', 'id' => $problem->confirm->id], ['title'=> 'Создать ГЦП']);
                                                        ?>
                                                    </div>

                                                <?php else : ?>

                                                    <div class="text-center first_regular_column_of_stage"></div>

                                                <?php endif; ?>

                                                <div class="text-center regular_column"></div>
                                                <div class="text-center regular_column"></div>
                                                <div class="text-center regular_column"></div>

                                                <div class="text-center first_regular_column_of_stage"></div>
                                                <div class="text-center regular_column"></div>
                                                <div class="text-center regular_column"></div>
                                                <div class="text-center regular_column"></div>
                                                <div class="text-center column_business_model"></div>

                                            </div>

                                        <?php endif;?>

                                        <?php foreach ($problem->gcps as $gcp) : ?>

                                            <div class="" style="display:flex; height: 100%;">

                                                <!--Наименования ценностных предложений-->
                                                <?php $gcp_title = 'ГЦП ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1] . '.' . explode(' ',$gcp->title)[1]; ?>
                                                <div class="text-center first_regular_column_of_stage">
                                                    <?= Html::a($gcp_title, ['/gcp/index', 'id' => $gcp->confirm_problem_id],
                                                        ['class' => 'link_in_column_result_table', 'title' => $gcp->description]); ?>
                                                </div>

                                                <!--Статусы ценностных предложений-->
                                                <?php if ($gcp->exist_confirm === 1) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title'=> 'Посмотреть подтверждение ГЦП']);
                                                        ?>
                                                    </div>

                                                <?php elseif ($gcp->exist_confirm === 0) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title'=> 'Посмотреть подтверждение ГЦП']);
                                                        ?>
                                                    </div>

                                                <?php elseif ($gcp->exist_confirm === null) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/create', 'id' => $gcp->id], ['title'=> 'Подтвердить ГЦП']);
                                                        ?>
                                                    </div>

                                                <?php endif; ?>

                                                <!--Даты создания ценностных предложений-->
                                                <div class="text-center regular_column"><?= date('d.m.y',$gcp->created_at); ?></div>

                                                <!--Даты подтверждения ценностных предложений-->
                                                <div class="text-center regular_column"><?php if ($gcp->time_confirm !== null) echo date('d.m.y',$gcp->time_confirm); ?></div>

                                                <!--Параметры ценностных предложений-->
                                                <div class="" style="display:flex; flex-direction: column;">

                                                    <!--Если у ценностного предложения отсутствуют mvp-->
                                                    <?php if (empty($gcp->mvps)) : ?>

                                                        <div class="" style="display:flex; height: 100%;">

                                                            <?php if ($gcp->exist_confirm === 1) : ?>

                                                                <div class="text-center first_regular_column_of_stage">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/mvp/index', 'id' => $gcp->confirm->id], ['title'=> 'Создать MVP']);
                                                                    ?>
                                                                </div>

                                                            <?php else : ?>

                                                                <div class="text-center first_regular_column_of_stage"></div>

                                                            <?php endif; ?>

                                                            <div class="text-center regular_column"></div>
                                                            <div class="text-center regular_column"></div>
                                                            <div class="text-center regular_column"></div>
                                                            <div class="text-center column_business_model"></div>

                                                        </div>

                                                    <?php endif;?>

                                                    <?php foreach ($gcp->mvps as $mvp) : ?>

                                                        <div class="" style="display:flex; height: 100%;">

                                                            <!--Наименования mvps-->
                                                            <?php
                                                            $mvp_title = 'MVP ' . ($number_segment+1) . '.' . explode(' ',$problem->title)[1]
                                                                . '.' . explode(' ',$gcp->title)[1] . '.' . explode(' ',$mvp->title)[1];
                                                            ?>
                                                            <div class="text-center first_regular_column_of_stage">
                                                                <?= Html::a($mvp_title, ['/mvp/index', 'id' => $mvp->confirm_gcp_id],
                                                                    ['class' => 'link_in_column_result_table', 'title' => $mvp->description]); ?>
                                                            </div>

                                                            <!--Статусы mvps-->
                                                            <?php if ($mvp->exist_confirm === 1) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть подтверждение MVP']);
                                                                    ?>
                                                                </div>

                                                            <?php elseif ($mvp->exist_confirm === 0) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть подтверждение MVP']);
                                                                    ?>
                                                                </div>

                                                            <?php elseif ($mvp->exist_confirm === null) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/create', 'id' => $mvp->id], ['title'=> 'Подтвердить MVP']);
                                                                    ?>
                                                                </div>

                                                            <?php endif; ?>

                                                            <!--Даты создания mvps-->
                                                            <div class="text-center regular_column"><?= date('d.m.y',$mvp->created_at); ?></div>

                                                            <!--Даты подтверждения mvps-->
                                                            <div class="text-center regular_column"><?php if ($mvp->time_confirm !== null) echo date('d.m.y',$mvp->time_confirm); ?></div>

                                                            <!--Бизнес модели-->
                                                            <?php if ($mvp->businessModel) : ?>

                                                                <div class="text-center column_business_model">
                                                                    <?= Html::a(Html::img('@web/images/icons/icon-pdf.png', ['style' => ['width' => '20px']]),
                                                                        ['/business-model/index', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть бизнес-модель']);?>
                                                                </div>

                                                            <?php elseif (empty($mvp->businessModel) && $mvp->exist_confirm === 1) : ?>

                                                                <div class="text-center column_business_model">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/business-model/index', 'id' => $mvp->confirm->id], ['title'=> 'Создать бизнес-модель']);
                                                                    ?>
                                                                </div>

                                                            <?php else : ?>

                                                                <div class="text-center column_business_model"></div>

                                                            <?php endif; ?>

                                                        </div>

                                                    <?php endforeach; ?>

                                                </div>

                                            </div>

                                        <?php endforeach; ?>

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
