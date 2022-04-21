//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');
var module = (window.location.pathname).split('/')[1];

// Ссылка на профиль пользователя
$(body).on('click', '.column-user-fio', function () {
    var id = $(this).attr('id').split('-')[1];
    var page = window.location.pathname.split('/')[3];
    if (page === 'index') location.href = '/profile/index?id=' + id;
    else if (page === 'group') location.href = '/profile/index?id=' + id;
    else if (page === 'admins') location.href = '/' + module + '/profile/index?id=' + id;
    else if (page === 'experts') location.href = '/expert/profile/index?id=' + id;
});


// Вызов модального окна для назначения админа пользователю
$(body).on('click', '.open_add_admin_modal', function () {

    var id = $(this).attr('id').split('-')[1];
    var url = '/' + module + '/users/get-modal-add-admin-to-user?id=' + id;
    var modal = $('#add_admin_modal');
    var container = $(modal).find('.modal-body');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
        },
        error: function(){
            alert('Ошибка');
        }
    });
});


// Сохранение админа для пользователя
$(body).on('beforeSubmit', '#formAddAdminToUser', function (e) {

    var id_admin = $('#selectAddAdminToUser').val();
    var data = $(this).serialize();
    var url = $(this).attr('action') + id_admin;

    $.ajax({
        url: url,
        data: data,
        method: 'POST',
        cache: false,
        success: function(response){

            // Изменение кнопки с ФИО админа
            var button = $('#open_add_admin_modal-' + response.user.id);
            $(button).html(response.admin.username);
            if ($(button).hasClass('btn-default')) {
                $(button).toggleClass('btn-success btn-default');
                $(button).css('background', '#52BE7F');
            }
            // Закрытие модального окна
            $('#add_admin_modal').modal('hide');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// Вызов модального окна для изменения статуса пользователя
$(body).on('click', '.open_change_status_modal', function () {

    var id = $(this).attr('id').split('-')[1];
    var url = '/' + module + '/users/get-modal-update-status?id=' + id;
    var modal = $('#change_status_modal');
    var container = $(modal).find('.modal-body');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
        },
        error: function(){
            alert('Ошибка');
        }
    });
});


// Сохранение cтатуса пользователя
$(body).on('beforeSubmit', '#formStatusUpdate', function (e) {

    var status = $('#selectStatusUpdate').val();

    if (status !== '200') {

        var data = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            data: data,
            method: 'POST',
            cache: false,
            success: function(response){

                // Изменение кнопки статуса пользователя
                var button = $('#open_change_status_modal-' + response.model.id);
                if (response.model.status === '0') {
                    $(button).html('Заблокирован');
                    if ($(button).hasClass('btn-default')) {
                        $(button).toggleClass('btn-danger btn-default');
                        $(button).css('background', '#d9534f');
                    }
                    else if ($(button).hasClass('btn-success')) {
                        $(button).toggleClass('btn-danger btn-success');
                        $(button).css('background', '#d9534f');
                    }
                }
                else if (response.model.status === '10') {
                    $(button).html('Активирован');
                    if ($(button).hasClass('btn-default')) {
                        $(button).toggleClass('btn-success btn-default');
                        $(button).css('background', '#52BE7F');
                    }
                    else if ($(button).hasClass('btn-danger')) {
                        $(button).toggleClass('btn-success btn-danger');
                        $(button).css('background', '#52BE7F');
                    }
                }

                // Закрытие модального окна
                $('#change_status_modal').modal('hide');
            },
            error: function(){
                alert('Ошибка');
            }
        });
    }
    else {

        var id_user = $(this).attr('action').split('=')[1];
        var user_container = $('.user_container_number-' + id_user);
        var user_fio = $(user_container).find('.block-fio').html();

        var modal_confirm = $('#confirm_user_delete_modal');
        $(modal_confirm).modal('show');

        var page = window.location.pathname.split('/')[3];
        if (page === 'index') $(modal_confirm).find('.modal-body').find('h4').html('Вы действительно хотите удалить пользователя «' + user_fio + '» и все его данные.');
        else if (page === 'group') $(modal_confirm).find('.modal-body').find('h4').html('Вы действительно хотите удалить пользователя «' + user_fio + '» и все его данные.');
        else if (page === 'admins') $(modal_confirm).find('.modal-body').find('h4').html('Вы действительно хотите удалить трекера «' + user_fio + '» и все его данные.');
        else if (page === 'experts') $(modal_confirm).find('.modal-body').find('h4').html('Вы действительно хотите удалить эксперта «' + user_fio + '» и все его данные.');

        $(modal_confirm).find('.modal-footer').find('.button_confirm_user_delete').attr('id', 'button_confirm_user_delete-' + id_user);
        // Закрытие модального окна изменения статуса
        $('#change_status_modal').modal('hide');
    }

    e.preventDefault();
    return false;
});


// Удаление пользователя
$(body).on('click', '.button_confirm_user_delete', function (e) {

    var id_user = $(this).attr('id').split('button_confirm_user_delete-')[1];
    var url = '/' + module + '/users/user-delete?id=' + id_user;

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            if (response.success) {
                // Удаляем блок пользователя, которого удалили
                $('.user_container_number-' + id_user).remove();
                // Закрытие модального окна
                $('#confirm_user_delete_modal').modal('hide');
            } else {
                // Показываем сообщение об ошибке
                $('#confirm_user_delete_modal').find('.modal-body').find('h4').html(response.message);
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// Обновляем колонку пользователей
setInterval(function(){

    $(body).find('.column-user-fio').each(function (index, item) {

        var id_user = $(item).attr('id').split('link_user_profile-')[1];

        $.ajax({
            url: '/' + module + '/users/update-data-column-user?id=' + id_user,
            method: 'POST',
            cache: false,
            success: function(response){

                $(item).html(response.renderAjax);
            }
        });

    });

}, 30000);
