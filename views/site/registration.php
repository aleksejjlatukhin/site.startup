<?php

use app\models\forms\FormClientAndRole;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Регистрация';
$this->registerCssFile('@web/css/registration.css');

/**
 * @var FormClientAndRole $formClientAndRole
 * @var array $dataClients
 */

?>

<div class="row page-registration">

    <div class="col-md-3"></div>

    <div class="col-md-6 result-registration">

        <h2 class="text-center">Регистрация</h2>

        <div class="block-form-user-role">

            <?php $form = ActiveForm::begin([
                'id' => 'form_client_and_role',
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

                <?= $form->field($formClientAndRole, 'clientId', [
                    'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Организация, к которой будет привязан Ваш аккаунт *</div><div>{input}</div>'
                ])->widget(Select2::class, [
                    'data' => $dataClients,
                    'options' => [
                        'autocomplete' => 'off',
                        'id' => 'formClientAndRole_clientId',
                        'placeholder' => 'Выберите организацию, к которой будет привязан Ваш аккаунт',
                    ],
                    'disabled' => false,  //Сделать поле неактивным
                    'hideSearch' => true, //Скрытие поиска
                ]) ?>

            <?php ActiveForm::end(); ?>

        </div>

        <div class="block-form-registration"></div>

    </div>

    <div class="col-md-3"></div>
    
</div>

<!--Модальные окна-->
<?= $this->render('registration_modal') ?>
<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/registration.js'); ?>
