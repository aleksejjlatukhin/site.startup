<?php

use yii\helpers\Html;

?>

<div class="gcp-index-export">

    <!--Заголовки для списка ЦП-->
    <table class="all_headers_data_gcps">

        <tr>
            <td class="block_gcp_title" colspan="2">Обознач.</td>
            <td class="block_gcp_description">Описание гипотезы ценностного предложения</td>
            <td class="block_gcp_date">Дата создания</td>
            <td class="block_gcp_date">Дата подтв.</td>
        </tr>

    </table>

    <table class="block_all_gcps">

        <?php foreach ($models as $model) : ?>

        <tr>

            <td class="block_gcp_status">
                <?php
                if ($model->exist_confirm === 1) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                }elseif ($model->exist_confirm === null && empty($model->confirm)) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                }elseif ($model->exist_confirm === null && !empty($model->confirm)) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                }elseif ($model->exist_confirm === 0) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                }
                ?>
            </td>

            <td class="block_gcp_title"><?= $model->title; ?></td>
            <td class="block_gcp_description"><?= $model->description; ?></td>
            <td class="block_gcp_date"><?= date("d.m.y", $model->created_at); ?></td>

            <td class="block_gcp_date">
                <?php if ($model->time_confirm) : ?>
                    <?= date("d.m.y", $model->time_confirm); ?>
                <?php endif; ?>
            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>
