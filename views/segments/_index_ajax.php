<?php

use app\models\ProjectCommunications;
use app\models\StatusConfirmHypothesis;
use yii\helpers\Html;
use app\models\User;
use app\models\Segments;
use app\models\EnableExpertise;
use app\models\StageExpertise;

/**
 * @var Segments[] $models
 */

?>

<!--Данные для списка сегментов-->
<?php foreach ($models as $model) : ?>

    <div class="row container-one_hypothesis row_hypothesis-<?= $model->getId() ?>">

        <div class="col-lg-3" style="padding-left: 5px; padding-right: 5px;">

            <div class="row" style="display:flex; align-items: center;">

                <div class="col-lg-1" style="padding-bottom: 3px;">

                    <?php
                    if ($model->getExistConfirm() === StatusConfirmHypothesis::COMPLETED) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                    }elseif (!$model->confirm && $model->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                    }elseif ($model->confirm && $model->getExistConfirm() === StatusConfirmHypothesis::MISSING_OR_INCOMPLETE) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                    }elseif ($model->getExistConfirm() === StatusConfirmHypothesis::NOT_COMPLETED) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                    }
                    ?>

                </div>

                <div class="col-lg-11">

                    <div class="hypothesis_title" style="padding-left: 15px;">
                        <?= $model->getName() ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-1 text_description_segment text-center" style="padding-left: 25px;">
            <?php
            if ($model->getTypeOfInteractionBetweenSubjects() === Segments::TYPE_B2C) {
                echo '<div class="">B2C</div>';
            }
            elseif ($model->getTypeOfInteractionBetweenSubjects() === Segments::TYPE_B2B) {
                echo '<div class="">B2B</div>';
            }
            ?>
        </div>

        <div class="col-lg-2 text_description_segment text-center" title="<?= $model->getFieldOfActivity() ?>">
            <?= $model->getFieldOfActivity() ?>
        </div>

        <div class="col-lg-2 text_description_segment text-center" title="<?= $model->getSortOfActivity() ?>">
            <?= $model->getSortOfActivity() ?>
        </div>

        <div class="col-lg-1 text-center">
            <?= number_format($model->getMarketVolume(), 0, '', ' ') ?>
        </div>

        <div class="col-lg-3">
            <div class="row pull-right" style="display:flex; align-items: center; padding-right: 10px;">
                <div style="margin-right: 25px;">

                    <?php if ($model->confirm) : ?>

                        <?= Html::a('Далее', ['/confirm-segment/view', 'id' => $model->confirm->getId()], [
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
                        ]) ?>

                    <?php else : ?>

                        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                            <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                                <?= Html::a('Подтвердить', ['#'], [
                                    'disabled' => true,
                                    'onclick' => 'return false;',
                                    'title' => 'Необходимо разрешить экспертизу',
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
                                ]) ?>

                            <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON) : ?>

                                <?= Html::a('Подтвердить', ['/confirm-segment/create', 'id' => $model->getId()], [
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
                                ]) ?>

                            <?php endif; ?>

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
                            ]) ?>

                        <?php endif; ?>
                    <?php endif; ?>

                </div>
                <div>

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/segments/enable-expertise', 'id' => $model->getId()], [
                                'class' => 'link-enable-expertise',
                                'title' => 'Разрешить экспертизу',
                            ]) ?>

                        <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->getId()], [
                                'class' => 'link-get-list-expertise',
                                'title' => 'Смотреть экспертизу',
                            ]) ?>

                        <?php endif; ?>

                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segments/get-hypothesis-to-update', 'id' => $model->getId()], [
                            'class' => 'update-hypothesis',
                            'title' => 'Редактировать',
                        ]) ?>

                        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segments/delete', 'id' => $model->getId()], [
                            'class' => 'delete_hypothesis',
                            'title' => 'Удалить',
                        ]) ?>

                    <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'no-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не разрешена',
                            ]) ?>

                        <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON && ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->getProjectId())) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->getId()], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза',
                            ]) ?>

                        <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON && !ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->getProjectId())) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не доступна',
                            ]) ?>

                        <?php endif; ?>

                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segments/show-all-information', 'id' => $model->getId()], [
                            'class' => 'openAllInformationSegment', 'title' => 'Смотреть описание сегмента',
                        ]) ?>

                    <?php else : ?>

                        <?php if ($model->getEnableExpertise() === EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'no-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не разрешена',
                            ]) ?>

                        <?php elseif ($model->getEnableExpertise() === EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::SEGMENT], 'stageId' => $model->getId()], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Смотреть экспертизу',
                            ]) ?>

                        <?php endif; ?>

                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segments/show-all-information', 'id' => $model->getId()], [
                            'class' => 'openAllInformationSegment', 'title' => 'Смотреть описание сегмента',
                        ]) ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php endforeach;?>