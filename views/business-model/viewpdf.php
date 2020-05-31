<?php

$this->title = 'Бизнес-модель';

?>

<div class="business-model-view-export">

    <h2><?= $this->title; ?></h2>

    <table>

        <tr>
            <td rowspan="" class="block-200-export">
                <h4>Потребительский сегмент</h4><?= $model->segment->name; ?>
            </td>
            <td rowspan="2" class="block-200-export">
                <h4>Ключевые партнеры: </h4><?= $model->partners; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h4>Ключевые виды деятельности</h4><?= mb_strtolower($model->segment->sort_of_activity); ?>
            </td>
            <td rowspan="2" class="block-200-export">
                <h4>Ценностное предложение</h4><?= $model->gcp->description; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h4>Взаимоотношения с клиентами</h4><?= $model->relations; ?>
            </td>
        </tr>

        <tr>
            <td rowspan="" class="block-200-export">
                <h4>Потенциальное количество потребителей</h4>
                <?= ' от ' . number_format($model->segment->quantity_from * 1000, 0, '', ' ') .
                '<br> до ' . number_format($model->segment->quantity_to * 1000, 0, '', ' '); ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h4>Ключевые ресурсы</h4><?= $model->resources; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h4>Каналы коммуникации и сбыта</h4><?= $model->distribution_of_sales; ?>
            </td>
        </tr>

        <tr>
            <td colspan="3" class="block-100-export">
                <h4 style="color: #3c3c3c">Структура издержек</h4><?= $model->cost; ?>
            </td>
            <td colspan="2" class="block-100-export">
                <h4 style="color: #3c3c3c">Потоки поступления доходов</h4><?= $model->revenue; ?>
            </td>
        </tr>

    </table>

</div>