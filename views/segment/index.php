<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Генерация ГЦС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
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
                    return Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '180'],
            ],

            [
                'attribute' => 'field_of_activity',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '180'],
            ],

            [
                'attribute' => 'sort_of_activity',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '180'],
            ],

            [
                'attribute' => 'age',
                'label' => 'Возраст потреб.*',
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '50'],
            ],

            [
                'attribute' => 'income',
                'label' => 'Доход потр.* (руб/мес)',
                'value' => function ($model) {
                    if ($model->income !== null){
                        return number_format($model->income, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '110'],
            ],

            [
                'attribute' => 'quantity',
                'label' => 'Потенциал. кол. потреб.*',
                'value' => function ($model) {
                    if ($model->quantity !== null){
                        return number_format($model->quantity, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '110'],
            ],

            [
                'attribute' => 'market_volume',
                'label' => 'V - рынка* (млн/год)',
                'value' => function ($model) {
                    if ($model->market_volume !== null){
                        return number_format($model->market_volume, 0, '', ' ');
                    }
                },
                'contentOptions'=>['style'=>'white-space: normal;'],
                'options' => ['width' => '90'],
            ],

            'add_info:ntext',

        ],
    ]); ?>

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

<div class="row">
    <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">ГЦС*</span> — гипотеза целевого сегмента.</p>
</div>

<div class="row">
    <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Возраст потреб.*</span> — возраст потребителя.</p>
</div>

<div class="row">
    <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Доход потр.*</span> — доход потребителя.</p>
</div>

<div class="row">
    <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">Потенциал. кол. потреб.*</span> — потенциальное количество потребителей.</p>
</div>

<div class="row">
    <p class="col-sm-6" style="font-style: italic; font-size: 13px;"><span class="bolder">V - рынка*</span> — объем рынка.</p>
</div>

