<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Редактирование программы подтверждения ' . $generationProblem->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="confirm-problem-update">

    <h2><?= $this->title; ?></h2>

    <div class="row">
        <div class="col-md-8">
            <div class="faq_list">
                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Данные сегмента</div>
                    </div>
                    <div class="faq_item_body">

                        <?= DetailView::widget([
                            'model' => $segment,
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

                    </div>
                </div>

                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Формулировка гипотезы проблемы</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $generationProblem->description; ?>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'count_respond',
                        'label' => 'Количество респондентов (учавствующих в опросе)'
                    ],
                ],
            ]) ?>

        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
