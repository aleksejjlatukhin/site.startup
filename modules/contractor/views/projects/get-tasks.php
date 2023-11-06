<?php

use app\models\ContractorTasks;
use yii\helpers\Html;

/**
 * @var $tasks ContractorTasks[]
 */

?>

<div class="row container-fluid bolder" style="display: flex; background-color: #E0E0E0; align-items: center; padding-top: 15px; padding-bottom: 15px;">
    <div class="col-md-6">
        <div class="row" style="display:flex; align-items: center;">
            <div class="col-md-2">Создано</div>
            <div class="col-md-2">Статус</div>
            <div class="col-md-4">Деятельность</div>
            <div class="col-md-4">Этап проекта</div>
        </div>
    </div>
    <div class="col-md-5">Описание</div>
    <div class="col-md-1"></div>
</div>

<?php foreach ($tasks as $task): ?>

    <div class="row container-fluid" style="display: flex; background-color: #E0E0E0; align-items: center; margin-top: 3px; padding-top: 10px; padding-bottom: 10px;">
        <div class="col-md-6">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-md-2">
                    <?= date('d.m.Y H:i:s', $task->getCreatedAt()) ?>
                </div>
                <div class="col-md-2">
                    <?= $task->getStatusToString() ?>
                </div>
                <div class="col-md-4">
                    <?= $task->activity->getTitle() ?>
                </div>
                <div class="col-md-4">
                    <?= $task->getNameStage() ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <?= $task->getDescription() ?>
        </div>
        <div class="col-md-1">
            <?= Html::a('Далее', $task->getStageUrl(), [
                'class' => 'btn btn-default',
                'style' => [
                    'display' => 'flex',
                    'align-items' => 'center',
                    'justify-content' => 'center',
                    'color' => '#FFFFFF',
                    'background' => '#52BE7F',
                    'width' => '100px',
                    'height' => '40px',
                    'font-size' => '18px',
                    'border-radius' => '8px',
                ]
            ]) ?>
        </div>
    </div>

<?php endforeach; ?>


