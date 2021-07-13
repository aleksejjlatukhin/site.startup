//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');

//Отслеживаем изменения в форме роли пользователя
$(body).on('change', 'form#form_user_role', function(){

    var url = $(this).attr('action');
    var data = $(this).serialize();
    $.ajax({
        url: url,
        data: data,
        method: 'POST',
        cache: false,
        success: function(response){

            if ($(window).width() > 1000 && $(window).width() < 1700) {
                $('.wrap').css('margin-bottom', '0');
            } else {
                $('.wrap').css('margin-bottom', '20px');
            }
            $('.block-form-registration').html(response.renderAjax);

        }, error: function(){
            alert('Ошибка');
        }
    });
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
                $(body).append($('#error_user_singup').first());
                $('#error_user_singup').modal('show');
            }

            var result_singup = $('#result_singup');

            if(response.success_singup){
                $('.result-registration').html('<\h3 style=\"color: #FFFFFF;\" class=\"text-center\">' + response.message + '<\/h3>')
                $('.wrap').css('margin-bottom', '20px');
            }

            if(response.error_singup_send_email){
                $(result_singup).find('.modal-body').find('h4').html('');
                $(result_singup).find('.modal-body').find('h4').html(response.message);
                $(body).append($(result_singup).first());
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