<?php

use yii\bootstrap\Modal;

?>


<?php
// Модальное окно для создания Бизнес-модели
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Внесите данные для создания бизнес-модели</h3>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно редактирования Бизнес-модели
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Редактирование бизнес-модели</h3>',
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>




<?php
// Модальное окно - сообщение о том что данных недостаточно для создания Бизнес-модели
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal_error'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 25px;">Недостаточно данных для создания бизнес-модели.</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Вернитесь к подтверждению продукта (MVP).
</h4>

<?php Modal::end(); ?>
