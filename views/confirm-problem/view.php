<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Программа подтверждения ' . $generationProblem->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-problem-view">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

        <?php endif; ?>
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
                        <div class="faq_item_title_inner">Формулировка гипотезы проблемы</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $generationProblem->description; ?>
                        </p>

                    </div>
                </div>

                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Вводные данные для подтверждения</div>
                    </div>
                    <div class="faq_item_body">

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'count_respond',
                                'count_positive',
                            ],
                        ]) ?>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px 5px 0 0;height: 55px;padding-top: 12px;padding-left: 20px;margin-top: 10px;">Формирование данных программы подтверждения <?= $generationProblem->title; ?></div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Данные респондентов</th>
            <!--<th scope="col" style="text-align: center;width: 180px;">Проведение интервью</th>-->
            <th scope="col" style="text-align: center;width: 180px;">Позитивные ответы / всего опрошенных</th>
            <th scope="col" style="text-align: center;width: 190px;">Результат подтверждения ГПС</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Дата отзыва</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; padding-top: 20px;font-weight: 700;">
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

                <?php if ($data_responds === 0) : ?>

                    <?= Html::a('Начать', ['responds-confirm/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

                <?php elseif ($data_responds == count($responds) && $data_desc == count($responds)) : ?>

                    <?/*= Html::a('Добавить', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']])*/ ?>

                <?php else : ?>

                    <?= Html::a('Продолжить', ['responds-confirm/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

                <?php endif; ?>

            </td>

            <td style="text-align: center; padding-top: 20px;">

                <?php
                $sum = 0;
                foreach ($responds as $respond){
                    $sum += $respond->exist_respond;
                }

                if ($sum !== 0){

                    $value = round(($sum / count($responds) * 100) * 100) / 100;

                }else{

                    $value = 0;
                }

                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p style='font-weight: 700;font-size: 13px;'>$value  %</p>", Url::to(['responds-confirm/exist', 'id' => $model->id]));
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

                    echo '<div style="font-weight: 700;font-size: 13px;">' . $a . ' / ' . $b . '</div>';

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

                    if($sumPositive !== 0){

                        $valPositive = round(($sumPositive / count($responds) * 100) *100) / 100;

                    }else {

                        $valPositive = 0;
                    }


                    if ($sumPositive < $model->count_positive){

                        $model->exist_confirm = 0;

                        echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-red'></progress><p style='font-size: 13px; font-weight: 700;'>$valPositive %</p>",
                            Url::to(['responds-confirm/by-status-interview', 'id' => $model->id]));

                        if ($generationProblem->exist_confirm === 0){
                            echo '<span style="color:red; font-size: 13px; font-weight: 700;">Тест не пройден!</span><br>';
                        }
                    }

                    if ($model->count_positive <= $sumPositive){

                        $model->exist_confirm = 1;

                        echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p style='font-size: 13px; font-weight: 700;'>$valPositive %</p>",
                            Url::to(['responds-confirm/by-status-interview', 'id' => $model->id]));


                        if ($generationProblem->exist_confirm === 1){
                            echo '<span style="color:green; font-size: 13px; font-weight: 700;">Тест пройден!</span><br>';
                        }
                    }


                    if ($sumPositive != 0 && $sumPositive < $model->count_positive && count($responds) == $data_desc){
                        echo '<div style="color: red; margin-top: 15px; font-size: 13px; font-weight: 700;">Недостаточное количество позитивных респондентов</div>';

                        if (User::isUserSimple(Yii::$app->user->identity['username'])){

                            echo Html::a('Добавить!', ['responds-confirm/index', 'id' => $model->id], ['class' => 'btn btn-danger', 'style' => ['margin-top' => '10px', 'width' => '110px']]);
                        }
                    }

                    if ($model->count_positive <= $sumPositive && empty($model->gcps)){
                        echo '<span style="color: green; font-size: 13px; font-weight: 700;">Переходите <br>к разработке ГЦП</span>';
                    }

                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;font-weight: 700;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert-confirm/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <div style="padding-bottom: 10px;font-size: 13px; "><?= Html::a("+ добавить", Url::to(['feedback-expert-confirm/create', 'id' => $model->id]));?></div>

                <?php endif; ?>
            </td>

            <td style="text-align: center; padding-top: 20px;font-weight: 700;">

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

    if (User::isUserSimple(Yii::$app->user->identity['username'])){

        if ($generationProblem->exist_confirm !== $model->exist_confirm){

            if ($model->exist_confirm == 0){

                echo Html::a('Закончить программу >>', ['not-exist-confirm', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Проблема не подтверждена! Вы действительно хотите закончить тест для "' . $generationProblem->title . '" ?',
                        'method' => 'post',
                    ],
                ]);
            }

            if ($model->exist_confirm == 1){

                echo Html::a('Разработка ГЦП >>', ['exist-confirm', 'id' => $model->id], ['class' => 'btn btn-success',]);
            }
        }
    }


    if ($model->exist_confirm == 1 && $generationProblem->exist_confirm == 1) {

        if (!empty($model->gcps)){

            echo Html::a('Разработка ГЦП >>', ['gcp/index', 'id' => $model->id], ['class' => 'btn btn-success']);

        }else{

            if (User::isUserSimple(Yii::$app->user->identity['username'])) {

                echo Html::a('Разработка ГЦП >>', ['gcp/create', 'id' => $model->id], ['class' => 'btn btn-success']);
            }
        }
    }

    ?>

</div>
