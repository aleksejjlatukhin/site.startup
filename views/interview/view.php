<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Interview */

$this->title = 'Генерация ПИ - исходные данные';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="interview-view">

    <h3>Постановка задачи</h3>

    <?= DetailView::widget([
        'model' => $segment,
        'attributes' => [
            'quantity',
            'market_volume',
            'name',
            'field_of_activity:ntext',
            'sort_of_activity:ntext',
            'age',
            'income',
            [
                'attribute' => 'add_info',
                'visible' => !empty($segment->add_info),
            ],
        ],
    ]) ?>

    <br>

    <p> <h3>Исходные данные интервью</h3>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить интервью для "' . $segment->name . '" ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'count_respond',
            'greeting_interview',
            'view_interview',
            'reason_interview',
        ],
    ]) ?>

    <div class="d-inline p-2 bg-success" style="font-size: 18px;border-radius: 5px;height: 50px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Примерный список вопросов для проведения интервью</div>

    <?php
    $j = 0;
    if (!empty($model->questions)){
        foreach ($model->questions as $question){
            if ($question->status == 1){
                $j++;
                echo '<div class=""><b>' . $j . '.</b> ' . $question->title . '</div>' . '<br>';
            }
        }
    }else{
        echo "Вопросов пока нет...";
    }

    ?>

    <div class="d-inline p-2 bg-success" style="font-size: 18px;border-radius: 5px;height: 50px;padding-top: 12px;padding-left: 20px;margin-bottom: 20px;">Список респондентов</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            [
                'header' => '№',
                'class' => 'yii\grid\SerialColumn'
            ],

            //'name',
            [
                'attribute' => 'name',
                'value' => function ($responds) {
                    if (mb_strlen($responds->name) > 30){

                        $responds->name = mb_substr($responds->name, 0, 30) . '...';
                    }
                    return Html::a(Html::encode($responds->name), Url::to(['respond/view', 'id' => $responds->id]));
                },
                'options' => ['width' => '130'],
                'format' => 'raw',
            ],
            'info_respond',
            'add_info',
            //'date_interview',
            [
                'attribute' => 'date_interview',
                'options' => ['width' => '70'],
                'format' => ['date', 'dd.MM.yyyy'],
            ],
            'place_interview',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <p class="open_fast">
        <?= Html::submitButton('Добавить респондента', ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="popap_fast">

        <?php $form = ActiveForm::begin(); ?>

        <div class="col-sm-9">
            <?= $form->field($newRespond, 'name')->textInput(['maxlength' => true])->label('Напишите Ф.И.О. респондента') ?>
        </div>

        <span class="cross-out glyphicon text-danger glyphicon-remove"></span>

        <div class="col-sm-12 form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


        <?php ActiveForm::end(); ?>

</div>


