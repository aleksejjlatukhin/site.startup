<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Segment;

/* @var $this yii\web\View */
/* @var $model app\models\Segment */

$this->title = 'Сегмент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="segment-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <p>

            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?/*= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы точно хотите удалить сегмент ' . $model->name . '?',
                    'method' => 'post',
                ],
            ]) */?>

            <? if (!empty($model->creat_date)) {
                    echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default pull-right']);
            }?>

            <?php if(!($model->interview)) : ?>
                <?= Html::a('Переход к генерации ГПС* >>', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php else: ?>
                <?= Html::a('Генерация ГПС* >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success']) ?>
            <?php endif;?>

        </p>

    <?php else : ?>

        <p>

            <? if (!empty($model->creat_date)) {

                echo Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $model->id], ['class' => 'btn btn-default']);

            }?>

            <?php if(!($model->interview)) : ?>

                <p style="margin-top: 20px;"></p>

            <?php else: ?>

                <?= Html::a('Генерация ГПС* >>', ['interview/view', 'id' => $model->interview->id], ['class' => 'btn btn-success']) ?>

            <?php endif;?>

        </p>

    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'name',
            'description:ntext',

            [
                'attribute' => 'type_of_interaction_between_subjects',
                'label' => 'Вид информационного и экономического взаимодействия между субъектами рынка',
                'value' => function ($model) {
                    if ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C){
                        return 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)';
                    }
                    elseif ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B){
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
                'value' => function ($model) {
                    return $model->field_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'field_of_activity_b2b',
                'label' => 'Сфера деятельности предприятия',
                'value' => function ($model) {
                    return $model->field_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'sort_of_activity_b2c',
                'label' => 'Вид деятельности потребителя',
                'value' => function ($model) {
                    return $model->sort_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'sort_of_activity_b2b',
                'label' => 'Вид деятельности предприятия',
                'value' => function ($model) {
                    return $model->sort_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'specialization_of_activity_b2c',
                'label' => 'Специализация вида деятельности потребителя',
                'value' => function ($model) {
                    return $model->specialization_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'specialization_of_activity_b2b',
                'label' => 'Специализация вида деятельности предприятия',
                'value' => function ($model) {
                    return $model->specialization_of_activity;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'company_products',
                'label' => 'Продукция / услуги предприятия',
                'value' => function ($model) {
                    return $model->company_products;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'company_partner',
                'label' => 'Партнеры предприятия',
                'value' => function ($model) {
                    return $model->company_partner;
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],

            [
                'attribute' => 'age',
                'label' => 'Возраст потребителя',
                'value' => function ($model) {
                    if ($model->age_from !== null && $model->age_to !== null){
                        return 'от ' . number_format($model->age_from, 0, '', ' ') . ' до '
                            . number_format($model->age_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'gender_consumer',
                'label' => 'Пол потребителя',
                'value' => function ($model) {
                    if ($model->gender_consumer == Segment::GENDER_WOMAN) {
                        return 'Женский';
                    }else {
                        return 'Мужской';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],

            [
                'attribute' => 'education_of_consumer',
                'label' => 'Образование потребителя',
                'value' => function ($model) {
                    if ($model->education_of_consumer == Segment::SECONDARY_EDUCATION) {
                        return 'Среднее образование';
                    }elseif ($model->education_of_consumer == Segment::SECONDARY_SPECIAL_EDUCATION) {
                        return 'Среднее образование (специальное)';
                    }elseif ($model->education_of_consumer == Segment::HIGHER_INCOMPLETE_EDUCATION) {
                        return 'Высшее образование (незаконченное)';
                    }elseif ($model->education_of_consumer == Segment::HIGHER_EDUCATION) {
                        return 'Высшее образование';
                    }else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'income_b2c',
                'label' => 'Доход потребителя (тыс. руб./мес.)',
                'value' => function ($model) {
                    if ($model->income_from !== null && $model->income_to !== null){
                        return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                            . number_format($model->income_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'income_b2b',
                'label' => 'Доход предприятия (млн. руб./год)',
                'value' => function ($model) {
                    if ($model->income_from !== null && $model->income_to !== null){
                        return 'от ' . number_format($model->income_from, 0, '', ' ') . ' до '
                            . number_format($model->income_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],


            [
                'attribute' => 'quantity_b2c',
                'label' => 'Потенциальное количество потребителей (тыс. чел.)',
                'value' => function ($model) {
                    if ($model->quantity_from !== null && $model->quantity_to !== null){
                        return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                            . number_format($model->quantity_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2C)
            ],


            [
                'attribute' => 'quantity_b2b',
                'label' => 'Потенциальное количество представителей сегмента (ед.)',
                'value' => function ($model) {
                    if ($model->quantity_from !== null && $model->quantity_to !== null){
                        return 'от ' . number_format($model->quantity_from, 0, '', ' ') . ' до '
                            . number_format($model->quantity_to, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'visible' => ($model->type_of_interaction_between_subjects == Segment::TYPE_B2B)
            ],


            [
                'attribute' => 'market_volume',
                'label' => 'Объем рынка (млн. руб./год)',
                'value' => function ($model) {
                    if ($model->market_volume !== null){
                        return number_format($model->market_volume, 0, '', ' ');
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'add_info',
                'visible' => !empty($model->add_info),
            ],
        ],
    ]) ?>


    <?//= Html::a('Далее', ['interview/create', 'id' => $model->id], ['class' => 'btn btn-success btn-block']) ?>
    
    <div style="font-style: italic"><span class="bolder">Генерация ГПС*</span> - генерация гипотез проблем сегмента</div>

</div>
