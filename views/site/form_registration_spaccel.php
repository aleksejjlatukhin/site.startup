<?php

use app\models\User;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form_client_and_role',
    'options' => ['class' => 'g-py-15'],
    'errorCssClass' => 'u-has-error-v1',
    'successCssClass' => 'u-has-success-v1-1',
]); ?>

<?= $form->field($formClientAndRole, 'clientId', [
    'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Организация, к которой будет привязан Ваш аккаунт</div><div>{input}</div>'
])->widget(Select2::class, [
    'data' => $dataClients,
    'options' => ['id' => 'formClientAndRole_clientId', 'placeholder' => 'Выберите организацию, к которой будет привязан Ваш аккаунт'],
    'disabled' => false,  //Сделать поле неактивным
    'hideSearch' => true, //Скрытие поиска
]); ?>

<?= $form->field($formClientAndRole, 'role', [
    'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Проектная роль пользователя</div><div>{input}</div>'
])->widget(Select2::class, [
    'data' => [User::ROLE_USER => 'Проектант', User::ROLE_ADMIN => 'Трекер', User::ROLE_EXPERT => 'Эксперт', User::ROLE_MANAGER => 'Менеджер'],
    'options' => ['id' => 'formClientAndRole_role', 'placeholder' => 'Выберите проектную роль пользователя'],
    'disabled' => false,  //Сделать поле неактивным
    'hideSearch' => true, //Скрытие поиска
]); ?>

<?php ActiveForm::end(); ?>
