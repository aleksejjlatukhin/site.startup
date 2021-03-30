//Установка Simple ScrollBar для блока выбора беседы
const simpleBarConversations = new SimpleBar(document.getElementById('conversation-list-menu'));


// Установка прелоадера
$(function () {
    var step = '',
        block_loading = $('#loading'),
        text = $(block_loading).text();

    function changeStep () {
        $(block_loading).text(text + step);
        if (step === '...') step = '';
        else step += '.';
    }

    var interval = setInterval(changeStep, 500);

    $(document).ready(function () {
        setTimeout(function () {
            clearInterval(interval);
            $('#preloader').fadeOut('500','swing');
        }, 3000);
    });
});


var body = $('body');

// Переход на страницу диалога
$(body).on('click', '.container-user_messages', function () {
    var id = $(this).attr('id').split('-')[1];
    if ($(this).attr('id').split('-')[0] === 'adminConversation') {
        window.location.href = '/message/view?id='+id;
    }
    else if ($(this).attr('id').split('-')[0] === 'conversationTechnicalSupport') {
        window.location.href = '/message/technical-support?id='+id;
    }
});

// Открытие и закрытие меню профиля на малых экранах
$(body).on('click', '.link_open_and_close_menu_profile', function () {
    $('.hide_block_menu_profile').toggle('display');
    if ($(this).html() === 'Открыть меню профиля') $(this).html('Закрыть меню профиля');
    else $(this).html('Открыть меню профиля');
});


$(document).ready(function () {

    // Отслеживаем событие workerman
    websocket.addEventListener('message', function (response) {

        // Получаем данные отправленные с сервера
        var data = JSON.parse(response.data);
        // Отслеживаем событие отправки сообщения
        if (data.action === 'send-message') {

            // Указываем по какому блоку определять получателя сообщения
            var identifyingBlockAdressee = $(body).find('#identifying_recipient_new_message-' + data.adressee_id);
            // Указываем по какому блоку определять отправителя сообщения
            var identifyingBlockSender = $(body).find('#identifying_recipient_new_message-' + data.sender_id);

            // Обновляем блок с беседами пользователя
            if ($(identifyingBlockAdressee).length || identifyingBlockSender.length)
                $(body).find('#conversation-list-menu').html(data.conversationsForUserAjax);
        }
    });
});


// Обновляем статус онлайн
setInterval(function(){

    var user_id = window.location.search.split('=')[1];
    var url = '/message/get-users-is-online?id=' + user_id + '&pathname=index';

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            var blockAdminOnline = $(body).find('#adminConversation-' + response.admin.conversation_id).find('.checkStatusOnlineUser');
            if ($(blockAdminOnline).hasClass('active')) {
                if (response.admin.isOnline !== true) $(blockAdminOnline).removeClass('active');
            } else {
                if (response.admin.isOnline === true) $(blockAdminOnline).addClass('active');
            }

            var blockDevOnline = $(body).find('#conversationTechnicalSupport-' + response.development.conversation_id).find('.checkStatusOnlineUser');
            if ($(blockDevOnline).hasClass('active')) {
                if (response.development.isOnline !== true) $(blockDevOnline).removeClass('active');
            } else {
                if (response.development.isOnline === true) $(blockDevOnline).addClass('active');
            }

        }, error: function(){
            alert('Ошибка');
        }
    });

}, 180000);