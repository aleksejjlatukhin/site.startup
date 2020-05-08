<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Gcp */

$this->title = 'Описание: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Разработка ГЦП', 'url' => ['index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gcp-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <p>
            <?= Html::a('Редактирование', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php if (empty($model->confirm)) : ?>
                <?= Html::a('Подтвердить ГЦП >>', ['confirm-gcp/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php else: ?>
                <?= Html::a('Подтверждение ГЦП', ['confirm-gcp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>

            <?php if ($model->exist_confirm == 1){

                if (!empty($model->confirm->mvps)){

                    echo Html::a('Разработка ГMVP >>', ['mvp/index', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);

                }else{

                    echo Html::a('Разработка ГMVP >>', ['mvp/create', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
                }

            }?>
        </p>

    <?php else : ?>

        <p>
            <?php if (!empty($model->confirm)) : ?>
                <?= Html::a('Подтверждение ГЦП', ['confirm-gcp/view', 'id' => $model->confirm->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>

            <?php if ($model->exist_confirm == 1){

                if (!empty($model->confirm->mvps)){

                    echo Html::a('Разработка ГMVP >>', ['mvp/index', 'id' => $model->confirm->id], ['class' => 'btn btn-default']);
                }
            }?>
        </p>

    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'description:ntext',

                    [
                        'attribute' => 'date_create',
                        'format' => ['date', 'dd.MM.yyyy'],
                    ],

                    [
                        'attribute' => 'exist_confirm',
                        'label' => 'Подтверждение гипотезы',
                        'visible' => ($model->exist_confirm !== null),
                        'value' => function($model){
                            if ($model->exist_confirm == 0){
                                return '<span style="color:red; font-size: 13px; font-weight: 700;">ГЦП не подтверждена!</span>';
                            }
                            if ($model->exist_confirm == 1){
                                return '<span style="color:green; font-size: 13px; font-weight: 700;">ГЦП подтверждена!</span>';
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

            <div style="margin-top: -10px;"><?= Html::a('<< Разработка ГЦП', ['index', 'id' => $confirmProblem->id], ['class' => 'btn btn-default']) ?></div>

            <div style="font-style: italic;margin-top: 20px;"><span class="bolder">ГЦП*</span> - гипотеза ценностного предложения.</div>

        </div>
    </div>


</div>
