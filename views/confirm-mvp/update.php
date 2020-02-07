<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmMvp */

$this->title = 'Редактирование программы подтверждения ' . $mvp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['confirm-gcp/view', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица MVP', 'url' => ['mvp/index', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание ' . $mvp->title, 'url' => ['mvp/view', 'id' => $mvp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $mvp->title, 'url' => ['confirm-mvp/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-mvp-update">

    <h2 style="margin: 20px 0;"><?= Html::encode($this->title) ?></h2>

    <h4>MVP требующее подтверждения:</h4>
    <p>- <?= $mvp->description;?></p>

    <h4>Подтвержденная гипотеза ценностного предложения:</h4>
    <p>- <?= $gcp->description;?></p>

    <h3 style="margin: 30px 0 10px 0;">Данные сегмента</h3>

    <div style="margin-bottom: 30px;">

        <?= DetailView::widget([
            'model' => $segment,
            'attributes' => [
                'quantity',
                'market_volume',
                'name',
                'field_of_activity:ntext',
                'sort_of_activity:ntext',
                'age',
                'income',
                [
                    'attribute' => 'add_info',
                    'visible' => !empty($segment->add_info),
                ],
            ],
        ]) ?>

    </div>

    <h3>Респонденты</h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'count_respond',
                'label' => 'Количество респондентов'
            ],
        ],
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'mvp' => $mvp,
        'confirmGcp' => $confirmGcp,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
