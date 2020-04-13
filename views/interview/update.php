<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Редактирование программы генерации ГПС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="interview-update">

    <h2 style="margin-bottom: 20px;"><?= $this->title; ?></h2>

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
            </div>
        </div>
    </div>

    <?= $this->render('_form_update', [
        'model' => $model,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
