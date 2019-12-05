<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Feedback Experts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-expert-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Feedback Expert', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'interview_id',
            'title',
            'name',
            'position',
            //'feedback_file',
            //'comment',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
