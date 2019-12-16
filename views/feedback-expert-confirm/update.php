<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FeedbackExpertConfirm */

$this->title = 'Редактирование отзыва №' . mb_substr($model->title, -2, 3);
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание программы ППИ', 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание отзыва №' . mb_substr($model->title, -2, 3), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-expert-confirm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
