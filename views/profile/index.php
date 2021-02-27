<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'Данные пользователя';
$this->registerCssFile('@web/css/profile-style.css');
?>

<div class="user-index">

    <div class="row profile_menu">

        <?= Html::a('Данные пользователя', ['/profile/index', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Сводные таблицы', ['/profile/result', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Дорожные карты', ['/profile/roadmap', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Протоколы', ['/profile/report', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

        <?= Html::a('Презентации', ['/profile/presentation', 'id' => $user->id], [
            'class' => 'link_in_the_header',
        ]) ?>

    </div>


    <div class="data_user_content">

        <div class="col-md-12 col-lg-4">

            <?php if ($user['avatar_image']) : ?>

                <?= Html::img('/web/upload/user-'.$user->id.'/avatar/'.$user->avatar_image, ['class' => 'avatar_image']); ?>

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <div class="block_for_buttons_avatar_image">

                        <div class="container_link_button_avatar_image"><?= Html::a('Обновить фотографию', '#', ['class' => 'add_image link_button_avatar_image',]);?></div>

                        <div class="container_link_button_avatar_image"><?= Html::a('Редактировать миниатюру', '#', ['class' => 'update_image link_button_avatar_image',]);?></div>

                        <div class="container_link_button_avatar_image"><?= Html::a('Удалить фотографию', Url::to(['/profile/delete-avatar', 'id' => $avatarForm->userId]), ['class' => 'delete_image link_button_avatar_image',]);?></div>

                    </div>

                <?php endif; ?>

            <?php else : ?>

                <?= Html::img('/images/avatar/default.jpg',['class' => 'avatar_image']); ?>

                <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                    <div class="block_for_buttons_avatar_image">

                        <div class="container_link_button_avatar_image"><?= Html::a('Добавить фотографию', '#', ['class' => 'add_image link_button_avatar_image',]);?></div>

                    </div>

                <?php endif; ?>

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


        <div class="col-md-12 col-lg-8 info_user_content">

            <div class="row">

                <div class="col-lg-4"><label style="padding-left: 10px;">Дата регистрации:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $user['created_at']); ?></span></div>

                <div class="col-lg-4"><label style="padding-left: 10px;">Последнее изменение:</label><span style="padding-left: 10px;"><?= date('d.m.Y', $user['updated_at']); ?></span></div>

                <div class="col-lg-4"><label style="padding-left: 10px;">Статус:</label>

                    <?php if ($user['status'] == User::STATUS_ACTIVE) : ?>
                        <span style="padding-left: 10px;">Активирован</span>
                    <?php elseif ($user['status'] == User::STATUS_NOT_ACTIVE) : ?>
                        <span style="padding-left: 10px;">Не активирован</span>
                    <?php elseif ($user['status'] == User::STATUS_DELETED) : ?>
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

                <div class="col-md-4">
                    <?= $form->field($user, 'second_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($user, 'first_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($user, 'middle_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($user, 'telephone', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($user, 'email', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($user, 'username', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => true,
                        'readonly' => true,
                        'class' => 'style_form_field_respond form-control',
                    ]); ?>
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
                        ])?>
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
                        ]);?>
                    </div>

                <?php endif; ?>

                <?php ActiveForm::end(); ?>

            </div>

            <div class="update_user_form row">

                <?php $form = ActiveForm::begin([
                    'id' => 'update_data_profile',
                    'action' => Url::to(['/profile/update-profile', 'id' => $profile->id]),
                    'options' => ['class' => 'g-py-15'],
                    'errorCssClass' => 'u-has-error-v1',
                    'successCssClass' => 'u-has-success-v1-1',
                ]); ?>

                <div class="col-md-4">
                    <?= $form->field($profile, 'second_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => 50,
                        'minlength' => 2,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'autocomplete' => 'off'
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'first_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => 50,
                        'minlength' => 2,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'autocomplete' => 'off'
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'middle_name', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => 50,
                        'minlength' => 2,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'autocomplete' => 'off'
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'telephone', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'maxlength' => 50,
                        'minlength' => 6,
                        'class' => 'style_form_field_respond form-control',
                        'autocomplete' => 'off'
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'email', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'type' => 'email',
                        'maxlength' => true,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'autocomplete' => 'off'
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($profile, 'username', [
                        'template' => '<div style="padding-left: 10px;">{label}</div><div>{input}</div>'
                    ])->textInput([
                        'minlength' => 3,
                        'maxlength' => 32,
                        'required' => true,
                        'class' => 'style_form_field_respond form-control',
                        'placeholder' => 'Введите от 3 до 32 символов',
                        'autocomplete' => 'off'
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

            <div class="change_password_content">

                <div class="row change_password_content_data_user">

                    <div class="col-lg-4"><label style="padding-left: 10px;">ФИО:</label><span style="padding-left: 10px;"><?= $user->second_name.' '.$user->first_name.' '.$user->middle_name; ?></span></div>
                    <div class="col-lg-4"><label style="padding-left: 10px;">Логин:</label><span style="padding-left: 10px;"><?= $user->username; ?></span></div>
                    <div class="col-lg-4"><label style="padding-left: 10px;">Email:</label><span style="padding-left: 10px;"><?= $user->email; ?></span></div>

                </div>

                <div class="row change_password_form">

                    <?php $form = ActiveForm::begin([
                        'id' => 'form_change_password_user',
                        'action' => Url::to(['/profile/change-password', 'id' => $user->id]),
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

    </div>

    <?/*= $this->render('menu_user', [
        'user' => $user,
    ]) */?>


    <!--<div class="col-md-9" style="padding-left: 0;">

        <h5 class="d-inline p-2" style="font-weight: 700;text-transform: uppercase;text-align: center; background-color: #0972a5;color: #fff; height: 50px; line-height: 50px;margin-bottom: 0;">
            <div class="row">

                <?/*= Html::encode($this->title) */?>

            </div>
        </h5>

        <div style="display:flex; padding: 20px 0 10px 0;">

            <div class="" style="padding-left: 0;">
                <?/*= Html::img([$user['avatar_image']],['width' => '200px', 'min-height' => '200px', 'max-height' => '300px'])*/?>

                <div class="row" style="margin: 10px 0;padding-left: 0;">
                    <?/*= Html::a('Редактировать данные',Url::to(['/profile/update-profile', 'id' => $user['id']]), ['class' => 'btn btn-sm btn-primary', 'style' => ['width' => '200px']]);*/?>
                </div>

                <div class="row" style="margin: 10px 0;padding-left: 0;">
                    <?/*= Html::a('Сменить пароль',Url::to(['/profile/change-password', 'id' => $user['id']]), ['class' => 'btn btn-sm btn-primary', 'style' => ['width' => '200px']]);*/?>
                </div>

            </div>

            <div class="" style="width: 100%; margin-left: 30px;">

                <div>
                    <div class="col-md-4" style="padding: 0;">Дата регистрации: </div>
                    <span style="font-weight: 700;"><?/*= date('d.m.Y', $user['created_at']); */?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Последнее изменение: </div>
                    <span style="font-weight: 700;"><?/*= date('d.m.Y', $user['updated_at']); */?></span>
                </div>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">

                    <div class="col-md-4" style="padding: 0;">Статус:</div>

                    <span style="font-weight: 700;">
                        <?/* if ($user['status'] == User::STATUS_ACTIVE) echo '<span style="color: green;">активирован</span>'; */?>
                        <?/* if ($user['status'] == User::STATUS_NOT_ACTIVE) echo '<span style="color: #0972a5;">не активирован</span>'; */?>
                        <?/* if ($user['status'] == User::STATUS_DELETED) echo '<span style="color: red;">заблокирован</span>'; */?>
                    </span>

                </div>

                <div style="padding-top: 10px;">
                    <div class="col-md-4" style="padding: 0;">Фамилия: </div>
                    <span style="font-weight: 700;"><?/*= $user['second_name']; */?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Имя: </div>
                    <span style="font-weight: 700;"><?/*= $user['first_name']; */?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Отчество: </div>
                    <span style="font-weight: 700;"><?/*= $user['middle_name']; */?></span>
                </div>

                <div>
                    <div class="col-md-4" style="padding: 0;">Логин: </div>
                    <span style="font-weight: 700;"><?/*= $user['username']; */?></span>
                </div>

                <?php /*if (!empty($user['telephone'])) : */?>

                    <div>
                        <div class="col-md-4" style="padding: 0;">Телефон: </div>
                            <span style="font-weight: 700;">
                                <?/*= $user['telephone']; */?>
                            </span>
                    </div>

                <?php /*endif; */?>

                <div style="border-bottom: 1px solid #ccc;padding-bottom: 10px;">
                    <div class="col-md-4" style="padding-left: 0;">Эл.почта: </div>
                    <span style="font-weight: 700;"><?/*= $user['email']; */?></span>
                </div>

            </div>
        </div>


        <script>

            $( ".catalog" ).dcAccordion({speed:300});

        </script>

    </div>-->

    <!--Модальные окна-->
    <?= $this->render('modal'); ?>

</div>


<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/profile_index.js'); ?>