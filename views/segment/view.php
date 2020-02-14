<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="segment-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>

        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить сегмент ' . $model->name . '?',
                'method' => 'post',
            ],
        ]) */?>

        <? if (!empty($model->field_of_activity) && !empty($model->sort_of_activity) && !empty($model->age) &&
            !empty($model->income) && !empty($model->quantity) && !empty($model->market_volume)) {
                echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default']);
        }?>

        <?php if(!($model->interview)) : ?>
            <?= Html::a('Переход к генерации ГПС >>', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success pull-right']) ?>
        <?php else: ?>
            <?= Html::a('Генерация ГПС >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success pull-right']) ?>
        <?php endif;?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',
            'age',

            [
                'attribute' => 'income',
                'value' => function ($model) {
                    if ($model->income !== null){
                        return number_format($model->income, 0, '', ' ');
                    }
                },
            ],

            [
                'attribute' => 'quantity',
                'value' => function ($model) {
                    if ($model->quantity !== null){
                        return number_format($model->quantity, 0, '', ' ');
                    }
                },
            ],

            [
                'attribute' => 'market_volume',
                'value' => function ($model) {
                    if ($model->market_volume !== null){
                        return number_format($model->market_volume, 0, '', ' ');
                    }
                },
            ],

            [
                'attribute' => 'add_info',
                'visible' => !empty($model->add_info),
            ],
        ],
    ]) ?>


    <?//= Html::a('Далее', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success btn-block']) ?>
    
    <div style="font-style: italic"><span class="bolder">Генерация ГПС*</span> - генерация гипотез проблем сегмента</div>

</div>
