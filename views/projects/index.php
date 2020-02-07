<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои проекты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p style="margin-bottom: 20px;">
        <?= Html::a('Создать проект', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['width' => '70'],
        'summary' => false,
        'columns' => [

            [
                'header' => '№',
                'class' => 'yii\grid\SerialColumn',
            ],

            [
                'attribute' => 'project_name',
                'value' => function ($data) {
                    return Html::a(Html::encode($data->project_name), Url::to(['view', 'id' => $data->id]));
                },
                'format' => 'raw',
            ],

            'project_fullname:ntext',
            'rid',
            'patent_number',

            [
                'attribute' => 'patent_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'patent_name:ntext',
            'technology',

            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'update_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

        ],
    ]); ?>


</div>
