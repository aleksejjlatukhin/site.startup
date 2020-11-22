
$(document).ready(function() {

    // Проверка установленного значения B2C/B2B
    setInterval(function(){

        if($('#select2-type-interaction-container').html() === 'Коммерческие взаимоотношения между организацией и частным потребителем (B2C)'){

            $('.form-template-b2b').hide();
            $('.form-template-b2c').show();
        }

        else {

            $('.form-template-b2b').show();
            $('.form-template-b2c').hide();
        }

    }, 1000);


    //Фон для модального окна информации (сегмент с таким именем уже существует)
    var segment_already_exists_modal = $('#segment_already_exists').find('.modal-content');
    segment_already_exists_modal.css('background-color', '#707F99');

    //Фон для модального окна информации (данные не загружены)
    var data_not_loaded_modal = $('#data_not_loaded').find('.modal-content');
    data_not_loaded_modal.css('background-color', '#707F99');


    //Возвращение скролла первого модального окна после закрытия второго
    $('.modal').on('hidden.bs.modal', function (e) {
        if($('.modal:visible').length)
        {
            $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
            $('body').addClass('modal-open');
        }
    }).on('show.bs.modal', function (e) {
        if($('.modal:visible').length)
        {
            $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
            $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
        }
    });

});


var body = $('body');


//При нажатии на кнопку новый сегмент
$(body).on('click', '#showHypothesisToCreate', function(e){

    var url = $(this).attr('href');
    var hypothesis_create_modal = $('.hypothesis_create_modal');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(hypothesis_create_modal).modal('show');
            $(hypothesis_create_modal).find('.modal-body').html(response.renderAjax);

        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});



//Сохранение новой гипотезы из формы
$(body).on('beforeSubmit', '#hypothesisCreateForm', function(e){

    var data = $(this).serialize() + '&type_sort_id=' + $('#listType').val();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            //Если данные загружены и проверены
            if(response.success){

                //Закрываем модальное окно и делаем перезагрузку
                $('.hypothesis_create_modal').modal('hide');
                $('.block_all_hypothesis').html(response.renderAjax);
            }

            //Если сегмент с таким именем уже существует
            if(response.segment_already_exists){

                $('#segment_already_exists').modal('show');
            }

            //Если данные не загружены
            if(response.data_not_loaded){

                $('#data_not_loaded').modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});



//При нажатии на кнопку редактировать
$(body).on('click', '.update-hypothesis', function(e){

    var url = $(this).attr('href');
    var modal = $('.hypothesis_update_modal');

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(modal).modal('show');
            $(modal).find('.modal-body').html(response.renderAjax);
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});



//Редактирование гипотезы целевого сегмента
$(body).on('beforeSubmit', '#hypothesisUpdateForm', function(e){

    var data = $(this).serialize() + '&type_sort_id=' + $('#listType').val();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            //Если данные загружены и проверены
            if(response.success){

                $('.hypothesis_update_modal').modal('hide');
                $('.block_all_hypothesis').html(response.renderAjax);
            }

            //Если сегмент с таким именем уже существует
            if(response.segment_already_exists){

                $('#segment_already_exists').modal('show');
            }

            //Если данные не загружены
            if(response.data_not_loaded){

                $('#data_not_loaded').modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();

    return false;
});



//Сортировка сегментов
$(body).change('#listType', function(){

    var current_url = window.location.href;
    current_url = current_url.split('=');
    var current_id = current_url[1];

    var select_value = $('#listType').val();

    if (select_value !== null) {

        var url = '/segment/sorting-models?current_id=' + current_id + '&type_sort_id=' + select_value;

        $.ajax({
            url: url,
            method: 'POST',
            cache: false,
            success: function(response){

                $('.block_all_hypothesis').html(response.renderAjax);
            },
            error: function(){
                alert('Ошибка')
                ;}
        });
    }

});