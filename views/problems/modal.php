<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>

<?php
// Модальное окно - создание ГПС
Modal::begin([
    'options' => ['class' => 'hypothesis_create_modal',],
    'size' => 'modal-lg',
    'header' => Html::a('Генерация гипотезы проблемы сегмента' . Html::img('/images/icons/icon_report_next.png'), ['/problems/get-instruction'],[
        'class' => 'link_to_instruction_page_in_modal open_modal_instruction_page', 'title' => 'Инструкция']),
    'headerOptions' => ['style' => ['text-align' => 'center']]
]); ?>
<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>



<?php
// Модальное окно - редактирование ГПС
Modal::begin([
    'options' => ['class' => 'hypothesis_update_modal'],
    'size' => 'modal-lg',
    'header' => Html::a('Редактирование проблемы: <span></span>' . Html::img('/images/icons/icon_report_next.png'), ['/problems/get-instruction'],[
        'class' => 'link_to_instruction_page_in_modal open_modal_instruction_page', 'title' => 'Инструкция']),
    'headerOptions' => ['style' => ['text-align' => 'center']]
]); ?>
<!--Контент загружается через Ajax-->
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
    'header' => '<div style="font-size: 28px; font-weight: 700;">Информация о интервью</div>',
    'headerOptions' => ['class' => 'style_header_modal_form', 'style' => ['text-align' => 'center']]
]); ?>
<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Подтверждение закрытия окна редактирования проблемы
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
