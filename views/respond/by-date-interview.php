<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проверка стадии проведения интервью';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index', 'id' => $project->user_id]];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['segment/index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $segment->name, 'url' => ['segment/view', 'id' => $segment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Программа генерации ГПС', 'url' => ['interview/view', 'id' => $interview->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="respond by-date-interview">

    <h2 style="margin-bottom: 15px;">
        <span style="margin-right: 30px;"><?= Html::encode($this->title) ?></span>
        <?= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-sm btn-default']) ?>
    </h2>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="width: 20px;padding-bottom: 25px;text-align: center;">№</th>
            <th scope="col" rowspan="2" style="width: 180px;text-align: center;padding-bottom: 25px">Респонденты</th>
            <th scope="col" colspan="2" style="text-align: center; width: 140px;">Дата интервью</th>
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
            <tr class="text-center">
                <th scope="row" style="text-align: center"><?= $j; ?></th>

                <td style="font-weight: 700;">
                    <?php
                    if (!empty($model->name)){
                        $name = $model->name;
                        if (mb_strlen($name) > 50){

                            $name = mb_substr($model->name, 0, 50) . '...';
                        }
                        echo Html::a(Html::encode($name), Url::to(['view', 'id' => $model->id]));
                    }
                    ?>
                </td>

                <td>
                    <?if (!empty($model->date_plan)) {
                        echo date("d.m.Y", $model->date_plan);
                    }?>
                </td>

                <td style="font-weight: 700;">
                    <? if (!empty($model->descInterview->date_fact)){
                        $date_fact = date("d.m.Y", strtotime($model->descInterview->date_fact));
                        echo Html::a(Html::encode($date_fact), Url::to(['desc-interview/view', 'id' => $model->descInterview->id]));
                    } ?>
                </td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?//= Html::a('<< Программа генерации ГПС', ['interview/view', 'id' => $interview->id], ['class' => 'btn btn-default']) ?>
</div>
