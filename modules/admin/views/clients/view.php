<?php

$this->title = 'Админка | Профиль организации';
$this->registerCssFile('@web/css/profile-style.css');

use app\models\ClientActivation;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>


<div class="client-profile">

    <div class="row profile_menu" style="height: 51px;">

    </div>


    <div class="data_client_content">

        <div class="col-md-12 col-lg-4">

            <?php if ($clientSettings->getAvatarImage()) : ?>

                <?= Html::img('/web/upload/company-'.$client->getId().'/avatar/'.$clientSettings->getAvatarImage(), ['class' => 'avatar_image']); ?>

                <div class="block_for_buttons_avatar_image">

                    <div class="container_link_button_avatar_image"><?= Html::a('Обновить фотографию', '#', ['class' => 'add_image link_button_avatar_image',]);?></div>

                    <div class="container_link_button_avatar_image"><?= Html::a('Редактировать миниатюру', '#', ['class' => 'update_image link_button_avatar_image',]);?></div>

                    <div class="container_link_button_avatar_image"><?= Html::a('Удалить фотографию', Url::to(['/admin/clients/delete-avatar', 'id' => $avatarForm->clientId]), ['class' => 'delete_image link_button_avatar_image',]);?></div>

                </div>

            <?php else : ?>

                <?= Html::img('/images/avatar/default.jpg',['class' => 'avatar_image']); ?>

                <div class="block_for_buttons_avatar_image">

                    <div class="container_link_button_avatar_image"><?= Html::a('Добавить фотографию', '#', ['class' => 'add_image link_button_avatar_image',]);?></div>

                </div>

            <?php endif; ?>


            <?php $form = ActiveForm::begin([
                'id' => 'formAvatarImage',
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <?= $form->field($avatarForm, 'loadImage', ['template' => '<div style="display:none;">{input}</div>'])->fileInput(['id' => 'loadImageAvatar', 'accept' => 'image/x-png,image/jpeg']); ?>
            <?= $form->field($avatarForm, 'imageMax')->label(false)->hiddenInput(); ?>

            <?php ActiveForm::end(); ?>

        </div>


        <div class="col-md-12 col-lg-8 info_client_content">

            <div class="row">

                <div class="col-lg-4"><label style="padding-left: 10px;">Дата регистрации в Spaccel:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $client->created_at); ?></span></div>

                <div class="col-lg-4">
                    <label style="padding-left: 10px;">Тариф:</label>
                    <span style="padding-left: 10px;">
                        <?php if ($client->findLastClientRatesPlan()) : ?>
                            <?= $client->findLastClientRatesPlan()->findRatesPlan()->getName(); ?>
                        <?php else : ?>
                            Не установлен
                        <?php endif; ?>
                    </span></div>

                <div class="col-lg-4"><label style="padding-left: 10px;">Статус:</label>

                    <?php if ($client->findClientActivation()->getStatus() == ClientActivation::ACTIVE) : ?>
                        <span style="padding-left: 10px;">Активирована</span>
                    <?php elseif ($client->findClientActivation()->getStatus() == ClientActivation::NO_ACTIVE) : ?>
                        <span style="padding-left: 10px;">Заблокирована</span>
                    <?php endif; ?>

                </div>

            </div>

            <div class="view_client_form row">

                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <div class="col-md-12">
                    <?= $form->field($model, 'name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'fullname', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'city', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'description', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]); ?>
                </div>

                <div class="col-xs-12 col-md-6">
                    <?= Html::button('Редактировать', [
                        'id' => 'show_form_update_data',
                        'class' => 'btn btn-default',
                        'style' => [
                            'color' => '#FFFFFF',
                            'background' => '#707F99',
                            'padding' => '0 7px',
                            'width' => '100%',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-top' => '35px',
                        ]
                    ])?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

            <div class="update_client_form row">

                <?php $form = ActiveForm::begin([
                    'id' => 'update_data_profile',
                    'action' => Url::to(['/admin/clients/update-profile', 'id' => $model->id]),
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <div class="col-md-12">
                    <?= $form->field($model, 'name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'fullname', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'city', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'description', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textarea([
                        'rows' => 2,
                        'maxlength' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => '',
                    ]); ?>
                </div>

                <div class="col-md-6">
                    <?= Html::button('Отмена', [
                        'id' => 'show_form_view_data',
                        'class' => 'btn btn-default',
                        'style' => [
                            'background' => '#E0E0E0',
                            'padding' => '0 7px',
                            'width' => '100%',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-top' => '35px',
                        ]
                    ])?>
                </div>

                <div class="col-md-6">
                    <?= Html::submitButton( 'Сохранить',[
                        'class' => 'btn btn-success',
                        'style' => [
                            'display' => 'flex',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'background' => '#52BE7F',
                            'width' => '100%',
                            'height' => '40px',
                            'font-size' => '24px',
                            'border-radius' => '8px',
                            'margin-top' => '35px',
                        ],
                    ]);?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

    <!--Модальные окна-->
    <?= $this->render('modal_profile_company'); ?>

</div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/profile_company.js'); ?>

