//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

$(document).ready(function() {

    // Проверка установленного значения B2C/B2B
    setInterval(function(){

        if($('#select2-type-interaction-container').html() === 'B2C'){

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
    $('.modal').on('hidden.bs.modal', function () {
        if($('.modal:visible').length)
        {
            $('.modal-backdrop').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) - 10);
            $('body').addClass('modal-open');
        }
    }).on('show.bs.modal', function () {
        if($('.modal:visible').length)
        {
            $('.modal-backdrop.in').first().css('z-index', parseInt($('.modal:visible').last().css('z-index')) + 10);
            $(this).css('z-index', parseInt($('.modal-backdrop.in').first().css('z-index')) + 10);
        }
    });

});


var body = $('body');
var id_page = window.location.search.split('=')[1];


// Показать инструкцию для стадии разработки
$(body).on('click', '.open_modal_instruction_page', function (e) {

    var url = $(this).attr('href');
    var modal = $('.modal_instruction_page');
    $(body).append($(modal).first());

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            if ($(modal).find('.modal-header').find('.modal-header-text-append').length === 0) {
                $(modal).find('.modal-header').append('<div class="modal-header-text-append">Генерация гипотез целевых сегментов</div>');
            }
            $(modal).find('.modal-body').html(response);
            $(modal).modal('show');
        }
    });

    e.preventDefault();
    return false;
});


//Отслеживаем изменения в форме создания сегмента и записываем их в кэш
$(body).on('change', 'form#hypothesisCreateForm', function(){

    var url = '/segments/save-cache-creation-form?id=' + id_page;
    var data = $(this).serialize();
    $.ajax({url: url, data: data, method: 'POST'});
});


//При нажатии на кнопку новый сегмент
$(body).on('click', '#showHypothesisToCreate', function(e){

    var url = $(this).attr('href');
    var hypothesis_create_modal = $('.hypothesis_create_modal');
    $(body).append($(hypothesis_create_modal).first());

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(hypothesis_create_modal).modal('show');
            $(hypothesis_create_modal).find('.modal-body').html(response.renderAjax);

            // Расчет платежеспособности B2C
            var incomeFrom = parseInt($("input#income_from").val());
            var incomeTo = parseInt($("input#income_to").val());
            var quantity = parseInt($("input#quantity").val());
            if (incomeFrom > 0 && incomeTo > 0 && quantity > 0) {
                var res = ((incomeFrom + incomeTo) * 6) * quantity / 1000000;
                $("input#market_volume_b2c").val(Math.round(res));
            }

            // Расчет платежеспособности B2B
            var incomeFromB2B = parseInt($("input#income_from_b2b").val());
            var incomeToB2B = parseInt($("input#income_to_b2b").val());
            var quantityB2B = parseInt($("input#quantity_b2b").val());
            if (incomeFromB2B > 0 && incomeToB2B > 0 && quantityB2B > 0) {
                var resB2B = ((incomeFromB2B + incomeToB2B) / 2) * quantityB2B;
                $("input#market_volume_b2b").val(Math.round(resB2B));
            }
        }
    });

    e.preventDefault();
    return false;
});



//Сохранение новой гипотезы из формы
$(body).on('beforeSubmit', '#hypothesisCreateForm', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');
    var id = url.split('=')[1];

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            //Если данные загружены и проверены
            if(response.success){

                if (response.count === '1') {
                    $('.hypothesis_create_modal').modal('hide');
                    location.href = '/segments/index?id=' + id;
                } else {
                    $('.hypothesis_create_modal').modal('hide');
                    $('.block_all_hypothesis').html(response.renderAjax);
                }
            }

            //Если сегмент с таким именем уже существует
            if(response.segment_already_exists){

                var segment_already_exists = $('#segment_already_exists');
                $(body).append($(segment_already_exists).first());
                $(segment_already_exists).modal('show');
            }

            //Если данные не загружены
            if(response.data_not_loaded){

                var data_not_loaded = $('#data_not_loaded');
                $(body).append($(data_not_loaded).first());
                $(data_not_loaded).modal('show');
            }
        }
    });

    e.preventDefault();
    return false;
});



//При нажатии на кнопку редактировать
$(body).on('click', '.update-hypothesis', function(e){

    var url = $(this).attr('href');
    var hypothesis_update_modal = $('.hypothesis_update_modal');
    $(body).append($(hypothesis_update_modal).first());

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(hypothesis_update_modal).modal('show');
            $(hypothesis_update_modal).find('.modal-body').html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


var catchChange = false;
//Отслеживаем изменения в форме редактирования сегмента
$(body).on('change', 'form#hypothesisUpdateForm', function(){
    if (catchChange === false) catchChange = true;
});

//Если в форме редактирования были внесены изменения,
//то при любой попытке закрыть окно показать окно подтверждения
$(body).on('hide.bs.modal', '.hypothesis_update_modal', function(e){
    if(catchChange === true) {
        $('#confirm_closing_update_modal').appendTo('body').modal('show');
        e.stopImmediatePropagation();
        e.preventDefault();
        return false;
    }
});


//Подтверждение закрытия окна редактирования сегмента
$(body).on('click', '#button_confirm_closing_modal', function (e) {
    catchChange = false;
    $('#confirm_closing_update_modal').modal('hide');
    $('.hypothesis_update_modal').modal('hide');
    e.preventDefault();
    return false;
});


//Редактирование гипотезы целевого сегмента
$(body).on('beforeSubmit', '#hypothesisUpdateForm', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            //Если данные загружены и проверены
            if(response.success){

                if (catchChange === true) catchChange = false;
                $('.hypothesis_update_modal').modal('hide');
                $('.block_all_hypothesis').html(response.renderAjax);
            }

            //Если сегмент с таким именем уже существует
            if(response.segment_already_exists){

                var segment_already_exists = $('#segment_already_exists');
                $(body).append($(segment_already_exists).first());
                $(segment_already_exists).modal('show');
            }

            //Если данные не загружены
            if(response.data_not_loaded){

                var data_not_loaded = $('#data_not_loaded');
                $(body).append($(data_not_loaded).first());
                $(data_not_loaded).modal('show');
            }
        }
    });

    e.preventDefault();
    return false;
});


// При нажатии на иконку разрешить экспертизу
$(body).on('click', '.link-enable-expertise', function (e) {

    $.ajax({
        url: $(this).attr('href'),
        method: 'POST',
        cache: false,
        success: function(response){

            $('.block_all_hypothesis').html(response.renderAjax);
        }
    });

    e.preventDefault();
    return false;
});


// Показать форму поиска сегментов
$(body).on('click', '.show_search_segments', function (e) {

    $('.search_block_mobile').toggle('display');
    $('.row_header_data_generation_mobile').toggle('display');
    e.preventDefault();
    return false;
});
