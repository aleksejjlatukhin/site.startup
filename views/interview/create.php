<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Создание программы генерации ГПС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="interview-create">

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
            </div>
        </div>
    </div>


    <?= $this->render('_form', [
        'model' => $model,
        'segment' => $segment,
        'project' => $project,
        'newQuestions' => $newQuestions,
    ]) ?>

</div>
