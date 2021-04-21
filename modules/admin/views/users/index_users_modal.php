<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>


<?php // Модальное окно добавления администратора
Modal::begin([
    'options' => ['id' => 'add_admin_modal'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center">Назначение администратора</h3>',
]); ?>
<!--Контент добавляется через Ajax-->
<?php Modal::end(); ?>


<?php // Модальное окно изменение статуса пользователя
Modal::begin([
    'options' => ['id' => 'change_status_modal'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center">Изменение статуса</h3>',
]); ?>
<!--Контент добавляется через Ajax-->
<?php Modal::end(); ?>


<?php // Подтверждение удаления пользователя
Modal::begin([
    'options' => [
        'id' => 'confirm_user_delete_modal',
        'class' => 'confirm_user_delete_modal',
    ],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center header-update-modal">Выберите действие</h3>',
    'footer' => '<div class="text-center">'.

        Html::a('Отмена', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'onclick' => "$('#confirm_user_delete_modal').modal('hide'); $('#change_status_modal').modal('show'); return false;"
        ]).

        Html::a('Ок', ['#'],[
            'class' => 'btn btn-default button_confirm_user_delete',
            'style' => ['width' => '120px'],
        ]).

        '</div>'
]); ?>

<h4 class="text-center"></h4>

<?php Modal::end(); ?>