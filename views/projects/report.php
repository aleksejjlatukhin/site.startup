<?php

use app\models\Segments;
use app\models\StatusConfirmHypothesis;
use yii\helpers\Html;

/**
 * @var Segments[] $segments
 */

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

            <div class="column_title_of_segment"><?= $segment->propertyContainer->getProperty('title') ?></div>

            <?php if (mb_strlen($segment->getName()) > 50) : ?>

                <div class="column_description_of_segment column_block_text_max_1800" title="<?= $segment->getName() ?>">
                    <?= Html::a(mb_substr($segment->getName(), 0, 50) . '...', ['/segments/index', 'id' => $segment->getProjectId()],
                        ['class' => 'link_for_description_stage']) ?>
                </div>

            <?php else : ?>

                <div class="column_description_of_segment column_block_text_max_1800">
                    <?= Html::a($segment->getName(), ['/segments/index', 'id' => $segment->getProjectId()],
                        ['class' => 'link_for_description_stage']) ?>
                </div>

            <?php endif; ?>


            <div class="column_description_of_segment column_block_text_min_1800">
                <?= Html::a($segment->getName(), ['/segments/index', 'id' => $segment->getProjectId()],
                    ['class' => 'link_for_description_stage']) ?>
            </div>


            <div class="column_stage_confirm"><?= $segment->confirm->getCountRespond() ?></div>

            <div class="column_stage_confirm"><?= $segment->confirm->getCountPositive() ?></div>

            <div class="column_stage_confirm"><?= $segment->confirm->getCountConfirmMembers() ?></div>

            <div class="column_stage_confirm"><?= ($segment->confirm->getCountDescInterviewsOfModel() - $segment->confirm->getCountConfirmMembers()) ?></div>

            <div class="column_stage_confirm"><?= ($segment->confirm->getCountRespond() - $segment->confirm->getCountDescInterviewsOfModel()) ?></div>

            <div class="column_stage_confirm">

                <?php if ($segment->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->getId()], ['title' => 'Продолжить подтверждение']) ?>

                <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                        ['/confirm-segment/view', 'id' => $segment->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                <?php endif; ?>

            </div>

            <div class="column_stage_confirm"></div>

        </div>

        <!--Если у сегмента не существует подтверждения-->
        <?php else : ?>

        <div class="stage_data_string">

            <div class="column_title_of_segment"><?= $segment->propertyContainer->getProperty('title') ?></div>


            <?php if (mb_strlen($segment->getName()) > 50) : ?>

                <div class="column_description_of_segment column_block_text_max_1800" title="<?= $segment->getName() ?>">
                    <?= Html::a(mb_substr($segment->getName(), 0, 50) . '...', ['/segments/index', 'id' => $segment->getProjectId()],
                        ['class' => 'link_for_description_stage']) ?>
                </div>

            <?php else : ?>

                <div class="column_description_of_segment column_block_text_max_1800">
                    <?= Html::a($segment->getName(), ['/segments/index', 'id' => $segment->getProjectId()],
                        ['class' => 'link_for_description_stage']) ?>
                </div>

            <?php endif; ?>


            <div class="column_description_of_segment column_block_text_min_1800">
                <?= Html::a($segment->getName(), ['/segments/index', 'id' => $segment->getProjectId()],
                    ['class' => 'link_for_description_stage']) ?>
            </div>


            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>
            <div class="column_stage_confirm">-</div>

            <div class="column_stage_confirm">
                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                    ['/confirm-segment/create', 'id' => $segment->getId()], ['title' => 'Создать подтверждение']) ?>
            </div>

            <div class="column_stage_confirm"></div>

        </div>

        <?php endif; ?>

        <!--Строки проблем сегментов-->
        <?php foreach ($segment->problems as $problem) : ?>

            <!--Если у проблемы существует подтверждение-->
            <?php if($problem->confirm) : ?>

            <div class="stage_data_string">

                <div class="column_title_of_stage"><?= $problem->propertyContainer->getProperty('title') ?></div>


                <?php if (mb_strlen($problem->getDescription()) > 100) : ?>

                    <div class="column_description_of_stage column_block_text_max_1800" title="<?= $problem->getDescription() ?>">
                        <?= Html::a(mb_substr($problem->getDescription(), 0, 100) . '...', ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_max_1800">
                        <?= Html::a($problem->getDescription(), ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php endif; ?>


                <?php if (mb_strlen($problem->getDescription()) > 130) : ?>

                    <div class="column_description_of_stage column_block_text_min_1800" title="<?= $problem->getDescription() ?>">
                        <?= Html::a(mb_substr($problem->getDescription(), 0, 130) . '...', ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_min_1800">
                        <?= Html::a($problem->getDescription(), ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php endif; ?>


                <div class="column_stage_confirm"><?= $problem->confirm->getCountRespond() ?></div>

                <div class="column_stage_confirm"><?= $problem->confirm->getCountPositive() ?></div>

                <div class="column_stage_confirm"><?= $problem->confirm->getCountConfirmMembers() ?></div>

                <div class="column_stage_confirm"><?= ($problem->confirm->getCountDescInterviewsOfModel() - $problem->confirm->getCountConfirmMembers()) ?></div>

                <div class="column_stage_confirm"><?= ($problem->confirm->getCountRespond() - $problem->confirm->getCountDescInterviewsOfModel()) ?></div>

                <div class="column_stage_confirm">

                    <?php if ($problem->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                        <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                    <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->getId()], ['title' => 'Продолжить подтверждение']) ?>

                    <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                        <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                            ['/confirm-problem/view', 'id' => $problem->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                    <?php endif; ?>

                </div>

                <div class="column_stage_confirm"></div>

            </div>

            <!--Если у проблемы не существует подтверждения-->
            <?php else : ?>

            <div class="stage_data_string">

                <div class="column_title_of_stage"><?= $problem->propertyContainer->getProperty('title') ?></div>


                <?php if (mb_strlen($problem->getDescription()) > 100) : ?>

                    <div class="column_description_of_stage column_block_text_max_1800" title="<?= $problem->getDescription() ?>">
                        <?= Html::a(mb_substr($problem->getDescription(), 0, 100) . '...', ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_max_1800">
                        <?= Html::a($problem->getDescription(), ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php endif; ?>


                <?php if (mb_strlen($problem->getDescription()) > 130) : ?>

                    <div class="column_description_of_stage column_block_text_min_1800" title="<?= $problem->getDescription() ?>">
                        <?= Html::a(mb_substr($problem->getDescription(), 0, 130) . '...', ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php else : ?>

                    <div class="column_description_of_stage column_block_text_min_1800">
                        <?= Html::a($problem->getDescription(), ['/problems/index/', 'id' => $problem->getConfirmSegmentId()],
                            ['class' => 'link_for_description_stage']) ?>
                    </div>

                <?php endif; ?>


                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>
                <div class="column_stage_confirm">-</div>

                <div class="column_stage_confirm">
                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                        ['/confirm-problem/create', 'id' => $problem->getId()], ['title' => 'Создать подтверждение']) ?>
                </div>

                <div class="column_stage_confirm"></div>

            </div>

            <?php endif; ?>

            <!--Строки ценностных предложений-->
            <?php foreach ($problem->gcps as $gcp) : ?>

                <!--Если у ценностного предложения существует подтверждение-->
                <?php if($gcp->confirm) : ?>

                    <div class="stage_data_string">

                        <div class="column_title_of_stage"><?= $gcp->propertyContainer->getProperty('title') ?></div>


                        <?php if (mb_strlen($gcp->getDescription()) > 100) : ?>

                            <div class="column_description_of_stage column_block_text_max_1800" title="<?= $gcp->getDescription() ?>">
                                <?= Html::a(mb_substr($gcp->getDescription(), 0, 100) . '...', ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_max_1800">
                                <?= Html::a($gcp->getDescription(), ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php endif; ?>


                        <?php if (mb_strlen($gcp->getDescription()) > 130) : ?>

                            <div class="column_description_of_stage column_block_text_min_1800" title="<?= $gcp->getDescription() ?>">
                                <?= Html::a(mb_substr($gcp->getDescription(), 0, 130) . '...', ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_min_1800">
                                <?= Html::a($gcp->getDescription(), ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php endif; ?>


                        <div class="column_stage_confirm"><?= $gcp->confirm->getCountRespond() ?></div>

                        <div class="column_stage_confirm"><?= $gcp->confirm->getCountPositive() ?></div>

                        <div class="column_stage_confirm"><?= $gcp->confirm->getCountConfirmMembers() ?></div>

                        <div class="column_stage_confirm"><?= ($gcp->confirm->getCountDescInterviewsOfModel() - $gcp->confirm->getCountConfirmMembers()) ?></div>

                        <div class="column_stage_confirm"><?= ($gcp->confirm->getCountRespond() - $gcp->confirm->getCountDescInterviewsOfModel()) ?></div>

                        <div class="column_stage_confirm">

                            <?php if ($gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                            <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()], ['title' => 'Продолжить подтверждение']) ?>

                            <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                            <?php endif; ?>

                        </div>

                        <div class="column_stage_confirm"></div>

                    </div>

                <!--Если у ценностного предложения не существует подтверждения-->
                <?php else : ?>

                    <div class="stage_data_string">

                        <div class="column_title_of_stage"><?= $gcp->propertyContainer->getProperty('title') ?></div>

                        <?php if (mb_strlen($gcp->getDescription()) > 100) : ?>

                            <div class="column_description_of_stage column_block_text_max_1800" title="<?= $gcp->getDescription() ?>">
                                <?= Html::a(mb_substr($gcp->getDescription(), 0, 100) . '...', ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_max_1800">
                                <?= Html::a($gcp->getDescription(), ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php endif; ?>


                        <?php if (mb_strlen($gcp->getDescription()) > 130) : ?>

                            <div class="column_description_of_stage column_block_text_min_1800" title="<?= $gcp->getDescription() ?>">
                                <?= Html::a(mb_substr($gcp->getDescription(), 0, 130) . '...', ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php else : ?>

                            <div class="column_description_of_stage column_block_text_min_1800">
                                <?= Html::a($gcp->getDescription(), ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                    ['class' => 'link_for_description_stage']) ?>
                            </div>

                        <?php endif; ?>


                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>
                        <div class="column_stage_confirm">-</div>

                        <div class="column_stage_confirm">
                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                ['/confirm-gcp/create', 'id' => $gcp->getId()], ['title' => 'Создать подтверждение']) ?>
                        </div>

                        <div class="column_stage_confirm"></div>

                    </div>

                <?php endif; ?>

                <!--Строки MVP(продуктов)-->
                <?php foreach ($gcp->mvps as $mvp) : ?>

                    <!--Если у MVP существует подтверждение-->
                    <?php if($mvp->confirm) : ?>

                        <div class="stage_data_string">

                            <div class="column_title_of_stage"><?= $mvp->propertyContainer->getProperty('title') ?></div>


                            <?php if (mb_strlen($mvp->getDescription()) > 100) : ?>

                                <div class="column_description_of_stage column_block_text_max_1800" title="<?= $mvp->getDescription() ?>">
                                    <?= Html::a(mb_substr($mvp->getDescription(), 0, 100) . '...', ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_max_1800">
                                    <?= Html::a($mvp->getDescription(), ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php endif; ?>


                            <?php if (mb_strlen($mvp->getDescription()) > 130) : ?>

                                <div class="column_description_of_stage column_block_text_min_1800" title="<?= $mvp->getDescription() ?>">
                                    <?= Html::a(mb_substr($mvp->getDescription(), 0, 130) . '...', ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_min_1800">
                                    <?= Html::a($mvp->getDescription(), ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php endif; ?>


                            <div class="column_stage_confirm"><?= $mvp->confirm->getCountRespond() ?></div>

                            <div class="column_stage_confirm"><?= $mvp->confirm->getCountPositive() ?></div>

                            <div class="column_stage_confirm"><?= $mvp->confirm->getCountConfirmMembers() ?></div>

                            <div class="column_stage_confirm"><?= ($mvp->confirm->getCountDescInterviewsOfModel() - $mvp->confirm->getCountConfirmMembers()) ?></div>

                            <div class="column_stage_confirm"><?= ($mvp->confirm->getCountRespond() - $mvp->confirm->getCountDescInterviewsOfModel()) ?></div>

                            <div class="column_stage_confirm">

                                <?php if ($mvp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                                <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()], ['title' => 'Продолжить подтверждение']) ?>

                                <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()], ['title' => 'Посмотреть подтверждение']) ?>

                                <?php endif; ?>

                            </div>

                            <!--Бизнес модели-->
                            <?php if (!$mvp->businessModel && $mvp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                <div class="column_stage_confirm">
                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                        ['/business-model/index', 'id' => $mvp->confirm->getId()], ['title'=> 'Создать бизнес-модель']) ?>
                                </div>

                            <?php elseif ($mvp->businessModel) : ?>

                                <div class="column_stage_confirm">
                                    <?= Html::a(Html::img('@web/images/icons/icon-pdf.png', ['style' => ['width' => '20px']]),
                                        ['/business-model/index', 'id' => $mvp->confirm->getId()], ['title'=> 'Посмотреть бизнес-модель']) ?>
                                </div>

                            <?php else : ?>

                                <div class="column_stage_confirm"></div>

                            <?php endif; ?>

                        </div>

                    <!--Если у MVP не существует подтверждения-->
                    <?php else : ?>

                        <div class="stage_data_string">

                            <div class="column_title_of_stage"><?= $mvp->propertyContainer->getProperty('title') ?></div>


                            <?php if (mb_strlen($mvp->getDescription()) > 100) : ?>

                                <div class="column_description_of_stage column_block_text_max_1800" title="<?= $mvp->getDescription() ?>">
                                    <?= Html::a(mb_substr($mvp->getDescription(), 0, 100) . '...', ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_max_1800">
                                    <?= Html::a($mvp->getDescription(), ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php endif; ?>


                            <?php if (mb_strlen($mvp->getDescription()) > 130) : ?>

                                <div class="column_description_of_stage column_block_text_min_1800" title="<?= $mvp->getDescription() ?>">
                                    <?= Html::a(mb_substr($mvp->getDescription(), 0, 130) . '...', ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php else : ?>

                                <div class="column_description_of_stage column_block_text_min_1800">
                                    <?= Html::a($mvp->getDescription(), ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                        ['class' => 'link_for_description_stage']) ?>
                                </div>

                            <?php endif; ?>


                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>
                            <div class="column_stage_confirm">-</div>

                            <div class="column_stage_confirm">
                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-mvp/create', 'id' => $mvp->getId()], ['title' => 'Создать подтверждение']) ?>
                            </div>

                            <div class="column_stage_confirm"></div>

                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endforeach;?>

</div>
