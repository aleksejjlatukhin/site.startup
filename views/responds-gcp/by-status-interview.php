<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результат подтверждения ' . $gcp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['confirm-gcp/view', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-gcp by-status-interview">

    <h2><?= Html::encode($this->title) ?></h2>


    <?php
    $i = 0;
    $j = 0;
    foreach ($responds as $respond){
        if ($respond->descInterview !== null){
            $i++;
        }

        if ($respond->descInterview->status == 1){
            $j++;
        }
    }
    ?>

    <div class="d-inline p-2 bg-success">

        <p>Количество респондентов: <?= $confirmGcp->count_respond; ?> </p>

        <p>Количество опрошенных респондентов: <?= $i; ?> </p>

        <p>Необходимое количество позитивных ответов: <?= $confirmGcp->count_positive; ?> </p>

        <p>Количество позитивных ответов: <?= $j; ?> </p>

    </div>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'text-center'],
        'summary' =>false,
        'columns' => [
            [
                'header' => '№',
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width' => '20'],
            ],

            [
                'attribute' => 'name',
                'label' => 'Респонденты',
                'headerOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    return Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'status',
                'headerOptions' => ['class' => 'text-center'],
                'label' => 'Результаты опроса',
                'value' => function($model){
                    if ($model->descInterview->status == 1){
                        return '<span style="color:green">Привлекательно</span>';
                    }

                    if($model->descInterview == null){
                        return 'Анкета не заполнена';
                    }

                    if($model->descInterview->status == 0){
                        return '<span style="color:red">Неинтересно</span>';
                    }

                },
                'format' => 'html',
            ],

        ],
    ]); ?>

    <?= Html::a('<< Программа подтверждения', ['confirm-gcp/view', 'id' => $confirmGcp->id], ['class' => 'btn btn-default']) ?>

</div>
