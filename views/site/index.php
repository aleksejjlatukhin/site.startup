<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;


$this->title = 'Главная';

?>
<div class="site-index">

    <div class="background_for_main_page">

        <div class="row top_line_text_main_page">
            <div class="col-md-12">Инструмент для Прокачки бизнес-идеи, поиска потребительского сегмента, формирования продукта</div>
        </div>

        <div class="content_main_page">


            <?php if (Yii::$app->user->isGuest) : ?>


                <div class="row style_form_login">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 45px 0;">Добро пожаловать!</div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login_user_form',
                        'action' => Url::to(['/site/login']),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>

                    <div class="col-md-12">

                        <?= $form->field($model_login, 'identity', ['template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Логин или email</div><div>{input}</div>'])
                            ->label('Логин')
                            ->textInput([
                                'maxlength' => true,
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => 'Введите логин или email',
                                'autocomplete' => 'off'
                            ]) ?>

                    </div>

                    <div class="col-md-12">

                        <?= $form->field($model_login, 'password', ['template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Пароль</div><div>{input}</div>'])
                            ->passwordInput([
                                'required' => true,
                                'class' => 'style_form_field_respond form-control',
                                'placeholder' => 'Введите пароль',
                                'autocomplete' => 'off'
                            ]) ?>

                    </div>

                    <div class="col-md-12" style="margin-top: 5px;">

                        <?= $form->field($model_login, 'rememberMe', [
                            'template' => "{input}{label}"
                        ])->checkbox(['checked' => true],false)->label('Запомнить меня');
                        ?>

                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 5px;">
                        <?= Html::submitButton('Войти', [
                            'class' => 'btn btn-default',
                            'name' => 'login-button',
                            'style' => [
                                'background' => '#E0E0E0',
                                'color' => '4F4F4F',
                                'border-radius' => '8px',
                                'width' => '140px',
                                'height' => '40px',
                                'font-size' => '16px',
                                'font-weight' => '700'
                            ]
                        ]) ?>
                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 15px; margin-bottom: 5px;">или</div>

                    <div class="col-md-12 text-center">
                        <?= Html::a('Зарегистрироваться',['/'], [
                            'onclick' => 'return false',
                            'class' => 'link_singup',
                            'id' => 'go_user_singup',
                        ]);?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>


                <div class="row style_error_not_user">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 45px 0;">Не верный ввод!</div>

                    <div class="col-md-12 text-center" style=" margin: 45px 0 70px 0;">Поля логин и пароль введены не верно или несоответствуют друг другу.</div>

                    <div class="col-md-12 text-center" style="margin-top: 30px;">

                        <?= Html::button('Забыли пароль?', [
                            'id' => 'go_password_recovery_for_email',
                            'class' => 'btn btn-default',
                            'style' => [
                                'background' => '#E0E0E0',
                                'color' => '4F4F4F',
                                'border-radius' => '8px',
                                'width' => '170px',
                                'height' => '40px',
                                'font-size' => '16px',
                                'font-weight' => '700'
                            ]
                        ]) ?>

                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 15px; margin-bottom: 5px;">или</div>

                    <div class="col-md-12 text-center">
                        <?= Html::a('Вернуться назад',['#'], [
                            'onclick' => 'return false',
                            'class' => 'link_singup',
                            'id' => 'go_back_login_form',
                        ]);?>
                    </div>

                </div>


                <div class="row style_go_password_recovery_for_email">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 45px 0;">Восстановление пароля</div>

                    <div class="col-md-12 text-center" style="margin: 45px 0 15px 0;">Введите адрес электронной почты (указанный при регистрации)</div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form_send_email',
                        'action' => Url::to(['/site/send-email']),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>

                    <div class="col-md-12" style="margin-top: 5px;">

                        <?= $form->field($model_send_email, 'email')->label(false)->input('email',[
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => 'Введите email',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 45px;">

                        <?= Html::submitButton('Отправить', [
                            'class' => 'btn btn-default',
                            'name' => 'send-email-button',
                            'style' => [
                                'background' => '#E0E0E0',
                                'color' => '4F4F4F',
                                'border-radius' => '8px',
                                'width' => '170px',
                                'height' => '40px',
                                'font-size' => '16px',
                                'font-weight' => '700'
                            ]
                        ]) ?>

                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 15px; margin-bottom: 5px;">или</div>

                    <div class="col-md-12 text-center">
                        <?= Html::a('Вернуться назад',['#'], ['onclick' => 'return false', 'class' => 'link_singup', 'id' => 'go_to_back_login_form',]);?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>


                <div class="row style_answer_for_password_recovery">

                    <div class="col-md-12 text-center title" style="font-size: 20px; margin: 25px 0 45px 0;"></div>

                    <div class="col-md-12 text-center text" style="margin: 45px 0 0 0;"></div>

                    <div class="col-md-12 text-center link_back" style="position: absolute; bottom: 0; height: 45px;">
                        <?= Html::a('Вернуться назад',['#'], ['onclick' => 'return false', 'class' => 'link_singup', 'id' => 'go2_to_back_login_form',]);?>
                    </div>

                </div>


                <div class="row style_error_not_confirm_singup">

                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 25px 0 45px 0;">Подтвердите регистрацию</div>

                    <div class="col-md-12 text-center ajax-message" style="margin: 45px 0 70px 0;"></div>

                    <div class="col-md-12 text-center" style="position: absolute; bottom: 0; height: 45px;">
                        <?= Html::a('Вернуться назад',['#'], [
                            'onclick' => 'return false',
                            'class' => 'link_singup',
                            'id' => 'go4_to_back_login_form',
                        ]);?>
                    </div>

                </div>


                <div class="row style_form_singup">

                    <?php $form = ActiveForm::begin([
                        'id' => 'form_user_singup',
                        'action' => Url::to(['/site/singup']),
                        'options' => ['class' => 'g-py-15'],
                        'errorCssClass' => 'u-has-error-v1',
                        'successCssClass' => 'u-has-success-v1-1',
                    ]); ?>


                    <div class="col-md-12 text-center" style="font-size: 20px; margin: 10px 0 20px 0; font-weight: 700; text-transform: uppercase;">Регистрация</div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'role', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Проектная роль пользователя</div><div>{input}</div>'
                        ])->widget(Select2::class, [
                            'data' => [User::ROLE_USER => 'Проектант', User::ROLE_ADMIN => 'Администратор'],
                            'options' => ['id' => 'type-interaction',],
                            'disabled' => false,  //Сделать поле неактивным
                            'hideSearch' => true, //Скрытие поиска
                        ]);
                        ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'email', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Email</div><div>{input}</div>'
                        ])->textInput([
                            'type' => 'email',
                            'required' => true,
                            'maxlength' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'telephone', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Телефон</div><div>{input}</div>'
                        ])->textInput([
                            'maxlength' => 50,
                            'minlength' => 6,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'second_name', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Фамилия</div><div>{input}</div>'
                        ])->textInput([
                            'maxlength' => 50,
                            'minlength' => 2,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            'autocomplete' => 'off'
                            ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'first_name', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Имя</div><div>{input}</div>'
                        ])->textInput([
                            'maxlength' => 50,
                            'minlength' => 2,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'middle_name', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Отчество</div><div>{input}</div>'
                        ])->textInput([
                            'maxlength' => 50,
                            'minlength' => 2,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => '',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'username', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Логин</div><div>{input}</div>'
                        ])->textInput([
                            'maxlength' => 32,
                            'minlength' => 3,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => 'Введите от 3 до 32 символов',
                            'autocomplete' => 'off'
                            ]) ?>

                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model_signup, 'password', [
                            'template' => '<div style="padding-left: 15px; padding-bottom: 5px;">Пароль</div><div>{input}</div>'
                        ])->passwordInput([
                            'maxlength' => 32,
                            'minlength' => 6,
                            'required' => true,
                            'class' => 'style_form_field_respond form-control',
                            'placeholder' => 'Введите от 6 до 32 символов',
                            'autocomplete' => 'off'
                        ]) ?>

                    </div>

                    <div class="col-md-12">

                        <div class="row">

                            <div class="col-md-4 text-center" style="margin-top: 5px;">

                                <?= $form->field($model_signup, 'exist_agree', [
                                    'template' => "{input}{label}"
                                ])->checkbox(['value' => 1, 'checked ' => true], false) ?>

                            </div>

                            <div class="col-md-4 text-center">

                                <?= Html::submitButton('Зарегистрировать меня', [
                                    'class' => 'btn btn-default',
                                    'name' => 'singup-button',
                                    'style' => [
                                        'margin-top' => '10px',
                                        'background' => '#E0E0E0',
                                        'color' => '4F4F4F',
                                        'border-radius' => '8px',
                                        'width' => '220px',
                                        'height' => '40px',
                                        'font-size' => '16px',
                                        'font-weight' => '700'
                                    ]
                                ]) ?>

                            </div>

                            <div class="col-md-4 text-center" style="margin-top: 15px;">

                                <?= Html::a('Вернуться назад',['#'], [
                                    'onclick' => 'return false',
                                    'class' => 'link_singup',
                                    'id' => 'go3_to_back_login_form',
                                    ]);?>

                            </div>

                        </div>

                    </div>

                    <?php ActiveForm::end(); ?>

                </div>


            <?php endif;?>


            <div class="content_main_page_block_text">

                <div>
                    <h1 class="top_title_main_page">Акселератор стартап-проектов</h1>
                </div>

                <div>
                    <div class="bottom_title_main_page">Customer Development <span>ШАГ</span> ЗА <span>ШАГОМ</span></div>
                </div>

            </div>

        </div>

    </div>


    <?php
    // Модальное окно - валидация данных при регистрации
    Modal::begin([
        'options' => [
            'id' => 'error_user_singup',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Измените данные согласно этой информации</h3>',
    ]);
    ?>

    <?php
    Modal::end();
    ?>


    <?php
    // Модальное окно - результате при регистрации и отправке письма на почту
    Modal::begin([
        'options' => [
            'id' => 'result_singup',
        ],
        'size' => 'modal-md',
        'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Информация</h3>',
    ]);
    ?>

    <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;"></h4>

    <?php
    Modal::end();
    ?>


    <?php if ($user->status === User::STATUS_NOT_ACTIVE) : ?>

        <?php
        // Модальное окно - Ошибка регистрации
        Modal::begin([
            'options' => [
                'id' => 'user_status',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Ожидайте активации вашего стутуса администратором</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Мы отправим Вам письмо на электронную почту, когда будет принято данное решение.
        </h4>

        <?php
        Modal::end();
        ?>


    <?php elseif ($user->status === User::STATUS_DELETED) : ?>

        <?php
        // Модальное окно - Ошибка регистрации
        Modal::begin([
            'options' => [
                'id' => 'user_status',
            ],
            'size' => 'modal-md',
            'header' => '<h3 class="text-center" style="color: #F2F2F2; padding: 0 30px;">Ваша учетная запись заблокирована</h3>',
        ]);
        ?>

        <h4 class="text-center" style="color: #F2F2F2; padding: 0 30px;">
            Обратитесь по этому вопросу к администратору.
        </h4>

        <?php
        Modal::end();
        ?>

    <?php endif; ?>


</div>


<?php


$script = "

    $(document).ready(function() {

        //Фон для модального окна информации (валидация данных при регистрации)
        var error_user_singup_modal = $('#error_user_singup').find('.modal-content');
        error_user_singup_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации (Ошибка регистрации)
        var error_model_singup_modal = $('#error_model_singup').find('.modal-content');
        error_model_singup_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации о статусе пользователя
        var user_status_modal = $('#user_status').find('.modal-content');
        user_status_modal.css('background-color', '#707F99');
        
        //Фон для модального окна информации об результате при регистрации и отправке письма на почту
        var result_singup_modal = $('#result_singup').find('.modal-content');
        result_singup_modal.css('background-color', '#707F99');
        
        //Если администратор не активировал пользователя показать сообщение в модальном окне
        $('#user_status').modal('show');

    });

    //Вернуться к форме входа
    $('body').on('click', '#go_back_login_form', function(){
        $('.style_error_not_user').hide();
        $('.style_form_login').show();
    });
    
    //Вернуться к форме входа
    $('body').on('click', '#go_to_back_login_form', function(){
        $('.style_go_password_recovery_for_email').hide();
        $('.style_form_login').show();
    });
    
    //Вернуться к форме входа
    $('body').on('click', '#go2_to_back_login_form', function(){
        $('.style_answer_for_password_recovery').hide();
        $('.style_form_login').show();
    });
    
    //Вернуться к форме входа
    $('body').on('click', '#go3_to_back_login_form', function(){
        $('.style_form_singup').hide();
        $('.content_main_page_block_text').show();
        $('.style_form_login').show();
    })
    
    //Вернуться к форме входа
    $('body').on('click', '#go4_to_back_login_form', function(){
        $('.style_error_not_confirm_singup').hide();
        $('.style_form_login').show();
    })
    
    
    //Перейти к отправке почты для восстановления пароля
    $('body').on('click', '#go_password_recovery_for_email', function(){
        $('.style_error_not_user').hide();
        $('.style_go_password_recovery_for_email').show();
    });
    
    //Вернуться к отправке почты для восстановления пароля
    $('body').on('click', '#go_back_password_recovery_for_email', function(){
        $('.style_answer_for_password_recovery').hide();
        $('.style_go_password_recovery_for_email').show();
    });
    
    //Переход к регистрации пользователя
    $('body').on('click', '#go_user_singup', function(){
        $('.style_form_login').hide();
        $('.content_main_page_block_text').hide();
        $('.style_form_singup').show();
    });

    //Отправка формы для входа пользователя
    $('body').on('beforeSubmit', '#login_user_form', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                if(response['error_not_user']) {
                    $('.style_form_login').hide();
                    $('.style_error_not_user').show();
                }
                
                if(response['error_not_confirm_singup']) {
                    $('.style_form_login').hide();
                    $('.style_error_not_confirm_singup').find('.ajax-message').html('');
                    $('.style_error_not_confirm_singup').find('.ajax-message').html(response['message']);
                    $('.style_error_not_confirm_singup').show();
                }
                
                if(response['user_success']) {
                    location.reload();
                }
                
                if(response['admin_success']) {
                    window.location.href = \"/admin\";
                }
                
            },
            error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();

        return false;
    });
    
    
    //Отправка формы для получения письма на почту для смены пароля
    $('body').on('beforeSubmit', '#form_send_email', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
                
                if(response['success']) {
                   
                    $('.style_go_password_recovery_for_email').hide();
                    $('.style_answer_for_password_recovery').find('.title').html(response['message']['title']);
                    $('.style_answer_for_password_recovery').find('.text').html(response['message']['text']);
                    $('.style_answer_for_password_recovery').show();
                }
                
                if(response['error']) {
                   
                    $('.style_go_password_recovery_for_email').hide();
                    $('.style_answer_for_password_recovery').find('.title').html(response['message']['title']);
                    $('.style_answer_for_password_recovery').find('.text').html(response['message']['text']);
                    $('.style_answer_for_password_recovery').find('.link_back').find('a').attr('id', 'go_back_password_recovery_for_email');
                    $('.style_answer_for_password_recovery').show();
                }
            },
            error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();

        return false;
    });
    
    
    
    //Отправка формы регистрации пользователя
    $('body').on('beforeSubmit', '#form_user_singup', function(e){
    
        var data = $(this).serialize();
        var url = $(this).attr('action');
        
        var error_user_singup_modal = $('#error_user_singup').find('.modal-body');
        error_user_singup_modal.html('');
        
        $.ajax({
        
            url: url,
            method: 'POST',
            data: data,
            cache: false,
            success: function(response){
            
                if(response['error_uniq_email']) {
                    error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - почтовый адрес уже зарегистрирован;<\/h4>');
                }
                
                if(response['error_uniq_username']) {
                    error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - логин уже зарегистрирован;<\/h4>');
                }
                
                if(response['error_match_username']) {
                    error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - логин должен содержать только латинские символы и цыфры, не допускается использование пробелов;<\/h4>');
                }
                
                if(response['error_exist_agree']) {
                    error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - необходимо принять пользовательское соглашение;<\/h4>');
                }
                
                if(response['error_uniq_email'] || response['error_uniq_username'] || response['error_exist_agree'] || response['error_match_username']) {
                    $('#error_user_singup').modal('show');
                }
                
                if(response['success_singup']){
                    $('.style_form_singup').hide();
                    $('.content_main_page_block_text').show();
                    $('.style_form_login').show();
                    $('#result_singup').find('.modal-body').find('h4').html('');
                    $('#result_singup').find('.modal-body').find('h4').html(response['message']);
                    $('#result_singup').modal('show');
                }
                
                if(response['error_singup_send_email']){
                    $('#result_singup').find('.modal-body').find('h4').html('');
                    $('#result_singup').find('.modal-body').find('h4').html(response['message']);
                    $('#result_singup').modal('show');
                }
                

            },
            error: function(){
                alert('Ошибка');
            }
        });
    
        e.preventDefault();

        return false;
    });
    
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);

?>