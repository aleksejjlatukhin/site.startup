<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->project_name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="projects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить проект ' . $model->project_name . '?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Далее', ['segment/index', 'id' => $model->id], ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

//            'id',
//            'user_id',
            'project_fullname:ntext',
            'project_name',
            'description:ntext',
            'rid',
            'core_rid:ntext',
            'patent_number',
            //'patent_date',

            [
                'attribute' => 'patent_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'patent_name:ntext',

            [
                'attribute'=>'Целевые сегменты',
                'value' => $model->getConceptDesc($model),
                'format' => 'html',
            ],

            [
                'attribute'=>'Команда проекта',
                'value' => $model->getAuthorInfo($model),
                'format' => 'html',
            ],

            'technology',
            'layout_technology:ntext',
            'register_name',
            //'register_date',

            [
                'attribute' => 'register_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'site',
            'invest_name',

            [
                'attribute' => 'invest_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'invest_amount',

            [
                'attribute' => 'date_of_announcement',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'announcement_event',

            [
                'attribute' => 'created_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'update_at',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute'=>'files',
                'value' => str_replace(',', ' | ', $model->files),
                'format' => 'html',
            ]

        ],
    ]) ?>
    <?= Html::a('Далее', ['segment/index', 'id' => $model->id], ['class' => 'btn btn-success btn-block']) ?>

</div>
