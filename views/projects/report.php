<?php

use yii\helpers\Html;

?>

<div class="report-project">

    <!--Шапка таблицы-->
    <div class="report-project-header">

        <div class="left_part_header">Наименование этапа</div>

        <div class="right_part_header">
            
            <div class="right_part_header_top">Результаты проведенных тестов</div>

            <div class="right_part_header_bottom">

                <div>Запланировано</div>
                <div>Необходимо</div>
                <div>Положительные</div>
                <div>Отрицательные</div>
                <div>Не опрошены</div>
                <div>Статус</div>
                <div>Бизнес-модель</div>

            </div>
        </div>
    </div>

    <!--Строки сегментов-->
    <?php foreach ($segments as $segment) : ?>

        <!--Если у сегмента существует подтверждение-->
        <?php if($segment->confirm) : ?>

        <div class="stage_data_string">

            <div class="column_title_of_segment"><?= $segment->propertyContainer->getProperty('title'); ?></div>

            <?php if (mb_strlen($segment->name) > 50) : ?>

                <div class="column_description_of_segment column_block_text_max_1800" title="<?= $segment->name; ?>">
                    <?= Html::a(mb_substr($segment->name, 0, 50) . '...', ['/segments/index', 'id' => $segment->projectId],
                        ['class' => 'link_for_description_stage']); ?>
                </div>

            <?php else : ?>

                <div class="column_description_of_segment column_block_text_max_1800">
                    <?= Html::a($segment->name, ['/segments/index', 'id' => $segment->projectId],
                        ['class' => 'link_for_description_stage']); ?>
                </div>

            <?php endif; ?>


            <div class="column_description_of_segment column_block_text_min_1800">
                <?= Html::a($segment->name, ['/segments/index', 'id' => $segment->projectId],
                    ['class' => 'link_for_description_stage']); ?>
            </div>


            <div class="column_stage_confirm"><?= $segment->confirm->count_respond; ?></div>

            <div class="column_stage_confirm"><?= $segment->confirm->count_positive; ?></div>

            <div class="column_stage_confirm"><?= $segment->confirm->countConfirmMembers; ?></div>

            <div class="column_stage_confirm"><?= ($segment->confirm->countDescInterviewsOfModel - $segment->confirm->countConfirmMembers); ?></div>

            <div class="column_stage_confirm"><?= ($segment->confirm->count_respond - $segment->confirm->countDescInterviewsOfModel); ?></div>

            <div class="column_stage_confirm">

                <?php if ($segment->exist_confirm === 1) : ?>

                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                <?php elseif ($segment->exist_confirm === null) : ?>

                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title' => 'Продолжить подтверждение']); ?>

                <?php elseif ($segment->exist_confirm === 0) : ?>

                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                <?php endif; ?>

            </div>

            <div class="column_stage_confirm"></div>

        </div>

        <!--Если у сегмента не существует подтверждения-->
        <?php else : ?>

        <div class="stage_data_string">

            <div class="column_title_of_segment"><?= $segment->propertyContainer->getProperty('title'); ?></div>


            <?php if (mb_strlen($segment->name) > 50) : ?>

                <div class="column_description_of_segment column_block_text_max_1800" title="<?= $segment->name; ?>">
                    <?= Html::a(mb_substr($segment->name, 0, 50) . '...', ['/segments/index', 'id' => $segment->projectId],
                        ['class' => 'link_for_description_stage']); ?>
                </div>

            <?php else : ?>

                <div class="column_description_of_segment column_block_text_max_1800">
                    <?= Html::a($segment->name, ['/segments/index', 'id' => $segment->projectId],
                        ['class' => 'link_for_description_stage']); ?>
                </div>

            <?php endif; ?>


            <div class="column_description_of_segment column_block_text_min_1800">
                <?= Html::a($segment->name, ['/segments/index', 'id' => $segment->projectId],
                    ['class' => 'link_for_description_stage']); ?>
            </div>


            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>

            <div class="column_stage_confirm">
                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                    ['/confirm-segment/create', 'id' => $segment->id], ['title' => 'Создать подтверждение']); ?>
            </div>

            <div class="column_stage_confirm"></div>

        </div>

        <?php endif; ?>

        <!--Строки проблем сегментов-->
        <?php foreach ($segment->problems as $problem) : ?>

            <!--Если у проблемы существует подтверждение-->
            <?php if($problem->confirm) : ?>

            <div class="stage_data_string">

                <div class="column_title_of_stage"><?= $problem->propertyContainer->getProperty('title'); ?></div>


                <?php if (mb_strlen($problem->description) > 100) : ?>

                    <div class="column_description_of_stage column_block_text_max_1800" title="<?= $problem->description; ?>">
                        <?= Html::a(mb_substr($problem->description, 0, 100) . '...', ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_max_1800">
                        <?= Html::a($problem->description, ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php endif; ?>


                <?php if (mb_strlen($problem->description) > 130) : ?>

                    <div class="column_description_of_stage column_block_text_min_1800" title="<?= $problem->description; ?>">
                        <?= Html::a(mb_substr($problem->description, 0, 130) . '...', ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_min_1800">
                        <?= Html::a($problem->description, ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php endif; ?>


                <div class="column_stage_confirm"><?= $problem->confirm->count_respond; ?></div>

                <div class="column_stage_confirm"><?= $problem->confirm->count_positive; ?></div>

                <div class="column_stage_confirm"><?= $problem->confirm->countConfirmMembers; ?></div>

                <div class="column_stage_confirm"><?= ($problem->confirm->countDescInterviewsOfModel - $problem->confirm->countConfirmMembers); ?></div>

                <div class="column_stage_confirm"><?= ($problem->confirm->count_respond - $problem->confirm->countDescInterviewsOfModel); ?></div>

                <div class="column_stage_confirm">

                    <?php if ($problem->exist_confirm === 1) : ?>

                        <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                    <?php elseif ($problem->exist_confirm === null) : ?>

                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title' => 'Продолжить подтверждение']); ?>

                    <?php elseif ($problem->exist_confirm === 0) : ?>

                        <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                    <?php endif; ?>

                </div>

                <div class="column_stage_confirm"></div>

            </div>

            <!--Если у проблемы не существует подтверждения-->
            <?php else : ?>

            <div class="stage_data_string">

                <div class="column_title_of_stage"><?= $problem->propertyContainer->getProperty('title'); ?></div>


                <?php if (mb_strlen($problem->description) > 100) : ?>

                    <div class="column_description_of_stage column_block_text_max_1800" title="<?= $problem->description; ?>">
                        <?= Html::a(mb_substr($problem->description, 0, 100) . '...', ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_max_1800">
                        <?= Html::a($problem->description, ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php endif; ?>


                <?php if (mb_strlen($problem->description) > 130) : ?>

                    <div class="column_description_of_stage column_block_text_min_1800" title="<?= $problem->description; ?>">
                        <?= Html::a(mb_substr($problem->description, 0, 130) . '...', ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_min_1800">
                        <?= Html::a($problem->description, ['/problems/index/', 'id' => $problem->confirmSegmentId],
                            ['class' => 'link_for_description_stage']); ?>
                    </div>

                <?php endif; ?>


                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>

                <div class="column_stage_confirm">
                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                        ['/confirm-problem/create', 'id' => $problem->id], ['title' => 'Создать подтверждение']); ?>
                </div>

                <div class="column_stage_confirm"></div>

            </div>

            <?php endif; ?>

            <!--Строки ценностных предложений-->
            <?php foreach ($problem->gcps as $gcp) : ?>

                <!--Если у ценностного предложения существует подтверждение-->
                <?php if($gcp->confirm) : ?>

                    <div class="stage_data_string">

                        <div class="column_title_of_stage"><?= $gcp->propertyContainer->getProperty('title'); ?></div>


                        <?php if (mb_strlen($gcp->description) > 100) : ?>

                            <div class="column_description_of_stage column_block_text_max_1800" title="<?= $gcp->description; ?>">
                                <?= Html::a(mb_substr($gcp->description, 0, 100) . '...', ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_max_1800">
                                <?= Html::a($gcp->description, ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php endif; ?>


                        <?php if (mb_strlen($gcp->description) > 130) : ?>

                            <div class="column_description_of_stage column_block_text_min_1800" title="<?= $gcp->description; ?>">
                                <?= Html::a(mb_substr($gcp->description, 0, 130) . '...', ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_min_1800">
                                <?= Html::a($gcp->description, ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php endif; ?>


                        <div class="column_stage_confirm"><?= $gcp->confirm->count_respond; ?></div>

                        <div class="column_stage_confirm"><?= $gcp->confirm->count_positive; ?></div>

                        <div class="column_stage_confirm"><?= $gcp->confirm->countConfirmMembers; ?></div>

                        <div class="column_stage_confirm"><?= ($gcp->confirm->countDescInterviewsOfModel - $gcp->confirm->countConfirmMembers); ?></div>

                        <div class="column_stage_confirm"><?= ($gcp->confirm->count_respond - $gcp->confirm->countDescInterviewsOfModel); ?></div>

                        <div class="column_stage_confirm">

                            <?php if ($gcp->exist_confirm === 1) : ?>

                                <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                            <?php elseif ($gcp->exist_confirm === null) : ?>

                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title' => 'Продолжить подтверждение']); ?>

                            <?php elseif ($gcp->exist_confirm === 0) : ?>

                                <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                            <?php endif; ?>

                        </div>

                        <div class="column_stage_confirm"></div>

                    </div>

                <!--Если у ценностного предложения не существует подтверждения-->
                <?php else : ?>

                    <div class="stage_data_string">

                        <div class="column_title_of_stage"><?= $gcp->propertyContainer->getProperty('title'); ?></div>

                        <?php if (mb_strlen($gcp->description) > 100) : ?>

                            <div class="column_description_of_stage column_block_text_max_1800" title="<?= $gcp->description; ?>">
                                <?= Html::a(mb_substr($gcp->description, 0, 100) . '...', ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_max_1800">
                                <?= Html::a($gcp->description, ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php endif; ?>


                        <?php if (mb_strlen($gcp->description) > 130) : ?>

                            <div class="column_description_of_stage column_block_text_min_1800" title="<?= $gcp->description; ?>">
                                <?= Html::a(mb_substr($gcp->description, 0, 130) . '...', ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_min_1800">
                                <?= Html::a($gcp->description, ['/gcps/index', 'id' => $gcp->confirmProblemId],
                                    ['class' => 'link_for_description_stage']); ?>
                            </div>

                        <?php endif; ?>


                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>

                        <div class="column_stage_confirm">
                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                ['/confirm-gcp/create', 'id' => $gcp->id], ['title' => 'Создать подтверждение']); ?>
                        </div>

                        <div class="column_stage_confirm"></div>

                    </div>

                <?php endif; ?>

                <!--Строки MVP(продуктов)-->
                <?php foreach ($gcp->mvps as $mvp) : ?>

                    <!--Если у MVP существует подтверждение-->
                    <?php if($mvp->confirm) : ?>

                        <div class="stage_data_string">

                            <div class="column_title_of_stage"><?= $mvp->propertyContainer->getProperty('title'); ?></div>


                            <?php if (mb_strlen($mvp->description) > 100) : ?>

                                <div class="column_description_of_stage column_block_text_max_1800" title="<?= $mvp->description; ?>">
                                    <?= Html::a(mb_substr($mvp->description, 0, 100) . '...', ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_max_1800">
                                    <?= Html::a($mvp->description, ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php endif; ?>


                            <?php if (mb_strlen($mvp->description) > 130) : ?>

                                <div class="column_description_of_stage column_block_text_min_1800" title="<?= $mvp->description; ?>">
                                    <?= Html::a(mb_substr($mvp->description, 0, 130) . '...', ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_min_1800">
                                    <?= Html::a($mvp->description, ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php endif; ?>


                            <div class="column_stage_confirm"><?= $mvp->confirm->count_respond; ?></div>

                            <div class="column_stage_confirm"><?= $mvp->confirm->count_positive; ?></div>

                            <div class="column_stage_confirm"><?= $mvp->confirm->countConfirmMembers; ?></div>

                            <div class="column_stage_confirm"><?= ($mvp->confirm->countDescInterviewsOfModel - $mvp->confirm->countConfirmMembers); ?></div>

                            <div class="column_stage_confirm"><?= ($mvp->confirm->count_respond - $mvp->confirm->countDescInterviewsOfModel); ?></div>

                            <div class="column_stage_confirm">

                                <?php if ($mvp->exist_confirm === 1) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                                <?php elseif ($mvp->exist_confirm === null) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title' => 'Продолжить подтверждение']); ?>

                                <?php elseif ($mvp->exist_confirm === 0) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->id], ['title' => 'Посмотреть подтверждение']); ?>

                                <?php endif; ?>

                            </div>

                            <!--Бизнес модели-->
                            <?php if ($mvp->businessModel) : ?>

                                <div class="column_stage_confirm">
                                    <?= Html::a(Html::img('@web/images/icons/icon-pdf.png', ['style' => ['width' => '20px']]),
                                        ['/business-model/index', 'id' => $mvp->confirm->id], ['title'=> 'Посмотреть бизнес-модель']);?>
                                </div>

                            <?php elseif (empty($mvp->businessModel) && $mvp->exist_confirm === 1) : ?>

                                <div class="column_stage_confirm">
                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                        ['/business-model/index', 'id' => $mvp->confirm->id], ['title'=> 'Создать бизнес-модель']);
                                    ?>
                                </div>

                            <?php else : ?>

                                <div class="column_stage_confirm"></div>

                            <?php endif; ?>

                        </div>

                    <!--Если у MVP не существует подтверждения-->
                    <?php else : ?>

                        <div class="stage_data_string">

                            <div class="column_title_of_stage"><?= $mvp->propertyContainer->getProperty('title'); ?></div>


                            <?php if (mb_strlen($mvp->description) > 100) : ?>

                                <div class="column_description_of_stage column_block_text_max_1800" title="<?= $mvp->description; ?>">
                                    <?= Html::a(mb_substr($mvp->description, 0, 100) . '...', ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_max_1800">
                                    <?= Html::a($mvp->description, ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php endif; ?>


                            <?php if (mb_strlen($mvp->description) > 130) : ?>

                                <div class="column_description_of_stage column_block_text_min_1800" title="<?= $mvp->description; ?>">
                                    <?= Html::a(mb_substr($mvp->description, 0, 130) . '...', ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_min_1800">
                                    <?= Html::a($mvp->description, ['/mvps/index', 'id' => $mvp->confirmGcpId],
                                        ['class' => 'link_for_description_stage']); ?>
                                </div>

                            <?php endif; ?>


                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>

                            <div class="column_stage_confirm">
                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-mvp/create', 'id' => $mvp->id], ['title' => 'Создать подтверждение']); ?>
                            </div>

                            <div class="column_stage_confirm"></div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach;?>

</div>




