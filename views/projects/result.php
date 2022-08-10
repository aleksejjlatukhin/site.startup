<?php

use app\models\Gcps;
use app\models\Mvps;
use app\models\Problems;
use app\models\Projects;
use app\models\Segments;
use app\models\StatusConfirmHypothesis;
use yii\helpers\Html;

/**
 * @var Projects $project
 * @var Segments[] $segments
 * @var Problems $problem
 * @var Gcps $gcp
 * @var Mvps $mvp
 */

?>

<div class="TableResultProject">


    <div class="containerHeaderTableResultProject">
        <div class="headerTableResultProject">
            <div class="text-center">

                <?= Html::a('<div style="margin-top: -50px;">Проект: ' . $project->getProjectName() . ' ' . Html::img('/images/icons/icon_export_light.png', ['style' => ['width' => '22px', 'margin-left' => '10px', 'margin-bottom' => '5px']]) . '</div>', [
                    '/projects/result-export', 'id' => $project->getId()], [
                    'class' => 'export_link_hypothesis',
                    'target' => '_blank',
                    'title' => 'Скачать в pdf',
                ]) ?>

            </div>
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




    <div class="containerDataOfTableResultProject">

        <div class="dataOfTableResultProject">

            <?php foreach ($segments as $number_segment => $segment) : ?>

                <div class="rowSegmentTableResultProject">

                    <div class="container_all_stage">

                        <!--Наименования сегментов-->
                        <div class="column_segment_name">
                            <?= Html::a('<span>Сегмент ' . ($number_segment+1) . ': </span>' . $segment->getName(),
                                ['/segments/index', 'id' => $segment->getProjectId()], ['class' => 'link_in_column_result_table']) ?>
                        </div>

                        <!--Статусы сегментов-->
                        <?php if ($segment->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-segment/view', 'id' => $segment->confirm->getId()], ['title'=> 'Посмотреть подтверждение сегмента']) ?>
                            </div>

                        <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-segment/view', 'id' => $segment->confirm->getId()], ['title'=> 'Посмотреть подтверждение сегмента']) ?>
                            </div>

                        <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                            <div class="text-center regular_column">
                                <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                    ['/confirm-segment/create', 'id' => $segment->getId()], ['title'=> 'Подтвердить сегмент']) ?>
                            </div>

                        <?php endif; ?>

                        <!--Даты создания сегментов-->
                        <div class="text-center regular_column">
                            <?= date('d.m.y',$segment->getCreatedAt()) ?>
                        </div>

                        <!--Даты подтверждения сегментов-->
                        <div class="text-center regular_column">
                            <?php if ($segment->getTimeConfirm() !== null) {
                                echo date('d.m.y', $segment->getTimeConfirm());
                            } ?>
                        </div>

                        <!--Параметры проблем-->
                        <div class="" style="display:flex; flex-direction: column;">

                            <!--Если у сегмента отсутствуют гипотезы проблем-->
                            <?php if (empty($segment->problems)) : ?>

                                <div class="" style="display:flex; height: 100%;">

                                    <?php if ($segment->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                        <div class="text-center first_regular_column_of_stage">
                                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                ['/problems/index', 'id' => $segment->confirm->id], ['title'=> 'Создать ГПС']) ?>
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
                                    <?php $problem_title = 'ГПС ' . ($number_segment+1) . '.' . explode(' ',$problem->getTitle())[1]; ?>
                                    <div class="text-center first_regular_column_of_stage">
                                        <?= Html::a($problem_title, ['/problems/index', 'id' => $problem->getConfirmSegmentId()],
                                            ['class' => 'link_in_column_result_table', 'title' => $problem->getDescription()]) ?>
                                    </div>

                                    <!--Статусы проблем-->
                                    <?php if ($problem->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/view', 'id' => $problem->confirm->getId()], ['title'=> 'Посмотреть подтверждение ГПС']) ?>
                                        </div>

                                    <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/view', 'id' => $problem->confirm->getId()], ['title'=> 'Посмотреть подтверждение ГПС']) ?>
                                        </div>

                                    <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                        <div class="text-center regular_column">
                                            <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                ['/confirm-problem/create', 'id' => $problem->getId()], ['title'=> 'Подтвердить ГПС']) ?>
                                        </div>

                                    <?php endif; ?>

                                    <!--Даты создания проблем-->
                                    <div class="text-center regular_column"><?= date('d.m.y',$problem->getCreatedAt()) ?></div>

                                    <!--Даты подтверждения проблем-->
                                    <div class="text-center regular_column">
                                        <?php if ($problem->getTimeConfirm() !== null) {
                                            echo date('d.m.y', $problem->getTimeConfirm());
                                        } ?>
                                    </div>

                                    <!--Параметры ценностных предложений-->
                                    <div class="" style="display:flex; flex-direction: column;">

                                        <!--Если у проблемы отсутствуют гипотезы ценностных предложений-->
                                        <?php if (empty($problem->gcps)) : ?>

                                            <div class="" style="display:flex; height: 100%;">

                                                <?php if ($problem->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                                    <div class="text-center first_regular_column_of_stage">
                                                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                            ['/gcps/index', 'id' => $problem->confirm->getId()], ['title'=> 'Создать ГЦП']) ?>
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
                                                <?php $gcp_title = 'ГЦП ' . ($number_segment+1) . '.' . explode(' ',$problem->getTitle())[1] . '.' . explode(' ',$gcp->getTitle())[1]; ?>
                                                <div class="text-center first_regular_column_of_stage">
                                                    <?= Html::a($gcp_title, ['/gcps/index', 'id' => $gcp->getConfirmProblemId()],
                                                        ['class' => 'link_in_column_result_table', 'title' => $gcp->getDescription()]) ?>
                                                </div>

                                                <!--Статусы ценностных предложений-->
                                                <?php if ($gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()], ['title'=> 'Посмотреть подтверждение ГЦП']) ?>
                                                    </div>

                                                <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()], ['title'=> 'Посмотреть подтверждение ГЦП']) ?>
                                                    </div>

                                                <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                                    <div class="text-center regular_column">
                                                        <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                            ['/confirm-gcp/create', 'id' => $gcp->getId()], ['title'=> 'Подтвердить ГЦП']) ?>
                                                    </div>

                                                <?php endif; ?>

                                                <!--Даты создания ценностных предложений-->
                                                <div class="text-center regular_column"><?= date('d.m.y',$gcp->getCreatedAt()) ?></div>

                                                <!--Даты подтверждения ценностных предложений-->
                                                <div class="text-center regular_column">
                                                    <?php if ($gcp->getTimeConfirm() !== null) {
                                                        echo date('d.m.y', $gcp->getTimeConfirm());
                                                    } ?>
                                                </div>

                                                <!--Параметры ценностных предложений-->
                                                <div class="" style="display:flex; flex-direction: column;">

                                                    <!--Если у ценностного предложения отсутствуют mvp-->
                                                    <?php if (empty($gcp->mvps)) : ?>

                                                        <div class="" style="display:flex; height: 100%;">

                                                            <?php if ($gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                                                <div class="text-center first_regular_column_of_stage">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/mvps/index', 'id' => $gcp->confirm->getId()], ['title'=> 'Создать MVP']) ?>
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
                                                            $mvp_title = 'MVP ' . ($number_segment+1) . '.' . explode(' ',$problem->getTitle())[1]
                                                                . '.' . explode(' ',$gcp->getTitle())[1] . '.' . explode(' ',$mvp->getTitle())[1];
                                                            ?>
                                                            <div class="text-center first_regular_column_of_stage">
                                                                <?= Html::a($mvp_title, ['/mvps/index', 'id' => $mvp->getConfirmGcpId()],
                                                                    ['class' => 'link_in_column_result_table', 'title' => $mvp->getDescription()]) ?>
                                                            </div>

                                                            <!--Статусы mvps-->
                                                            <?php if ($mvp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()], ['title'=> 'Посмотреть подтверждение MVP']) ?>
                                                                </div>

                                                            <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()], ['title'=> 'Посмотреть подтверждение MVP']) ?>
                                                                </div>

                                                            <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                                                <div class="text-center regular_column">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/confirm-mvp/create', 'id' => $mvp->getId()], ['title'=> 'Подтвердить MVP']) ?>
                                                                </div>

                                                            <?php endif; ?>

                                                            <!--Даты создания mvps-->
                                                            <div class="text-center regular_column"><?= date('d.m.y',$mvp->getCreatedAt()) ?></div>

                                                            <!--Даты подтверждения mvps-->
                                                            <div class="text-center regular_column">
                                                                <?php if ($mvp->getTimeConfirm() !== null) {
                                                                    echo date('d.m.y', $mvp->getTimeConfirm());
                                                                } ?>
                                                            </div>

                                                            <!--Бизнес модели-->
                                                            <?php if (!$mvp->businessModel && $mvp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                                                <div class="text-center column_business_model">
                                                                    <?= Html::a(Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]),
                                                                        ['/business-model/index', 'id' => $mvp->confirm->getId()], ['title'=> 'Создать бизнес-модель']) ?>
                                                                </div>

                                                            <?php elseif ($mvp->businessModel) : ?>

                                                                <div class="text-center column_business_model">
                                                                    <?= Html::a(Html::img('@web/images/icons/icon-pdf.png', ['style' => ['width' => '20px']]),
                                                                        ['/business-model/index', 'id' => $mvp->confirm->getId()], ['title'=> 'Посмотреть бизнес-модель']) ?>
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
