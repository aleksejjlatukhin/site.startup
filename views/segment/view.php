<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Сегмент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $model->name;
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

        <? if (!empty($model->creat_date)) {
                echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default pull-right']);
        }?>

        <?php if(!($model->interview)) : ?>
            <?= Html::a('Переход к генерации ГПС* >>', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Генерация ГПС* >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success']) ?>
        <?php endif;?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',

            [
                'attribute' => 'age',
                'label' => 'Возраст потребителя',
                'value' => function ($model) {
                    if ($model->age_from !== null && $model->age_to !== null){
                        return 'от ' . number_format($model->age_from, 0, '', ' ') . ' до '
                            . number_format($model->age_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'income',
                'label' => 'Доход потребителя (тыс. руб./мес.)',
                'value' => function ($model) {
                    if ($model->income_from !== null && $model->income_to !== null){
                        return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                            . number_format($model->income_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'quantity',
                'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                'value' => function ($model) {
                    if ($model->quantity_from !== null && $model->quantity_to !== null){
                        return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                            . number_format($model->quantity_to, 0, '', ' ');
                    }
                },
            ],


            [
                'attribute' => 'market_volume',
                'label' => 'Объем рынка (млн. руб./год)',
                'value' => function ($model) {
                    if ($model->market_volume_from !== null && $model->market_volume_to !== null){
                        return 'от ' . number_format($model->market_volume_from, 0, '', ' ') . ' до '
                            . number_format($model->market_volume_to, 0, '', ' ');
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
