<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Создание сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦП', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
