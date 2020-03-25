<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разработка ГЦП';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = 'Разработка ГЦП';
?>
<div class="gcp-index">

    <p>

        <span style="font-size: 30px;">Таблица разработанных ГЦП</span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?= Html::a('Добавить ГЦП', ['create', 'id' => $confirmProblem->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

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

            [
                'attribute' => 'title',
                'value' => function($model){
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                },
                'format' => 'html',
                'enableSorting' => false,
            ],

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
                'enableSorting' => false,
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <div style="font-style: italic;margin-left: auto;"><span class="bolder">ГЦП*</span> - гипотеза ценностного предложения.</div>

</div>
