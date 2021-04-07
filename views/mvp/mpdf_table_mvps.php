<?php

use yii\helpers\Html;

?>

<div class="mvp-index-export">

    <!--Заголовки для списка MVP-->
    <table class="all_headers_data_mvps">

        <tr>
            <td class="block_mvp_title" colspan="2">Обознач.</td>
            <td class="block_mvp_description">Описание минимально жизнеспособного продукта</td>
            <td class="block_mvp_date">Дата создания</td>
            <td class="block_mvp_date">Дата подтв.</td>
        </tr>

    </table>

    <table class="block_all_mvps">

        <?php foreach ($models as $model) : ?>

            <tr>

                <td class="block_mvp_status">
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

                <td class="block_mvp_title"><?= $model->title; ?></td>
                <td class="block_mvp_description"><?= $model->description; ?></td>
                <td class="block_mvp_date"><?= date("d.m.y", $model->created_at); ?></td>

                <td class="block_mvp_date">
                    <?php if ($model->time_confirm) : ?>
                        <?= date("d.m.y", $model->time_confirm); ?>
                    <?php endif; ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</div>
