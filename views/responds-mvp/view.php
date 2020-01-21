<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsMvp */

$this->title = 'Респондент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ПИ - исходные данные', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $generationProblem->title, 'url' => ['generation-problem/view', 'id' => $generationProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $generationProblem->title, 'url' => ['confirm-problem/view', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица ГЦП', 'url' => ['gcp/index', 'id' => $confirmProblem->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание: ' . $gcp->title, 'url' => ['gcp/view', 'id' => $gcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $gcp->title, 'url' => ['confirm-gcp/view', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Таблица MVP', 'url' => ['mvp/index', 'id' => $confirmGcp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Описание ' . $mvp->title, 'url' => ['mvp/view', 'id' => $mvp->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа подтверждения ' . $mvp->title, 'url' => ['confirm-mvp/view', 'id' => $confirmMvp->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="responds-mvp-view">

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

        <?if(!($desc_interview->responds_mvp_id == $model->id)){
            echo Html::a('Добавить анкету', ['desc-interview-mvp/create', 'id' => $model->id], ['class' => 'btn btn-success pull-right']);
        }else{
            echo Html::a('материалы анкеты', ['desc-interview-mvp/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success pull-right']);
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
                'label' => 'Фактическая дата интервью',
                'value' => function($model){
                    return $model->descInterview->date_fact;
                },
                'visible' => !empty($model->descInterview->date_fact),
                'format' => ['date', 'dd.MM.yyyy'],
            ],


            [
                'attribute' => 'interview_status',
                'label' => 'Значимость предложения',
                'value' => function($model){
                    if ($model->descInterview->status == 0){
                        return '<span style="color:red">Неинтересно</span>';
                    }
                    if ($model->descInterview->status == 1){
                        return '<span>Привлекательно</span>';
                    }
                    if ($model->descInterview->status == 2){
                        return '<span style="color:green">Хочу купить</span>';
                    }

                },
                'visible' => !empty($model->descInterview),
                'format' => 'html',
            ],
        ],
    ]) ?>

    <?= Html::a('Вернуться на страницу подтверждения', ['confirm-mvp/view', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-default']) ?>

    <?= Html::a('Информация о респондентах', ['responds-mvp/index', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-default pull-right']) ?>


</div>
