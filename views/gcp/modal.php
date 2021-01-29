<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>


<?php
// Модальное окно - создание ГЦП
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => '<div style="display:flex; align-items: center; justify-content: center; font-weight: 700;"><span style="font-size: 24px; color: #4F4F4F; padding-right: 10px;">Создание гипотезы ценностного предложения</span>' . Html::a(Html::img('/images/icons/icon_info.png'), ['#'], [
            'data-toggle' => 'modal',
            'data-target' => "#information_create_hypothesis",
            'title' => 'Посмотреть описание',
        ]) . '</div>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Модальное окно - редактирование описания ГЦП
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center" style="color: #4F4F4F; font-weight: 700;"></h3>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Описание выполнения задачи при создании ГЦП
Modal::begin([
    'options' => ['id' => 'information_create_hypothesis'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Сгенерируйте гипотезу ценностного предложения и отредактируйте её по грамматическому смыслу.
</h4>

<?php
Modal::end();
?>


<?php
// Модальное окно - сообщение о том что данных недостаточно для создания ГЦП
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания ГЦП.</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Вернитесь к подтверждению проблемы сегмента.
</h4>

<?php Modal::end(); ?>


<?php
// Подтверждение закрытия окна редактирования ГЦП
Modal::begin([
    'options' => [
        'id' => 'confirm_closing_update_modal',
        'class' => 'confirm_closing_modal',
    ],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center header-update-modal">Выберите действие</h3>',
    'footer' => '<div class="text-center">'.

        Html::a('Отмена', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'onclick' => "$('#confirm_closing_update_modal').modal('hide'); return false;"
        ]).

        Html::a('Ок', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'id' => 'button_confirm_closing_modal',
        ]).

        '</div>'
]); ?>
<h4 class="text-center">Изменения не будут сохранены. Вы действительно хотите закрыть окно?</h4>
<!--Контент добавляется через Ajax-->
<?php Modal::end(); ?>
