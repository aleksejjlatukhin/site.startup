<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Project', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'created_at',
            'update_at',
            'project_fullname:ntext',
            //'project_name',
            //'description:ntext',
            //'rid',
            //'patent_number',
            //'patent_date',
            //'patent_name:ntext',
            //'core_rid:ntext',
            //'technology',
            //'layout_technology:ntext',
            //'register_name',
            //'register_date',
            //'site',
            //'invest_name',
            //'invest_date',
            //'invest_amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
