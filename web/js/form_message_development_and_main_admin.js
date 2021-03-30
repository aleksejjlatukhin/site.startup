var body = $('body');


// Передаем событие клика по кнопке сохранить сообщение
$(body).on('click', '.send_message_button', function () {
    $('#submit_send_message').trigger('click');
});


// Сохраняем форму отправки сообщения
$(body).on('beforeSubmit', '#create-message-development', function (e) {

    var form = $(this);
    var url = form.attr('action');
    var formData = new FormData(form[0]);

    $.ajax({

        url: url,
        method: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        cache: false,
        success: function(response){

            // Отправляем данные workerman
            websocket.send(JSON.stringify(response));
            // Очищаем форму сообщений
            $('#create-message-development')[0].reset();
            $('#input_send_message').html('');
            // Очищаем и скрываем блок для показа загруженных файлов
            var block_attach_files = $('.block_attach_files');
            if ($(block_attach_files).css('display') === 'block') {
                $(block_attach_files).html('');
                $(block_attach_files).css('display', 'none');
            }
            // Корректируем высоту блока сообщений и поля textarea
            var heightScreen = $(body).height(); // Высота экрана
            $('.data-chat').css('height', (heightScreen - 290));
            $('textarea#input_send_message').css('height', '51px').attr('required', true);

        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// Обновляем данные на странице
websocket.onmessage = function(response) {

    // Получаем данные отправленные с сервера
    var data = JSON.parse(response.data);

    // Отслеживаем событие отправки сообщения
    if (data.action === 'send-message') {

        // Указываем по какому блоку определять получателя сообщения
        var identifyingBlockAdressee = $(body).find('#identifying_recipient_new_message-' + data.adressee_id);
        // Указываем по какому блоку определять отправителя сообщения
        var identifyingBlockSender = $(body).find('#identifying_recipient_new_message-' + data.sender_id);
        // Блок чата, куда добавляются сообщения
        var chat = $(body).find('#data-chat');

        // Добавляем новое сообщение на страницу получателя
        if ($(identifyingBlockAdressee).length && data.location_pathname === window.location.pathname
            && data.conversation_id === window.location.search.split('=')[1]) {

            // Если в беседе ранее не было сообщений, то удаляем этот блок с текстом
            if ($(chat).find('.block_not_exist_message').length)
                $(chat).find('.block_not_exist_message').remove();
            // Добавляем новое сообщение в конец чата
            $(chat).find('.simplebar-content').append(data.messageAjax);
        }

        // Добавляем новое сообщение на страницу отправителя
        if ($(identifyingBlockSender).length) {

            // Если в беседе ранее не было сообщений, то удаляем этот блок с текстом
            if ($(chat).find('.block_not_exist_message').length)
                $(chat).find('.block_not_exist_message').remove();
            // Добавляем новое сообщение в конец чата
            $(chat).find('.simplebar-content').append(data.messageAjax);
        }

        // Делаем скролл чата только на странице отправителя
        if (data.sender === 'development' && $(identifyingBlockSender).length)
            simpleBarDataChatDevelopment.getScrollElement().scrollTop = simpleBarDataChatDevelopment.getScrollElement().scrollHeight;
        else if (data.sender === 'main_admin' && $(identifyingBlockSender).length)
            simpleBarDataChatMainAdmin.getScrollElement().scrollTop = simpleBarDataChatMainAdmin.getScrollElement().scrollHeight;
    }

    // Отслеживаем событие прочитывания сообщения
    else if (data.action === 'read-message') {

        var message_id = data.message.id;

        // Указываем по какому блоку определять получателя сообщения
        var identifyingBlockAdresseeAfterRead = $(body).find('#identifying_recipient_new_message-' + data.message.adressee_id);
        // Указываем по какому блоку определять отправителя сообщения
        var identifyingBlockSenderAfterRead = $(body).find('#identifying_recipient_new_message-' + data.message.sender_id);

        if ($(identifyingBlockAdresseeAfterRead).length || $(identifyingBlockSenderAfterRead).length) // Делаем сообщение прочитанным
            $(body).find('.data-chat').find('#message_id-' + message_id).removeClass('unreadmessage');
    }

};


// Отслеживаем высоту textarea и изменяем высоту блока сообщений
$(body).on('input', 'textarea#input_send_message', function () {

    var changeHeightTetxtarea = $(this).css('height').split('px')[0] - 64,
        heightScreen = $(body).height(),
        block_attach_files = $('.block_attach_files'),
        heightBlockAttachFiles = 0;

    // Корректируем высоту блока сообщений
    if ($(block_attach_files).css('display') === 'block') heightBlockAttachFiles = $(block_attach_files).css('height').split('px')[0];
    $('.data-chat').css('height', (heightScreen - changeHeightTetxtarea - heightBlockAttachFiles - 290));


    // Сохраняем данные в кэш
    var conversation_id = window.location.search.split('=')[1];
    var url = '/admin/message/save-cache-message-development-form?id=' + conversation_id;
    var data = $(this).serialize();
    $.ajax({
        url: url,
        data: data,
        method: 'POST',
        cache: false,
        error: function(){
            alert('Ошибка');
        }
    });

});


// При клике на иконку прикрепления файлов
// имитировать нажатие на настоящую кнопку
$(body).on('click', '.attach_files_button', function () {
    $('#input_message_files').trigger('click');
});


// Отслеживаем изменения в поле загрузки файлов
$(body).on('change', '#input_message_files', function () {

    var block_attach_files = $('.block_attach_files'), // Блок для показа загруженных файлов
        heightScreen = $(body).height(), // Высота экрана
        input_send_message = $('textarea#input_send_message').css('height').split('px')[0] - 64; // Высота поля description

    // Очищаем блок для показа загруженных файлов
    $(block_attach_files).html('');
    //Количество добавленных файлов
    var add_count = this.files.length;

    if (6 > add_count > 0) {
        // Если загружены файлы делаем поле description необязательным
        $('#input_send_message').attr('required', false);
        // Показываем загруженные файлы
        for (var i = 0; i < this.files.length; i++) $(block_attach_files).append('<div>' + this.files[i].name + '</div>');
        $(block_attach_files).css('display', 'block');
    } else if(add_count > 5) {
        // Поле description обязательно
        $('#input_send_message').attr('required', true);
        // Очищаем массив files
        $(this)[0].value = "";
        // Показываем сообщение о превышении лимита на загрузку файлов
        $(block_attach_files).append('<div class="text-danger">Максимальное количество - 5 файлов</div>');
        $(block_attach_files).css('display', 'block');
    }else {
        // Поле description обязательно
        $('#input_send_message').attr('required', true);
    }

    // Корректируем высоту блока сообщений
    $('.data-chat').css('height', (heightScreen - input_send_message - $(block_attach_files).css('height').split('px')[0] - 290));

});


//Постраничная навигация сообщений (вывод предыдущих сообщений)
$(body).on('click', '.pagination-messages .messages-pagination-list li.next a', function(e){

    var conversation_id = window.location.search.split('=')[1];
    var pagination_active_page = $('.pagination-messages .messages-pagination-list li.pagination_active_page a');
    var number_active_page = $(pagination_active_page).html();
    var next_page = Number.parseInt(number_active_page);
    var idFirstMessagePreviosPage = $('.message:first').attr('id').split('-')[1];
    var url = '/admin/message/get-page-message-development?id=' + conversation_id + '&page=' + next_page + '&final=' + idFirstMessagePreviosPage;

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            // Удаляем старые элементы пагинации
            var oldPaginationMessages = $('.pagination-messages'); $(oldPaginationMessages).remove();
            var oldBlockForLinkNextPageMasseges = $('.block_for_link_next_page_masseges'); $(oldBlockForLinkNextPageMasseges).remove();
            // Добавляем на страницу предыдущие сообщения
            $('#data-chat').find('.simplebar-content').prepend(response.nextPageMessageAjax);

            if (response.lastPage){
                // На последней странице удаляем ссылку для показа предыдущих сообщений
                var newBlockForLinkNextPageMasseges = $(body).find('.block_for_link_next_page_masseges');
                $(newBlockForLinkNextPageMasseges).remove();
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// При клике на этот элемент показываем предыдущие сообщения
$(body).on('click', '.button_next_page_masseges', function (e) {

    $('.pagination-messages .messages-pagination-list li.next a').trigger('click');
    e.preventDefault();
    return false;
});