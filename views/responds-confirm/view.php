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
$this->params['breadcrumbs'][] = ['label' => 'Описание программы ППИ', 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
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
            echo Html::a('Добавить интервью', ['desc-interview-confirm/create', 'id' => $model->id], ['class' => 'btn btn-success pull-right']);
        }else{
            echo Html::a('материалы интервью', ['desc-interview-confirm/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success pull-right']);
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
            'place_interview',

            [
                'attribute' => 'date_plan',
                'label' => 'Запланированная дата интервью',
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'date_fact',
                'label' => 'Фактическая дата интервью',
                'value' => function($model){
                    return $model->descInterview->date_fact;
                },
                'visible' => !empty($model->descInterview->date_fact),
                'format' => ['date', 'dd.MM.yyyy'],
            ],

            [
                'attribute' => 'description',
                'label' => 'Материалы интервью',
                'value' => function($model){
                    return $model->descInterview->description;
                },
                'visible' => !empty($model->descInterview->description),
            ],

            [
                'attribute' => 'interview_status',
                'label' => 'Индикатор теста',
                'value' => function($model){
                    return !$model->descInterview->status ? '<span style="color:red">Не пройден</span>' : '<span style="color:green">Пройден</span>';
                },
                'visible' => !empty($model->descInterview),
                'format' => 'html',
            ],


            [
                'attribute' => 'interview_file',
                'label' => 'Файл',
                'value' => function($model){
                    $string = '';
                    $string .= Html::a($model->descInterview->interview_file, ['desc-interview-confirm/download', 'filename' => $model->descInterview->interview_file], ['class' => '']);
                    return $string;
                },
                'visible' => !empty($model->descInterview->interview_file),
                'format' => 'html',
            ],
        ],
    ]) ?>

    <?= Html::a('Вернуться к описанию программы ППИ', ['confirm-problem/view', 'id' => $model->confirm_problem_id], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Информация о респондентах', ['responds-confirm/index', 'id' => $model->confirm_problem_id], ['class' => 'btn btn-default pull-right']) ?>


</div>
