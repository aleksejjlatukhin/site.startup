<?php

use app\models\CommunicationTypes;
use app\models\CommunicationPatterns;
use yii\helpers\Html;


?>


<?php if ($pattern->communication_type == CommunicationTypes::MAIN_ADMIN_ASKS_ABOUT_READINESS_CONDUCT_EXPERTISE) : ?>

    <div class="col-xs-6 col-lg-8"><?= $pattern->description; ?></div>
    <div class="col-xs-3 col-lg-2 text-center">
        <?php if (in_array($pattern->project_access_period, [1, 21])) {
            echo $pattern->project_access_period . ' день';
        } elseif (in_array($pattern->project_access_period, [2, 3, 4, 22, 23, 24])) {
            echo $pattern->project_access_period . ' дня';
        } else {
            echo $pattern->project_access_period . ' дней';
        } ?>
    </div>
    <div class="col-xs-3 col-lg-2 text-center">

        <?php if ($pattern->is_active == CommunicationPatterns::ACTIVE) : ?>

            <?= Html::a(Html::img('/images/icons/icon_circle_active.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                ['/admin/communications/deactivate-pattern', 'id' => $pattern->id],
                [
                    'class' => 'deactivate-communication-pattern',
                    'style' => ['margin-left' => '30px'],
                    'title' => 'Отменить'
                ]
            ); ?>

        <?php else : ?>

            <?= Html::a(Html::img('/images/icons/icon_circle_default.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                [
                    '/admin/communications/activate-pattern', 'id' => $pattern->id,
                    'communicationType' => $pattern->communication_type
                ],
                [
                    'class' => 'activate-communication-pattern',
                    'style' => ['margin-left' => '30px'],
                    'title' => 'Применить'
                ]
            ); ?>

        <?php endif; ?>

        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
            [
                '/admin/communications/get-form-update-communication-pattern', 'id' => $pattern->id,
                'communicationType' => $pattern->communication_type
            ],
            [
                'class' => 'update-communication-pattern',
                'title' => 'Редактировать'
            ]
        ); ?>

        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),
            ['/admin/communications/delete-pattern', 'id' => $pattern->id],
            [
                'class' => 'delete-communication-pattern',
                'title' => 'Удалить'
            ]
        ); ?>

    </div>

<?php else : ?>

    <div class="col-xs-9 col-lg-10"><?= $pattern->description; ?></div>

    <div class="col-xs-3 col-lg-2 text-center">

        <?php if ($pattern->is_active == CommunicationPatterns::ACTIVE) : ?>

            <?= Html::a(Html::img('/images/icons/icon_circle_active.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                ['/admin/communications/deactivate-pattern', 'id' => $pattern->id],
                [
                    'class' => 'deactivate-communication-pattern',
                    'style' => ['margin-left' => '30px'],
                    'title' => 'Отменить'
                ]
            ); ?>

        <?php else : ?>

            <?= Html::a(Html::img('/images/icons/icon_circle_default.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                [
                    '/admin/communications/activate-pattern', 'id' => $pattern->id,
                    'communicationType' => $pattern->communication_type
                ],
                [
                    'class' => 'activate-communication-pattern',
                    'style' => ['margin-left' => '30px'],
                    'title' => 'Применить'
                ]
            ); ?>

        <?php endif; ?>

        <?= Html::a(Html::img('/images/icons/icon_update.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
            [
                '/admin/communications/get-form-update-communication-pattern', 'id' => $pattern->id,
                'communicationType' => $pattern->communication_type
            ],
            [
                'class' => 'update-communication-pattern',
                'title' => 'Редактировать'
            ]
        ); ?>

        <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),
            ['/admin/communications/delete-pattern', 'id' => $pattern->id],
            [
                'class' => 'delete-communication-pattern',
                'title' => 'Удалить'
            ]
        ); ?>

    </div>

<?php endif; ?>
