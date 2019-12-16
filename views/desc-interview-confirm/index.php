<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Desc Interview Confirms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="desc-interview-confirm-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Desc Interview Confirm', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'responds_confirm_id',
            'date_fact',
            'description:ntext',
            'interview_file',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
