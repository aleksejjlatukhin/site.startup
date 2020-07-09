<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои проекты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-index table-project-kartik">

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <h2><?= Html::encode($this->title) ?></h2>

        <p style="margin-bottom: 20px;">
            <?= Html::a('Создать проект', ['create', 'id' => $user['id']], ['class' => 'btn btn-success']) ?>
        </p>

    <?php else : ?>

        <h2 style="margin-bottom: 20px;"><?= Html::encode($this->title) ?></h2>

    <?php endif; ?>


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
                    return Html::a(Html::encode($data->project_name), Url::to(['/segment/index', 'id' => $data->id]), [
                        'class' => 'table-kartik-link',
                        'title' => 'Переход к генерации ГЦС'
                    ]);
                },
                'format' => 'raw',
            ],

            'project_fullname:ntext',
            'rid',
            //'patent_number',

            //[
                //'attribute' => 'patent_date',
                //'format' => ['date', 'dd.MM.yyyy'],
            //],

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

            [
                'class' => 'yii\grid\ActionColumn',
                //'header'=>'Действия',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('Просмотр', ['/projects/view', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-default',
                            'style' => ['width' => '130px'],
                            'title' => 'Просмотр',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('Редактирование', ['/projects/update', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-primary',
                            'style' => ['width' => '130px', 'margin' => '5px 0'],
                            'title' => 'Редактирование',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('Удаление', ['/projects/delete', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-danger',
                            'style' => ['width' => '130px'],
                            'title' => 'Удаление',
                            'data' => [
                                'method' => 'post',
                                'confirm' =>'Вы уверены что хотите удалить этот проект?',
                            ]
                        ]);
                    },
                ],
            ],

        ],
    ]); ?>


</div>
