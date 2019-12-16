<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результат ППИ';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание программы ППИ', 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-confirm exist">

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

        <p>Количество респондентов: <?= $confirmProblem->count_respond; ?> </p>

        <p>Количество опрошенных респондентов: <?= $i; ?> </p>

        <p>Необходимое количество позитивных интервью: <?= $confirmProblem->count_positive; ?> </p>

        <p>Количество позитивных интервью: <?= $j; ?> </p>

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
                'headerOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    return Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'status',
                'headerOptions' => ['class' => 'text-center'],
                'label' => 'Индикатор теста',
                'value' => function($model){
                    if ($model->descInterview->status == 1){
                        return '<span style="color:green">Тест пройден</span>';
                    }

                    if($model->descInterview == null){
                        return 'Отсутствует интервью';
                    }

                    if($model->descInterview->status == 0){
                        return '<span style="color:red">Тест не пройден!</span>';
                    }

                },
                'format' => 'html',
            ],

        ],
    ]); ?>

    <?= Html::a('Вернуться к описанию программы ППИ', ['confirm-problem/view', 'id' => $confirmProblem->id], ['class' => 'btn btn-default']) ?>

</div>

