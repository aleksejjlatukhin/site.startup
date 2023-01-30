<?php

use app\models\WishList;
use yii\helpers\Html;

/**
 * @var WishList[] $models
 */

?>

<?php if ($models): ?>

    <?php foreach ($models as $model): ?>

        <div class="parent-wish_list_new">
            <div class="row one-wish_list_new">

                <div class="col-md-2 pl-20"><?= $model->getSizeName() ?></div>
                <div class="col-md-2"><?= $model->location->getName() ?></div>
                <div class="col-md-2"><?= $model->getTypeCompanyName() ?></div>
                <div class="col-md-2"><?= $model->getTypeProductionName() ?></div>
                <div class="col-md-2"><?= date('d.m.Y', $model->getCreatedAt()) ?></div>
                <div class="col-md-2">
                    <div class="row" style="display:flex; align-items: center;">
                        <div class="col-md-6">
                            <?php if ($model->isReadyForCompletion()): ?>
                                <?= Html::a('Завершить', ['/client/wish-list/complete', 'id' => $model->getId()], [
                                    'class' => 'btn btn-default pull-right wish-list-complete',
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
                                ])?>
                            <?php else: ?>
                                <?= Html::button('Завершить', [
                                    'disabled' => true,
                                    'class' => 'btn btn-default pull-right',
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
                                ])?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?= Html::a(Html::img('/images/icons/icon_delete.png', ['style' => ['width' => '24px']]),['/client/wish-list/delete', 'id' => $model->getId()], [
                                'class' => 'pull-right delete-wish-list',
                                'title' => 'Удалить',
                            ]) ?>
                            <?= Html::a(Html::img('/images/icons/update_warning_vector.png', ['style' => ['width' => '24px', 'margin-right' => '20px']]),['/client/wish-list/update', 'id' => $model->getId()], [
                                'class' => 'pull-right',
                                'title' => 'Редактировать',
                            ]) ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row one-wish_list_ready-data">
                <div class="col-md-12">

                    <?php if ($model->requirements): ?>

                        <div class="requirementsTable">
                            <div class="row headers">
                                <div class="col-md-6">Описание запроса</div>
                                <div class="col-md-6">Причины</div>
                            </div>

                            <?php foreach ($model->requirements as $key => $requirement): ?>

                                <div class="row requirementsDataTable">
                                    <div class="col-md-6">
                                        <?= '<span class="bolder">' . ($key+1) . '. </span>' . $requirement->getRequirement() ?>
                                    </div>

                                    <div class="col-md-6">
                                        <?php foreach ($requirement->reasons as $reason): ?>
                                            <div class="mb-10"> - <?= $reason->getReason() ?></div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>

                            <?php endforeach; ?>
                        </div>

                    <?php else: ?>

                        <div class="bolder text-center">Запросы не добавлены</div>

                    <?php endif; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="row mt-15">
        <div class="col-md-12 text-center bolder">Ничего не найдено</div>
    </div>

<?php endif; ?>
