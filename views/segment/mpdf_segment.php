<?php

use app\models\Segment;

?>

<div class="segment-view-export">

    <h4>Наименование сегмента</h4>
    <div><?= $segment->name; ?></div>
    <h4>Краткое описание сегмента</h4>
    <div><?= $segment->description; ?></div>

    <?php if ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2C) : ?>

        <h4>Вид информационного и экономического взаимодействия между субъектами рынка</h4>
        <div>Коммерческие взаимоотношения между организацией и частным потребителем (B2C)</div>

        <h4>Сфера деятельности потребителя</h4>
        <div><?= $segment->field_of_activity; ?></div>

        <h4>Вид / специализация деятельности потребителя</h4>
        <div><?= $segment->sort_of_activity; ?></div>

        <h4>Возраст потребителя</h4>
        <div>
            <?= 'от ' . number_format($segment->age_from, 0, '', ' ') . ' до '
            . number_format($segment->age_to, 0, '', ' ') . ' лет'; ?>
        </div>

        <h4>Пол потребителя</h4>
        <div>
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

        <h4>Образование потребителя</h4>
        <div>
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

        <h4>Доход потребителя</h4>
        <div>
            <?= 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
            . number_format($segment->income_to, 0, '', ' ') . ' руб./мес.'; ?>
        </div>

        <h4>Потенциальное количество потребителей</h4>
        <div>
            <?= 'от ' . number_format($segment->quantity_from * 1000, 0, '', ' ') . ' до '
            . number_format($segment->quantity_to * 1000, 0, '', ' ') . ' человек'; ?>
        </div>

    <?php elseif ($segment->type_of_interaction_between_subjects == Segment::TYPE_B2B) : ?>

        <h4>Вид информационного и экономического взаимодействия между субъектами рынка</h4>
        <div>Коммерческие взаимоотношения между представителями бизнес-аудитории (B2B)</div>

        <h4>Сфера деятельности предприятия</h4>
        <div><?= $segment->field_of_activity; ?></div>

        <h4>Вид / специализация деятельности предприятия</h4>
        <div><?= $segment->sort_of_activity; ?></div>

        <h4>Продукция / услуги предприятия</h4>
        <div><?= $segment->company_products; ?></div>

        <h4>Партнеры предприятия</h4>
        <div><?= $segment->company_partner; ?></div>

        <h4>Потенциальное количество представителей сегмента</h4>
        <div>
            <?= 'от ' . number_format($segment->quantity_from, 0, '', ' ') . ' до '
            . number_format($segment->quantity_to, 0, '', ' '); ?>
        </div>

        <h4>Доход предприятия</h4>
        <div>
            <?= 'от ' . number_format($segment->income_from, 0, '', ' ') . ' до '
            . number_format($segment->income_to, 0, '', ' ') . ' млн. руб./год'; ?>
        </div>

    <?php endif; ?>


    <h4>Платежеспособность целевого сегмента</h4>
    <div><?= number_format($segment->market_volume, 0, '', ' ') . ' млн. руб./год'; ?></div>


    <?php if (!empty($segment->add_info)) : ?>

        <h4>Дополнительная информация</h4>
        <div><?= $segment->add_info; ?></div>

    <?php endif; ?>

</div>