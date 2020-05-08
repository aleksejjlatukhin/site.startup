<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterviewConfirm */

$this->title = 'Анкета респондента';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Респондент: ' . $respond->name, 'url' => ['responds-confirm/view', 'id' => $respond->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="desc-interview-confirm-view">

    <h2><?= Html::encode($this->title  . ': ' . $respond->name) ?></h2>

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <p>
            <?= Html::a('<< Общие данные респондента', ['responds-confirm/view', 'id' => $model->responds_confirm_id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Редактировать анкету', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?/*= Html::a('Удалить', ['delete', 'id' => $model->responds_confirm_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы точно хотите удалить материалы интервью?',
                'method' => 'post',
            ],
        ]) */?>
        </p>

    <?php else : ?>

        <p>
            <?= Html::a('<< Общие данные респондента', ['responds-confirm/view', 'id' => $model->responds_confirm_id], ['class' => 'btn btn-default']) ?>
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
                        'value' => !$model->status ? '<span style="color:red">Проблемы не существует или она малозначимая</span>' : '<span style="color:green">Значимая проблема</span>',
                        'format' => 'html',
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
