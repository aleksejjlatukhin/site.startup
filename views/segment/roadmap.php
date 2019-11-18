<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дорожная карта сегментов';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br><hr><br>


    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" rowspan="2">№</th>
            <th scope="col" rowspan="2">Сегменты</th>
            <th scope="col" rowspan="2">Дата создания</th>
            <th scope="col" colspan="2">Генерация ГПС</th>
            <th scope="col" colspan="2">Подтверждение ПС</th>
            <th scope="col" colspan="2">Разработка ГЦП</th>
            <th scope="col" colspan="2">Подтверждение ГЦП</th>
            <th scope="col" colspan="2">Разработка ГMVP</th>
            <th scope="col" colspan="2">Подтверждение ГMVP</th>
        </tr>
        <tr class="text-center">
            <td>План</td>
            <td>Факт</td>
            <td>План</td>
            <td>Факт</td>
            <td>План</td>
            <td>Факт</td>
            <td>План</td>
            <td>Факт</td>
            <td>План</td>
            <td>Факт</td>
            <td>План</td>
            <td>Факт</td>
        </tr>
        </thead>
        <tbody>
        <? $j = 0;?>
        <?php foreach ($models as $model) : ?>
        <?php $j++;?>
            <tr class="text-center">
                <th scope="row"><?= $j; ?></th>

                <td>
                    <?php
                    $name = $model->name;
                    if (mb_strlen($name) > 10){

                        $name = mb_substr($model->name, 0, 10) . '...';
                    }
                    echo Html::a(Html::encode($name), Url::to(['view', 'id' => $model->id]));
                    ?>
                </td>

                <td><?= date("d.m.y", strtotime($model->creat_date)); ?></td>
                <td><?= date("d.m.y", strtotime($model->plan_gps)); ?></td>
                <td><? if (!empty($model->fact_gps)){
                    echo date("d.m.y", strtotime($model->fact_gps));
                    } ?></td>

                <td><?= date("d.m.y", strtotime($model->plan_ps)); ?></td>
                <td><? if (!empty($model->fact_ps)){
                        echo date("d.m.y", strtotime($model->fact_ps));
                    } ?></td>

                <td><?= date("d.m.y", strtotime($model->plan_dev_gcp)); ?></td>
                <td><? if (!empty($model->fact_dev_gcp)){
                        echo date("d.m.y", strtotime($model->fact_dev_gcp));
                    } ?></td>

                <td><?= date("d.m.y", strtotime($model->plan_gcp)); ?></td>
                <td><? if (!empty($model->fact_gcp)){
                        echo date("d.m.y", strtotime($model->fact_gcp));
                    } ?></td>

                <td><?= date("d.m.y", strtotime($model->plan_dev_gmvp)); ?></td>
                <td><? if (!empty($model->fact_dev_gmvp)){
                        echo date("d.m.y", strtotime($model->fact_dev_gmvp));
                    } ?></td>

                <td><?= date("d.m.y", strtotime($model->plan_gmvp)); ?></td>
                <td><? if (!empty($model->fact_gmvp)){
                        echo date("d.m.y", strtotime($model->fact_gmvp));
                    } ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


</div>
