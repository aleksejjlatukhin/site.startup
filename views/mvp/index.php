<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разработка ГMVP';
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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mvp-index">

    <p>

        <span style="font-size: 30px;"><?= $this->title; ?></span>

        <?= Html::a('Сводная таблица проекта', ['projects/result', 'id' => $project->id], ['class' => 'btn btn-default pull-right', 'style' => ['margin-left' => '5px']]) ?>

        <?= Html::a('Дорожная карта сегмента', ['segment/one-roadmap', 'id' => $segment->id], ['class' => 'btn btn-success pull-right']) ?>

        <?= Html::a('Добавить MVP', ['create', 'id' => $confirmGcp->id], ['class' => 'btn btn-primary pull-right', 'style' => ['margin-right' => '5px']]) ?>

    </p>

    <p style="text-indent: 20px;">Minimum Viable Product(MVP) — минимально жизнеспособный продукт,
        концепция минимализма программной комплектации выводимого на рынок устройства.
        Минимально жизнеспособный продукт - продукт, обладающий минимальными,
        но достаточными для удовлетворения первых потребителей функциями.
        Основная задача — получение обратной связи для формирования гипотез дальнейшего развития продукта.
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
                'value' => function($model){
                    return Html::a($model->title, ['view', 'id' => $model->id]);
                },
                'format' => 'html',
                'options' => ['width' => '200']
            ],

            [
                'attribute' => 'description',
                'label' => 'Формулировка MVP',
            ],

            [
                'attribute' => 'exist_confirm',
                'label' => 'Результаты подтверждения',
                'value' => function($model){

                    $c = 0; $d = 0; $e = 0;

                    $responds = $model->confirm->responds;

                    if ($model->exist_confirm !== null){
                        if ($responds){
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

                            return '<p>"Хочу купить": <span style="color: green">' . $e . '</span></p>
                <p>"Привлекательно": <span style="color: blue">' . $d . '</span></p>
                <p>"Не интересно": <span style="color: red">' . $c . '</span></p>';
                        }
                    }
                },
                'format' => 'html',
                //'options' => ['width' => '250'],
            ],

            [
                'attribute' => 'statuses',
                'label' => 'Статус подтверждения',
                'value' => function($model) {
                    if ($model->exist_confirm === 0) {
                        return '<span style="color:red">ГMVP не подтверждена</span>';
                    }
                    if ($model->exist_confirm === 1) {
                        return '<span style="color:green">ГMVP подтверждена</span>';
                    }
                },
                'format' => 'html',
            ],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>