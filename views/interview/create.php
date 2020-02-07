<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Разработка программы ПИ';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="interview-create">

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

    <h2>Разработка программы ПИ</h2>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
