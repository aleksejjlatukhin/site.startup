//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');


// При клике по строке с названием проекта
// Показываем коммуникации
$(body).on('click', '.container-one_hypothesis', function () {

    var project_id = $(this).parent().attr('id').split('-')[1];
    var block_data = $(this).parent().find('.hereAddProjectCommunications');

    if ($(block_data).is(':hidden')){

        var url = '/expert/communications/get-communications?project_id=' + project_id;

        $.ajax({
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){
                // Добавляем коммуникации по проекту в блок контента
                $(block_data).html(response.renderAjax);
            }
        });

        // Делаем активный блок неактиным
        $(body).find('.container-one_hypothesis.active').trigger('click');
        // Делаем выбранный блок активным
        $(this).addClass('active');
        $(this).parent().find('.container-one_hypothesis').css({
            'background' : '#7F9FC5',
            'border-radius' : '12px 12px 0px 0px',
        });
    }
    if ($(block_data).is(':visible')) {
        // Делаем выбранный блок неактивным
        $(this).removeClass('active');
        $(this).parent().find('.container-one_hypothesis').css({
            'background' : '#707F99',
            'border-radius' : '12px',
        });
    }

    // Меняем видимость блока
    $(block_data).toggle('display');
});


// Прочтение уведомления
$(body).on('click', '.link-read-notification', function (e) {

    var communication_id = $(this).attr('id').split('-')[1],
        url = '/expert/communications/read-communication?id=' + communication_id,
        container = $(this).parent();

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            $(container).hide();
            // Меняем в шапке сайта в иконке количество непрочитанных коммуникаций
            var blockCountUnreadCommunications = $(body).find('.countUnreadCommunications');
            var newQuantityAfterRead = response.countUnreadCommunications;
            $(blockCountUnreadCommunications).html(newQuantityAfterRead);
            if (newQuantityAfterRead < 1) $(blockCountUnreadCommunications).removeClass('active');
            // Меняем кол-во непрочитанных коммуникаций по проекту
            var blockCountUnreadCommunicationsByProject = $('#communications_project-' + response.project_id).find('.countUnreadCommunicationsByProject');
            var countUnreadCommunicationsByProject = response.countUnreadCommunicationsByProject;
            $(blockCountUnreadCommunicationsByProject).html(countUnreadCommunicationsByProject);
            if (countUnreadCommunicationsByProject < 1) $(blockCountUnreadCommunicationsByProject).hide();
        }
    });

    e.preventDefault();
    return false;
});


// Показать форму для ответа на коммуникацию
$(body).on('click', '.link-notification-response', function (e) {

    var communication_id = $(this).attr('id').split('-')[1],
        url = '/expert/communications/get-form-communication-response?id=' + communication_id,
        container = $(this).parent();

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            $(container).html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


// Отмена создания ответа на коммуникацию
$(body).on('click', '.cancel-create-response-communication', function (e) {

    var project_id = $(this).attr('id').split('-')[1],
        block_data = $(this).parents('.hereAddProjectCommunications'),
        url = '/expert/communications/get-communications?project_id=' + project_id;

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            // Обновляем содержимое блока для коммуникаций
            $(block_data).html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


// Сохранение формы ответа на коммуникацию
$(body).on('beforeSubmit', '#formCreateResponseCommunication', function (e) {

    var block_data = $(this).parents('.hereAddProjectCommunications'),
        url = $(this).attr('action'),
        data = $(this).serialize();

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){
            // Обновляем содержимое блока для коммуникаций
            $(block_data).html(response.renderAjax);
            // Меняем в шапке сайта в иконке количество непрочитанных коммуникаций
            var blockCountUnreadCommunications = $(body).find('.countUnreadCommunications');
            var newQuantityAfterRead = response.countUnreadCommunications;
            $(blockCountUnreadCommunications).html(newQuantityAfterRead);
            if (newQuantityAfterRead < 1) $(blockCountUnreadCommunications).removeClass('active');
            // Меняем кол-во непрочитанных коммуникаций по проекту
            var blockCountUnreadCommunicationsByProject = $('#communications_project-' + response.project_id).find('.countUnreadCommunicationsByProject');
            var countUnreadCommunicationsByProject = response.countUnreadCommunicationsByProject;
            $(blockCountUnreadCommunicationsByProject).html(countUnreadCommunicationsByProject);
            if (countUnreadCommunicationsByProject < 1) $(blockCountUnreadCommunicationsByProject).hide();
        }
    });

    e.preventDefault();
    return false;
})


// Создание беседы с трекером
$(body).on('click', '.link-create-conversation', function (e) {

    var url = $(this).attr('href'),
        container = $(this).parent();

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            if (response.success) {
                $(container).html('В сообщениях создана беседа с трекером.');
            }
        }
    });

    e.preventDefault();
    return false;
});