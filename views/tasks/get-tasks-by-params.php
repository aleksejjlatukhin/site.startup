<?php

use app\models\ContractorTasks;

/**
 * @var $tasks ContractorTasks[]
 */

?>

<div class="row container-fluid bolder" style="display: flex; background-color: #E0E0E0; align-items: center; padding-top: 15px; padding-bottom: 15px;">
    <div class="col-md-2">Создано</div>
    <div class="col-md-2">Деятельность</div>
    <div class="col-md-2">Этап проекта</div>
    <div class="col-md-5">Описание</div>
    <div class="col-md-1">Статус</div>
</div>

<?php foreach ($tasks as $task): ?>

    <div class="row container-fluid" style="display: flex; background-color: #E0E0E0; align-items: center; margin-top: 3px; padding-top: 10px; padding-bottom: 10px;">
        <div class="col-md-2">
            <?= date('d.m.Y H:i:s', $task->getCreatedAt()) ?>
        </div>
        <div class="col-md-2">
            <?= $task->activity->getTitle() ?>
        </div>
        <div class="col-md-2">
            <?= $task->getStageLink() ?>
        </div>
        <div class="col-md-5">
            <?= $task->getDescription() ?>
        </div>
        <div class="col-md-1">
            <?= $task->getStatusToString() ?>
        </div>
    </div>

<?php endforeach; ?>
