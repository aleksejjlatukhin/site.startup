<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmMvp */

$this->title = 'Программа подтверждения ' . $mvp->title;
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
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-mvp-view">

    <h2 style="margin: 20px 0;"><?= Html::encode($this->title) ?></h2>
    <hr>

    <h4>MVP требующее подтверждения:</h4>
    <p>- <?= $mvp->description;?></p>

    <h4>Подтвержденная гипотеза ценностного предложения:</h4>
    <p>- <?= $gcp->description;?></p>

    <hr>
    <h3>Данные для подтверждения <?= $mvp->title ?></h3>

    <p>
        <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'count_respond',
            'count_positive',
        ],
    ]) ?>

    <br>

    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Формирование данных программы подтверждения <?= $mvp->title ?></div>

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
                        echo Html::a(count($responds) . ' респондент', Url::to(['responds-mvp/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 2 || count($responds)%10 == 3 || count($responds)%10 == 4){
                        echo Html::a(count($responds) . ' респондента', Url::to(['responds-mvp/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 0 || count($responds)%10 > 4){
                        echo Html::a(count($responds) . ' респондентов', Url::to(['responds-mvp/index', 'id' => $model->id]));
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

                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p>$value  %</p>", Url::to(['responds-mvp/exist', 'id' => $model->id]));
                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?php
                $a = 0;
                $b = 0;
                foreach ($responds as $respond){
                    if (!empty($respond->descInterview)){
                        $b++;
                        if ($respond->descInterview->status > 0){
                            $a++;
                        }
                    }
                }

                echo $a . ' / ' . $b;

                ?>

            </td>


            <td style="text-align:center; padding-top: 20px;">

                <?php

                $sumPositive = 0;
                foreach ($responds as $respond){
                    if ($respond->descInterview->status > 0){
                        $sumPositive++;
                    }
                }

                $valPositive = round(($sumPositive / count($responds) * 100) *100) / 100;

                if ($sumPositive < $model->count_positive){

                    $model->exist_confirm = 0;

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-red'></progress><p>$valPositive %</p>",
                        Url::to(['responds-mvp/by-status-interview', 'id' => $model->id]));

                }

                if ($model->count_positive <= $sumPositive){

                    $model->exist_confirm = 1;

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p>$valPositive %</p>",
                        Url::to(['responds-mvp/by-status-interview', 'id' => $model->id]));

                }

                $c = 0; $d = 0; $e = 0;
                foreach ($responds as $respond){
                    if ($respond->descInterview->status === 0){
                        $c++;
                    }
                    if ($respond->descInterview->status === 1){
                        $d++;
                    }
                    if ($respond->descInterview->status === 2){
                        $e++;
                    }
                }
                ?>

                <p>"Хочу купить": <span style="color: green"><?= $e;?></span></p>
                <p>"Привлекательно": <span style="color: blue"><?= $d;?></span></p>
                <p>"Не интересно": <span style="color: red"><?= $c;?></span></p>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert-mvp/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['feedback-expert-mvp/create', 'id' => $model->id]));?></div>

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

    if ($mvp->exist_confirm !== $model->exist_confirm){

        if ($model->exist_confirm == 0){

            echo Html::a('Закончить тест', ['not-exist-confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'MVP не подтвержден! Вы действительно хотите закончить тест для "' . $mvp->title . '" ?',
                    'method' => 'post',
                ],
            ]);
        }

        if ($model->exist_confirm == 1){

            echo Html::a('Закончить тест', ['exist-confirm', 'id' => $model->id], ['class' => 'btn btn-success',]);
        }
    }

    if ($mvp->exist_confirm !== null && $mvp->exist_confirm == $model->exist_confirm) {

        echo Html::a('Сводная таблица данных по проекту', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default']);
    }

    if ($mvp->exist_confirm == 1 && $mvp->exist_confirm == $model->exist_confirm) {

        if (!empty($model->business)){
            echo Html::a('Описание бизнес-модели', ['business-model/view', 'id' => $model->business->id], ['class' => 'btn btn-success', 'style' => ['margin-left' => '10px']]);
        }else{
            echo Html::a('Создать бизнес-модель', ['business-model/create', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-left' => '10px']]);

        }
    }




    ?>

</div>
