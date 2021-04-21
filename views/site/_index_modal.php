<?php

use yii\bootstrap\Modal;
use app\models\User;

?>


<?php // Модальное окно - валидация данных при регистрации
Modal::begin([
    'options' => ['id' => 'error_user_singup'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Измените данные согласно этой информации</h3>',
]);
?>

<?php Modal::end(); ?>


<?php // Модальное окно - результате при регистрации и отправке письма на почту
Modal::begin([
    'options' => ['id' => 'result_singup'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;"></h4>

<?php Modal::end(); ?>


<?php if ($user->status === User::STATUS_NOT_ACTIVE) : ?>

    <?php // Модальное окно - Ошибка регистрации
    Modal::begin([
        'options' => ['id' => 'user_status'],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Ожидайте активации вашего стутуса администратором</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Мы отправим Вам письмо на электронную почту, когда будет принято данное решение.
    </h4>

    <?php Modal::end(); ?>


<?php elseif ($user->status === User::STATUS_DELETED) : ?>

    <?php // Модальное окно - Ошибка регистрации
    Modal::begin([
        'options' => [
            'id' => 'user_status',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Ваша учетная запись заблокирована</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
        Обратитесь по этому вопросу к администратору.
    </h4>

    <?php Modal::end(); ?>

<?php endif; ?>
