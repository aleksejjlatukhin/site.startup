<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsConfirm */

$this->title = 'Респондент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="responds-confirm-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Редактировать данные', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить респондента', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить респондента "' . $model->name . '"?',
                'method' => 'post',
            ],
        ]) ?>
        <?if(!($desc_interview->responds_confirm_id == $model->id)){
            echo Html::a('Добавить анкету', ['desc-interview-confirm/create', 'id' => $model->id], ['class' => 'btn btn-success pull-right']);
        }else{
            echo Html::a('материалы анкеты', ['desc-interview-confirm/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success pull-right']);
        }?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'name',
                'label' => 'Ф.И.О. респондента'
            ],
            'info_respond',

            [
                'attribute' =>'email',
                'visible' => ($model->email != null ),
            ],

            [
                'attribute' => 'date_fact',
                'label' => 'Дата опроса',
                'value' => function($model){
                    return $model->descInterview->date_fact;
                },
                'visible' => !empty($model->descInterview->date_fact),
                'format' => ['date', 'dd.MM.yyyy'],
            ],


            [
                'attribute' => 'interview_status',
                'label' => 'Значимость проблемы',
                'value' => function($model){
                    return !$model->descInterview->status ? '<span style="color:red">Проблемы не существует или она малозначимая</span>' : '<span style="color:green">Значимая проблема</span>';
                },
                'visible' => !empty($model->descInterview),
                'format' => 'html',
            ],

        ],
    ]) ?>

    <?= Html::a('Вернуться на страницу подтверждения', ['confirm-problem/view', 'id' => $model->confirm_problem_id], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Информация о респондентах', ['responds-confirm/index', 'id' => $model->confirm_problem_id], ['class' => 'btn btn-default pull-right']) ?>


</div>
