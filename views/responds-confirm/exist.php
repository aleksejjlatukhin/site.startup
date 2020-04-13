<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проверка заполнения данных о респондентах';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-confirm exist">

    <h2 style="margin-bottom: 15px;">
        <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
        <?= Html::a('<< Программа подтверждения', ['confirm-problem/view', 'id' => $confirmProblem->id], ['class' => 'btn btn-sm btn-default']) ?>
    </h2>


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
                    return '<div style="font-weight: 700;">' . Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id])) . '</div>';
                },
                'format' => 'raw',
                'enableSorting' => false,
            ],

            [
                'attribute' => 'data',
                'headerOptions' => ['class' => 'text-center'],
                'label' => 'Данные респондентов',
                'value' => function($model){
                    if (!empty($model->name) && !empty($model->info_respond)){
                        return 'Заполнены';
                    }else{
                        return 'Необходимо заполнить';
                    }

                }
            ],

        ],
    ]); ?>

</div>
