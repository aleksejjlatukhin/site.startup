<?php

use yii\helpers\Html;
use app\models\CommunicationPatterns;

?>

<?php if ($patterns) : ?>

    <!--Заголовки для созданных шаблонов-->
    <div class="row block-patterns">
        <div class="col-xs-9 col-lg-10">Описание шаблона коммуникации</div>
        <div class="col-xs-3 col-lg-2 text-center">Действия</div>
    </div>

    <!--Созданные шаблоны-->
    <?php foreach ($patterns as $pattern) : ?>
        <div class="row style-row-pattern row-pattern-<?= $pattern->id; ?>">

            <div class="col-xs-9 col-lg-10"><?= $pattern->description; ?></div>

            <div class="col-xs-3 col-lg-2 text-center">

                <?php if ($pattern->is_active == CommunicationPatterns::ACTIVE) : ?>

                    <?= Html::a(Html::img('/images/icons/icon_circle_active.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                        ['/client/communications/deactivate-pattern', 'id' => $pattern->id],
                        [
                            'class' => 'deactivate-communication-pattern',
                            'style' => ['margin-left' => '30px'],
                            'title' => 'Отменить'
                        ]
                    ); ?>

                <?php else : ?>

                    <?= Html::a(Html::img('/images/icons/icon_circle_default.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),
                        [
                            '/client/communications/activate-pattern', 'id' => $pattern->id,
                            'communicationType' => $communicationType
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
                        '/client/communications/get-form-update-communication-pattern', 'id' => $pattern->id,
                        'communicationType' => $communicationType
                    ],
                    [
                        'class' => 'update-communication-pattern',
                        'title' => 'Редактировать'
                    ]
                ); ?>

                <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),
                    ['/client/communications/delete-pattern', 'id' => $pattern->id],
                    [
                        'class' => 'delete-communication-pattern',
                        'title' => 'Удалить'
                    ]
                ); ?>

            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>