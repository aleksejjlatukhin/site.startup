<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гипотезы ценностных предложений';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = 'Таблица ГЦП';
?>
<div class="gcp-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <br>

    <p>
        <?= Html::a('Добавить ГЦП', ['create', 'id' => $confirmProblem->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '№',
                'options' => ['width' => '30']
            ],

            //'title',
            [
                'attribute' => 'title',
                'value' => function($model){
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                },
                'format' => 'html',
            ],
            //'good',
            //'benefit',
            //'contrast',
            //'description:ntext',
            [
                'attribute' => 'description',
                'header' => '<div style="text-align: center">Формулировка ГЦП</div>',
            ],

            [
                'attribute' => 'exist_confirm',
                'label' => 'Подтверждение ГЦП',
                //'visible' => ($model->exist_confirm !== null),
                'value' => function($model){
                    if ($model->exist_confirm === null){
                        return '';
                    }
                    if ($model->exist_confirm == 0){
                        return '<span style="color:red">Тест закончен, ГЦП не подтверждена!</span>';
                    }
                    if ($model->exist_confirm == 1){
                        return '<span style="color:green">Тест закончен, ГЦП подтверждена!</span>';
                    }
                },
                'format' => 'html',
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
