<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = 'Проект: ' . $model->project_name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->project_name;
\yii\web\YiiAsset::register($this);
?>
<div class="projects-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить проект ' . $model->project_name . '? 
Все данные будут удалены безвозвратно!',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Генерация ГЦС >>', ['segment/index', 'id' => $model->id], ['class' => 'btn btn-success pull-right']) ?>

        <?= Html::a('Сводная таблица проекта', ['result', 'id' => $model->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-right' => '5px']]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'project_name',
            'project_fullname:ntext',
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

            [
                'attribute' => 'invest_amount',
                'value' => function($model){
                    if($model->invest_amount !== null){
                       return number_format($model->invest_amount, 0, '', ' ');
                    }
                },
            ],

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
                'attribute' => 'pre_files',
                'label' => 'Презентационные файлы',
                'value' => function($model){
                    $string = '';
                    foreach ($model->preFiles as $file){
                        $string .= Html::a($file->file_name, ['download', 'id' => $file->id], ['class' => '']) . '<br>';
                    }
                    return $string;
                },
                'format' => 'html',
            ]

        ],
    ]) ?>
    <?//= Html::a('Далее', ['segment/index', 'id' => $model->id], ['class' => 'btn btn-success btn-block']) ?>

</div>
