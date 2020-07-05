<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Создание программы генерации ГПС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="interview-create table-project-kartik">

    <?= $this->render('_form', [
        'model' => $model,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
