<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дорожная карта сегмента "' . $model->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['segment/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <br><hr><br>


    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="padding-bottom: 45px">№</th>
            <th scope="col" rowspan="2" style="width: 130px;text-align: center;padding-bottom: 45px">Сегменты</th>
            <th scope="col" style="width: 90px; text-align: center">Генерация ГЦС</th>
            <th scope="col" colspan="2" style="text-align: center">Генерация ГПС</th>
            <th scope="col" colspan="2" style="text-align: center">Подтверждение ГПС</th>
            <th scope="col" colspan="2" style="text-align: center">Разработка ГЦП</th>
            <th scope="col" colspan="2" style="text-align: center">Подтверждение ГЦП</th>
            <th scope="col" colspan="2" style="text-align: center">Разработка ГMVP</th>
            <th scope="col" colspan="2" style="text-align: center">Подтверждение ГMVP</th>
        </tr>
        <tr class="text-center">
            <td>Дата создания</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
            <td style="padding-top: 20px;">План</td>
            <td style="padding-top: 20px;">Факт</td>
        </tr>
        </thead>
        <tbody>
        <? $j = 0;?>
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

                <td><?if (!empty($model->creat_date)) {
                        echo date("d.m.y", strtotime($model->creat_date));
                    }?></td>

                <td><?if (!empty($model->plan_gps)) {
                        echo date("d.m.y", strtotime($model->plan_gps));
                    }?></td>

                <td><? if (!empty($model->fact_gps)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_gps)), Url::to(['generation-problem/view', 'id' => $problem->id]));
                    } ?></td>

                <td><? if (!empty($model->plan_ps)){
                        echo date("d.m.y", strtotime($model->plan_ps));
                    } ?></td>

                <td><? if (!empty($model->fact_ps)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_ps)), Url::to(['generation-problem/view', 'id' => $confirmProblem->id]));
                    } ?></td>

                <td><? if (!empty($model->plan_dev_gcp)){
                        echo date("d.m.y", strtotime($model->plan_dev_gcp));
                    } ?></td>

                <td><? if (!empty($model->fact_dev_gcp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_dev_gcp)), Url::to(['gcp/view', 'id' => $offer->id]));
                    } ?></td>

                <td><? if (!empty($model->plan_gcp)){
                        echo date("d.m.y", strtotime($model->plan_gcp));
                    } ?></td>

                <td><? if (!empty($model->fact_gcp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_gcp)), Url::to(['gcp/view', 'id' => $confirmGcp->id]));
                    } ?></td>

                <td><? if (!empty($model->plan_dev_gmvp)){
                        echo date("d.m.y", strtotime($model->plan_dev_gmvp));
                    } ?></td>

                <td><? if (!empty($model->fact_dev_gmvp)){
                        echo date("d.m.y", strtotime($model->fact_dev_gmvp));
                    } ?></td>

                <td><? if (!empty($model->plan_gmvp)){
                        echo date("d.m.y", strtotime($model->plan_gmvp));
                    } ?></td>

                <td><? if (!empty($model->fact_gmvp)){
                        echo date("d.m.y", strtotime($model->fact_gmvp));
                    } ?></td>

            </tr>
        </tbody>
    </table>


</div>

