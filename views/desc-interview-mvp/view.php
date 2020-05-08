<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterviewMvp */

$this->title = 'Анкета респондента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
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
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $mvp->title, 'url' => ['confirm-mvp/view', 'id' => $confirmMvp->id]];
$this->params['breadcrumbs'][] = ['label' => $respond->name, 'url' => ['responds-mvp/view', 'id' => $respond->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="desc-interview-mvp-view">

    <h2><?= Html::encode($this->title . ': ' . $respond->name) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <p>
            <?= Html::a('<< Общие данные респондента', ['responds-mvp/view', 'id' => $model->responds_mvp_id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Редактировать анкету', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>

    <?php else : ?>

        <p>
            <?= Html::a('<< Общие данные респондента', ['responds-mvp/view', 'id' => $model->responds_mvp_id], ['class' => 'btn btn-default']) ?>
        </p>

    <?php endif; ?>


    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'date_fact',
                        'format' => ['date', 'dd.MM.yyyy'],

                    ],

                    [
                        'attribute' => 'status',
                        'value' => function($model){
                            if ($model->status == 0){
                                return '<span style="color:red">Неинтересно</span>';
                            }
                            if ($model->status == 1){
                                return '<span>Привлекательно</span>';
                            }
                            if ($model->status == 2){
                                return '<span style="color:green">Хочу купить</span>';
                            }
                        },
                        'format' => 'html',
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
