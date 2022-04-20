<?php

use app\models\ProjectCommunications;
use yii\helpers\Html;
use app\models\User;
use app\models\EnableExpertise;
use app\models\StageExpertise;

?>


<!--Данные для списка MVP -->
<?php foreach ($models as $model) : ?>

    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

        <div class="col-lg-1">
            <div class="row">

                <div class="col-lg-4" style="padding: 0;">

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

                <div class="col-lg-8 hypothesis_title" style="padding: 0 0 0 5px;">

                    <?= $model->title; ?>

                </div>
            </div>
        </div>

        <div class="col-lg-6 text_description_problem" title="<?= $model->description; ?>">

            <?= $model->description; ?>

        </div>

        <div class="col-lg-1 text-center">

            <?= date("d.m.y", $model->created_at); ?>

        </div>

        <div class="col-lg-1 text-center">

            <?php if ($model->time_confirm) : ?>
                <?= date("d.m.y", $model->time_confirm); ?>
            <?php endif; ?>

        </div>

        <div class="col-lg-3">

            <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                <div style="margin-right: 25px;">

                    <?php if ($model->confirm) : ?>

                        <?= Html::a('Далее', ['/confirm-mvp/view', 'id' => $model->confirm->id], [
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

                            <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

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
                                ]); ?>

                            <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                                <?= Html::a('Подтвердить', ['/confirm-mvp/create', 'id' => $model->id], [
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

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/mvps/enable-expertise', 'id' => $model->id], [
                                'class' => 'link-enable-expertise',
                                'title' => 'Разрешить экспертизу',
                            ]); ?>

                            <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/mvps/get-hypothesis-to-update', 'id' => $model->id], [
                                'class' => 'update-hypothesis',
                                'title' => 'Редактировать',
                            ]); ?>

                            <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/mvps/delete', 'id' => $model->id], [
                                'class' => 'delete_hypothesis',
                                'title' => 'Удалить',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '95px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::MVP], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'title' => 'Смотреть экспертизу',
                            ]); ?>

                        <?php endif; ?>

                    <?php elseif (User::isUserExpert(Yii::$app->user->identity['username'])) : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'no-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не разрешена',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::MVP], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза',
                            ]); ?>

                        <?php endif; ?>

                    <?php else : ?>

                        <?php if ($model->getEnableExpertise() == EnableExpertise::OFF) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-danger.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'class' => 'no-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не разрешена',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON  && ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->projectId)) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['/expertise/get-list', 'stage' => StageExpertise::getList()[StageExpertise::MVP], 'stageId' => $model->id], [
                                'class' => 'link-get-list-expertise',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза',
                            ]); ?>

                        <?php elseif ($model->getEnableExpertise() == EnableExpertise::ON  && !ProjectCommunications::checkOfAccessToCarryingExpertise(Yii::$app->user->getId(), $model->projectId)) : ?>

                            <?= Html::a(Html::img('/images/icons/icon-enable-expertise-success.png', ['style' => ['width' => '35px', 'margin-right' => '20px']]),['#'], [
                                'onclick' => 'return false;',
                                'style' => ['margin-left' => '20px'],
                                'title' => 'Экспертиза не доступна',
                            ]); ?>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>
