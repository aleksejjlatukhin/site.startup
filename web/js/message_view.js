//Установка Simple ScrollBar для блока выбора беседы
const simpleBarConversations = new SimpleBar(document.getElementById('conversation-list-menu'));
//Установка Simple ScrollBar для блока сообщений
const simpleBarDataChatUser = new SimpleBar(document.getElementById('data-chat'));


var body = $('body');

// Прокрутка блока сообщений (во время работы прелоадера)
window.addEventListener('DOMContentLoaded', function() {

    // Первое непрочитанное сообщение для пользователя
    var unreadmessage = $(body).find('.addressee-user.unreadmessage:first');
    if ($(unreadmessage).length)
        simpleBarDataChatUser.getScrollElement().scrollTop = $(unreadmessage).offset().top - $(unreadmessage).height() - $('.data-chat').height();
    else
        simpleBarDataChatUser.getScrollElement().scrollTop = simpleBarDataChatUser.getScrollElement().scrollHeight;
});


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
    if ($('.conversation-list-menu').css('position') === 'fixed') $('.button_open_close_list_users').toggle('display');
    if ($(this).html() === 'Открыть меню профиля') $(this).html('Закрыть меню профиля');
    else $(this).html('Открыть меню профиля');
});


// Открытие и закрытие списка пользователей на малых экранах
$(body).on('click', '.button_open_close_list_users', function () {

    $('.link_open_and_close_menu_profile').toggle('display');
    var conversation_list_menu = $('.conversation-list-menu');
    if ($(conversation_list_menu).hasClass('active')) {
        $(this).html('Открыть список пользователей');
        $(this).css('background', '#707F99');
        $(conversation_list_menu).removeClass('active');
    }
    else {
        $(this).html('Закрыть список пользователей');
        $(this).css('background', '#4F4F4F');
        $(conversation_list_menu).addClass('active');
    }
});


$(document).ready(function () {

    // Отслеживаем событие отправки сообщения
    websocket.addEventListener('message', function (response) {

        // Получаем данные отправленные с сервера
        var data = JSON.parse(response.data);
        // Отслеживаем событие отправки сообщения
        if (data.action === 'send-message') {

            // Указываем по какому блоку определять получателя сообщения
            var identifyingBlockAdressee = $(body).find('#identifying_recipient_new_message-' + data.adressee_id);
            // Указываем по какому блоку определять отправителя сообщения
            var identifyingBlockSender = $(body).find('#identifying_recipient_new_message-' + data.sender_id);

            var conversation_list_menu = $('#conversation-list-menu');
            var conversation_id = $(conversation_list_menu).find('.active-message').attr('id');
            conversation_id = '#' + conversation_id;

            // Обновляем блок с беседами проектанта
            if ($(identifyingBlockAdressee).length || identifyingBlockSender.length) {
                $(conversation_list_menu).html(data.conversationsForUserAjax);
                $(conversation_list_menu).find(conversation_id).addClass('active-message');
            }
        }
    });


    // Если высота блока сообщений не имеет скролла, то при открытии
    // страницы непрочитанные сообщения станут прочитанными
    var timeoutReadMessage;
    var heightScreen = $(body).height(); // Высота экрана
    var scrollHeight = simpleBarDataChatUser.getScrollElement().scrollHeight; // Высота скролла
    if (scrollHeight <= heightScreen - 290) {

        var chat = $(body).find('.data-chat');
        if(timeoutReadMessage) clearTimeout(timeoutReadMessage);
        timeoutReadMessage = setTimeout(function() { //чтобы не искать одно и то же несколько раз

            $(chat).find('.addressee-user.unreadmessage').each(function (index, item) {

                var message_id = $(item).attr('id').split('-')[1];
                var url = '/message/read-message-admin?id=' + message_id;

                $.ajax({
                    url: url,
                    method: 'POST',
                    cache: false,
                    success: function(response){
                        // Отправляем данные workerman
                        websocket.send(JSON.stringify(response));
                    },
                    error: function(){
                        alert('Ошибка');
                    }
                });
            });
        },100);
    }

    // Отслеживаем скролл непрочитанных сообщений
    simpleBarDataChatUser.getScrollElement().addEventListener('scroll', function () {

        var chat = $(body).find('.data-chat');
        if(timeoutReadMessage) clearTimeout(timeoutReadMessage);
        timeoutReadMessage = setTimeout(function() { //чтобы не искать одно и то же несколько раз

            $(chat).find('.addressee-user.unreadmessage').each(function (index, item) {

                var scrollTop = simpleBarDataChatUser.getScrollElement().scrollTop,
                    scrollHeight = simpleBarDataChatUser.getScrollElement().scrollHeight,
                    posTop = $(item).offset().top;

                if (posTop + ($(item).height() / 2) <= $(chat).height() || scrollTop + $(item).height() > scrollHeight - $(chat).height()) {

                    var message_id = $(item).attr('id').split('-')[1];
                    var url = '/message/read-message-admin?id=' + message_id;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        cache: false,
                        success: function(response){
                            // Отправляем данные workerman
                            websocket.send(JSON.stringify(response));
                        },
                        error: function(){
                            alert('Ошибка');
                        }
                    });
                }
            });
        },100);
    });

});


// Обновляем статус онлайн
setInterval(function(){

    var conversation_id = window.location.search.split('=')[1];
    var url = '/message/get-users-is-online?id=' + conversation_id + '&pathname=view';

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
