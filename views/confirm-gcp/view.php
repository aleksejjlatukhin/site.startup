<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\User;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmGcp */

$this->title = 'Программа подтверждения ' . $gcp->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="confirm-gcp-view">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

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
                                'description:ntext',

                                [
                                    'attribute' => 'type_of_interaction_between_subjects',
                                    'label' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
                                    'value' => function ($segment) {
                                        if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C){
                                            return 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)';
                                        }
                                        elseif ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B){
                                            return 'Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)';
                                        }
                                        else{
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                ],

                                [
                                    'attribute' => 'field_of_activity_b2c',
                                    'label' => 'Сфера деятельности потребителя',
                                    'value' => function ($segment) {
                                        return $segment->field_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],

                                [
                                    'attribute' => 'field_of_activity_b2b',
                                    'label' => 'Сфера деятельности предприятия',
                                    'value' => function ($segment) {
                                        return $segment->field_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],

                                [
                                    'attribute' => 'sort_of_activity_b2c',
                                    'label' => 'Вид деятельности потребителя',
                                    'value' => function ($segment) {
                                        return $segment->sort_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],

                                [
                                    'attribute' => 'sort_of_activity_b2b',
                                    'label' => 'Вид деятельности предприятия',
                                    'value' => function ($segment) {
                                        return $segment->sort_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],

                                [
                                    'attribute' => 'specialization_of_activity_b2c',
                                    'label' => 'Специализация вида деятельности потребителя',
                                    'value' => function ($segment) {
                                        return $segment->specialization_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],

                                [
                                    'attribute' => 'specialization_of_activity_b2b',
                                    'label' => 'Специализация вида деятельности предприятия',
                                    'value' => function ($segment) {
                                        return $segment->specialization_of_activity;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],

                                [
                                    'attribute' => 'company_products',
                                    'label' => 'Продукция / услуги предприятия',
                                    'value' => function ($segment) {
                                        return $segment->company_products;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],

                                [
                                    'attribute' => 'company_partner',
                                    'label' => 'Партнеры предприятия',
                                    'value' => function ($segment) {
                                        return $segment->company_partner;
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],

                                [
                                    'attribute' => 'age',
                                    'label' => 'Возраст потребителя',
                                    'value' => function ($segment) {
                                        if ($segment->age_from !== null && $segment->age_to !== null){
                                            return 'от ' . number_format($segment->age_from, 0, '', ' ') . ' до '
                                                . number_format($segment->age_to, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],

                                [
                                    'attribute' => 'gender_consumer',
                                    'label' => 'Пол потребителя',
                                    'value' => function ($segment) {
                                        if ($segment->gender_consumer == Segment::GENDER_WOMAN) {
                                            return 'Женский';
                                        }else {
                                            return 'Мужской';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],

                                [
                                    'attribute' => 'education_of_consumer',
                                    'label' => 'Образование потребителя',
                                    'value' => function ($segment) {
                                        if ($segment->education_of_consumer == Segment::SECONDARY_EDUCATION) {
                                            return 'Среднее образование';
                                        }elseif ($segment->education_of_consumer == Segment::SECONDARY_SPECIAL_EDUCATION) {
                                            return 'Среднее образование (специальное)';
                                        }elseif ($segment->education_of_consumer == Segment::HIGHER_INCOMPLETE_EDUCATION) {
                                            return 'Высшее образование (незаконченное)';
                                        }elseif ($segment->education_of_consumer == Segment::HIGHER_EDUCATION) {
                                            return 'Высшее образование';
                                        }else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],


                                [
                                    'attribute' => 'income_b2c',
                                    'label' => 'Доход потребителя (тыс. руб./мес.)',
                                    'value' => function ($segment) {
                                        if ($segment->income_from !== null && $segment->income_to !== null){
                                            return 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
                                                . number_format($segment->income_to, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],


                                [
                                    'attribute' => 'income_b2b',
                                    'label' => 'Доход предприятия (млн. руб./год)',
                                    'value' => function ($segment) {
                                        if ($segment->income_from !== null && $segment->income_to !== null){
                                            return 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
                                                . number_format($segment->income_to, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],


                                [
                                    'attribute' => 'quantity_b2c',
                                    'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                                    'value' => function ($segment) {
                                        if ($segment->quantity_from !== null && $segment->quantity_to !== null){
                                            return 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
                                                . number_format($segment->quantity_to, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C)
                                ],


                                [
                                    'attribute' => 'quantity_b2b',
                                    'label' => 'Потенциальное количество представителей сегмента (ед.)',
                                    'value' => function ($segment) {
                                        if ($segment->quantity_from !== null && $segment->quantity_to !== null){
                                            return 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
                                                . number_format($segment->quantity_to, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
                                    'visible' => ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B)
                                ],


                                [
                                    'attribute' => 'market_volume',
                                    'label' => 'Объем рынка (млн. руб./год)',
                                    'value' => function ($segment) {
                                        if ($segment->market_volume !== null){
                                            return number_format($segment->market_volume, 0, '', ' ');
                                        } else {
                                            return '';
                                        }
                                    },
                                    'format' => 'raw',
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
                        <div class="faq_item_title_inner">Формулировка ГЦП</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $gcp->description; ?>
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



    <div class="d-inline p-2 bg-primary" style="font-size: 22px;border-radius: 5px 5px 0 0;height: 55px;padding-top: 12px;padding-left: 20px;margin-top: 10px;">Формирование данных программы подтверждения <?= $gcp->title ?></div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Респонденты</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Данные респондентов</th>
            <th scope="col" style="text-align: center;width: 180px;">Позитивные ответы / всего опрошенных</th>
            <th scope="col" style="text-align: center;width: 190px;">Результат подтверждения ГЦП</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Отзыв эксперта</th>
            <th scope="col" style="text-align: center;width: 180px;padding-bottom: 20px;">Дата отзыва</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="text-align: center; padding-top: 20px;font-weight: 700;">
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

                <?php if ($data_responds === 0) : ?>

                    <?= Html::a('Начать', ['responds-gcp/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

                <?php elseif ($data_responds == count($responds) && $data_desc == count($responds)) : ?>

                    <?/*= Html::a('Добавить', ['respond/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']])*/ ?>

                <?php else : ?>

                    <?= Html::a('Продолжить', ['responds-gcp/index', 'id' => $model->id], ['class' => 'btn btn-success', 'style' => ['margin-top' => '20px', 'width' => '110px']]) ?>

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


                echo Html::a("<progress max='100' value='$value' id='info-respond'></progress><p style='font-weight: 700;font-size: 13px;'>$value  %</p>", Url::to(['responds-gcp/exist', 'id' => $model->id]));
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

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-red'></progress><p style='font-weight: 700;font-size: 13px;'>$valPositive %</p>",
                        Url::to(['responds-gcp/by-status-interview', 'id' => $model->id]));

                    if ($gcp->exist_confirm === 0){
                        echo '<span style="color:red; font-size: 13px; font-weight: 700;">Тест не пройден!</span><br>';
                    }
                }

                if ($model->count_positive <= $sumPositive){

                    $model->exist_confirm = 1;

                    echo Html::a("<progress max='100' value='$valPositive' id='info-interview' class='info-green'></progress><p style='font-weight: 700;font-size: 13px;'>$valPositive %</p>",
                        Url::to(['responds-gcp/by-status-interview', 'id' => $model->id]));

                    if ($gcp->exist_confirm === 1){
                        echo '<span style="color:green; font-size: 13px; font-weight: 700;">Тест пройден!</span><br>';
                    }
                }

                if (/*$sumPositive != 0 && */$sumPositive < $model->count_positive && count($responds) == $data_desc){
                    echo '<div style="color: red; margin-top: 15px; font-size: 13px; font-weight: 700;">Недостаточное количество позитивных респондентов</div>';

                    if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                        echo Html::a('Добавить!', ['responds-gcp/index', 'id' => $model->id], ['class' => 'btn btn-danger', 'style' => ['margin-top' => '10px', 'width' => '110px']]);
                    }
                }

                if ($model->count_positive <= $sumPositive && empty($model->mvps)){
                    echo '<span style="color: green; font-size: 13px; font-weight: 700;">Переходите <br>к разработке ГMVP</span>';
                }

                ?>

            </td>

            <td style="text-align: center; padding-top: 20px;font-weight: 700;">

                <? if (!empty($model->feedbacks)){
                    foreach ($model->feedbacks as $feedback) {
                        //echo $problem->title . '<br>';
                        echo Html::a($feedback->title, Url::to(['feedback-expert-gcp/view', 'id' => $feedback->id])) . '<hr>';
                    }
                }
                ?>

                <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

                    <div style="padding-bottom: 10px;font-size: 13px;"><?= Html::a("+ добавить", Url::to(['feedback-expert-gcp/create', 'id' => $model->id]));?></div>

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

    if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

        if ($gcp->exist_confirm !== $model->exist_confirm){

            if ($model->exist_confirm == 0){

                echo Html::a('Закончить программу >>', ['not-exist-confirm', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Гипотеза не подтверждена! Вы действительно хотите закончить тест для "' . $gcp->title . '" ?',
                        'method' => 'post',
                    ],
                ]);
            }

            if ($model->exist_confirm == 1){

                echo Html::a('Разработка ГMVP >>', ['exist-confirm', 'id' => $model->id], ['class' => 'btn btn-success',]);
            }
        }
    }



    if ($model->exist_confirm == 1 && $gcp->exist_confirm == 1) {

        if(!empty($model->mvps)){

            echo Html::a('Разработка ГMVP >>', ['mvp/index', 'id' => $model->id], ['class' => 'btn btn-success']);

        }else{

            if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])){

                echo Html::a('Разработка ГMVP >>', ['mvp/create', 'id' => $model->id], ['class' => 'btn btn-success']);
            }

        }
    }

    ?>

</div>
