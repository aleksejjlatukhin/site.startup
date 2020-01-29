<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-model-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Business Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'confirm_mvp_id',
            'quantity',
            'sort_of_activity',
            'relations',
            //'partners',
            //'distribution_of_sales',
            //'resources',
            //'cost',
            //'revenue',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
