<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск представителей сегмента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="respond by-status-responds">

    <h2 style="margin-bottom: 10px;">
        <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
        <?= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-sm btn-default']) ?>
    </h2>

    <?php
    $i = 0;
    $j = 0;
    foreach ($models as $respond){
        if ($respond->descInterview !== null){
            $i++;
        }

        if ($respond->descInterview->status == 1){
            $j++;
        }
    }
    ?>

    <div class="d-inline p-2 bg-success">

        <p>Количество респондентов: <?= $interview->count_respond; ?> </p>

        <p>Количество опрошенных респондентов: <?= $i; ?> </p>

        <p>Необходимое количество представителей сегмента: <?= $interview->count_positive; ?> </p>

        <p>Количество представителей сегмента: <?= $j; ?> </p>

    </div>

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
                    return '<div style="font-weight: 700;">' . Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id])) . '</div>';
                },
                'format' => 'raw',
                'enableSorting' => false,
            ],

            [
                'attribute' => 'status',
                'headerOptions' => ['class' => 'text-center'],
                'label' => 'Является респондент представителем сегмента?',
                'value' => function($model){

                    if ($model->descInterview->status == 1){
                        return '<span style="color:green">Да</span>';
                    }

                    if($model->descInterview == null){
                        return 'Отсутствует интервью';
                    }

                    if($model->descInterview->status == 0){
                        return '<span style="color:red">Нет</span>';
                    }

                },
                'format' => 'html',
            ],

        ],
    ]); ?>


    <?//= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-default']) ?>
</div>
