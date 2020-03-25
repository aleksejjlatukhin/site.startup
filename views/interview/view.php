<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Программа генерации ГПС';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="interview-view">


    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

    </p>


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
                        <div class="faq_item_title_inner">Исходные данные для интервью</div>
                    </div>
                    <div class="faq_item_body">

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

                    </div>
                </div>

                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Примерный список вопросов</div>
                    </div>
                    <div class="faq_item_body">

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

                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-top: 10px;">Таблица проведения программы генерации ГПС</div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="width: 130px;text-align: center;padding-bottom: 20px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 170px;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Проведение интервью</th>
            <th scope="col" style="text-align: center;width: 180px;">Представители сегмента</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">ГПС</th>
            <th scope="col" style="text-align: center;width: 100px;padding-bottom: 20px;">Дата ГПС</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Отзыв эксперта</th>
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

                <?php if ($data_responds === 0) : ?>

                    <?= Html::a('Начать', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

                <?php elseif ($data_responds == count($responds) && $data_interview == count($responds)) : ?>

                    <?/*= Html::a('Добавить', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']])*/ ?>

                <?php else : ?>

                    <?= Html::a('Продолжить', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

                <?php endif; ?>

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
                $data_desc = 0;
                foreach ($responds as $respond){

                    if (!empty($respond->descInterview)){
                        $data_desc++;

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

                if ($sumPositive != 0 && $sumPositive < $model->count_positive && count($responds) == $data_desc){
                    echo '<span style="color: red;">Недостаточное количество представителей сегмента</span>'
                    . Html::a('Добавить!', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-danger', 'style' => ['margin-top' => '20px', 'width' => '110px']]);
                }

                if ($model->count_positive <= $sumPositive && empty($model->problems)){
                    echo '<span style="color: green;">Переходите <br>к созданию ГПС</span>';
                }

                ?>
            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->problems)){
                    foreach ($model->problems as $problem) {

                        echo Html::a($problem->title, Url::to(['generation-problem/view', 'id' => $problem->id]));

                        if (isset($problem->confirm)){
                            if ($problem->exist_confirm === 0){
                                echo '<br><span style="color:red">Гипотеза проблемы не подтверждена!</span>';
                            }
                            if ($problem->exist_confirm === 1){
                                echo '<br><span style="color:green">Гипотеза проблемы подтверждена!</span>';
                            }
                            if ($problem->exist_confirm === null){
                                echo Html::a('Подтвердить', ['confirm-problem/view', 'id' => $problem->confirm->id], ['class' => 'btn btn-primary', 'style' => ['margin-top' => '10px', 'width' => '110px']]);
                            }
                        }else{

                            echo Html::a('Подтвердить', ['confirm-problem/create', 'id' => $problem->id], ['class' => 'btn btn-primary', 'style' => ['margin-top' => '10px', 'width' => '110px']]);
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
                        echo date("d.m.Y", strtotime($problem->date_gps));
                        if (isset($problem->exist_confirm)){
                            echo '<div style="height: 40px;"></div>' . '<hr>';
                        }else{
                            echo '<div style="height: 44px;"></div>' . '<hr>';
                        }
                    }
                }
                ?>

                <br>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?
                $height = [];
                foreach ($model->problems as $i => $problem) {
                    if (isset($problem->exist_confirm)){
                        $height[] = 40;
                    }else{
                        $height[] = 44;
                    }
                }

                if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $k => $feedback) {
                        echo Html::a($feedback->title, Url::to(['feedback-expert/view', 'id' => $feedback->id])) . '<div style="height:'. $height[$k] .'px"></div><hr>';
                    }
                }
                ?>

                <div style="padding-bottom: 10px;"><?= Html::a("+ добавить", Url::to(['feedback-expert/create', 'id' => $model->id]));?></div>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $k => $feedback) {
                        echo date("d.m.Y", strtotime($feedback->date_feedback)) . '<div style="height:'. $height[$k] .'px"></div><hr>';
                    }
                }
                ?>

                <br>

            </td>


        </tr>
        </tbody>
    </table>

    <div style="font-style: italic"><span class="bolder">Программа генерации ГПС</span> - программа генерации гипотез проблем сегмента.</div>

</div>


