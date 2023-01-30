<?php

use app\models\WishList;

/**
 * @var WishList[] $models
 */

?>

<?php if ($models): ?>

    <?php foreach ($models as $model): ?>

    <div class="parent-wish_list_ready">
        <div class="row one-wish_list_ready">
            <div class="col-md-2 pl-20"><?= $model->getSizeName() ?></div>
            <div class="col-md-2"><?= $model->location->getName() ?></div>
            <div class="col-md-2"><?= $model->getTypeCompanyName() ?></div>
            <div class="col-md-2"><?= $model->getTypeProductionName() ?></div>
            <div class="col-md-2"><?= date('d.m.Y', $model->getCompletedAt()) ?></div>
            <div class="col-md-2"><?= $model->client->getName() ?></div>
        </div>

        <div class="row one-wish_list_ready-data">
            <div class="col-md-12">
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
            </div>
        </div>
    </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="row mt-15">
        <div class="col-md-12 text-center bolder">Ничего не найдено</div>
    </div>

<?php endif; ?>