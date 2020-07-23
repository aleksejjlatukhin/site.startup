<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Создание сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦC', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-create table-project-kartik">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_formCreate', [
        'model' => $model,
        'project' => $project,
    ]) ?>

</div>
