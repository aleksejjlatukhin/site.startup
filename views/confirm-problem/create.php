<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\ConfirmProblem */

$this->title = 'Создание программы подтверждения ' . $generationProblem->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="confirm-problem-create">

    <h2><?= $this->title; ?></h2>

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
                        <div class="faq_item_title_inner">Формулировка гипотезы проблемы</div>
                    </div>
                    <div class="faq_item_body">

                        <p style="margin-top: 0; padding: 10px;background-color: #d9d6c4;">
                            <?= $generationProblem->description; ?>
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'count_respond',
                        'label' => 'Количество респондентов (представителей сегмента)'
                    ],
                ],
            ]) ?>

        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'genarationProblem' => $generationProblem,
        'interview' => $interview,
        'segment' => $segment,
        'project' => $project,
    ]) ?>

</div>
