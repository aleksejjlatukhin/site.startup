<?php

use app\models\ConfirmProblem;
use app\models\EnableExpertise;
use app\models\ProjectCommunications;
use app\models\RespondsProblem;
use app\models\StageExpertise;
use app\models\StatusConfirmHypothesis;
use yii\helpers\Html;
use app\models\User;
use yii\widgets\LinkPager;

/**
 * @var RespondsProblem[] $responds
 * @var ConfirmProblem $confirm
 * @var int $pagesResponds
 */

?>


<div class="block_all_responds">

    <?php foreach ($responds as $respond): ?>

        <div class="row container-one_respond" style="margin: 3px 0; padding: 0;">

            <div class="col-md-3" style="display:flex; align-items: center;">

                <div style="padding-right: 10px; padding-bottom: 3px;">

                    <?php
                    if ($respond->interview) {
                        if ($respond->interview->getStatus() === 1) {
                            echo  Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]);
                        }
                        elseif ($respond->interview->getStatus() === 0) {
                            echo  Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);
                        }
                    }
                    else {
                        echo  Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);
                    }
                    ?>

                </div>

                <div class="" style="overflow: hidden; max-height: 60px; padding: 5px 0;">

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $confirm->hypothesis->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                        <?=  Html::a($respond->getName(), ['#'], [
                            'id' => "respond_name-" . $respond->getId(),
                            'class' => 'container-respond_name_link showRespondUpdateForm',
                            'title' => 'Редактировать данные респондента',
                        ]) ?>

                    <?php else : ?>

                        <?=  Html::a($respond->getName(), ['#'], [
                            'id' => "respond_name-" . $respond->getId(),
                            'class' => 'container-respond_name_link showRespondUpdateForm',
                            'title' => 'Данные респондента',
                        ]) ?>

                    <?php endif; ?>

                </div>

            </div>

            <div class="col-md-3" style="font-size: 14px; padding: 0 10px 0 0; overflow: hidden; max-height: inherit;" title="<?= $respond->getInfoRespond() ?>">
                <?= $respond->getInfoRespond() ?>
            </div>

            <div class="col-md-3" style="font-size: 14px; padding: 0 5px 0 0; overflow: hidden; max-height: inherit;" title="<?= $respond->getPlaceInterview() ?>">
                <?= $respond->getPlaceInterview() ?>
            </div>

            <div class="col-md-1">

                <?php
                if ($respond->getDatePlan()){

                    echo '<div class="text-center" style="padding: 0 5px; margin-left: -10px;">' . date("d.m.y", $respond->getDatePlan()) . '</div>';
                }
                ?>

            </div>

            <div class="col-md-1">

                <?php
                if ($respond->interview){

                    $date_fact = date("d.m.y", $respond->interview->getUpdatedAt());
                    echo '<div class="text-center" style="margin-left: -10px;">' . Html::encode($date_fact) . '</div>';

                }elseif (!empty($respond->getInfoRespond()) && !empty($respond->getPlaceInterview()) && $respond->getDatePlan()
                    && User::isUserSimple(Yii::$app->user->identity['username'])){

                    echo '<div class="text-center" style="margin-left: -10px;">' . Html::a(
                            Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]),
                            ['/responds/data-availability', 'stage' => $confirm->getStage(), 'id' => $confirm->getId()], [
                            'id' => 'respond_descInterview_form-' . $respond->getId(),
                            'class' => 'showDescInterviewCreateForm',
                            'title' => 'Добавить интервью'
                        ]) .
                        '</div>';
                } ?>

            </div>

            <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $confirm->hypothesis->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                <div class="col-md-1" style="text-align: right;">

                    <?php
                    if ($respond->interview) {

                        echo Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]), ['#'], [
                            'id' => 'descInterview_form-' . $respond->interview->getId(),
                            'class' => 'showDescInterviewUpdateForm',
                            'title' => 'Редактировать результаты интервью',
                        ]);
                    }

                    echo Html::a(Html::img('/images/icons/icon_delete.png',
                        ['style' => ['width' => '24px']]), ['#'], [
                        'id' => 'link_respond_delete-' . $respond->getId(),
                        'class' => 'showDeleteRespondModal',
                        'title' => 'Удалить респондента',
                    ]);
                    ?>

                </div>

            <?php else : ?>

                <div class="col-md-1" style="text-align: center;">

                    <?php
                    if ($respond->interview) {

                        echo Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px']]), ['#'], [
                            'id' => 'descInterview_form-' . $respond->interview->getId(),
                            'class' => 'showDescInterviewUpdateForm',
                            'title' => 'Результаты интервью',
                        ]);
                    }
                    ?>

                </div>

            <?php endif; ?>

        </div>

    <?php  endforeach;?>

    <!--Pagination-->
    <div class="pagination-responds-confirm">
        <?= LinkPager::widget([
            'pagination' => $pagesResponds,
            'activePageCssClass' => 'pagination_active_page',
            'options' => ['class' => 'responds-confirm-pagin-list'],
        ]) ?>
    </div>

</div>


<div class="row container-fluid" style="position: absolute; bottom: 0; width: 100%;">

    <div class="col-md-12" style="color: #4F4F4F; font-size: 16px; display: flex; justify-content: space-between; padding: 10px 20px; border-radius: 12px; border: 2px solid #707F99; align-items: center; margin-top: 30px;">

        <div class="" style="padding: 0;">
            Необходимо респондентов: <?= $confirm->getCountPositive() ?>
        </div>

        <div class="" style="padding: 0;">
            Внесено респондентов: <?= $confirm->getCountRespondsOfModel() ?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) ?>
            Подтверждают проблему: <?= $confirm->getCountConfirmMembers() ?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) ?>
            Не подтверждают проблему: <?= ($confirm->getCountDescInterviewsOfModel() - $confirm->getCountConfirmMembers()) ?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]) ?>
            Не опрошены: <?= ($confirm->getCountRespond() - $confirm->getCountDescInterviewsOfModel()) ?>
        </div>

        <div style="display:flex; align-items:center; padding: 0;">

            <?php if ($confirm->getEnableExpertise() === EnableExpertise::ON) : ?>

                <?php if (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                    <?php if (ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $confirm->hypothesis->getProjectId())) : ?>

                        <?= Html::a('Экспертиза',['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::CONFIRM_PROBLEM], 'stageId' => $confirm->getId()], [
                            'class' => 'link-get-list-expertise btn btn-lg btn-default',
                            'title' => 'Экспертиза',
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#707F99',
                                'color' => '#FFFFFF',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                                'margin-right' => '10px',
                            ],
                        ]) ?>

                    <?php endif; ?>

                <?php else : ?>

                    <?= Html::a('Экспертиза',['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::CONFIRM_PROBLEM], 'stageId' => $confirm->getId()], [
                        'class' => 'link-get-list-expertise btn btn-lg btn-default',
                        'title' => 'Смотреть экспертизу',
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#707F99',
                            'color' => '#FFFFFF',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-right' => '10px',
                        ],
                    ]) ?>

                <?php endif; ?>

            <?php endif; ?>

            <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $confirm->hypothesis->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                <?php if ($confirm->getButtonMovingNextStage()) : ?>

                    <?= Html::a( 'Далее', ['/confirm-problem/moving-next-stage', 'id' => $confirm->getId()],[
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#52BE7F',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                        'class' => 'btn btn-lg btn-success',
                        'id' => 'button_MovingNextStage',
                    ]) ?>

                <?php else : ?>

                    <?php if (($confirm->getCountRespond() - $confirm->getCountDescInterviewsOfModel()) === 0) : ?>

                        <?= Html::a( 'Далее', ['/confirm-problem/moving-next-stage', 'id' => $confirm->getId()],[
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#eb5757',
                                'color' => '#FFFFFF',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                            'class' => 'btn btn-lg btn-default',
                            'id' => 'button_MovingNextStage',
                        ]) ?>

                    <?php else : ?>

                        <?= Html::a( 'Далее', ['/confirm-problem/moving-next-stage', 'id' => $confirm->getId()],[
                            'style' => [
                                'display' => 'flex',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'background' => '#E0E0E0',
                                'color' => '#FFFFFF',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '24px',
                                'border-radius' => '8px',
                            ],
                            'class' => 'btn btn-lg btn-default',
                            'id' => 'button_MovingNextStage',
                        ]) ?>

                    <?php endif; ?>

                <?php endif; ?>

            <?php else : ?>

                <?php if ($confirm->hypothesis->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) : ?>

                    <?= Html::a( 'Далее', ['/gcps/index', 'id' => $confirm->getId()],[
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#52BE7F',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                        'class' => 'btn btn-lg btn-success',
                    ]) ?>

                <?php else : ?>

                    <?= Html::a( 'Далее', ['#'],[
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#E0E0E0',
                            'color' => '#FFFFFF',
                            'width' => '140px',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                        ],
                        'class' => 'btn btn-lg btn-default',
                        'onclick' => 'return false',
                    ]) ?>

                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</div>
