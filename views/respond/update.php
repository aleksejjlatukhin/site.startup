<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Respond */

$this->title = 'Редактирование данных респондента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $model->interview_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Страница редактирования';
?>

<div class="respond-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
