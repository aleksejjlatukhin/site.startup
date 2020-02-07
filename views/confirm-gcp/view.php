<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */

$this->title = 'Программа подтверждения ' . $gcp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-gcp-view">

    <h3 style="margin: 15px 0;">Данные сегмента</h3>
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

    <?= DetailView::widget([
        'model' => $gcp,
        'attributes' => [
            'title',
            'description',
        ],
    ]) ?>

    <br>
    <h3>Данные для подтверждения <?= $gcp->title ?></h3>

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
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

    <br>

    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Формирование данных программы подтверждения <?= $gcp->title ?></div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;padding: 30px 0;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Количество позитивных ответов / всего опрошенных</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 20px 0;">Результат подтверждения ГЦП</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;padding: 30px 0;">Дата отзыва</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; padding-top: 20px;">
                <?php if (!empty($responds)) {
                    if (count($responds)%10 == 1){
                        echo Html::a(count($responds) . ' респондент', Url::to(['responds-gcp/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 2 || count($responds)%10 == 3 || count($responds)%10 == 4){
                        echo Html::a(count($responds) . ' респондента', Url::to(['responds-gcp/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 0 || count($responds)%10 > 4){
                        echo Html::a(count($responds) . ' респондентов', Url::to(['responds-gcp/index', 'id' => $model->id]));
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

                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p>$value  %</p>", Url::to(['responds-gcp/exist', 'id' => $model->id]));
                ?>

            </td>

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
                        Url::to(['responds-gcp/by-status-interview', 'id' => $model->id]));

                    echo '<span style="color:red">Тест не пройден!</span>';
                }

                if ($model->count_positive <= $sumPositive){

                    $model->exist_confirm = 1;

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p>$valPositive %</p>",
                        Url::to(['responds-gcp/by-status-interview', 'id' => $model->id]));

                    echo '<span style="color:green">Тест пройден</span>';
                }

                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert-gcp/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['feedback-expert-gcp/create', 'id' => $model->id]));?></div>

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

    if ($gcp->exist_confirm !== $model->exist_confirm){

        if ($model->exist_confirm == 0){

            echo Html::a('Закончить тест >>', ['not-exist-confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Гипотеза не подтверждена! Вы действительно хотите закончить тест для "' . $gcp->title . '" ?',
                    'method' => 'post',
                ],
            ]);
        }

        if ($model->exist_confirm == 1){

            echo Html::a('Закончить тест >>', ['exist-confirm', 'id' => $model->id], ['class' => 'btn btn-success',]);
        }
    }

    if ($model->exist_confirm == 1 && $gcp->exist_confirm == 1) {
        echo Html::a('Перейти на страницу MVP >>', ['mvp/index', 'id' => $model->id], ['class' => 'btn btn-success']);
    }

    ?>

</div>
