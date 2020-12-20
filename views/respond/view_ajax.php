<?php

use yii\helpers\Html;
use app\models\User;

?>

<!--Список респондентов-->
<div class="block_all_responds">

    <?php foreach ($responds as $respond): ?>

        <div class="row container-one_respond" style="margin: 3px 0; padding: 0;">

            <div class="col-md-3" style="display:flex; align-items: center;">

                <div style="padding-right: 10px; padding-bottom: 3px;">

                    <?php
                    if ($respond->descInterview->status == 1) {
                        echo  Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]);
                    }
                    elseif ($respond->descInterview->status === null) {
                        echo  Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);
                    }
                    elseif ($respond->descInterview->status == 0) {
                        echo  Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);
                    }
                    else {
                        echo '';
                    }
                    ?>

                </div>

                <div class="" style="overflow: hidden; max-height: 60px; padding: 5px 0;">

                    <?=  Html::a($respond->name, ['#'], [
                        'id' => "respond_name-$respond->id",
                        'class' => 'container-respond_name_link showRespondUpdateForm',
                        'title' => 'Редактировать данные респондента',
                    ]); ?>

                </div>

            </div>

            <div class="col-md-2" style="font-size: 14px; padding: 0 10px 0 0; overflow: hidden; max-height: inherit;" title="<?= $respond->info_respond; ?>">
                <?= $respond->info_respond; ?>
            </div>

            <div class="col-md-2" style="font-size: 14px; padding: 0 5px 0 0; overflow: hidden; max-height: inherit;" title="<?= $respond->place_interview; ?>">
                <?= $respond->place_interview; ?>
            </div>

            <div class="col-md-1">

                <?php
                if (!empty($respond->date_plan)){

                    echo '<div class="text-center" style="padding: 0 5px;">' . date("d.m.y", $respond->date_plan) . '</div>';
                }
                ?>

            </div>

            <div class="col-md-1">

                <?php
                if (!empty($respond->descInterview)){

                    $date_fact = date("d.m.y", $respond->descInterview->updated_at);
                    echo '<div class="text-center">' . Html::encode($date_fact) . '</div>';

                }elseif (!empty($respond->info_respond) && !empty($respond->place_interview) && !empty($respond->date_plan)
                    && empty($respond->descInterview->updated_at) && User::isUserSimple(Yii::$app->user->identity['username'])){

                    echo '<div class="text-center">' . Html::a(
                            Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]),
                            ['/respond/data-availability', 'id' => $model->id], [
                            'id' => 'respond_descInterview_form-' . $respond->id,
                            'class' => 'showDescInterviewCreateForm',
                            'title' => 'Добавить интервью'
                        ]) .
                        '</div>';
                } ?>

            </div>

            <?php if ($respond->descInterview) : ?>
                <div class="col-md-2" style="font-size: 14px; padding: 0; overflow: hidden; max-height: inherit;" title="<?= $respond->descInterview->result; ?>">
                    <?= $respond->descInterview->result; ?>
                </div>
            <?php else : ?>
                <div class="col-md-2"></div>
            <?php endif; ?>

            <div class="col-md-1" style="text-align: right;">

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <?php
                    if ($respond->descInterview) {

                        echo Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]), ['#'], [
                            'id' => 'descInterview_form-' . $respond->descInterview->id,
                            'class' => 'showDescInterviewUpdateForm',
                            'title' => 'Редактировать результаты интервью',
                        ]);
                    }

                    echo Html::a(Html::img('/images/icons/icon_delete.png',
                        ['style' => ['width' => '24px']]), ['#'], [
                        'id' => 'link_respond_delete-' . $respond->id,
                        'class' => 'showDeleteRespondModal',
                        'title' => 'Удалить респондента',
                    ]);
                    ?>

                <? else : ?>

                    <?php
                    if ($respond->descInterview) {

                        echo Html::a(Html::img('/images/icons/icon_view.png', ['style' => ['width' => '28px']]), ['#'], [
                            'id' => 'descInterview_form-' . $respond->descInterview->id,
                            'class' => 'showDescInterviewUpdateForm',
                            'title' => 'Результаты опроса',
                        ]);
                    }
                    ?>

                <? endif; ?>

            </div>

        </div>

    <?php  endforeach;?>

    <!--Pagination-->
    <div class="pagination-responds-confirm">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $pagesResponds,
            'activePageCssClass' => 'pagination_active_page',
            'options' => ['class' => 'responds-confirm-pagin-list'],
        ]); ?>
    </div>

</div>


<div class="row container-fluid" style="position: absolute; bottom: 90px; width: 100%;">

    <div class="col-md-12" style="color: #4F4F4F; font-size: 16px; display: flex; justify-content: space-between; padding: 10px 20px; border-radius: 12px; border: 2px solid #707F99; align-items: center; margin-top: 30px;">

        <div class="" style="padding: 0;">
            Необходимо респондентов: <?= $model->count_positive;?>
        </div>

        <div class="" style="padding: 0;">
            Внесено респондентов: <?= $model->countRespondsOfModel;?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]);?>
            Соответствуют сегменту: <?= $model->countConfirmMembers;?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]);?>
            Не соответствуют сегменту: <?= ($model->countDescInterviewsOfModel - $model->countConfirmMembers);?>
        </div>

        <div class="" style="padding: 0;">
            <?= Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px',]]);?>
            Не опрошены: <?= ($model->count_respond - $model->countDescInterviewsOfModel);?>
        </div>

        <div class="" style="padding: 0;">

            <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                <?php if ($model->buttonMovingNextStage === true) : ?>

                    <?= Html::a( 'Далее', ['/interview/moving-next-stage', 'id' => $model->id],[
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
                    ]);?>

                <?php else : ?>

                    <?= Html::a( 'Далее', ['/interview/moving-next-stage', 'id' => $model->id],[
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
                    ]);?>

                <?php endif; ?>

            <?php else : ?>

                <?php if ($model->segment->exist_confirm == 1) : ?>

                    <?= Html::a( 'Далее', ['/generation-problem/index', 'id' => $model->id],[
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
                    ]);?>

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
                    ]);?>

                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</div>
