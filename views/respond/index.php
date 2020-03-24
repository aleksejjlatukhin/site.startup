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
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond-index">

    <h2>
        <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
        <?= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-sm btn-default']) ?>
    </h2>


    <table class="table table-bordered table-striped" style="margin-top: 15px;">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="width: 20px;padding-bottom: 25px;text-align: center;">№</th>
            <th scope="col" rowspan="2" style="width: 180px;text-align: center;padding-bottom: 25px">Респонденты</th>
            <th scope="col" rowspan="2" style="width: 250px;text-align: center;padding-bottom: 25px">Данные респондента</th>
            <th scope="col" colspan="2" style="text-align: center; width: 140px;">Дата интервью</th>
            <th scope="col" rowspan="2" style="width: 250px;text-align: center;padding-bottom: 25px">Место проведения</th>
            <th scope="col" rowspan="2" style="width: 250px;text-align: center;padding-bottom: 25px">Варианты проблем</th>
        </tr>
        <tr class="text-center">
            <td style="width: 70px;">План</td>
            <td style="width: 70px;">Факт</td>
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

                <td class="text-center"><?if (!empty($model->date_plan)) {
                        echo date("d.m.Y", $model->date_plan);
                    }?></td>

                <td class="text-center"><? if (!empty($model->descInterview->date_fact)){
                        $date_fact = date("d.m.Y", strtotime($model->descInterview->date_fact));
                        echo Html::a(Html::encode($date_fact), Url::to(['desc-interview/view', 'id' => $model->descInterview->id]));
                    } ?></td>

                <td><? if (!empty($model->place_interview)){
                        echo $model->place_interview;
                    } ?></td>

                <td><? if (!empty($model->descInterview)){
                        echo $model->descInterview->result;
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

</div>
