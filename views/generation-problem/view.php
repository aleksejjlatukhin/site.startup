<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GenerationProblem */

$this->title = 'Описание: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="stages">
    <div class="stage"><span>Разработка программы ПИ</span></div>
    <div class="stage"><span>Проведение ПИ</span></div>
    <div class="stage active"><span>Выводы по ГПС</span></div>
    <div class="stage"><span>Отзыв эксперта</span></div>
</div>

<div class="generation-problem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить ' . $model->title . ' ?',
                'method' => 'post',
            ],
        ]) */?>
        <?php if (empty($model->confirm)) : ?>
            <?= Html::a('Подтвердить ПС', ['confirm-problem/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Подтверждение ПС', ['confirm-problem/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'title',
            'description:ntext',
            [
                'attribute' => 'date_gps',
                'format' => ['date', 'dd.MM.yyyy'],
            ],
        ],
    ]) ?>

    <?= Html::a('Вернуться к исходным данным', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-default']) ?>

</div>
