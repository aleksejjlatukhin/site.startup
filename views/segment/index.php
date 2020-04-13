<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация ГЦС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-index">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегментов', ['roadmap', 'id' => $project->id], ['class' => 'btn btn-success pull-right']) ?>

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

                        return Html::a(Html::encode($model->name), Url::to(['update', 'id' => $model->id]));

                    } else {

                        return Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
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
                'contentOptions'=>['style'=>'white-space: normal;'],
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


    <?php if (\app\models\User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

        </div>

        <p class="open_fast">
            <?= Html::submitButton('Добавить целевой семент', ['class' => 'btn btn-primary']) ?>
        </p>

        <div class="popap_fast">

            <?php $form = ActiveForm::begin(); ?>

            <div class="col-sm-6">
                <?= $form->field($newModel, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <span class="cross-out glyphicon text-danger glyphicon-remove"></span>

            <div class="col-sm-12 form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <br>

    <?php endif; ?>



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

