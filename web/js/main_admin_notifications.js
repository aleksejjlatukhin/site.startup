//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');


// Отправка коммуникации
$(body).on('click', '.send-communication', function (e) {

    var url = $(this).attr('href');
    var container = $(this).parents('.response-action-to-communication');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function (response) {

            // Меняем в шапке сайта в иконке количество непрочитанных коммуникаций
            var blockCountUnreadCommunications = $(body).find('.countUnreadCommunications');
            var newQuantityAfterRead = response.countUnreadCommunications;
            $(blockCountUnreadCommunications).html(newQuantityAfterRead);
            if (newQuantityAfterRead < 1) $(blockCountUnreadCommunications).removeClass('active');

            if (response.type == 300) {

                $(container).html('<div class="text-success">Назначен(-а) на проект</div>')

            } else if (response.type == 350) {

                $(container).html('<div class="text-danger">Отказано</div>')
            }
        }
    });

    e.preventDefault();
    return false;
})


// Прочтение уведомления
$(body).on('click', '.link-read-notification', function (e) {

    var communication_id = $(this).attr('id').split('-')[1],
        url = '/admin/communications/read-communication?id=' + communication_id,
        container = $(this).parent();

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            $(container).html('<div class="text-success">Прочитано</div>');
            // Меняем в шапке сайта в иконке количество непрочитанных коммуникаций
            var blockCountUnreadCommunications = $(body).find('.countUnreadCommunications');
            var newQuantityAfterRead = response.countUnreadCommunications;
            $(blockCountUnreadCommunications).html(newQuantityAfterRead);
            if (newQuantityAfterRead < 1) $(blockCountUnreadCommunications).removeClass('active');
        }
    });

    e.preventDefault();
    return false;
});