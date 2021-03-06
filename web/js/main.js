
$(document).ready(function() {

    /*
    Динамическое изменение высоты и ширины textarea
    */
    $('body').on('input', 'textarea', function() {

        this.style.height = '60px';
        this.style.width = '100%';
        this.style.height = (this.scrollHeight + 6) + 'px';
    });

    /*
    Показать описание стадии
    */
    $('.view_desc_stage').hover(function() {
        $('.arrow_link').find('span').css({
            'height': '2px',
            'transition': 'all 0.2s ease',
        });
    }).mouseleave(function(){
        $('.arrow_link').find('span').css({
            'height': '1px',
            'transition': 'all 0.2s ease',
        });
    }).on('click', function() {
        var arrow_link = $('.arrow_link');
        $(arrow_link).toggleClass('active_arrow_link');
        if ($(arrow_link).hasClass('active_arrow_link')) {

            $('.block_description_stage').css({
                'display':'block',
            });

            setTimeout(function() {
                $('.segment_info_data').css('border-radius', '0');
                $('.block_description_stage').css({
                    'display':'block',
                    'opacity': '1',
                    'max-height': '1000px',
                    'padding': '15px',
                    'transition': 'max-height 0.5s ease',
                });
            }, 100);

        } else {
            $('.block_description_stage').css('max-height', '0');
            setTimeout(function() {$('.segment_info_data').css('border-radius', '0 0 12px 12px');}, 500);
            setTimeout(function() {$('.block_description_stage').css({'padding': '0', 'opacity': '0'});}, 400);
            setTimeout(function() {$('.block_description_stage').css({'display':'none'});}, 1000);
        }
    });


    /*
    Разворащивающееся меню
    */
    //$('.catalog').dcAccordion({speed:300});

    /*
    Открываем при загругрузке страницы событием click дефолтные вкладки на страницах подтверждений
    */
    var url_pathname = location.pathname;
    var array_search_results = [
        '/confirm-segment/add-questions', '/confirm-segment/view', '/confirm-problem/add-questions', '/confirm-problem/view',
        '/confirm-gcp/add-questions', '/confirm-gcp/view', '/confirm-mvp/add-questions', '/confirm-mvp/view',
    ];
    if (url_pathname !== '/') {
        array_search_results.forEach(function (elem) {
            if(elem.indexOf(url_pathname) !== -1) {
                document.getElementById("defaultOpen").click();
            }
        });
    }

});


/*
Вкладки на странице "Подтверждение"
*/
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}


/*
Добавляем контент в окно просмотра данных проекта
 */
$(document).on('click', 'body .openAllInformationProject', function(e) {

    var url = $(this).attr('href');
    var modal = $('#data_project_modal');
    var container = $(modal).find('.modal-body');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


/*
Добавляем контент в окно просмотра данных сегмента
 */
$(document).on('click', 'body .openAllInformationSegment', function(e) {

    var url = $(this).attr('href');
    var modal = $('#data_segment_modal');
    var container = $(modal).find('.modal-body');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


/*
Добавляем контент в дорожную карту проекта
 */
$(document).on('click', 'body .openRoadmapProject', function(e) {

    var url = $(this).attr('href');
    var modal = $('#showRoadmapProject');
    var container = $(modal).find('.modal-body');
    var header = $(modal).find('.modal-header').find('h2');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
            $(header).html('Дорожная карта проекта «' + response.project.project_name + '»');
        }
    });

    e.preventDefault();
    return false;
});


/*
Добавляем контент в дорожную карту сегмента
*/
$(document).on('click', 'body .openRoadmapSegment', function(e) {

    var url = $(this).attr('href');
    var modal = $('#showRoadmapSegment');
    var container = $(modal).find('.modal-body');
    var header = $(modal).find('.modal-header').find('.roadmap_segment_modal_header_title_h2');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
            $(header).html('Дорожная карта сегмента «' + response.segment.name + '»');
        }
    });

    e.preventDefault();
    return false;
});


/*
При вызове окна удаления гипотезы
*/
$(document).on('click', 'body .delete_hypothesis', function(e) {

    var url = $(this).attr('href');
    var modal = $('#delete_hypothesis_modal');
    var model_id = url.split('id=')[1];
    var controller = url.split('/')[1];
    var hypothesis_title = $('.row_hypothesis-' + model_id).find('.hypothesis_title').html();

    switch (controller) {
        case 'projects':
            $(modal).find('.modal-body').find('h4').html('Вы дейтвительно хотите удалить проект «' + hypothesis_title + '» ?');
            break;
        case 'segment':
            $(modal).find('.modal-body').find('h4').html('Вы дейтвительно хотите удалить сегмент «' + hypothesis_title + '» ?');
            break;
        default:
            $(modal).find('.modal-body').find('h4').html('Вы дейтвительно хотите удалить «' + hypothesis_title + '» ?');
    }

    $(modal).find('#confirm_delete_hypothesis').attr('href', url);
    $(modal).modal('show');

    e.preventDefault();
    return false;
});


/*
При подтверждении удаления гипотезы
*/
$(document).on('click', 'body #confirm_delete_hypothesis', function(e) {

    var url = $(this).attr('href');
    var model_id = url.split('id=')[1];

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(){

            $('.row_hypothesis-' + model_id).hide();
            $('#delete_hypothesis_modal').modal('hide');
        },
        error: function () {
           alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


/*
Добавляем контент в сводную таблицу проекта
 */
$(document).on('click', 'body .openResultTableProject', function(e) {

    var url = $(this).attr('href');
    var modal = $('#showResultTableProject');
    var container = $(modal).find('.modal-body');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


/*
Добавляем контент в протокол проекта
 */
$(document).on('click', 'body .openReportProject', function(e) {

    var url = $(this).attr('href');
    var modal = $('#showReportProject');
    var container = $(modal).find('.modal-body');
    var header = $(modal).find('.modal-header').find('h2');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(container).html(response.renderAjax);
            $(header).html('Протокол проекта «' + response.project.project_name + '»');
        }
    });

    e.preventDefault();
    return false;
});


/*
Отслеживаем клик по звездочке в списке вопросов
*/
$(document).on('click', 'a.star-link', function (e) {

    var url = $(this).attr('href');
    var star = $(this).find('.star');

    if ($(star).hasClass('active')) {
        $(star).removeClass('active');
    } else {
        $(star).addClass('active');
    }

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        error: function(){alert('Ошибка')},
    });

    e.preventDefault();
    return false;
});