<?php

use yii\helpers\Html;
use app\models\Segment;

?>

<div class="segment-index-export">

    <!--Заголовки для списка сегментов-->
    <table class="all_headers_data_segments">

        <tr>
            <td class="block_segmnet_name" colspan="2">Наименование сегмента</td>
            <td class="block_segmnet_type">Тип</td>
            <td class="block_segmnet_params">Сфера деятельности</td>
            <td class="block_segmnet_params">Вид деятельности</td>
            <td class="block_segmnet_params">Специализация</td>
            <td class="block_segmnet_market_volume">
                <div>Платеже- способность</div>
                <div>млн. руб./год</div>
            </td>
        </tr>

    </table>

    <table class="block_all_segments">

        <?php foreach ($models as $model) : ?>

        <tr>

            <td class="block_segmnet_status">
                <?php
                if ($model->exist_confirm === 1) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/positive-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                }elseif ($model->exist_confirm === null && empty($model->interview)) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                }elseif ($model->exist_confirm === null && !empty($model->interview)) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/next-step.png', ['style' => ['width' => '20px']]) . '</div>';

                }elseif ($model->exist_confirm === 0) {

                    echo '<div class="" style="padding: 0 5px;">' . Html::img('@web/images/icons/danger-offer.png', ['style' => ['width' => '20px',]]) . '</div>';

                }
                ?>
            </td>

            <td class="block_segmnet_name"><?= $model->name; ?></td>

            <td class="block_segmnet_type">
                <?php

                if ($model->type_of_interaction_between_subjects === Segment::TYPE_B2C) {
                    echo '<div class="">B2C</div>';
                }
                elseif ($model->type_of_interaction_between_subjects === Segment::TYPE_B2B) {
                    echo '<div class="">B2B</div>';
                }

                ?>
            </td>

            <td class="block_segmnet_params"><?= $model->field_of_activity; ?></td>
            <td class="block_segmnet_params"><?= $model->sort_of_activity; ?></td>
            <td class="block_segmnet_params"><?= $model->specialization_of_activity; ?></td>
            <td class="block_segmnet_market_volume"><?= number_format($model->market_volume, 0, '', ' '); ?></td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>