<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Gcp */

$this->title = 'Описание: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица ГЦП', 'url' => ['index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gcp-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if (empty($model->confirm)) : ?>
            <?= Html::a('Подтвердить ГЦП >>', ['confirm-gcp/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a('Подтверждение ГЦП', ['confirm-gcp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>

        <?/*= Html::a('Удаление', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить '. $model->title .'?',
                'method' => 'post',
            ],
        ]) */?>
        <?php if ($model->exist_confirm == 1){
            echo Html::a('Перейти на страницу MVP >>', ['mvp/index', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
        }?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'confirm_problem_id',
            //'title',
            //'good',
            //'benefit',
            //'contrast',
            'description:ntext',

            [
                'attribute' => 'date_create',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'exist_confirm',
                'label' => 'Подтверждение ГЦП',
                'visible' => ($model->exist_confirm !== null),
                'value' => function($model){
                    if ($model->exist_confirm == 0){
                        return '<span style="color:red">Тест закончен, гипотеза не подтверждена!</span>';
                    }
                    if ($model->exist_confirm == 1){
                        return '<span style="color:green">Тест закончен, гипотеза подтверждена!</span>';
                    }
                },
                'format' => 'html',
            ],

            [
                'attribute' => 'date_confirm',
                'visible' => ($model->date_confirm !== null),
                'format' => ['date', 'dd.MM.yyyy'],
            ],
        ],
    ]) ?>

</div>
