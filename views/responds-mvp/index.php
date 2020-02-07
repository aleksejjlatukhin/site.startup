<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Информация о респондентах';
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
?>
<div class="responds-mvp-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <br>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" style="width: 20px;text-align: center;">№</th>
            <th scope="col" style="width: 180px;text-align: center;">Респонденты</th>
            <th scope="col" style="width: 250px;text-align: center;">Данные респондента</th>
            <th scope="col" style="text-align: center; width: 140px;">Дата опроса</th>
            <th scope="col" style="width: 250px;text-align: center;">Электронная почта</th>
        </tr>
        </thead>
        <tbody>
        <? $j = 0;?>
        <?php foreach ($models as $model) : ?>
            <?php $j++;?>
            <tr>
                <th scope="row"><?= $j; ?></th>

                <td>
                    <?php
                    if (!empty($model->name)){
                        $name = $model->name;
                        if (mb_strlen($name) > 30){

                            $name = mb_substr($model->name, 0, 30) . '...';
                        }
                        echo Html::a(Html::encode($name), Url::to(['view', 'id' => $model->id]));
                    }
                    ?>
                </td>

                <td><?if (!empty($model->info_respond)) {
                        echo $model->info_respond;
                    }?></td>

                <td class="text-center"><? if (!empty($model->descInterview->date_fact)){
                        $date_fact = date("d.m.Y", strtotime($model->descInterview->date_fact));
                        echo Html::a(Html::encode($date_fact), Url::to(['desc-interview-mvp/view', 'id' => $model->descInterview->id]));
                    } ?></td>

                <td class="text-center"><? if (!empty($model->email)){
                        echo $model->email;
                    } ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

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

        <?php ActiveForm::end(); ?>

    </div>

    <hr>
    <?= Html::a('Вернуться на страницу подтверждения', ['confirm-mvp/view', 'id' => $confirmMvp->id], ['class' => 'btn btn-default']) ?>


</div>
