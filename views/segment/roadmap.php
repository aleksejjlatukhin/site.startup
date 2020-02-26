<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дорожная карта проекта "' . mb_strtolower($project->project_name) . '"';
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты', 'url' => ['projects/index']];
$this->params['breadcrumbs'][] = ['label' => $project->project_name, 'url' => ['projects/view', 'id' => $project->id]];
$this->params['breadcrumbs'][] = ['label' => 'Генерация ГЦС', 'url' => ['index', 'id' => $project->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="segment-index">

    <h2><?= 'Дорожная карта по проекту ' . Html::a(Html::encode(mb_strtolower('"' . $project->project_name . '"')), Url::to(['projects/view', 'id' => $project->id])) ?></h2><br>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th scope="col" rowspan="2" style="vertical-align: middle;">№</th>
            <th scope="col" rowspan="2" style="width: 130px;text-align: center; vertical-align: middle;">Сегменты</th>
            <th scope="col" style="width: 90px; text-align: center; vertical-align: middle;">Генерация ГЦС</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Генерация ГПС</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Подтверждение ГПС</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Разработка ГЦП</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Подтверждение ГЦП</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Разработка ГMVP</th>
            <th scope="col" colspan="2" style="text-align: center; vertical-align: middle;">Подтверждение ГMVP</th>
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
        <?php foreach ($models as $i => $model) : ?>
        <?php $j++;?>
            <tr class="text-center">
                <th scope="row"><?= $j; ?></th>

                <td>
                    <?php
                        echo Html::a(Html::encode($model->name), Url::to(['view', 'id' => $model->id]));
                    ?>
                </td>

                <td style="vertical-align: middle;"><?if (!empty($model->creat_date)) {
                        echo date("d.m.y", strtotime($model->creat_date));
                    }?></td>

                <td style="vertical-align: middle;"><?if (!empty($model->plan_gps)) {
                        echo date("d.m.y", strtotime($model->plan_gps));
                    }?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_gps)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_gps)), Url::to(['generation-problem/view', 'id' => $problem->id]));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->plan_ps)){
                        echo date("d.m.y", strtotime($model->plan_ps));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_ps)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_ps)), Url::to(['generation-problem/view', 'id' => $confirmProblems[$i]->id]));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->plan_dev_gcp)){
                        echo date("d.m.y", strtotime($model->plan_dev_gcp));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_dev_gcp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_dev_gcp)), Url::to(['gcp/view', 'id' => $offersGcp[$i]->id]));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->plan_gcp)){
                        echo date("d.m.y", strtotime($model->plan_gcp));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_gcp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_gcp)), Url::to(['gcp/view', 'id' => $comfirmGcpses[$i]->id]));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->plan_dev_gmvp)){
                        echo date("d.m.y", strtotime($model->plan_dev_gmvp));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_dev_gmvp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_dev_gmvp)), Url::to(['mvp/view', 'id' => $mvProds[$i]->id]));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->plan_gmvp)){
                        echo date("d.m.y", strtotime($model->plan_gmvp));
                    } ?></td>

                <td style="vertical-align: middle;"><? if (!empty($model->fact_gmvp)){
                        echo Html::a(date("d.m.y", strtotime($model->fact_gmvp)), Url::to(['mvp/view', 'id' => $comfirmMvpses[$i]->id]));
                    } ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div style="display: flex; flex: auto; flex-wrap: wrap;font-size: 13px;">
        <div style="width: 570px;">
            <p><span class="bolder">Генерация ГПС</span> - этап определения гипотез проблем сегмента.</p>
            <p>План — критическая дата для определения всех гипотез проблем сегмента.</p>
            <p>Факт — дата определения последней гипотезы проблемы для заданного сегмента.</p>
            <p><span class="bolder">Подтверждение ГПС</span> - этап подтверждения гипотез проблем сегмента.</p>
            <p>План — критическая дата для первого подтверждения гипотезы проблемы сегмента.</p>
            <p>Факт — дата определения первого подтверждения гипотезы проблемы сегмента.</p>
            <p><span class="bolder">Разработка ГЦП</span> - этап определения гипотез ценностных предложений.</p>
            <p>План — критическая дата для определения всех гипотез ценностных предложений.</p>
            <p>Факт — дата определения последней гипотезы ценностного предложения.</p>
        </div>
        <div style="width: 570px;">
            <p><span class="bolder">Подтверждение ГЦП</span> - этап подтверждения гипотез ценностных предложений.</p>
            <p>План — критическая дата для первого подтверждения гипотезы ценностного предложения.</p>
            <p>Факт — дата определения первого подтверждения гипотезы ценностного предложения.</p>
            <p><span class="bolder">Разработка ГMVP</span> - этап определения гипотезы MVP.</p>
            <p>План — критическая дата для определения всех гипотез MVP.</p>
            <p>Факт — дата определения последней гипотезы MVP.</p>
            <p><span class="bolder">Подтверждение ГMVP</span> - этап подтверждения MVP.</p>
            <p>План — критическая дата для первого подтверждения гипотезы MVP.</p>
            <p>Факт — дата определения первого подтверждения гипотезы MVP.</p>
        </div>
    </div>

    <br>

    <p style="font-style: italic; font-size: 13px;"><span class="bolder">Примечание:</span> дорожная карта для сегмента начинает формировать после заполнения данных о сегменте.</p>

    <p style="font-style: italic; font-size: 13px;"><span class="bolder">MVP:</span> (Minimum Viable Product) — минимально жизнеспособный продукт, обладающий минимальными, но достаточными для удовлетворения первых потребителей функциями.</p>
</div>
