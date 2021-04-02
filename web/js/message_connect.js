var identifyingBlock; // Блок для индентификации получателя сообщения
var id_user; // ID пользователя, который смотрит страницу


// Обновляем данные на странице
setInterval(function(){

    var body = $('body');
    id_user = $(body).find('.wrap').attr('id').split('-')[1];
    identifyingBlock = $(body).find('#identifying_recipient_new_message-' + id_user);

    // Извещаем получателя о новом сообщении
    if (id_user !== '0' && $(identifyingBlock).length) {

        $.ajax({
            url: '/message/get-count-unread-messages?id=' + id_user,
            method: 'POST',
            cache: false,
            success: function (response) {

                // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                var countUnreadMessages = $(body).find('.countUnreadMessages');
                if (response.countUnreadMessages > 0) {

                    if ($(countUnreadMessages).hasClass('active')) {
                        $(countUnreadMessages).html(response.countUnreadMessages);
                    } else {
                        $(countUnreadMessages).addClass('active');
                        $(countUnreadMessages).html(response.countUnreadMessages);
                    }
                } else {
                    if ($(countUnreadMessages).hasClass('active'))
                        $(countUnreadMessages).removeClass('active');
                }
            }
        });
    }

}, 30000);