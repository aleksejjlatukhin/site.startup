<?php

use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;

$this->title = 'Регистрация';
$this->registerCssFile('@web/css/registration.css');

?>

<div class="row page-registration">

    <div class="col-md-3"></div>

    <div class="col-md-6 result-registration">

        <h2 class="text-center">Регистрация</h2>

        <div class="block-form-user-role">

            <?php $form = ActiveForm::begin([
                'id' => 'form_user_role',
                'action' => Url::to(['/site/get-form-registration']),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

                <?= $form->field($formUserRole, 'role', [
                    'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Проектная роль пользователя</div><div>{input}</div>'
                ])->widget(Select2::class, [
                    'data' => [User::ROLE_USER => 'Проектант', User::ROLE_ADMIN => 'Трекер', User::ROLE_EXPERT => 'Эксперт'],
                    'options' => ['id' => 'type-interaction', 'placeholder' => 'Выберите проектную роль пользователя'],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]); ?>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="block-form-registration"></div>

    </div>

    <div class="col-md-3"></div>
    
</div>

<!--Модальные окна-->
<?= $this->render('registration_modal'); ?>
<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/registration.js'); ?>
