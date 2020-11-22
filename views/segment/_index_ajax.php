<?php

use yii\helpers\Html;
use app\models\User;
use app\models\Segment;

?>


<!--Данные для списка сегментов-->
<?php foreach ($models as $model) : ?>


    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>" style="margin: 3px 0;">

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


        <div class="col-md-2">

            <?php

            $field_of_activity = $model->field_of_activity;

            if (mb_strlen($field_of_activity) > 50) {
                $field_of_activity = mb_substr($field_of_activity, 0, 50);
                $field_of_activity = $field_of_activity . ' ...';
            }

            echo '<div title="' . $model->field_of_activity . '">' . $field_of_activity . '</div>';

            ?>

        </div>

        <div class="col-md-2">

            <?php

            $sort_of_activity = $model->sort_of_activity;

            if (mb_strlen($sort_of_activity) > 50) {
                $sort_of_activity = mb_substr($sort_of_activity, 0, 50);
                $sort_of_activity = $sort_of_activity . ' ...';
            }

            echo '<div title="' . $model->sort_of_activity . '">' . $sort_of_activity . '</div>';

            ?>

        </div>

        <div class="col-md-2">

            <?php

            $specialization_of_activity = $model->specialization_of_activity;

            if (mb_strlen($specialization_of_activity) > 50) {
                $specialization_of_activity = mb_substr($specialization_of_activity, 0, 50);
                $specialization_of_activity = $specialization_of_activity . ' ...';
            }

            echo '<div title="' . $model->specialization_of_activity . '">' . $specialization_of_activity . '</div>';

            ?>

        </div>


        <div class="col-md-1">

            <?php

            echo '<div class="text-right">' . number_format($model->market_volume, 0, '', ' ') . '</div>';

            ?>

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
                        ]);
                        ?>

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

                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/segment/delete', 'id' => $model->id], [
                        'class' => 'delete_hypothesis',
                        'title' => 'Удалить',
                    ]); ?>

                </div>

            </div>

        </div>

    </div>

<?php endforeach;?>
