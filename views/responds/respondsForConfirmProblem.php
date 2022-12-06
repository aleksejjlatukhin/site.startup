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
 * @var bool $isMobile
 */

?>


<div class="block_all_responds">

    <?php foreach ($responds as $respond): ?>

        <?php if (!$isMobile): ?>

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

        <?php else: ?>

            <div class="hypothesis_table_mobile" style="margin-bottom: 5px;">
                <div class="row container-one_hypothesis_mobile row_hypothesis-<?= $respond->getId() ?>">

                    <div class="col-xs-12">
                        <div class="hypothesis_title_mobile">
                            <?= $respond->getName() ?>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <span class="header_table_hypothesis_mobile">Статус</span>
                        <span class="text_14_table_hypothesis">
                            <?php
                            if ($respond->interview) {
                                if ($respond->interview->getStatus() === 1) {
                                    echo 'подтверждает проблему';
                                }
                                elseif ($respond->interview->getStatus() === 0) {
                                    echo 'не подтверждает проблему';
                                }
                            }
                            else {
                                echo 'ожидает проведения интервью';
                            }
                            ?>
                        </span>
                    </div>

                    <?php if ($respond->getEmail()): ?>
                        <div class="col-xs-12">
                            <span class="header_table_hypothesis_mobile">Email</span>
                            <span class="text_14_table_hypothesis">
                                <?= $respond->getEmail() ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if ($respond->getInfoRespond()): ?>
                        <div class="col-xs-12">
                            <div class="header_table_hypothesis_mobile">Данные респондента (кто, откуда, чем занят)</div>
                            <div class="text_14_table_hypothesis">
                                <?= $respond->getInfoRespond() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($respond->getPlaceInterview()): ?>
                        <div class="col-xs-12">
                            <div class="header_table_hypothesis_mobile">Место проведения интервью</div>
                            <div class="text_14_table_hypothesis">
                                <?= $respond->getPlaceInterview() ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($respond->getDatePlan()): ?>
                        <div class="col-xs-6">
                            <div class="header_table_hypothesis_mobile">Плановая дата</div>
                            <div class="text_14_table_hypothesis">
                                <?= date('d.m.Y', $respond->getDatePlan()) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($respond->interview): ?>
                        <div class="col-xs-6">
                            <div class="header_table_hypothesis_mobile">Фактическая дата</div>
                            <div class="text_14_table_hypothesis">
                                <?= date('d.m.Y', $respond->interview->getUpdatedAt()) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-xs-6"></div>
                    <?php endif; ?>

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) && $confirm->hypothesis->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) : ?>

                        <div class="hypothesis_buttons_mobile">

                            <?php if ($respond->interview): ?>

                                <?= Html::a('Редактировать интервью', ['#'], [
                                    'id' => 'descInterview_form-' . $respond->interview->getId(),
                                    'class' => 'btn btn-default showDescInterviewUpdateForm',
                                    'style' => [
                                        'display' => 'flex',
                                        'width' => '96%',
                                        'height' => '36px',
                                        'background' => '#52BE7F',
                                        'color' => '#FFFFFF',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'border-radius' => '0',
                                        'border' => '1px solid #ffffff',
                                        'font-size' => '18px',
                                        'margin' => '10px 2% 0% 2%',
                                    ]
                                ]) ?>

                            <?php else: ?>

                                <?= Html::a('Добавить интервью', ['/responds/data-availability', 'stage' => $confirm->getStage(), 'id' => $confirm->getId()], [
                                    'id' => 'respond_descInterview_form-' . $respond->getId(),
                                    'class' => 'btn btn-default showDescInterviewCreateForm',
                                    'style' => [
                                        'display' => 'flex',
                                        'width' => '96%',
                                        'height' => '36px',
                                        'background' => '#52BE7F',
                                        'color' => '#FFFFFF',
                                        'align-items' => 'center',
                                        'justify-content' => 'center',
                                        'border-radius' => '0',
                                        'border' => '1px solid #ffffff',
                                        'font-size' => '18px',
                                        'margin' => '10px 2% 0% 2%',
                                    ]
                                ])?>

                            <?php endif; ?>

                        </div>

                        <div class="hypothesis_buttons_mobile">

                            <?= Html::a('Редактировать', ['#'], [
                                'id' => "respond_name-" . $respond->getId(),
                                'class' => 'btn btn-default showRespondUpdateForm',
                                'style' => [
                                    'display' => 'flex',
                                    'width' => '47%',
                                    'height' => '36px',
                                    'background' => '#7F9FC5',
                                    'color' => '#FFFFFF',
                                    'align-items' => 'center',
                                    'justify-content' => 'center',
                                    'border-radius' => '0',
                                    'border' => '1px solid #ffffff',
                                    'font-size' => '18px',
                                    'margin' => '10px 1% 0% 2%',
                                ],
                            ]) ?>

                            <?= Html::a('Удалить респондента', ['#'], [
                                'id' => 'link_respond_delete-' . $respond->getId(),
                                'class' => 'btn btn-default showDeleteRespondModal',
                                'style' => [
                                    'display' => 'flex',
                                    'width' => '47%',
                                    'height' => '36px',
                                    'background' => '#F5A4A4',
                                    'color' => '#FFFFFF',
                                    'align-items' => 'center',
                                    'justify-content' => 'center',
                                    'border-radius' => '0',
                                    'border' => '1px solid #ffffff',
                                    'font-size' => '18px',
                                    'margin' => '10px 2% 0% 1%',
                                ],
                            ]) ?>

                        </div>

                    <?php else: ?>

                        <div class="hypothesis_buttons_mobile">

                            <?= Html::a('Смотреть данные интервью', ['#'], [
                                'id' => 'descInterview_form-' . $respond->interview->getId(),
                                'class' => 'btn btn-default showDescInterviewUpdateForm',
                                'style' => [
                                    'display' => 'flex',
                                    'width' => '96%',
                                    'height' => '36px',
                                    'background' => '#52BE7F',
                                    'color' => '#FFFFFF',
                                    'align-items' => 'center',
                                    'justify-content' => 'center',
                                    'border-radius' => '0',
                                    'border' => '1px solid #ffffff',
                                    'font-size' => '18px',
                                    'margin' => '10px 2% 0% 2%',
                                ]
                            ]) ?>

                        </div>

                    <?php endif; ?>

                </div>
            </div>

        <?php endif; ?>

    <?php  endforeach;?>

</div>


<div class="row container-fluid confirm-view-bottom-report-desktop" style="position: absolute; bottom: 0; width: 100%;">

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


<div class="confirm-view-bottom-report-mobile">
    <div class="row container-fluid " style="color: #4F4F4F; font-size: 14px; font-weight: 700; padding: 10px 20px; border-radius: 12px; border: 2px solid #707F99; margin: 0;">

        <div class="col-xs-12 text-center" style="font-size: 16px; text-transform: uppercase; margin-bottom: 10px;">Результаты интервью:</div>

        <div class="col-xs-12" style="padding: 0;">
            Необходимо респондентов: <?= $confirm->getCountPositive() ?>
        </div>

        <div class="col-xs-12" style="padding: 0;">
            Внесено респондентов: <?= $confirm->getCountRespondsOfModel() ?>
        </div>

        <div class="col-xs-12" style="padding: 0;">
            Подтверждают проблему: <?= $confirm->getCountConfirmMembers() ?>
        </div>

        <div class="col-xs-12" style="padding: 0;">
            Не подтверждают проблему: <?= ($confirm->getCountDescInterviewsOfModel() - $confirm->getCountConfirmMembers()) ?>
        </div>

        <div class="col-xs-12" style="padding: 0;">
            Не опрошены: <?= ($confirm->getCountRespond() - $confirm->getCountDescInterviewsOfModel()) ?>
        </div>

        <div class="col-xs-12 hypothesis_buttons_mobile" style="padding: 0;">

            <?php if ($confirm->getEnableExpertise() === EnableExpertise::ON) : ?>

                <?php if (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                    <?php if (ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $confirm->hypothesis->getProjectId())) : ?>

                        <?= Html::a('Экспертиза',['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::CONFIRM_PROBLEM], 'stageId' => $confirm->getId()], [
                            'class' => 'link-get-list-expertise btn btn-lg btn-default',
                            'style' => [
                                'display' => 'flex',
                                'width' => '96%',
                                'height' => '36px',
                                'background' => '#52BE7F',
                                'color' => '#FFFFFF',
                                'align-items' => 'center',
                                'justify-content' => 'center',
                                'border-radius' => '0',
                                'border' => '1px solid #ffffff',
                                'font-size' => '18px',
                                'margin-top' => '10px'
                            ]
                        ]) ?>

                    <?php endif; ?>

                <?php else : ?>

                    <?= Html::a('Смотреть экспертизу',['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::CONFIRM_PROBLEM], 'stageId' => $confirm->getId()], [
                        'class' => 'link-get-list-expertise btn btn-lg btn-default',
                        'style' => [
                            'display' => 'flex',
                            'width' => '96%',
                            'height' => '36px',
                            'background' => '#52BE7F',
                            'color' => '#FFFFFF',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'border-radius' => '0',
                            'border' => '1px solid #ffffff',
                            'font-size' => '18px',
                            'margin-top' => '10px'
                        ]
                    ]) ?>

                <?php endif; ?>

            <?php endif; ?>
        </div>

    </div>
</div>
