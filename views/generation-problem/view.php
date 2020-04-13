<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\GenerationProblem */

$this->title = 'Описание: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="generation-problem-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

        <p>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php if (empty($model->confirm)) : ?>
                <?= Html::a('Подтвердить ГПС >>', ['confirm-problem/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php else: ?>
                <?= Html::a('Подтверждение ГПС', ['confirm-problem/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>

            <?php if ($model->exist_confirm == 1){

                if (!empty($model->confirm->gcps)){

                    echo Html::a('Разработка ГЦП >>', ['gcp/index', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);

                }else{
                    echo Html::a('Разработка ГЦП >>', ['gcp/create', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
                }
            }?>
        </p>

    <?php else : ?>

        <p>
            <?php if (!empty($model->confirm)) : ?>
                <?= Html::a('Подтверждение ГПС', ['confirm-problem/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>

            <?php if ($model->exist_confirm == 1){

                if (!empty($model->confirm->gcps)){
                    echo Html::a('Разработка ГЦП >>', ['gcp/index', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
                }
            }?>
        </p>

    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    //'title',
                    'description:ntext',

                    [
                        'attribute' => 'date_gps',
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],

                    [
                        'attribute' => 'exist_confirm',
                        'label' => 'Подтверждение гипотезы',
                        'visible' => ($model->exist_confirm !== null),
                        'value' => function($model){
                            if ($model->exist_confirm == 0){
                                return '<span style="color:red; font-size: 13px; font-weight: 700;">Гипотеза проблемы не подтверждена!</span>';
                            }
                            if ($model->exist_confirm == 1){
                                return '<span style="color:green; font-size: 13px; font-weight: 700;">Гипотеза проблемы подтверждена!</span>';
                            }
                        },
                        'format' => 'html',
                    ],

                    [
                        'attribute' => 'date_confirm',
                        'visible' => ($model->date_confirm !== null),
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],
                ],
            ]) ?>

            <div style="margin-top: -10px;"><?= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-default']) ?></div>

            <div style="font-style: italic;margin-top: 20px;"><span class="bolder">ГПС</span> - гипотеза проблемы сегмента.</div>

        </div>
    </div>


</div>
