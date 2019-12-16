<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Описание программы ППИ';
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

    <h3>Данные сегмента</h3>

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

    <br>

    <p> <h3>Исходные данные ПИ</h3>
    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы действительно хотите удалить интервью для "' . $generationProblem->title . '" ?',
            'method' => 'post',
        ],
    ]) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'count_respond',
            'count_positive',
            'greeting_interview',
            'view_interview',
            'reason_interview',
        ],
    ]) ?>

    <h4><u>Примерный список вопросов для проведения интервью</u></h4 class="d-inline p-2 bg-success" style="font-size: 18px;border-radius: 5px;height: 50px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">

    <?php
    $j = 0;
    if (!empty($model->questions)){
        foreach ($model->questions as $question){
            if ($question->status == 1){
                $j++;
                echo '<div class=""><b>' . $j . '.</b> ' . $question->title . '</div>' . '<br>';
            }
        }
    }else{
        echo "Вопросов пока нет...";
    }

    ?>

    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Полученные данные ПИ</div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;padding-bottom: 15px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 15px;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Проведение интервью</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 15px;">Результат ППИ</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 15px;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;">Дата отзыва</th>
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

            <td style="text-align: center; padding-top: 20px;">

                <?php
                $sumInt = 0;
                foreach ($responds as $respond){
                    $sumInt += $respond->descInterview->exist_desc;
                }
                $valueInt = round(($sumInt / count($responds) * 100) *100) / 100;

                echo Html::a("<progress max='100' value='$valueInt' id='info-interview'></progress><p>$valueInt  %</p>",
                    Url::to(['responds-confirm/by-date-interview', 'id' => $model->id]));
                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?php
                    //$count_exist = 0;
                    $count_positive = 0;
                    foreach ($responds as $respond){
                        //$count_exist += $respond->descInterview->exist_desc;
                        if ($respond->descInterview->status == 1){
                            $count_positive++;
                        }
                    }


                echo Html::a("<progress max='$model->count_positive' value='$count_positive' id='info-interview'></progress><p>$count_positive / $model->count_positive</p>",
                    Url::to(['responds-confirm/by-status-interview', 'id' => $model->id]));

                    if ($count_positive < $model->count_positive){
                        echo '<span style="color:red">Тест не пройден!</span>';
                    }else{
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

</div>
