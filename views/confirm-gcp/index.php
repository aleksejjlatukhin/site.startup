<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Confirm Gcps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-gcp-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Confirm Gcp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gcp_id',
            'count_respond',
            'count_positive',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
