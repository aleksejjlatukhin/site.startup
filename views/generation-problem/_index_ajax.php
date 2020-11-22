<?php

use yii\helpers\Html;
use app\models\User;

?>


<!--Данные для списка проблем-->
<?php foreach ($models as $model) : ?>

    <div class="row container-one_hypothesis row_hypothesis-<?= $model->id;?>" style="margin: 3px 0; padding: 10px;">

        <div class="col-md-1">
            <div class="row">

                <div class="col-md-4" style="padding: 0;">

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

                <div class="col-md-8 hypothesis_title" style="padding: 0 0 0 5px;">

                    <?= $model->title; ?>

                </div>
            </div>
        </div>

        <div class="col-md-7">

            <?php
            $problem_desc = $model->description;
            if (mb_strlen($problem_desc) > 180) {
                $problem_desc = mb_substr($model->description, 0, 180) . '...';
            }
            ?>

            <?= '<div title="'.$model->description.'" style="line-height: 21px;">' . $problem_desc . '</div>'?>

        </div>

        <div class="col-md-1 text-center">

            <?= date("d.m.y", $model->created_at); ?>

        </div>

        <div class="col-md-1 text-center">

            <?php if ($model->time_confirm) : ?>
                <?= date("d.m.y", $model->time_confirm); ?>
            <?php endif; ?>

        </div>


        <div class="col-md-2">

            <div class="row pull-right" style="padding-right: 10px; display:flex; align-items: center;">

                <div style="margin-right: 25px;">

                    <?php if ($model->confirm) : ?>

                        <?= Html::a('Далее', ['/confirm-problem/view', 'id' => $model->confirm->id], [
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

                        <?= Html::a('Подтвердить', ['/confirm-problem/create', 'id' => $model->id], [
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

                        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/generation-problem/get-hypothesis-to-update', 'id' => $model->id], [
                            'class' => 'update-hypothesis',
                            'title' => 'Редактировать',
                        ]); ?>

                    <?php endif; ?>

                </div>

                <div >

                    <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/generation-problem/delete', 'id' => $model->id], [
                        'class' => 'delete_hypothesis',
                        'title' => 'Удалить',
                    ]); ?>

                </div>

            </div>

        </div>


    </div>

<?php endforeach; ?>