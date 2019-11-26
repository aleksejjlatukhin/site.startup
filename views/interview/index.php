<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Interviews';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="interview-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Interview', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'segment_id',
            'count_respond',
            'greeting_interview',
            'view_interview',
            //'reason_interview',
            //'question_1',
            //'question_2',
            //'question_3',
            //'question_4',
            //'question_5',
            //'question_6',
            //'question_7',
            //'question_8',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
