<?php

use app\models\Segment;

$this->title = 'Бизнес-модель';

?>

<div class="business-model-view-export">

    <h2><?= $this->title; ?></h2>

    <table>

        <tr>
            <td rowspan="2" class="block-200-export">
                <h5 style="text-transform: uppercase;">Ключевые партнеры</h5>
                <?= $model->partners; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h5 style="text-transform: uppercase;">Ключевые направления</h5>

                <div class="export_business_model_mini_header">Тип взаимодейстивия с рынком:</div>
                <?php
                if ($model->segment->type_of_interaction_between_subjects == Segment::TYPE_B2C) {
                    echo 'В2С (бизнес-клиент)';
                } else {
                    echo 'B2B (бизнес-бизнес)';
                }
                ?>

                <div class="export_business_model_mini_header">Сфера деятельности:</div>
                <?= $model->segment->field_of_activity; ?>

                <div class="export_business_model_mini_header">Вид деятельности:</div>
                <?= $model->segment->sort_of_activity; ?>

                <div class="export_business_model_mini_header">Специализация вида деятельности:</div>
                <?= $model->segment->specialization_of_activity; ?>

            </td>
            <td rowspan="2" class="block-200-export">
                <h5 style="text-transform: uppercase;">Ценностное предложение</h5><?= $model->gcp->description; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h5 style="text-transform: uppercase;">Взаимоотношения с клиентами</h5><?= $model->relations; ?>
            </td>
            <td rowspan="2" class="block-200-export">

                <h5 style="text-transform: uppercase;">Потребительский сегмент</h5>

                <div class="export_business_model_mini_header">Наименование:</div>
                <?= $model->segment->name; ?>

                <div class="export_business_model_mini_header">Краткое описание:</div>
                <?= $model->segment->description; ?>

                <div class="export_business_model_mini_header">Потенциальное количество потребителей:</div>
                <?= ' от ' . number_format($model->segment->quantity_from * 1000, 0, '', ' ') .
                ' до ' . number_format($model->segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>

                <div class="export_business_model_mini_header">Объем рынка:</div>
                <?= number_format($model->segment->market_volume * 1000000, 0, '', ' ') . ' рублей'; ?>

            </td>
        </tr>

        <tr>
            <td rowspan="" class="block-200-export">
                <h5 style="text-transform: uppercase;">Ключевые ресурсы</h5><?= $model->resources; ?>
            </td>
            <td rowspan="" class="block-200-export">
                <h5 style="text-transform: uppercase;">Каналы коммуникации и сбыта</h5><?= $model->distribution_of_sales; ?>
            </td>
        </tr>

    </table>

    <table>
        <tr>
            <td colspan="" class="block-100-export">
                <h5 style="text-transform: uppercase;">Структура издержек</h5><?= $model->cost; ?>
            </td>
            <td colspan="" class="block-100-export">
                <h5 style="text-transform: uppercase;">Потоки поступления доходов</h5><?= $model->revenue; ?>
            </td>
        </tr>
    </table>

</div>