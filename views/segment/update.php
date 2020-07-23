<?php

use yii\helpers\Html;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Редактирование сегмента: ' . $segment->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦC', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="segment-update table-project-kartik">

    <h2><?= Html::encode($this->title) ?></h2>


    <?= $this->render('_form', [
        'model' => $model,
        'project' => $project,
    ]) ?>


</div>
