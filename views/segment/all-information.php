<?php

use app\models\Segment;

?>

<div class="row container-fluid" style="color: #4F4F4F;">

    <div style="font-weight: 700;">Наименование сегмента</div>
    <div style="margin-bottom: 10px;"><?= $segment->name; ?></div>

    <div style="font-weight: 700;">Краткое описание сегмента</div>
    <div style="margin-bottom: 10px;"><?= $segment->description; ?></div>


    <?php if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C) : ?>

        <div style="font-weight: 700;">Вид информационного и экономического взаимодействия между субъектами рынка</div>
        <div style="margin-bottom: 10px;">Коммерческие взаимоотношения между организацией и частным потребителем (B2C)</div>

        <div style="font-weight: 700;">Сфера деятельности потребителя</div>
        <div style="margin-bottom: 10px;"><?= $segment->field_of_activity; ?></div>

        <div style="font-weight: 700;">Вид деятельности потребителя</div>
        <div style="margin-bottom: 10px;"><?= $segment->sort_of_activity; ?></div>

        <div style="font-weight: 700;">Специализация вида деятельности потребителя</div>
        <div style="margin-bottom: 10px;"><?= $segment->specialization_of_activity; ?></div>

        <div style="font-weight: 700;">Возраст потребителя</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->age_from, 0, '', ' ') . ' до '
            . number_format($segment->age_to, 0, '', ' ') . ' лет'; ?>
        </div>

        <div style="font-weight: 700;">Пол потребителя</div>
        <div style="margin-bottom: 10px;">
            <?php
            if ($segment->gender_consumer == Segment::GENDER_WOMAN) {
                echo 'Женский';
            } elseif ($segment->gender_consumer == Segment::GENDER_MAN) {
                echo 'Мужской';
            } else {
                echo 'Не важно';
            }
            ?>
        </div>

        <div style="font-weight: 700;">Образование потребителя</div>
        <div style="margin-bottom: 10px;">
            <?php
            if ($segment->education_of_consumer == Segment::SECONDARY_EDUCATION) {
                echo 'Среднее образование';
            }elseif ($segment->education_of_consumer == Segment::SECONDARY_SPECIAL_EDUCATION) {
                echo 'Среднее образование (специальное)';
            }elseif ($segment->education_of_consumer == Segment::HIGHER_INCOMPLETE_EDUCATION) {
                echo 'Высшее образование (незаконченное)';
            }elseif ($segment->education_of_consumer == Segment::HIGHER_EDUCATION) {
                echo 'Высшее образование';
            }else {
                echo '';
            }
            ?>
        </div>

        <div style="font-weight: 700;">Доход потребителя</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
            . number_format($segment->income_to, 0, '', ' ') . ' руб./мес.'; ?>
        </div>

        <div style="font-weight: 700;">Потенциальное количество потребителей</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') . ' до '
            . number_format($segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>
        </div>

    <?php elseif ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B) : ?>

        <div style="font-weight: 700;">Вид информационного и экономического взаимодействия между субъектами рынка</div>
        <div style="margin-bottom: 10px;">Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)</div>

        <div style="font-weight: 700;">Сфера деятельности предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->field_of_activity; ?></div>

        <div style="font-weight: 700;">Вид деятельности предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->sort_of_activity; ?></div>

        <div style="font-weight: 700;">Специализация вида деятельности предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->specialization_of_activity; ?></div>

        <div style="font-weight: 700;">Продукция / услуги предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->company_products; ?></div>

        <div style="font-weight: 700;">Партнеры предприятия</div>
        <div style="margin-bottom: 10px;"><?= $segment->company_partner; ?></div>

        <div style="font-weight: 700;">Потенциальное количество представителей сегмента</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
            . number_format($segment->quantity_to, 0, '', ' '); ?>
        </div>

        <div style="font-weight: 700;">Доход предприятия</div>
        <div style="margin-bottom: 10px;">
            <?= 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
            . number_format($segment->income_to, 0, '', ' ') . ' млн. руб./год'; ?>
        </div>

    <?php endif; ?>


    <div style="font-weight: 700;">Объем рынка</div>
    <div style="margin-bottom: 10px;"><?= number_format($segment->market_volume, 0, '', ' ') . ' млн. руб./год'; ?></div>


    <?php if (!empty($segment->add_info)) : ?>

        <div style="font-weight: 700;">Дополнительная информация</div>
        <div style="margin-bottom: 10px;"><?= $segment->add_info; ?></div>

    <?php endif; ?>

</div>
