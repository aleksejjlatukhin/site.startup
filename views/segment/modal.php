<?php

use yii\bootstrap\Modal;

?>


<?php
// Модальное окно - создание нового сегмента
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Создание нового сегмента</h3>',
]);
?>

<!--контент загружается через Ajax-->
<?php
Modal::end();
?>



<?php
// Модальное окно - Редактирование сегмента
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Редактирование данных сегмента</h3>',
]);
?>

<!--контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно - Сегмент с таким именем уже существует
Modal::begin([
    'options' => ['id' => 'segment_already_exists'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Сегмент с таким наименованием уже существует. Отредактируйте данное поле и сохраните форму.
</h4>

<?php
Modal::end();
?>


<?php
// Модальное окно - Данные не загружены
Modal::begin([
    'options' => ['id' => 'data_not_loaded'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Для сохранения формы сегмента необходимо<br>заполнить все поля со знаком *
</h4>

<?php
Modal::end();
?>
