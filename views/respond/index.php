<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Responds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="respond-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Respond', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'interview_id',
            'name',
            'info_respond',
            'add_info',
            //'date_interview',
            //'place_interview',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
