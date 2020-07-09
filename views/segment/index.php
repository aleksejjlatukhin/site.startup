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
            'data-toggle' => 'modal',
            'data-target' => "#data_project_modal",
        ]) ?>

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
                'options' => ['width' => '180'],
                'enableSorting' => false,
            ],

            [
                'attribute' => 'field_of_activity',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '180'],
                'enableSorting' => false,
            ],

            [
                'attribute' => 'sort_of_activity',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '180'],
                'enableSorting' => false
            ],

            [
                'attribute' => 'age',
                'label' => 'Возраст потреб.*',
                'value' => function ($model) {
                    if ($model->age_from !== null && $model->age_to !== null){
                        return 'от ' . number_format($model->age_from, 0, '', ' ') . '<br>до '
                            . number_format($model->age_to, 0, '', ' ');
                    }
                },
                'contentOptions' => ['style'=>'white-space: normal;'],
                'options' => ['width' => '80'],
                'format' => 'html',
            ],



            [
                'attribute' => 'income',
                'label' => 'Доход потреб.*',
                'value' => function ($model) {
                    if ($model->income_from !== null && $model->income_to !== null){
                        return 'от ' . number_format($model->income_from, 0, '', ' ') . '<br> до '
                            . number_format($model->income_to, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '90'],
                'format' => 'html',
            ],


            [
                'attribute' => 'quantity',
                'label' => 'Потенциал. кол. потреб.*',
                'value' => function ($model) {
                    if ($model->quantity_from !== null && $model->quantity_to !== null){
                        return 'от ' . number_format($model->quantity_from, 0, '', ' ') . '<br> до '
                            . number_format($model->quantity_to, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '110'],
                'format' => 'html',
            ],


            [
                'attribute' => 'market_volume',
                'label' => 'V - рынка (млн/год)*',
                'value' => function ($model) {
                    if ($model->market_volume_from !== null && $model->market_volume_to !== null){
                        return 'от ' . number_format($model->market_volume_from, 0, '', ' ') . '<br> до '
                            . number_format($model->market_volume_to, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '100'],
                'format' => 'html',
            ],


            [
                'attribute' => 'add_info',
                'enableSorting' => false,
            ],


        ],
    ]); ?>


    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        </div>

        <p class="open_fast">
            <?= Html::submitButton('Добавить целевой семент', ['class' => 'btn btn-primary']) ?>
        </p>

        <div class="popap_fast row">

            <?php $form = ActiveForm::begin(); ?>

            <div class="col-sm-6">
                <?= $form->field($newModel, 'name', [
                    'template' => '<div class="row" style="padding: 0;"><div class="col-xs-12">{label}</div><div class="col-xs-10">{input}</div><div class="col-xs-2 cross-out glyphicon text-danger glyphicon-remove" style="margin: 0;padding: 0;"></div><div class="col-xs-12">{error}</div></div>'
                ])->textInput(['maxlength' => true]) ?>
            </div>



            <div class="col-sm-12 form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <br>

    <?php endif; ?>


    <?php
    // Сообщение о том, что респондент с таким именем уже есть
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
                'attribute'=>'Целевые сегменты',
                'value' => $project->getConceptDesc($project),
                'format' => 'html',
            ],

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



    <div class="row">
        <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Генерация ГЦС</span> — генерация гипотез целевых сегментов.</p>
    </div>

    <div class="row">
        <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Возраст потреб.*</span> — возраст потребителя.</p>
    </div>

    <div class="row">
        <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Доход потреб.*</span> — доход потребителя (тыс. руб./мес.).</p>
    </div>

    <div class="row">
        <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Потенциал. кол. потреб.*</span> — потенциальное количество потребителей (тыс. чел.).</p>
    </div>

    <div class="row">
        <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">V - рынка (млн/год)*</span> — объем рынка (млн. руб./год).</p>
    </div>

