<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>

<?php
// Модальное окно - создание ГПС
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal',],
    'size' => 'modal-lg',
    'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;"><span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;">Создание гипотезы проблемы сегмента</span>' . Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
            'data-toggle' => 'modal',
            'data-target' => "#information_create_hypothesis",
            'title' => 'Посмотреть описание',
        ]) . '</div>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно - редактирование ГПС
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;">
                            <span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;"></span>
                        </div>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно - Информационное окно в создании ГПС
Modal::begin([
    'options' => ['id' => 'information_create_hypothesis'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Необходимо просмотреть и проанализировать все материалы интервью представителей сегмента и выявить проблемы, которые характерны для нескольких респондентов
</h4>


<?php Modal::end(); ?>



<?php
// Модальное окно - сообщение о том что данных недостаточно для создания ГПС
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания ГПС.</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Вернитесь к подтверждению сегмента.
</h4>

<?php Modal::end(); ?>



<?php
// Модальное окно - Информамация о представителях сегмента
Modal::begin([
    'options' => ['class' => 'respond_positive_view_modal',],
    'size' => 'modal-lg',
    'header' => '<div class="text-center"><span style="font-size: 24px; font-weight: 700;">Информация о интервью</span></div>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>