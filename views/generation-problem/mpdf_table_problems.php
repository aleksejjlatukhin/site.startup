<?php

use yii\helpers\Html;

?>

<div class="problem-index-export">

    <!--Заголовки для списка проблем-->
    <table class="all_headers_data_problems">

        <tr>
            <td class="block_problem_title" colspan="2">Обознач.</td>
            <td class="block_problem_description">Описание гипотезы проблемы сегмента</td>
            <td class="block_problem_params">Действие для проверки</td>
            <td class="block_problem_params">Метрика результата</td>
            <td class="block_problem_date">Дата создания</td>
            <td class="block_problem_date">Дата подтв.</td>
        </tr>

    </table>

    <table class="block_all_problems">

        <?php foreach ($models as $model) : ?>

        <tr>

            <td class="block_problem_status">
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

            <td class="block_problem_title"><?= $model->title; ?></td>
            <td class="block_problem_description"><?= $model->description; ?></td>
            <td class="block_problem_params"><?= $model->action_to_check; ?></td>
            <td class="block_problem_params"><?= $model->result_metric; ?></td>
            <td class="block_problem_date"><?= date("d.m.y", $model->created_at); ?></td>

            <td class="block_problem_date">
                <?php if ($model->time_confirm) : ?>
                    <?= date("d.m.y", $model->time_confirm); ?>
                <?php endif; ?>
            </td>

        </tr>

        <?php endforeach; ?>

    </table>

</div>
