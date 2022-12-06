<?php

use app\models\Segments;
use yii\helpers\Html;

/**
 * @var Segments $segment
 */

?>

<div class="block_export_link_hypothesis">
    <?= Html::a('<div style="margin-top: -15px;">Исходные данные сегмента' . Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px', 'margin-left' => '10px', 'margin-bottom' => '10px']]) . '</div>', [
        '/segments/mpdf-segment', 'id' => $segment->getId()], [
        'class' => 'export_link_hypothesis',
        'target' => '_blank',
        'title' => 'Скачать в pdf',
    ]); ?>
</div>

<div class="row container-fluid" style="color: #4F4F4F;">

    <div style="font-weight: 700;">Наименование сегмента</div>
    <div style="margin-bottom: 10px;"><?= $segment->getName() ?></div>

    <div style="font-weight: 700;">Краткое описание сегмента</div>
    <div style="margin-bottom: 10px;"><?= $segment->getDescription() ?></div>


    <?php if ($segment->getTypeOfInteractionBetweenSubjects() === Segments::TYPE_B2C) : ?>

        <div style="font-weight: 700;">Тип взаимодействия с потребителями</div>
        <div style="margin-bottom: 10px;">Коммерческие взаимоотношения между организацией и частным потребителем (B2C)</div>

        <div style="font-weight: 700;">Сфера деятельности потребителя</div>
        <div style="margin-bottom: 10px;"><?= $segment->getFieldOfActivity() ?></div>

        <div style="font-weight: 700;">Вид / специализация деятельности потребителя</div>
        <div style="margin-bottom: 10px;"><?= $segment->getSortOfActivity() ?></div>

        <div style="font-weight: 700;">Возраст потребителя</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->getAgeFrom(), 0, '', ' ') . ' до '
            . number_format($segment->getAgeTo(), 0, '', ' ') . ' лет' ?>
        </div>

        <div style="font-weight: 700;">Пол потребителя</div>
        <div style="margin-bottom: 10px;">
            <?php
            if ($segment->getGenderConsumer() === Segments::GENDER_WOMAN) {
                echo 'Женский';
            } elseif ($segment->getGenderConsumer() === Segments::GENDER_MAN) {
                echo 'Мужской';
            } else {
                echo 'Не важно';
            }
            ?>
        </div>

        <div style="font-weight: 700;">Образование потребителя</div>
        <div style="margin-bottom: 10px;">
            <?php
            if ($segment->getEducationOfConsumer() === Segments::SECONDARY_EDUCATION) {
                echo 'Среднее образование';
            }elseif ($segment->getEducationOfConsumer() === Segments::SECONDARY_SPECIAL_EDUCATION) {
                echo 'Среднее образование (специальное)';
            }elseif ($segment->getEducationOfConsumer() === Segments::HIGHER_INCOMPLETE_EDUCATION) {
                echo 'Высшее образование (незаконченное)';
            }elseif ($segment->getEducationOfConsumer() === Segments::HIGHER_EDUCATION) {
                echo 'Высшее образование';
            }else {
                echo '';
            }
            ?>
        </div>

        <div style="font-weight: 700;">Доход потребителя</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->getIncomeFrom(), 0, '', ' ') . ' до '
            . number_format($segment->getIncomeTo(), 0, '', ' ') . ' руб./мес.' ?>
        </div>

        <div style="font-weight: 700;">Потенциальное количество потребителей</div>
        <div style="margin-bottom: 10px;">
            <?= number_format($segment->getQuantity() * 1000, 0, '', ' ') . ' человек' ?>
        </div>

    <?php elseif ($segment->getTypeOfInteractionBetweenSubjects() === Segments::TYPE_B2B) : ?>

        <div style="font-weight: 700;">Тип взаимодействия с потребителями</div>
        <div style="margin-bottom: 10px;">Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)</div>

        <div style="font-weight: 700;">Сфера деятельности предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->getFieldOfActivity() ?></div>

        <div style="font-weight: 700;">Вид / специализация деятельности предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->getSortOfActivity() ?></div>

        <div style="font-weight: 700;">Продукция / услуги предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->getCompanyProducts() ?></div>

        <div style="font-weight: 700;">Партнеры предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->getCompanyPartner() ?></div>

        <div style="font-weight: 700;">Потенциальное количество представителей сегмента</div>
        <div style="margin-bottom: 10px;">
            <?= number_format($segment->getQuantity(), 0, '', ' ') ?>
        </div>

        <div style="font-weight: 700;">Доход предприятия</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->getIncomeFrom(), 0, '', ' ') . ' до '
            . number_format($segment->getIncomeTo(), 0, '', ' ') . ' млн. руб./год' ?>
        </div>

    <?php endif; ?>


    <div style="font-weight: 700;">Платежеспособность целевого сегмента</div>
    <div style="margin-bottom: 10px;"><?= number_format($segment->getMarketVolume(), 0, '', ' ') . ' млн. руб./год' ?></div>


    <?php if (!empty($segment->getAddInfo())) : ?>

        <div style="font-weight: 700;">Дополнительная информация</div>
        <div style="margin-bottom: 10px;"><?= $segment->getAddInfo() ?></div>

    <?php endif; ?>

</div>
