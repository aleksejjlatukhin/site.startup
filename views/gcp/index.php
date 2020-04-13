<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разработка ГЦП';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = 'Разработка ГЦП';
?>
<div class="gcp-index">

    <p>

        <span style="font-size: 30px;">Таблица разработанных ГЦП</span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <?= Html::a('Добавить ГЦП', ['create', 'id' => $confirmProblem->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

        <?php endif; ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [

            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '№',
                'options' => ['width' => '30']
            ],

            [
                'attribute' => 'title',
                'options' => ['width' => '200'],
                'header' => '<div style="text-align: center">Наименование ГЦП</div>',
                'value' => function($model){
                    return '<div style="text-align: center; font-size: 13px; font-weight: 700;">' . Html::a($model->title, ['view', 'id' => $model->id]) . '</div>';
                },
                'format' => 'html',
                'enableSorting' => false,
            ],

            [
                'attribute' => 'description',
                'header' => '<div style="text-align: center">Формулировка ГЦП</div>',
            ],

            [
                'attribute' => 'exist_confirm',
                'label' => 'Подтверждение ГЦП',
                'header' => '<div style="text-align: center">Подтверждение ГЦП</div>',
                //'visible' => ($model->exist_confirm !== null),
                'value' => function($model){
                    if ($model->exist_confirm === null && empty($model->confirm)){
                        return '<div style="text-align: center;">'. Html::a('Подтвердить', ['confirm-gcp/create', 'id' => $model->id], ['class' => 'btn btn-sm btn-success', 'style' => ['margin-top' => '10px', 'width' => '135px', 'font-weight' => '700']]) .'</div>';
                    }

                    if ($model->exist_confirm === null && !empty($model->confirm)){
                        return '<div style="text-align: center;">'. Html::a('Продолжить<br>подтверждение', ['confirm-gcp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-sm btn-warning', 'style' => ['margin-top' => '5px', 'width' => '135px', 'font-weight' => '700']]) .'</div>';
                    }

                    if ($model->exist_confirm === 0){
                        return '<div style="text-align: center; color:red; font-size: 13px; font-weight: 700;">ГЦП не подтверждена!</div>';
                    }
                    if ($model->exist_confirm === 1){
                        return '<div style="text-align: center; color: green; font-size: 13px; font-weight: 700;">ГЦП подтверждена!</div>';
                    }
                },
                'format' => 'html',
                'options' => ['width' => '220'],
                'enableSorting' => false,
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <div style="font-style: italic;margin-left: auto;"><span class="bolder">ГЦП*</span> - гипотеза ценностного предложения.</div>

</div>
