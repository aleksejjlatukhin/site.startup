<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */

$this->title = 'Программа подтверждения ' . $gcp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="confirm-gcp-create">

    <h2 style="margin: 20px 0;"><?= Html::encode($this->title) ?></h2><br>

    <?= DetailView::widget([
        'model' => $gcp,
        'attributes' => [
            //'title',
            'description',
        ],
    ]) ?>

    <h3 style="margin: 15px 0;">Данные сегмента</h3>
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


    <h3>Респонденты</h3>
    <hr>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'count_respond',
                'label' => 'Количество респондентов (подтвердивших проблему)'
            ],
        ],
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
