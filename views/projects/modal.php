<?php

use yii\bootstrap\Modal;

?>


<?php
// Модальное окно - создание проекта
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Создание проекта</h3>',
]);
?>

<!--контент загружается через Ajax-->
<?php
Modal::end();
?>


<?php
// Модальное окно - редактирование проекта
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Редактирование исходных данных проекта</h3>',
]);
?>

<!--контент загружается через Ajax-->
<?php
Modal::end();
?>


<?php
// Модальное окно - Проект с таким именем уже существует
Modal::begin([
    'options' => ['id' => 'project_already_exists',],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Проект с таким наименованием уже существует. Отредактируйте данное поле и сохраните форму.
</h4>

<?php
Modal::end();
?>
