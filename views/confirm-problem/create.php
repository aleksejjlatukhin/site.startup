<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Разработка программы ППИ';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationPromblem->title, 'url' => ['generation-problem/view', 'id' => $generationPromblem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-problem-create">

    <h1>Разработка программы ППИ</h1><br>

    <h3>Данные сегмента</h3>

    <?= DetailView::widget([
        'model' => $segment,
        'attributes' => [
            'quantity',
            'market_volume',
            'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',
            'age',
            'income',
            [
                'attribute' => 'add_info',
                'visible' => !empty($segment->add_info),
            ],
        ],
    ]) ?>

    <br>
    <hr>

    <?= $this->render('_form', [
        'model' => $model,
        'genarationPromblem' => $generationPromblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
