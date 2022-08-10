<?php

use app\models\forms\AvatarForm;
use app\models\forms\PasswordChangeForm;
use app\models\forms\ProfileForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\ActiveForm;

/**
 * @var User $user
 * @var ProfileForm $profile
 * @var PasswordChangeForm $passwordChangeForm
 * @var AvatarForm $avatarForm
 */

?>

<div class="col-md-12 col-lg-4">

    <?php if ($user['avatar_image']) : ?>

        <?= Html::img('/web/upload/user-'.$user->getId().'/avatar/'.$user->getAvatarImage(), ['class' => 'avatar_image']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <div class="block_for_buttons_avatar_image">

                <div class="container_link_button_avatar_image"><?= Html::a('Обновить фотографию', '#', ['class' => 'add_image link_button_avatar_image']) ?></div>

                <div class="container_link_button_avatar_image"><?= Html::a('Редактировать миниатюру', '#', ['class' => 'update_image link_button_avatar_image']) ?></div>

                <div class="container_link_button_avatar_image"><?= Html::a('Удалить фотографию', Url::to(['/profile/delete-avatar', 'id' => $avatarForm->getUserId()]), ['class' => 'delete_image link_button_avatar_image']) ?></div>

            </div>

        <?php endif; ?>

    <?php else : ?>

        <?= Html::img('/images/avatar/default.jpg',['class' => 'avatar_image']) ?>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <div class="block_for_buttons_avatar_image">

                <div class="container_link_button_avatar_image"><?= Html::a('Добавить фотографию', '#', ['class' => 'add_image link_button_avatar_image']) ?></div>

            </div>

        <?php endif; ?>

    <?php endif; ?>


    <?php $form = ActiveForm::begin([
        'id' => 'formAvatarImage',
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'g-py-15'],
        'errorCssClass' => 'u-has-error-v1',
        'successCssClass' => 'u-has-success-v1-1',
    ]); ?>

    <?= $form->field($avatarForm, 'loadImage', ['template' => '<div style="display:none;">{input}</div>'])->fileInput(['id' => 'loadImageAvatar', 'accept' => 'image/x-png,image/jpeg']) ?>
    <?= $form->field($avatarForm, 'imageMax')->label(false)->hiddenInput() ?>

    <?php ActiveForm::end(); ?>

</div>


<div class="col-md-12 col-lg-8 info_user_content">

    <div class="row">

        <?php if (!User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
            <div class="col-md-12">
                <div class="user_is_online">
                    <?php if ($user->checkOnline === true) : ?>
                        Пользователь сейчас Online
                    <?php else : ?>
                        Пользователь был в сети <?= $user->checkOnline ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-lg-4"><label style="padding-left: 10px;">Дата регистрации:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $user->getCreatedAt()) ?></span></div>

        <div class="col-lg-4"><label style="padding-left: 10px;">Последнее изменение:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $user->getCreatedAt()) ?></span></div>

        <div class="col-lg-4"><label style="padding-left: 10px;">Статус:</label>

            <?php if ($user->getStatus() === User::STATUS_ACTIVE) : ?>
                <span style="padding-left: 10px;">Активирован</span>
            <?php elseif ($user->getStatus() === User::STATUS_NOT_ACTIVE) : ?>
                <span style="padding-left: 10px;">Не активирован</span>
            <?php elseif ($user->getStatus() === User::STATUS_DELETED) : ?>
                <span style="padding-left: 10px;">Заблокирован</span>
            <?php endif; ?>

        </div>

    </div>

    <div class="view_user_form row">

        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="col-md-6">
            <?= $form->field($user, 'email', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
            ])->textInput([
                'maxlength' => true,
                'readonly' => true,
                'class' => 'style_form_field_respond form-control',
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($user, 'username', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
            ])->textInput([
                'maxlength' => true,
                'readonly' => true,
                'class' => 'style_form_field_respond form-control',
            ]) ?>
        </div>

        <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

            <div class="col-md-6">
                <?= Html::button('Редактировать профиль', [
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
                ]) ?>
            </div>

            <div class="col-md-6">
                <?= Html::button( 'Сменить пароль',[
                    'id' => 'show_form_change_password',
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
                ]) ?>
            </div>

        <?php endif; ?>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="update_user_form row">

        <?php $form = ActiveForm::begin([
            'id' => 'update_data_profile',
            'action' => Url::to(['/profile/update-profile', 'id' => $profile->getId()]),
            'options' => ['class' => 'g-py-15'],
            'errorCssClass' => 'u-has-error-v1',
            'successCssClass' => 'u-has-success-v1-1',
        ]); ?>

        <div class="col-md-6">
            <?= $form->field($profile, 'email', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
            ])->textInput([
                'type' => 'email',
                'maxlength' => true,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'autocomplete' => 'off'
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($profile, 'username', [
                'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
            ])->textInput([
                'minlength' => 3,
                'maxlength' => 32,
                'required' => true,
                'class' => 'style_form_field_respond form-control',
                'placeholder' => 'Введите от 3 до 32 символов',
                'autocomplete' => 'off'
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= Html::button('Отмена', [
                'class' => 'show_form_view_data btn btn-default',
                'style' => [
                    'background' => '#E0E0E0',
                    'padding' => '0 7px',
                    'width' => '100%',
                    'height' => '40px',
                    'font-size' => '24px',
                    'border-radius' => '8px',
                    'margin-top' => '35px',
                ]
            ]) ?>
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
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="change_password_content">

        <div class="row change_password_content_data_user">

            <div class="col-lg-4"><label style="padding-left: 10px;">Логин:</label><span style="padding-left: 10px;"><?= $user->getUsername() ?></span></div>
            <div class="col-lg-4"><label style="padding-left: 10px;">Email:</label><span style="padding-left: 10px;"><?= $user->getEmail() ?></span></div>

        </div>

        <div class="row change_password_form">

            <?php $form = ActiveForm::begin([
                'id' => 'form_change_password_user',
                'action' => Url::to(['/profile/change-password', 'id' => $user->getId()]),
                'options' => ['class' => 'g-py-15'],
                'errorCssClass' => 'u-has-error-v1',
                'successCssClass' => 'u-has-success-v1-1',
            ]); ?>

            <div class="col-md-4">
                <?= $form->field($passwordChangeForm, 'currentPassword', [
                    'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                ])->passwordInput([
                    'maxlength' => 32,
                    'minlength' => 6,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Введите от 6 до 32 символов',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($passwordChangeForm, 'newPassword', [
                    'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                ])->passwordInput([
                    'maxlength' => 32,
                    'minlength' => 6,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Введите от 6 до 32 символов',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($passwordChangeForm, 'newPasswordRepeat', [
                    'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                ])->passwordInput([
                    'maxlength' => 32,
                    'minlength' => 6,
                    'required' => true,
                    'class' => 'style_form_field_respond form-control',
                    'placeholder' => 'Введите от 6 до 32 символов',
                    'autocomplete' => 'off'
                ]) ?>
            </div>

            <div class="col-md-12" style="padding-top: 27px; padding-bottom: 10px;">
                Введите последовательно данные в указаные поля. Во время создания паролей не используйте пробел.
            </div>

            <div class="col-md-6">
                <?= Html::button('Отмена', [
                    'class' => 'show_form_view_data btn btn-default',
                    'style' => [
                        'background' => '#E0E0E0',
                        'padding' => '0 7px',
                        'width' => '100%',
                        'height' => '40px',
                        'font-size' => '24px',
                        'border-radius' => '8px',
                        'margin-top' => '35px',
                    ]
                ]) ?>
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
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>