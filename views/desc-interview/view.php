<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DescInterview */

$this->title = 'Материалы интервью';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $respond->interview_id]];
$this->params['breadcrumbs'][] = ['label' => 'Респондент: ' . $respond->name, 'url' => ['respond/view', 'id' => $respond->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="desc-interview-view">

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <h2><?= Html::encode($this->title  . ': ' . $respond->name) ?></h2>

        <p>
            <?= Html::a('<< Общие данные респондента', ['respond/view', 'id' => $model->respond_id], ['class' => 'btn btn-default']) ?>
            <?= Html::a('Редактировать интервью', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?/*= Html::a('Удалить', ['delete', 'id' => $model->respond_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы точно хотите удалить материалы интервью?',
                    'method' => 'post',
                ],
            ]) */?>
        </p>

    <?php else : ?>

        <h2><?= Html::encode($this->title  . ': ' . $respond->name) ?></h2>

        <p>
            <?= Html::a('<< Общие данные респондента', ['respond/view', 'id' => $model->respond_id], ['class' => 'btn btn-default']) ?>
        </p>

    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">

            <?= DetailView::widget([
                'model' => $model,

                'attributes' => [
                    //'id',
                    //'respond_id',
                    [
                        'attribute' => 'date_fact',
                        'format' => ['date', 'dd.MM.yyyy'],

                    ],

                    'description:ntext',

                    [
                        'attribute' => 'interview_file',
                        'value' => function($model){
                            if (!empty($model->interview_file)){
                                $string = '';
                                $string .= Html::a($model->interview_file, ['download', 'id' => $model->id], ['class' => '']);
                                return $string;
                            }
                        },
                        'visible' => !empty($model->interview_file),
                        'format' => 'html',
                    ],

                    'result',

                    [
                        'attribute' => 'status',
                        'value' => !$model->status ? '<span style="color:red">Нет</span>' : '<span style="color:green">Да</span>',
                        'format' => 'html',
                    ],
                ],
            ]) ?>

        </div>
    </div>

</div>
