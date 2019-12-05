<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Generation Problems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generation-problem-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Generation Problem', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'interview_id',
            'title',
            'description:ntext',
            'date_gps',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
