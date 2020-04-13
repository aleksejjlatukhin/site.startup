<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GenerationProblem */

$this->title = 'Создание гипотезы  проблемы сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="generation-problem-create">

    <div class="row">
        <div class="col-md-8">

            <h2><?= Html::encode($this->title) ?>

                <?= Html::a('Программа генерации ГПС', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-sm btn-default pull-right']) ?>

            </h2>

        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'responds' => $responds,
    ]) ?>

</div>
