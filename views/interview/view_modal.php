<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;

?>

<?php
// Описание выполнения задачи на шаге 2
Modal::begin([
    'options' => ['id' => 'information-table-questions', 'class' => 'information-table-questions'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
    Сформулируйте собственный список вопросов для интервью или отредактируйте список «по-умолчанию».
</h4>

<?php Modal::end(); ?>


<?php
// Описание выполнения задачи на шаге 3
Modal::begin([
    'options' => ['id' => 'information-table-responds', 'class' => 'error_respond_modal_style'],
    'size' => 'modal-md',
    'header' => '<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">1. Пройдите последовательно по ссылкам в таблице, заполняя информацию о каждом респонденте.</h4>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">2. Затем переходите к заполнению данных по интервью, при необходимости добавляйте новых респондентов.</h4>

<?php Modal::end(); ?>


<?php
// Форма добавления нового респондента
Modal::begin([
    'options' => ['id' => 'respondCreate_modal'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center">Добавить респондента</h3>',
    'headerOptions' => ['class' => 'style_header_modal_form'],
]); ?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Форма редактирование информации о респонденте
Modal::begin([
    'options' => ['id' => 'respond_update_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Сведения о респонденте</h3>',
    'headerOptions' => ['class' => 'style_header_modal_form'],
]); ?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Форма создания интервью для респондента
Modal::begin([
    'options' => ['id' => 'create_descInterview_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Внесите результаты интервью</h3>',
    'headerOptions' => ['class' => 'style_header_modal_form'],
]); ?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>


<?php
// Форма редактирование информации о интервью
Modal::begin([
    'options' => ['id' => 'update_descInterview_modal'],
    'size' => 'modal-lg',
    'header' => '<h3 class="text-center">Результаты интервью</h3>',
    'headerOptions' => ['class' => 'style_header_modal_form'],
]); ?>


<?php Modal::end(); ?>


<?php
// Подтверждение удаления респондента
Modal::begin([
    'options' => [
        'id' => 'delete-respond-modal',
        'class' => 'not_exist_confirm_modal',
    ],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center header-update-modal">Выберите действие</h3>',
    'footer' => '<div class="text-center">'.

        Html::a('Отмена', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'id' => 'cancel-delete-respond',
        ]).

        Html::a('Ок', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'id' => 'confirm-delete-respond',
        ]).

        '</div>'
]); ?>

<h4 class="text-center"></h4>

<?php Modal::end(); ?>


<?php
// Модальное окно - ограничения при работе с респондентами
Modal::begin([
    'options' => ['id' => 'error_respond_modal', 'class' => 'error_respond_modal_style'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;"></h3>',
]);
?>

<h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;"></h4>

<?php Modal::end(); ?>


<?php

// Сообщение о том, что в подтверждении недостаточно представителей сегмента
//и необходимо выбрать (вернуться или продолжить)
Modal::begin([
    'options' => ['id' => "not_exist-confirm-modal", 'class' => 'not_exist_confirm_modal'],
    'size' => 'modal-md',
    'header' => '<h3 class="text-center">Выберите действие</h3>',
    'footer' => '<div class="text-center">'.

        Html::a('Отмена', ['#'],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'id' => "cancel-not_exist-confirm",
        ]).

        Html::a('Ок', ['/interview/not-exist-confirm', 'id' => $model->id],[
            'class' => 'btn btn-default',
            'style' => ['width' => '120px'],
            'id' => "not_exist-confirm",
        ]).

        '</div>'
]); ?>

<h4 class="text-center">Вы не набрали достаточное количество представителей сегмента. Следующий этап будет не доступен. Завершить данное подтверждение?</h4>

<?php Modal::end(); ?>


<?php
//Модальное окно для таблицы ответов респондентов на вопросы интервью
Modal::begin([
    'options' => ['id' => 'showQuestionsAndAnswers'],
    'size' => 'modal-lg',
    'header' => Html::a('<div style="margin-top: -15px;">Ответы респондентов на вопросы интервью' . Html::img('/images/icons/icon_export.png', ['style' => ['width' => '22px', 'margin-left' => '10px', 'margin-bottom' => '10px']]) . '</div>', [
        '/interview/mpdf-questions-and-answers', 'id' => $model->id], [
        'class' => 'export_link',
        'target' => '_blank',
        'title' => 'Скачать в pdf',
    ]),
    'headerOptions' => ['class' => 'style_header_modal_form text-center'],
]); ?>

<!--Контент загружается через Ajax-->
<?php Modal::end(); ?>
