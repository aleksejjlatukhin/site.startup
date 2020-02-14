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
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['confirm-gcp/view', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГMVP', 'url' => ['mvp/index', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание ' . $mvp->title, 'url' => ['mvp/view', 'id' => $mvp->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-mvp-view">

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
                        <div class="faq_item_title_inner">Гипотеза ценностного предложения</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $gcp->description; ?>
                        </p>

                    </div>
                </div>

                <div class="faq_item">
                    <div class="faq_item_title">
                        <div class="faq_item_title_inner">Гипотеза MVP</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $mvp->description; ?>
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


    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px;height: 55px;padding-top: 12px;padding-left: 20px;margin-top: 10px;">Формирование данных программы подтверждения <?= $mvp->title ?></div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Позитивные ответы / всего опрошенных</th>
            <th scope="col" style="text-align: center;width: 180px;">Результат подтверждения ГЦП</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Дата отзыва</th>
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

                <?php if ($mvp->exist_confirm !== null) : ?>

                    <p>"Хочу купить": <span style="color: green"><?= $e;?></span></p>
                    <p>"Привлекательно": <span style="color: blue"><?= $d;?></span></p>
                    <p>"Не интересно": <span style="color: red"><?= $c;?></span></p>

                <?php endif; ?>

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

    if ($mvp->exist_confirm == 1 && $mvp->exist_confirm == $model->exist_confirm) {

        if (!empty($model->business)){
            echo Html::a('Показать бизнес-модель >>', ['business-model/view', 'id' => $model->business->id], ['class' => 'btn btn-success', 'style' => ['margin-left' => '10px']]);
        }else{
            echo Html::a('Генерация бизнес-модели >>', ['business-model/create', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-left' => '10px']]);

        }
    }

    ?>

</div>
