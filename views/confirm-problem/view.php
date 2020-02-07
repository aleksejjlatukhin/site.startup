<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Программа подтверждения ' . $generationProblem->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-problem-view">

    <h2>Программа подтверждения <?= $generationProblem->title; ?></h2><br>

    <?= DetailView::widget([
        'model' => $generationProblem,
        'attributes' => [
            [
                'attribute' => 'description',
                'label' => 'Формулировка проблемы'
            ],
        ],
    ]) ?>

    <h3>Данные сегмента</h3>

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

    <br>

    <p> <h3>Вводные данные для подтверждения <?= $generationProblem->title; ?></h3>
    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы действительно хотите удалить интервью для "' . $generationProblem->title . '" ?',
            'method' => 'post',
        ],
    ]) */?>

    <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

    <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'count_respond',
            'count_positive',
        ],
    ]) ?>


    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Формирование данных программы подтверждения <?= $generationProblem->title; ?></div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;padding: 30px 0;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Данные респондентов</th>
            <!--<th scope="col" style="text-align: center;width: 180px;">Проведение интервью</th>-->
            <th scope="col" style="text-align: center;width: 180px;">Количество позитивных ответов / всего опрошенных</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 20px 0;">Результат подтверждения ГПС</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Дата отзыва</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; padding-top: 20px;">
                <?php if (!empty($responds)) {
                    if (count($responds)%10 == 1){
                        echo Html::a(count($responds) . ' респондент', Url::to(['responds-confirm/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 2 || count($responds)%10 == 3 || count($responds)%10 == 4){
                        echo Html::a(count($responds) . ' респондента', Url::to(['responds-confirm/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 0 || count($responds)%10 > 4){
                        echo Html::a(count($responds) . ' респондентов', Url::to(['responds-confirm/index', 'id' => $model->id]));
                    }

                }?>
            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?php
                $sum = 0;
                foreach ($responds as $respond){
                    $sum += $respond->exist_respond;
                }
                $value = round(($sum / count($responds) * 100) * 100) / 100;

                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p>$value  %</p>", Url::to(['responds-confirm/exist', 'id' => $model->id]));
                ?>

            </td>

            <!--<td style="text-align: center; padding-top: 20px;">

                <?php
/*                $sumInt = 0;
                foreach ($responds as $respond){
                    $sumInt += $respond->descInterview->exist_desc;
                }
                $valueInt = round(($sumInt / count($responds) * 100) *100) / 100;

                echo Html::a("<progress max='100' value='$valueInt' id='info-interview'></progress><p>$valueInt  %</p>",
                    Url::to(['responds-confirm/by-date-interview', 'id' => $model->id]));
                */?>

            </td>-->

            <td style="text-align: center; padding-top: 20px;">

                <?php
                    $a = 0;
                    $b = 0;
                    foreach ($responds as $respond){
                        if (!empty($respond->descInterview)){
                            $b++;
                            if ($respond->descInterview->status == 1){
                                $a++;
                            }
                        }
                    }

                    echo $a . ' / ' . $b;

                ?>

            </td>


            <td style="text-align: center; padding-top: 20px;">

                <?php

                    $sumPositive = 0;
                    foreach ($responds as $respond){
                        if ($respond->descInterview->status == 1){
                            $sumPositive++;
                        }
                    }

                    $valPositive = round(($sumPositive / count($responds) * 100) *100) / 100;

                    if ($sumPositive < $model->count_positive){

                        $model->exist_confirm = 0;

                        echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-red'></progress><p>$valPositive %</p>",
                            Url::to(['responds-confirm/by-status-interview', 'id' => $model->id]));

                        echo '<span style="color:red">Тест не пройден!</span>';
                    }

                    if ($model->count_positive <= $sumPositive){

                        $model->exist_confirm = 1;

                        echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p>$valPositive %</p>",
                            Url::to(['responds-confirm/by-status-interview', 'id' => $model->id]));

                        echo '<span style="color:green">Тест пройден</span>';
                    }

                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert-confirm/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['feedback-expert-confirm/create', 'id' => $model->id]));?></div>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        echo date("d.m.Y", strtotime($feedback->date_feedback)) . '<hr>';
                    }
                }
                ?>

                <br>

            </td>


        </tr>
        </tbody>
    </table>

    <?

    if ($generationProblem->exist_confirm !== $model->exist_confirm){

        if ($model->exist_confirm == 0){

            echo Html::a('Закончить тест >>', ['not-exist-confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Проблема не подтверждена! Вы действительно хотите закончить тест для "' . $generationProblem->title . '" ?',
                    'method' => 'post',
                ],
            ]);
        }

        if ($model->exist_confirm == 1){

            echo Html::a('Закончить тест >>', ['exist-confirm', 'id' => $model->id], ['class' => 'btn btn-success',]);
        }
    }

    if ($model->exist_confirm == 1 && $generationProblem->exist_confirm == 1) {
        echo Html::a('Перейти на страницу ГЦП >>', ['gcp/index', 'id' => $model->id], ['class' => 'btn btn-success']);
    }

    ?>

</div>
