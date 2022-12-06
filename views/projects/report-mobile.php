<?php

use app\models\Segments;
use app\models\StatusConfirmHypothesis;
use yii\helpers\Html;

/**
 * @var Segments[] $segments
 */

$this->title = 'Протокол проекта';

?>

<div class="row">
    <div class="col-xs-12 header-title-mobile"><?= $this->title ?></div>
</div>

<?php if ($segments): ?>

    <div class="row container-fluid report-mobile">

        <?php foreach ($segments as $segment): ?>

            <div class="col-xs-12 one-report-mobile">

                <?php if($segment->confirm) : ?>

                    <?php if ($segment->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                        <div class="row report-mobile-header-segment report-mobile-bg-green">
                            <div class="col-xs-8">
                                <?= Html::a($segment->propertyContainer->getProperty('title')
                                    . ' - ' . $segment->getName(), ['/confirm-segment/view', 'id' => $segment->confirm->getId()],
                                    ['class' => 'link-stage-report-mobile white']) ?>
                            </div>
                            <div class="col-xs-4">
                                Создан <?= date('d.m.Y', $segment->getCreatedAt()) ?>
                            </div>
                        </div>

                    <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                        <div class="row report-mobile-header-segment report-mobile-bg-grey">
                            <div class="col-xs-8">
                                <?= Html::a($segment->propertyContainer->getProperty('title')
                                    . ' - ' . $segment->getName(), ['/confirm-segment/view', 'id' => $segment->confirm->getId()],
                                    ['class' => 'link-stage-report-mobile white']) ?>
                            </div>
                            <div class="col-xs-4">
                                Создан <?= date('d.m.Y', $segment->getCreatedAt()) ?>
                            </div>
                        </div>

                    <?php elseif ($segment->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                        <div class="row report-mobile-header-segment report-mobile-bg-red">
                            <div class="col-xs-8">
                                <?= Html::a($segment->propertyContainer->getProperty('title')
                                    . ' - ' . $segment->getName(), ['/confirm-segment/view', 'id' => $segment->confirm->getId()],
                                    ['class' => 'link-stage-report-mobile white']) ?>
                            </div>
                            <div class="col-xs-4">
                                Создан <?= date('d.m.Y', $segment->getCreatedAt()) ?>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="row">
                        <div class="col-xs-12 report-mobile-header-columns">
                            <div>План</div>
                            <div>Надо</div>
                            <div>Положит.</div>
                            <div>Отрицат.</div>
                            <div>Не опрошены</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 report-mobile-value-columns">
                            <div><?= $segment->confirm->getCountRespond() ?></div>
                            <div><?= $segment->confirm->getCountPositive() ?></div>
                            <div><?= $segment->confirm->getCountConfirmMembers() ?></div>
                            <div><?= ($segment->confirm->getCountDescInterviewsOfModel() - $segment->confirm->getCountConfirmMembers()) ?></div>
                            <div><?= ($segment->confirm->getCountRespond() - $segment->confirm->getCountDescInterviewsOfModel()) ?></div>
                        </div>
                    </div>

                <!--Если у сегмента не существует подтверждения-->
                <?php else : ?>

                    <div class="row report-mobile-header-segment report-mobile-bg-grey">
                        <div class="col-xs-8">
                            <?= Html::a($segment->propertyContainer->getProperty('title')
                                . ' - ' . $segment->getName(), ['/segments/index', 'id' => $segment->getProjectId()],
                                ['class' => 'link-stage-report-mobile white']) ?>
                        </div>
                        <div class="col-xs-4">
                            Создан <?= date('d.m.Y', $segment->getCreatedAt()) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 report-mobile-header-columns">
                            <div>План</div>
                            <div>Надо</div>
                            <div>Положит.</div>
                            <div>Отрицат.</div>
                            <div>Не опрошены</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 report-mobile-value-columns">
                            <div>-</div>
                            <div>-</div>
                            <div>-</div>
                            <div>-</div>
                            <div>-</div>
                        </div>
                    </div>

                <?php endif; ?>

                <!--Строки проблем сегментов-->
                <?php foreach ($segment->problems as $problem) : ?>

                    <!--Если у проблемы существует подтверждение-->
                    <?php if($problem->confirm) : ?>

                        <?php if ($problem->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                            <div class="row report-mobile-header-hypothesis report-mobile-bg-green">
                                <div class="col-xs-12">
                                    <?= Html::a($problem->propertyContainer->getProperty('title')
                                        . ' - ' . $problem->getDescription(), ['/confirm-problem/view', 'id' => $problem->confirm->getId()],
                                        ['class' => 'link-stage-report-mobile white']) ?>
                                </div>
                            </div>

                        <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                            <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                                <div class="col-xs-12">
                                    <?= Html::a($problem->propertyContainer->getProperty('title')
                                        . ' - ' . $problem->getDescription(), ['/confirm-problem/view', 'id' => $problem->confirm->getId()],
                                        ['class' => 'link-stage-report-mobile white']) ?>
                                </div>
                            </div>

                        <?php elseif ($problem->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                            <div class="row report-mobile-header-hypothesis report-mobile-bg-red">
                                <div class="col-xs-12">
                                    <?= Html::a($problem->propertyContainer->getProperty('title')
                                        . ' - ' . $problem->getDescription(), ['/confirm-problem/view', 'id' => $problem->confirm->getId()],
                                        ['class' => 'link-stage-report-mobile white']) ?>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="row">
                            <div class="col-xs-12 report-mobile-header-columns">
                                <div>План</div>
                                <div>Надо</div>
                                <div>Положит.</div>
                                <div>Отрицат.</div>
                                <div>Не опрошены</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 report-mobile-value-columns">
                                <div><?= $problem->confirm->getCountRespond() ?></div>
                                <div><?= $problem->confirm->getCountPositive() ?></div>
                                <div><?= $problem->confirm->getCountConfirmMembers() ?></div>
                                <div><?= ($problem->confirm->getCountDescInterviewsOfModel() - $problem->confirm->getCountConfirmMembers()) ?></div>
                                <div><?= ($problem->confirm->getCountRespond() - $problem->confirm->getCountDescInterviewsOfModel()) ?></div>
                            </div>
                        </div>

                    <!--Если у проблемы не существует подтверждение-->
                    <?php else: ?>

                        <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                            <div class="col-xs-12">
                                <?= Html::a($problem->propertyContainer->getProperty('title')
                                    . ' - ' . $problem->getDescription(), ['/problems/index', 'id' => $problem->getBasicConfirmId()],
                                    ['class' => 'link-stage-report-mobile white']) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 report-mobile-header-columns">
                                <div>План</div>
                                <div>Надо</div>
                                <div>Положит.</div>
                                <div>Отрицат.</div>
                                <div>Не опрошены</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 report-mobile-value-columns">
                                <div>-</div>
                                <div>-</div>
                                <div>-</div>
                                <div>-</div>
                                <div>-</div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <!--Строки ценностных предложений-->
                    <?php foreach ($problem->gcps as $gcp) : ?>

                        <!--Если у ЦП существует подтверждение-->
                        <?php if($gcp->confirm) : ?>

                            <?php if ($gcp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                <div class="row report-mobile-header-hypothesis report-mobile-bg-green">
                                    <div class="col-xs-12">
                                        <?= Html::a($gcp->propertyContainer->getProperty('title')
                                            . ' - ' . $gcp->getDescription(), ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()],
                                            ['class' => 'link-stage-report-mobile white']) ?>
                                    </div>
                                </div>

                            <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                                    <div class="col-xs-12">
                                        <?= Html::a($gcp->propertyContainer->getProperty('title')
                                            . ' - ' . $gcp->getDescription(), ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()],
                                            ['class' => 'link-stage-report-mobile white']) ?>
                                    </div>
                                </div>

                            <?php elseif ($gcp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                <div class="row report-mobile-header-hypothesis report-mobile-bg-red">
                                    <div class="col-xs-12">
                                        <?= Html::a($gcp->propertyContainer->getProperty('title')
                                            . ' - ' . $gcp->getDescription(), ['/confirm-gcp/view', 'id' => $gcp->confirm->getId()],
                                            ['class' => 'link-stage-report-mobile white']) ?>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <div class="row">
                                <div class="col-xs-12 report-mobile-header-columns">
                                    <div>План</div>
                                    <div>Надо</div>
                                    <div>Положит.</div>
                                    <div>Отрицат.</div>
                                    <div>Не опрошены</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 report-mobile-value-columns">
                                    <div><?= $gcp->confirm->getCountRespond() ?></div>
                                    <div><?= $gcp->confirm->getCountPositive() ?></div>
                                    <div><?= $gcp->confirm->getCountConfirmMembers() ?></div>
                                    <div><?= ($gcp->confirm->getCountDescInterviewsOfModel() - $gcp->confirm->getCountConfirmMembers()) ?></div>
                                    <div><?= ($gcp->confirm->getCountRespond() - $gcp->confirm->getCountDescInterviewsOfModel()) ?></div>
                                </div>
                            </div>

                            <!--Если у ЦП не существует подтверждение-->
                        <?php else: ?>

                            <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                                <div class="col-xs-12">
                                    <?= Html::a($gcp->propertyContainer->getProperty('title')
                                        . ' - ' . $gcp->getDescription(), ['/gcps/index', 'id' => $gcp->getBasicConfirmId()],
                                        ['class' => 'link-stage-report-mobile white']) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 report-mobile-header-columns">
                                    <div>План</div>
                                    <div>Надо</div>
                                    <div>Положит.</div>
                                    <div>Отрицат.</div>
                                    <div>Не опрошены</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 report-mobile-value-columns">
                                    <div>-</div>
                                    <div>-</div>
                                    <div>-</div>
                                    <div>-</div>
                                    <div>-</div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <!--Строки mvps-->
                        <?php foreach ($gcp->mvps as $mvp) : ?>

                            <!--Если у MVP существует подтверждение-->
                            <?php if($mvp->confirm) : ?>

                                <?php if ($mvp->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                                    <?php if ($mvp->businessModel): ?>
                                        <div class="row report-mobile-header-mvp">
                                            <div class="col-xs-9 report-mobile-bg-green">
                                                <?= Html::a($mvp->propertyContainer->getProperty('title')
                                                    . ' - ' . $mvp->getDescription(), ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()],
                                                    ['class' => 'link-stage-report-mobile white']) ?>
                                            </div>
                                            <div class="col-xs-3 report-mobile-bg-green">
                                                <?= Html::a('Бизнес-модель', ['/business-model/index', 'id' => $mvp->confirm->getId()],
                                                    ['class' => 'link-stage-report-mobile white']) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row report-mobile-header-mvp">
                                            <div class="col-xs-9 report-mobile-bg-green">
                                                <?= Html::a($mvp->propertyContainer->getProperty('title')
                                                    . ' - ' . $mvp->getDescription(), ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()],
                                                    ['class' => 'link-stage-report-mobile white']) ?>
                                            </div>
                                            <div class="col-xs-3 report-mobile-bg-grey">
                                                <?= Html::a('Бизнес-модель', ['/business-model/index', 'id' => $mvp->confirm->getId()],
                                                    ['class' => 'link-stage-report-mobile white']) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                                    <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                                        <div class="col-xs-12">
                                            <?= Html::a($mvp->propertyContainer->getProperty('title')
                                                . ' - ' . $mvp->getDescription(), ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()],
                                                ['class' => 'link-stage-report-mobile white']) ?>
                                        </div>
                                    </div>

                                <?php elseif ($mvp->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) : ?>

                                    <div class="row report-mobile-header-hypothesis report-mobile-bg-red">
                                        <div class="col-xs-12">
                                            <?= Html::a($mvp->propertyContainer->getProperty('title')
                                                . ' - ' . $mvp->getDescription(), ['/confirm-mvp/view', 'id' => $mvp->confirm->getId()],
                                                ['class' => 'link-stage-report-mobile white']) ?>
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-xs-12 report-mobile-header-columns">
                                        <div>План</div>
                                        <div>Надо</div>
                                        <div>Положит.</div>
                                        <div>Отрицат.</div>
                                        <div>Не опрошены</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 report-mobile-value-columns">
                                        <div><?= $mvp->confirm->getCountRespond() ?></div>
                                        <div><?= $mvp->confirm->getCountPositive() ?></div>
                                        <div><?= $mvp->confirm->getCountConfirmMembers() ?></div>
                                        <div><?= ($mvp->confirm->getCountDescInterviewsOfModel() - $mvp->confirm->getCountConfirmMembers()) ?></div>
                                        <div><?= ($mvp->confirm->getCountRespond() - $mvp->confirm->getCountDescInterviewsOfModel()) ?></div>
                                    </div>
                                </div>

                            <!--Если у MVP не существует подтверждение-->
                            <?php else: ?>

                                <div class="row report-mobile-header-hypothesis report-mobile-bg-grey">
                                    <div class="col-xs-12">
                                        <?= Html::a($mvp->propertyContainer->getProperty('title')
                                            . ' - ' . $mvp->getDescription(), ['/mvps/index', 'id' => $mvp->getBasicConfirmId()],
                                            ['class' => 'link-stage-report-mobile white']) ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 report-mobile-header-columns">
                                        <div>План</div>
                                        <div>Надо</div>
                                        <div>Положит.</div>
                                        <div>Отрицат.</div>
                                        <div>Не опрошены</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 report-mobile-value-columns">
                                        <div>-</div>
                                        <div>-</div>
                                        <div>-</div>
                                        <div>-</div>
                                        <div>-</div>
                                    </div>
                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    <?php endforeach; ?>

                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>
    </div>

<?php else: ?>
    <h3 class="text-center">Пока нет сегментов...</h3>
<?php endif; ?>

<div class="row">
    <div class="col-md-12" style="display:flex;justify-content: center;">
        <?= Html::button('Закрыть', [
            'onclick' => 'javascript:history.back()',
            'class' => 'btn button-close-result-mobile'
        ]) ?>
    </div>
</div>
