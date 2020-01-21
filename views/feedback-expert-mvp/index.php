<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feedback Expert Mvps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-expert-mvp-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Feedback Expert Mvp', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'confirm_mvp_id',
            'title',
            'name',
            'position',
            //'feedback_file',
            //'comment',
            //'date_feedback',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
