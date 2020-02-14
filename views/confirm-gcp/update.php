<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */

$this->title = 'Редактирование данных для подтверждения ' . $gcp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-gcp-update">

    <h2><?= Html::encode($this->title) ?></h2>

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
                                'age',

                                [
                                    'attribute' => 'income',
                                    'value' => number_format($segment->income, 0, '', ' '),

                                ],

                                [
                                    'attribute' => 'quantity',
                                    'value' => number_format($segment->quantity, 0, '', ' '),

                                ],

                                [
                                    'attribute' => 'market_volume',
                                    'value' => number_format($segment->market_volume, 0, '', ' '),

                                ],

                                [
                                    'attribute' => 'add_info',
                                    'visible' => !empty($segment->add_info),
                                ],
                            ],
                        ]) ?>

                    </div>
                </div>

                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Формулировка ГЦП</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $gcp->description; ?>
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
                        'label' => 'Количество респондентов (учавствующих в опросе)',
                    ],
                ],
            ]) ?>

        </div>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
        'gcp' => $gcp,
        'confirmProblem' => $confirmProblem,
        'generationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
