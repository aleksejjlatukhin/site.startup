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

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <p>
<!--            <span class="col-sm-3">-->
<!--                --><?//= Html::a('Добавить целевой семент', ['create', 'id' => $project->id], ['class' => 'btn btn-success']) ?>
<!--            </span>-->

            <span class="col-sm-3">
                <?= Html::a('Дорожная карта сегментов', ['roadmap', 'id' => $project->id], ['class' => 'btn btn-success']) ?>
            </span>
        </p>
    </div>

    <hr>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['width' => '70'],
        'columns' => [
            [
                'header' => '№',
                'class' => 'yii\grid\SerialColumn',
            ],

            //'id',
            //'project_id',

            [
                'attribute' => 'name',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],

            //'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',
            //'age',
            [
                'attribute' => 'age',
                'label' => 'Возраст потреби-теля'
            ],
            //'income',
            [
                'attribute' => 'income',
                'label' => 'Доход потреби-телей (руб/мес)'
            ],
            //'quantity',
            [
                'attribute' => 'quantity',
                'label' => 'Потенц. кол-во потреби-телей'
            ],
            'market_volume',
            'add_info:ntext',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<p class="open_fast">
    <?= Html::submitButton('Добавить целевой семент', ['class' => 'btn btn-primary']) ?>
</p>

<div class="popap_fast">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-sm-9">
        <?= $form->field($newModel, 'name')->textInput(['maxlength' => true]) ?>
    </div>

    <span class="cross-out glyphicon text-danger glyphicon-remove"></span>

    <div class="col-sm-12 form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>



    <?php ActiveForm::end(); ?>
</div>
