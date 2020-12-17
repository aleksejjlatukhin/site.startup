<?php

use yii\helpers\Html;
use app\models\User;
use app\models\Segment;

?>


<!--Данные для списка сегментов-->
<?php foreach ($models as $model) : ?>


    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>">

        <div class="col-md-3" style="padding-left: 5px; padding-right: 5px;">

            <div class="row" style="display:flex; align-items: center;">

                <div class="col-md-1" style="padding-bottom: 3px;">

                    <?php
                    if ($model->exist_confirm === 1) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                    }elseif ($model->exist_confirm === null && empty($model->interview)) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                    }elseif ($model->exist_confirm === null && !empty($model->interview)) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                    }elseif ($model->exist_confirm === 0) {

                        echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                    }
                    ?>

                </div>

                <div class="col-md-8">

                    <div class="hypothesis_title" style="padding-left: 15px;">
                        <?= $model->name;?>
                    </div>

                </div>

                <div class="col-md-3 text-center">

                    <?php

                    if ($model->type_of_interaction_between_subjects === Segment::TYPE_B2C) {
                        echo '<div class="">B2C</div>';
                    }
                    elseif ($model->type_of_interaction_between_subjects === Segment::TYPE_B2B) {
                        echo '<div class="">B2B</div>';
                    }

                    ?>

                </div>

            </div>

        </div>


        <div class="col-md-2 text_description_segment" title="<?= $model->field_of_activity; ?>">

            <?= $model->field_of_activity; ?>

        </div>

        <div class="col-md-2 text_description_segment" title="<?= $model->sort_of_activity; ?>">

            <?= $model->sort_of_activity; ?>

        </div>

        <div class="col-md-2 text_description_segment" title="<?= $model->specialization_of_activity; ?>">

            <?= $model->specialization_of_activity; ?>

        </div>


        <div class="col-md-1 text-right">

            <?= number_format($model->market_volume, 0, '', ' '); ?>

        </div>


        <div class="col-md-2">

            <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                <div style="margin-right: 25px;">

                    <?php if ($model->interview) : ?>

                        <?= Html::a('Далее', ['/interview/view', 'id' => $model->interview->id], [
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

                            <?= Html::a('Подтвердить', ['/interview/create', 'id' => $model->id], [
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

                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/segment/get-hypothesis-to-update', 'id' => $model->id], [
                            'class' => 'update-hypothesis',
                            'title' => 'Редактировать',
                        ]); ?>

                    <?php else : ?>

                        <?= Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px', 'margin-right' => '20px']]),['/segment/show-all-information', 'id' => $model->id], [
                            'class' => 'openAllInformationSegment', 'title' => 'Смотреть',
                        ]); ?>

                    <?php endif; ?>

                </div>

                <div >

                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segment/delete', 'id' => $model->id], [
                            'class' => 'delete_hypothesis',
                            'title' => 'Удалить',
                        ]); ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

<?php endforeach;?>
