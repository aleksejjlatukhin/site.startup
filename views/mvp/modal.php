<?php

use yii\bootstrap\Modal;

?>

<?php
// Модальное окно - создание MVP
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => '<div class="text-center" style="font-size: 24px; color: #4F4F4F; font-weight: 700;">Сформулируйте описание продукта (MVP)</div>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Модальное окно - редактирование описания MVP
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center" style="color: #4F4F4F; font-weight: 700;"></h3>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Модальное окно - сообщение о том что данных недостаточно для создания MVP
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Недостаточно данных для создания нового продукта (MVP).</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Вернитесь к подтверждению ценностного предложения.
</h4>

<?php Modal::end(); ?>
