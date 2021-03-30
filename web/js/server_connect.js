// Открываем websocket соединение
const websocket = new WebSocket('ws://localhost:2346');

var body = $('body');
var identifyingBlock; // Блок для индентификации получателя сообщения

$(document).ready(function () {

    websocket.addEventListener('message', function (response) {

        // Получаем данные отправленные с сервера
        var data = JSON.parse(response.data);

        // Отслеживаем событие отправки сообщения
        if (data.action === 'send-message') {

            // Указываем по какому блоку определять получателя сообщения
            if (window.location.pathname === '/')
                identifyingBlock = $('footer').find('#identifying_recipient_message-' + data.adressee_id);
            else
                identifyingBlock = $(body).find('#identifying_recipient_new_message-' + data.adressee_id);

            // Извещаем получателя о новом сообщении
            if ($(identifyingBlock).length) {

                // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                var countUnreadMessages = $('.countUnreadMessages');
                if ($(countUnreadMessages).hasClass('active')) {
                    var oldQuantity = $(countUnreadMessages).html(); oldQuantity = Number.parseInt(oldQuantity);
                    var newQuantity = oldQuantity + 1;
                    $(countUnreadMessages).html(newQuantity);
                }
                else {
                    $(countUnreadMessages).addClass('active');
                    $(countUnreadMessages).html('1');
                }
            }
        }

        // Отслеживаем событие прочитывания сообщения
        else if (data.action === 'read-message') {

            // Указываем по какому блоку определять получателя сообщения
            var identifyingBlockAdressee = $(body).find('#identifying_recipient_new_message-' + data.message.adressee_id);

            // Обновляем данные на странице получателя
            // после того как он прочитал сообщение
            if ($(identifyingBlockAdressee).length) {

                // Меняем в шапке сайта в иконке количество непрочитанных сообщений
                var countUnreadMessagesAfterRead = $(body).find('.countUnreadMessages');
                var newQuantityAfterRead = data.countUnreadMessages;
                $(countUnreadMessagesAfterRead).html(newQuantityAfterRead);
                if (newQuantityAfterRead < 1) $(countUnreadMessagesAfterRead).removeClass('active');
                // Меняем в блоке бесед кол-во непрочитанных сообщений для конкретной беседы
                var blockConversation = $('#conversation-list-menu').find(data.blockConversation);
                var blockCountUnreadMessagesConversation = $(blockConversation).find('.countUnreadMessagesSender');
                var countUnreadMessagesForConversation = data.countUnreadMessagesForConversation;
                $(blockCountUnreadMessagesConversation).html(countUnreadMessagesForConversation);
                if (countUnreadMessagesForConversation < 1) $(blockCountUnreadMessagesConversation).removeClass('active');
            }
        }
    });

});