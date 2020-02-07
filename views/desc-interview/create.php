<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterview */

$this->title = 'Материалы полученные во время интервью';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $respond->interview_id]];
$this->params['breadcrumbs'][] = ['label' => 'Респондент: ' . $respond->name, 'url' => ['respond/view', 'id' => $respond->id]];
$this->params['breadcrumbs'][] = 'Добавление материалов полученных во время интервью';
?>

<div class="desc-interview-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
