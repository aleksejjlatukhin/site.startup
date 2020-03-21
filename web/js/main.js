
$(document).ready(function() {

    //Добавить сегмент
    /*--------------------------------------------*/
    $('.open_fast').click(function () {
        $('.popap_fast').addClass('active');
        $('.open_fast').addClass('active');
    });

    $('.cross-out').click(function () {
        $('.popap_fast').removeClass('active');
        $('.open_fast').removeClass('active');
    });

    $('.link-del').click(function () {
        $('.feed-exp').addClass('active');
    });


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

});