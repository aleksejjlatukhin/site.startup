<?php

use yii\helpers\Html;
use app\models\Segment;

?>


<div class="row" style="margin: 0;">

    <div class="col-md-9 text_update_page">

        <?php echo Html::a( 'Бизнес-модель' . Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px', 'margin-left' => '10px', 'margin-bottom' => '10px']]), ['/business-model/mpdf-business-model', 'id' => $model->id], [
            'class' => 'export_link',
            'target' => '_blank',
            'title' => 'Скачать в pdf',
        ]);?>
    </div>

    <div class="button-update col-md-3">

        <?= Html::a('Редактировать', ['/business-model/get-hypothesis-to-update', 'id' => $model->id], [
            'class' => 'btn btn-default update-hypothesis',
            'style' => [
                'color' => '#FFFFFF',
                'background' => '#52BE7F',
                'padding' => '0 7px',
                'width' => '190px',
                'height' => '40px',
                'font-size' => '24px',
                'border-radius' => '8px',
            ],
        ])?>
    </div>
</div>

<div class="blocks_business_model">

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Ключевые партнеры</h5>
            <div><?= $model->partners; ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">

            <h5>Ключевые направления</h5>

            <div class="mini_header_desc_block">Тип взаимодейстивия с рынком:</div>
            <?php
            if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C) {
                echo 'В2С (бизнес-клиент)';
            } else {
                echo 'B2B (бизнес-бизнес)';
            }
            ?>

            <div class="mini_header_desc_block">Сфера деятельности:</div>
            <?= $segment->field_of_activity; ?>

            <div class="mini_header_desc_block">Вид деятельности:</div>
            <?= $segment->sort_of_activity; ?>

            <div class="mini_header_desc_block">Специализация вида деятельности:</div>
            <?= $segment->specialization_of_activity; ?>

        </div>

        <div class="desc_block_20">
            <h5>Ключевые ресурсы</h5>
            <div><?= $model->resources; ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Ценностное предложение</h5>
            <?= $gcp->description; ?>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">
            <h5>Взаимоотношения с клиентами</h5>
            <div><?= $model->relations; ?></div>
        </div>

        <div class="desc_block_20">
            <h5>Каналы коммуникации и сбыта</h5>
            <div><?= $model->distribution_of_sales; ?></div>
        </div>

    </div>

    <div class="block_20_business_model">

        <div class="desc_block_20">

            <h5>Потребительский сегмент</h5>

            <div class="mini_header_desc_block">Наименование:</div>
            <?= $segment->name; ?>

            <div class="mini_header_desc_block">Краткое описание:</div>
            <?= $segment->description; ?>

            <div class="mini_header_desc_block">Потенциальное количество потребителей:</div>
            <?= ' от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') .
            ' до ' . number_format($segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>

            <div class="mini_header_desc_block">Объем рынка:</div>
            <?= number_format($segment->market_volume * 1000000, 0, '', ' ') . ' рублей'; ?>

        </div>
    </div>

</div>

<div class="blocks_business_model">

    <div class="block_50_business_model">

        <div class="desc_block_50">
            <h5>Структура издержек</h5>
            <div><?= $model->cost; ?></div>
        </div>

    </div>

    <div class="block_50_business_model">

        <div class="desc_block_50">
            <h5>Потоки поступления доходов</h5>
            <div><?= $model->revenue; ?></div>
        </div>

    </div>

</div>
