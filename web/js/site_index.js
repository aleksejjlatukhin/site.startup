var body = $('body');

$(document).ready(function() {

    //Если администратор не активировал пользователя показать сообщение в модальном окне
    $('#user_status').modal('show');

});

//Вернуться к форме входа
$(body).on('click', '#go_back_login_form', function(){
    $('.style_error_not_user').hide();
    $('.style_form_login').show();
});

//Вернуться к форме входа
$(body).on('click', '#go_to_back_login_form', function(){
    $('.style_go_password_recovery_for_email').hide();
    $('.style_form_login').show();
});

//Вернуться к форме входа
$(body).on('click', '#go2_to_back_login_form', function(){
    $('.style_answer_for_password_recovery').hide();
    $('.style_form_login').show();
});

//Вернуться к форме входа
$(body).on('click', '#go3_to_back_login_form', function(){
    $('.style_form_singup').hide();
    $('.content_main_page_block_text').show();
    $('.style_form_login').show();
});

//Вернуться к форме входа
$(body).on('click', '#go4_to_back_login_form', function(){
    $('.style_error_not_confirm_singup').hide();
    $('.style_form_login').show();
});

//Перейти к отправке почты для восстановления пароля
$(body).on('click', '#go_password_recovery_for_email', function(){
    $('.style_error_not_user').hide();
    $('.style_go_password_recovery_for_email').show();
});

//Вернуться к отправке почты для восстановления пароля
$(body).on('click', '#go_back_password_recovery_for_email', function(){
    $('.style_answer_for_password_recovery').hide();
    $('.style_go_password_recovery_for_email').show();
});

//Переход к регистрации пользователя
$(body).on('click', '#go_user_singup', function(){
    $('.style_form_login').hide();
    $('.content_main_page_block_text').hide();
    $('.style_form_singup').show();
});


//Отправка формы для входа пользователя
$(body).on('beforeSubmit', '#login_user_form', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if(response.error_not_user) {
                $('.style_form_login').hide();
                $('.style_error_not_user').show();
            }

            if(response.error_not_confirm_singup) {
                $('.style_form_login').hide();
                var style_error_not_confirm_singup = $('.style_error_not_confirm_singup');
                $(style_error_not_confirm_singup).find('.ajax-message').html('');
                $(style_error_not_confirm_singup).find('.ajax-message').html(response.message);
                $(style_error_not_confirm_singup).show();
            }

            if(response.user_success) {
                location.reload();
            }

            if(response.admin_success) {
                window.location.href = '/admin';
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
$(body).on('beforeSubmit', '#form_send_email', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            var style_answer_for_password_recovery = $('.style_answer_for_password_recovery');

            if(response.success) {

                $('.style_go_password_recovery_for_email').hide();
                $(style_answer_for_password_recovery).find('.title').html(response.message.title);
                $(style_answer_for_password_recovery).find('.text').html(response.message.text);
                $(style_answer_for_password_recovery).show();
            }

            if(response.error) {

                $('.style_go_password_recovery_for_email').hide();
                $(style_answer_for_password_recovery).find('.title').html(response.message.title);
                $(style_answer_for_password_recovery).find('.text').html(response.message.text);
                $(style_answer_for_password_recovery).find('.link_back').find('a').attr('id', 'go_back_password_recovery_for_email');
                $(style_answer_for_password_recovery).show();
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
$(body).on('beforeSubmit', '#form_user_singup', function(e){

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

            if(response.error_uniq_email) {
                error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - почтовый адрес уже зарегистрирован;<\/h4>');
            }

            if(response.error_uniq_username) {
                error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - логин уже зарегистрирован;<\/h4>');
            }

            if(response.error_match_username) {
                error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - логин должен содержать только латинские символы и цыфры, не допускается использование пробелов;<\/h4>');
            }

            if(response.error_exist_agree) {
                error_user_singup_modal.append('<\h4 style=\"color: #F2F2F2; padding: 0 30px;\"> - необходимо согласие с настоящей Политикой конфиденциальности и условиями обработки персональных данных;<\/h4>');
            }

            if(response.error_uniq_email || response.error_uniq_username || response.error_exist_agree || response.error_match_username) {
                $('#error_user_singup').modal('show');
            }

            var result_singup = $('#result_singup');

            if(response.success_singup){
                $('.style_form_singup').hide();
                $('.content_main_page_block_text').show();
                $('.style_form_login').show();
                $(result_singup).find('.modal-body').find('h4').html('');
                $(result_singup).find('.modal-body').find('h4').html(response.message);
                $(result_singup).modal('show');
            }

            if(response.error_singup_send_email){
                $(result_singup).find('.modal-body').find('h4').html('');
                $(result_singup).find('.modal-body').find('h4').html(response.message);
                $(result_singup).modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});