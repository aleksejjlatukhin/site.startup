<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\RespondsMvp */

$this->title = 'Респондент: ' . $model->name;
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
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="responds-mvp-view">

    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <h3 style="margin-bottom: 10px;">
            <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
            <p style="margin-top: 10px;">
                <?= Html::a('Программа подтверждения', ['confirm-mvp/view', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-sm btn-default']) ?>
                <?= Html::a('Информация о респондентах', ['responds-mvp/index', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-sm btn-default']) ?>
            </p>
        </h3>

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
                echo Html::a('Добавить анкету', ['desc-interview-mvp/create', 'id' => $model->id], ['class' => 'btn btn-success']);
            }else{
                echo Html::a('материалы анкеты', ['desc-interview-mvp/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success']);
            }?>
        </p>

    <?php else : ?>

        <h3 style="margin-bottom: 10px;">
            <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
            <p style="margin-top: 10px;">
                <?= Html::a('Программа подтверждения', ['confirm-mvp/view', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-default']) ?>
                <?= Html::a('Информация о респондентах', ['responds-mvp/index', 'id' => $model->confirm_mvp_id], ['class' => 'btn btn-default']) ?>

                <?if(!($desc_interview->responds_mvp_id == $model->id)){
                    //echo Html::a('Добавить анкету', ['desc-interview-mvp/create', 'id' => $model->id], ['class' => 'btn btn-success']);
                }else{
                    echo Html::a('материалы анкеты', ['desc-interview-mvp/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success']);
                }?>
            </p>
        </h3>

    <?php endif; ?>


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


</div>
