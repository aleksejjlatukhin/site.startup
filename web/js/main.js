
$(document).ready(function() {

    // Показать описание стадии
    /*--------------------------------------------*/
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
        $('.arrow_link').toggleClass('active_arrow_link');
        if ($('.arrow_link').hasClass('active_arrow_link')) {
            $('.segment_info_data').css('border-radius', '0');
            $('.block_description_stage').css({
                'opacity': '1',
                'max-height': '1000px',
                'padding': '15px',
                'transition': 'max-height 0.5s ease',
            });
        } else {
            $('.block_description_stage').css('max-height', '0');
            setTimeout(function() {$('.segment_info_data').css('border-radius', '0 0 12px 12px');}, 500);
            setTimeout(function() {$('.block_description_stage').css({'padding': '0', 'opacity': '0',});}, 400);
        }
    });
    /*--------------------------------------------*/


    //Показать и скрыть данные
    /*--------------------------------------------*/
    $('.faq_item_title_inner').on('click',function(){
        $(this).parents('.faq_item').find('.faq_item_body').slideToggle(300);
        $(this).toggleClass('open');
        if ($(this).hasClass('show_all')){
            if ($(this).hasClass('open')) {
                $(this).html('Свернуть все');
                $('.faq_item_title_inner:not(.open)').trigger('click');
            } else {
                $(this).html('Смотреть все');
                $('.faq_item_title_inner.open').trigger('click');
            }
        }
    });
    /*--------------------------------------------*/


    /*Всплывающие  блоки */
    $('[data-toggle="popover"]').popover({html:true});

    /*Разворащивающееся меню*/
    //$('.catalog').dcAccordion({speed:300});

    /*Всплывающие подсказки в сводной таблице проекта(Bootstrap)*/
    /*$('[data-toggle="tooltip"]').tooltip({
        placement: "top",
        delay: {"show": 100, "hide": 100},
    });*/

    /*Всплывающие подсказки в сводной таблице проекта (Query UI Tooltip)*/
    $('[data-toggle="tooltip"]').tooltip({
        /*position: {
            my: "center bottom-10",
            at: "center top",
        },
        show: {
            effect: "slideDown",
            delay: 100
        },*/
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "pro-tooltip" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        }
    });



    /*
    Вкладки на странице "Программа генерации ГПС"
    ---это небольшая часть(остальное находится ниже)--
    */

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();

    /*
    Вкладки на странице "Программа генерации ГПС"
    ---это небольшая часть(остальное находится ниже)--
    */

});


/*
Вкладки на странице "Программа генерации ГПС"
---Начало---
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
Вкладки на странице "Программа генерации ГПС"
---Конец---
*/


/*
Добавляем контент в окно просмотра данных проекта
---Начало---
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
Добавляем контент в окно просмотра данных проекта
---Конец---
 */


/*
Добавляем контент в окно просмотра данных сегмента
---Начало---
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
Добавляем контент в окно просмотра данных сегмента
---Конец---
 */


/*
Добавляем контент в дорожную карту проекта
---Начало---
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
Добавляем контент в дорожную карту проекта
---Конец---
 */

/*
Добавляем контент в дорожную карту сегмента
---Начало---
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
Добавляем контент в дорожную карту сегмента
---Конец---
*/

/*
При вызове окна удаления гипотезы
---Начало---
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
При вызове окна удаления гипотезы
---Конец---
*/

/*
При подтверждении удаления гипотезы
---Начало---
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
При подтверждении удаления гипотезы
---Конец---
*/


/*
Добавляем контент в сводную таблицу проекта
---Начало---
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
Добавляем контент в сводную таблицу проекта
---Конец---
 */


/*
Добавляем контент в протокол проекта
---Начало---
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
Добавляем контент в протокол проекта
---Конец---
 */