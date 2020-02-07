<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Редактирование программы подтверждения ' . $generationProblem->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-problem-update">

    <h2><?= $this->title; ?></h2><br>

    <?= DetailView::widget([
        'model' => $generationProblem,
        'attributes' => [
            [
                'attribute' => 'description',
                'label' => 'Формулировка гипотезы проблемы'
            ],
        ],
    ]) ?>

    <h3>Данные сегмента</h3>

    <div style="margin-bottom: 30px;">

    <?= DetailView::widget([
        'model' => $segment,
        'attributes' => [
            'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',
            'age',

            [
                'attribute' => 'income',
                'value' => number_format($segment->income, 0, '', ' '),

            ],

            [
                'attribute' => 'quantity',
                'value' => number_format($segment->quantity, 0, '', ' '),

            ],

            [
                'attribute' => 'market_volume',
                'value' => number_format($segment->market_volume, 0, '', ' '),

            ],

            [
                'attribute' => 'add_info',
                'visible' => !empty($segment->add_info),
            ],
        ],
    ]) ?>

    </div>


    <h3>Респонденты</h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'count_respond',
                'label' => 'Количество респондентов'
            ],
        ],
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
