<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация ГЦС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-index table-project-kartik">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегментов', ['roadmap', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Данные проекта', ['#'], [
            'class' => 'btn btn-default pull-right',
            'style' => ['margin-left' => '5px'],
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

        <?= Html::a('Создать сегмент', Url::to(['/segment/create', 'id' => $project->id]), [
            'class' => 'btn btn-success pull-right',
        ]) ?>

    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'options' => ['width' => '70'],
        'summary' => false,
        'columns' => [
            [
                'header' => '№',
                'class' => 'yii\grid\SerialColumn',
            ],

            [
                'attribute' => 'name',
                'value' => function ($model) {
                    if (empty($model->creat_date)){

                        return Html::a(Html::encode($model->name), Url::to(['/segment/update', 'id' => $model->id]), [
                            'class' => 'table-kartik-link',
                            'title' => 'Редактирование'
                        ]);

                    } else {

                        if ($model->interview) {

                            return Html::a(Html::encode($model->name), Url::to(['/interview/view', 'id' => $model->interview->id]), [
                                'class' => 'table-kartik-link',
                                'title' => 'Переход к программе генерации ГПС'
                            ]);

                        } else {

                            return Html::a(Html::encode($model->name), Url::to(['/interview/create', 'id' => $model->id]), [
                                'class' => 'table-kartik-link',
                                'title' => 'Создание программы генерации ГПС'
                            ]);
                        }
                    }
                },
                'format' => 'raw',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'enableSorting' => false,
            ],

            [
                'attribute' => 'field_of_activity',
                'label' => 'Сфера деятельности',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'enableSorting' => false,
            ],

            [
                'attribute' => 'sort_of_activity',
                'label' => 'Вид деятельности',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'enableSorting' => false
            ],

            [
                'attribute' => 'specialization_of_activity',
                'label' => 'Специализация',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'enableSorting' => false
            ],

            [
                'attribute' => 'market_volume',
                'label' => 'Объем рынка (млн. руб./год)',
                'contentOptions'=>['style'=>'white-space: normal;', 'class' => 'text-center'],
                'enableSorting' => false
            ],

            [
                 'attribute' => 'detail',
                 'label' => false,
                 'value' => function($model){
                    if ($model->type_of_interaction_between_subjects === \app\models\Segment::TYPE_B2C) {
                        return '<div class="text-center">' . Html::a('B2C',['/segment/view', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Просмотр / Редактирование']) . '</div>';
                    }
                    elseif ($model->type_of_interaction_between_subjects === \app\models\Segment::TYPE_B2B) {
                        return '<div class="text-center">' . Html::a('B2B',['/segment/view', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Просмотр / Редактирование']) . '</div>';
                    }
                    else {
                        return '';
                    }
                 },
                 'format' => 'raw',
                 'contentOptions'=>['style'=>'white-space: normal;'],
                 'enableSorting' => false
            ],

        ],
    ]); ?>


    <?php
    // Модальное окно - данные проекта
    Modal::begin([
        'options' => [
            'id' => 'data_project_modal',
        ],
        'size' => 'modal-lg',
        'header' => '<h3 class="text-center">Данные проекта «'. $project->project_name .'»</h3>',
    ]);
    ?>

    <?= DetailView::widget([
        'model' => $project,
        'attributes' => [

            'project_name',
            'project_fullname:ntext',
            'description:ntext',
            'rid',
            'core_rid:ntext',
            'patent_number',

            [
                'attribute' => 'patent_date',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            'patent_name:ntext',

            [
                'attribute'=>'Команда проекта',
                'value' => $project->getAuthorInfo($project),
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
                'value' => function($project){
                    if($project->invest_amount !== null){
                        return number_format($project->invest_amount, 0, '', ' ');
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
                        $string .= Html::a($file->file_name, ['/projects/download', 'id' => $file->id], ['class' => '']) . '<br>';
                    }
                    return $string;
                },
                'format' => 'html',
            ]

        ],
    ]) ?>

    <?php
    Modal::end();
    ?>


