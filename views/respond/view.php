<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Respond */

$this->title = 'Респондент: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $model->interview_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-view">


    <?php if (User::isUserSimple(Yii::$app->user->identity['username']) || User::isUserDev(Yii::$app->user->identity['username'])) : ?>

        <h3 style="margin-bottom: 10px;">
            <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
            <p style="margin-top: 10px;">
                <?= Html::a('Программа генерации ГПС', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-sm btn-default']) ?>
                <?= Html::a('Информация о респондентах', ['respond/index', 'id' => $model->interview_id], ['class' => 'btn btn-sm btn-default']) ?>
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
            <?if(!($desc_interview->respond_id == $model->id)){
                echo Html::a('Добавить интервью', ['desc-interview/create', 'id' => $model->id], ['class' => 'btn btn-success']);
            }else{
                echo Html::a('материалы интервью', ['desc-interview/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success']);
            }?>
        </p>

    <?php else : ?>

        <h3 style="margin-bottom: 10px;">
            <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
            <p style="margin-top: 10px;">
                <?= Html::a('Программа генерации ГПС', ['interview/view', 'id' => $model->interview_id], ['class' => 'btn btn-default']) ?>
                <?= Html::a('Информация о респондентах', ['respond/index', 'id' => $model->interview_id], ['class' => 'btn btn-default']) ?>

                <?if(!($desc_interview->respond_id == $model->id)){
                    //echo Html::a('Добавить интервью', ['desc-interview/create', 'id' => $model->id], ['class' => 'btn btn-success']);
                }else{
                    echo Html::a('материалы интервью', ['desc-interview/view', 'id' => $desc_interview->id], ['class' => 'btn btn-success']);
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
                'visible' => ($model->email != null),
            ],

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
                'attribute' => 'interview_file',
                'label' => 'Файл',
                'value' => function($model){
                    $string = '';
                    $string .= Html::a($model->descInterview->interview_file, ['desc-interview/download', 'id' => $model->descInterview->id], ['class' => '']);
                    return $string;
                },
                'visible' => !empty($model->descInterview->interview_file),
                'format' => 'html',
            ],

            [
                'attribute' => 'result',
                'label' => 'Вывод из интервью',
                'value' => function($model){
                    return $model->descInterview->result;
                },
                'visible' => !empty($model->descInterview->result),
            ],

            [
                'attribute' => 'respond_status',
                'label' => 'Является ли респондент представителем сегмента?',
                'value' => function($model){
                    return !$model->descInterview->status ? '<span style="color:red">Нет</span>' : '<span style="color:green">Да</span>';
                },
                'visible' => !empty($model->descInterview),
                'format' => 'html',
            ],

        ],
    ]) ?>

</div>
