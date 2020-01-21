<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Генерация ПИ - исходные данные';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="stages">
    <div class="stage active"><span>Разработка программы ПИ</span></div>
    <div class="stage"><span>Проведение ПИ</span></div>
    <div class="stage"><span>Выводы по ГПС</span></div>
    <div class="stage"><span>Отзыв эксперта</span></div>
</div>

<div class="interview-view">

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

    <p> <h3>Исходные данные интервью</h3>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить интервью для "' . $segment->name . '" ?',
                'method' => 'post',
            ],
        ]) */?>
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

    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Формирование ГПС по данным из ПИ</div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="width: 80px;text-align: center;padding-bottom: 15px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Проведение интервью</th>
            <th scope="col" style="text-align: center;width: 180px;">Представители сегмента</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 15px;">ГПС</th>
            <th scope="col" style="text-align: center;width: 100px;padding-bottom: 15px;">Дата ГПС</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 15px;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 100px;">Дата отзыва</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; padding-top: 20px;">
                <?php if (!empty($responds)) {
                    if (count($responds)%10 == 1){
                        echo Html::a(count($responds) . ' респондент', Url::to(['respond/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 2 || count($responds)%10 == 3 || count($responds)%10 == 4){
                        echo Html::a(count($responds) . ' респондента', Url::to(['respond/index', 'id' => $model->id]));
                    }
                    if (count($responds)%10 == 0 || count($responds)%10 > 4){
                        echo Html::a(count($responds) . ' респондентов', Url::to(['respond/index', 'id' => $model->id]));
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

                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p>$value  %</p>", Url::to(['respond/exist', 'id' => $model->id]));
                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?php
                $sumInt = 0;
                foreach ($responds as $respond){
                    $sumInt += $respond->descInterview->exist_desc;
                }
                $valueInt = round(($sumInt / count($responds) * 100) *100) / 100;

                echo Html::a("<progress max='100' value='$valueInt' id='info-interview'></progress><p>$valueInt  %</p>", Url::to(['respond/by-date-interview', 'id' => $model->id]));
                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">
                <?php

                $sumPositive = 0;

                foreach ($responds as $respond){
                    if (!empty($respond->descInterview)){

                        if ($respond->descInterview->status == 1){
                            $sumPositive++;
                        }
                    }
                }

                $valPositive = round(($sumPositive / count($responds) * 100) *100) / 100;

                if ($model->count_positive <= $sumPositive){
                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p>$valPositive  %</p>", Url::to(['respond/by-status-responds', 'id' => $model->id]));
                }

                if ($sumPositive < $model->count_positive){

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-red'></progress><p>$valPositive  %</p>", Url::to(['respond/by-status-responds', 'id' => $model->id]));
                }


                ?>
            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->problems)){
                    foreach ($model->problems as $problem) {

                        echo Html::a($problem->title, Url::to(['generation-problem/view', 'id' => $problem->id]));
                        if (isset($problem->exist_confirm)){
                            if ($problem->exist_confirm == 0){
                                echo '<br><span style="color:red">Тест закончен, проблема не подтверждена!</span>';
                            }
                            if ($problem->exist_confirm == 1){
                                echo '<br><span style="color:green">Тест закончен, проблема подтверждена!</span>';
                            }
                        }
                        echo '<hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['generation-problem/create', 'id' => $model->id]));?></div>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->problems)){
                    foreach ($model->problems as $problem) {
                        echo date("d.m.Y", strtotime($problem->date_gps)) . '<hr>';
                    }
                }
                ?>

                <br>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['feedback-expert/create', 'id' => $model->id]));?></div>

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


