<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>


<?php
// Модальное окно для создания Бизнес-модели
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal'],
    'size' => 'modal-lg',
    'header' => Html::a('Внесите данные для создания бизнес-модели' . Html::img('/images/icons/icon_report_next.png'), ['/business-model/get-instruction'],[
        'class' => 'link_to_instruction_page_in_modal open_modal_instruction_page', 'title' => 'Инструкция']),
    'headerOptions' => ['style' => ['text-align' => 'center']]
]);
?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно редактирования Бизнес-модели
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => Html::a('Редактирование бизнес-модели' . Html::img('/images/icons/icon_report_next.png'), ['/business-model/get-instruction'],[
        'class' => 'link_to_instruction_page_in_modal open_modal_instruction_page', 'title' => 'Инструкция']),
    'headerOptions' => ['style' => ['text-align' => 'center']]
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


<?php
// Подтверждение закрытия окна редактирования Бизнес-модели
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