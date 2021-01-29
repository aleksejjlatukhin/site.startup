//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

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

//Отслеживаем изменения в форме создания сегмента и записываем их в кэш
$(body).on('change', 'form#hypothesisCreateForm', function(){

    var url = '/segment/save-cache-creation-form?id=' + id_page;
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

            if (response.cache_form_creation) {

                var form = response.cache_form_creation;
                var arrData = form.split('&FormCreateSegment');

                var formCreateSegmentName,
                    formCreateSegmentDescription,
                    formCreateSegmentType,
                    formCreateSegmentField_of_activity_b2c,
                    formCreateSegmentSort_of_activity_b2c,
                    formCreateSegmentSpecialization_of_activity_b2c,
                    formCreateSegmentAge_from,
                    formCreateSegmentAge_to,
                    formCreateSegmentGender_consumer,
                    formCreateSegmentEducation_of_consumer,
                    formCreateSegmentIncome_from,
                    formCreateSegmentIncome_to,
                    formCreateSegmentQuantity_from,
                    formCreateSegmentQuantity_to,
                    formCreateSegmentMarket_volume_b2c,
                    formCreateSegmentField_of_activity_b2b,
                    formCreateSegmentSort_of_activity_b2b,
                    formCreateSegmentSpecialization_of_activity_b2b,
                    formCreateSegmentCompany_products,
                    formCreateSegmentCompany_partner,
                    formCreateSegmentQuantity_from_b2b,
                    formCreateSegmentQuantity_to_b2b,
                    formCreateSegmentIncome_company_from,
                    formCreateSegmentIncome_company_to,
                    formCreateSegmentMarket_volume_b2b,
                    formCreateSegmentAdd_info;

                arrData.forEach(function(item) {
                    if (item.split('=')[0] === '[name]') formCreateSegmentName = item.split('=')[1];
                    if (item.split('=')[0] === '[description]') formCreateSegmentDescription = item.split('=')[1];
                    if (item.split('=')[0] === '[type_of_interaction_between_subjects]') formCreateSegmentType = item.split('=')[1];
                    if (item.split('=')[0] === '[field_of_activity_b2c]') formCreateSegmentField_of_activity_b2c = item.split('=')[1];
                    if (item.split('=')[0] === '[sort_of_activity_b2c]') formCreateSegmentSort_of_activity_b2c = item.split('=')[1];
                    if (item.split('=')[0] === '[specialization_of_activity_b2c]') formCreateSegmentSpecialization_of_activity_b2c = item.split('=')[1];
                    if (item.split('=')[0] === '[age_from]') formCreateSegmentAge_from = item.split('=')[1];
                    if (item.split('=')[0] === '[age_to]') formCreateSegmentAge_to = item.split('=')[1];
                    if (item.split('=')[0] === '[gender_consumer]') formCreateSegmentGender_consumer = item.split('=')[1];
                    if (item.split('=')[0] === '[education_of_consumer]') formCreateSegmentEducation_of_consumer = item.split('=')[1];
                    if (item.split('=')[0] === '[income_from]') formCreateSegmentIncome_from = item.split('=')[1];
                    if (item.split('=')[0] === '[income_to]') formCreateSegmentIncome_to = item.split('=')[1];
                    if (item.split('=')[0] === '[quantity_from]') formCreateSegmentQuantity_from = item.split('=')[1];
                    if (item.split('=')[0] === '[quantity_to]') formCreateSegmentQuantity_to = item.split('=')[1];
                    if (item.split('=')[0] === '[market_volume_b2c]') formCreateSegmentMarket_volume_b2c = item.split('=')[1];
                    if (item.split('=')[0] === '[field_of_activity_b2b]') formCreateSegmentField_of_activity_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[sort_of_activity_b2b]') formCreateSegmentSort_of_activity_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[specialization_of_activity_b2b]') formCreateSegmentSpecialization_of_activity_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[company_products]') formCreateSegmentCompany_products = item.split('=')[1];
                    if (item.split('=')[0] === '[company_partner]') formCreateSegmentCompany_partner = item.split('=')[1];
                    if (item.split('=')[0] === '[quantity_from_b2b]') formCreateSegmentQuantity_from_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[quantity_to_b2b]') formCreateSegmentQuantity_to_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[income_company_from]') formCreateSegmentIncome_company_from = item.split('=')[1];
                    if (item.split('=')[0] === '[income_company_to]') formCreateSegmentIncome_company_to = item.split('=')[1];
                    if (item.split('=')[0] === '[market_volume_b2b]') formCreateSegmentMarket_volume_b2b = item.split('=')[1];
                    if (item.split('=')[0] === '[add_info]') formCreateSegmentAdd_info = item.split('=')[1];
                });

                //Заполнение полей формы данными из кэша
                $(document.getElementsByName('FormCreateSegment[name]')).val(formCreateSegmentName);
                $(document.getElementsByName('FormCreateSegment[description]')).val(formCreateSegmentDescription);
                $(document.getElementsByName('FormCreateSegment[add_info]')).val(formCreateSegmentAdd_info);

                if (formCreateSegmentType === '200') {

                    //Форма для сегмента типа B2B
                    $(document.getElementsByName('FormCreateSegment[company_products]')).val(formCreateSegmentCompany_products);
                    $(document.getElementsByName('FormCreateSegment[company_partner]')).val(formCreateSegmentCompany_partner);
                    $(document.getElementsByName('FormCreateSegment[quantity_from_b2b]')).val(formCreateSegmentQuantity_from_b2b);
                    $(document.getElementsByName('FormCreateSegment[quantity_to_b2b]')).val(formCreateSegmentQuantity_to_b2b);
                    $(document.getElementsByName('FormCreateSegment[income_company_from]')).val(formCreateSegmentIncome_company_from);
                    $(document.getElementsByName('FormCreateSegment[income_company_to]')).val(formCreateSegmentIncome_company_to);
                    $(document.getElementsByName('FormCreateSegment[market_volume_b2b]')).val(formCreateSegmentMarket_volume_b2b);

                    //Поля типа Select
                    $('#type-interaction').val(formCreateSegmentType).trigger('change.select2');
                    if (formCreateSegmentField_of_activity_b2b !== '') {
                        $('#listOfAreasOfActivityB2B').val(formCreateSegmentField_of_activity_b2b).trigger('change.select2').trigger('select2:select');

                        if (formCreateSegmentSort_of_activity_b2b !== '') {

                            var timerListOfActivitiesB2B = setInterval(function() {
                                var listOfActivitiesB2B = $('#listOfActivitiesB2B');
                                if ($(listOfActivitiesB2B).prop('disabled') === false) {
                                    clearInterval(timerListOfActivitiesB2B);
                                    $(listOfActivitiesB2B).val(formCreateSegmentSort_of_activity_b2b).trigger('change.select2').trigger('select2:select');
                                    if (formCreateSegmentSpecialization_of_activity_b2b === '') $(body).find('form#hypothesisCreateForm').trigger('change');
                                }
                            }, 1000);

                            if (formCreateSegmentSpecialization_of_activity_b2b !== '') {

                                var timerListOfSpecializationsB2B = setInterval(function() {
                                    var listOfSpecializationsB2B = $('#listOfSpecializationsB2B');
                                    if ($(listOfSpecializationsB2B).prop('disabled') === false) {
                                        clearInterval(timerListOfSpecializationsB2B);
                                        $(listOfSpecializationsB2B).val(formCreateSegmentSpecialization_of_activity_b2b).trigger('change.select2').trigger('select2:select');
                                        $(body).find('form#hypothesisCreateForm').trigger('change');
                                    }
                                }, 1000);
                            }
                        }
                    }
                } else {

                    //Форма для сегмента типа B2C
                    $(document.getElementsByName('FormCreateSegment[age_from]')).val(formCreateSegmentAge_from);
                    $(document.getElementsByName('FormCreateSegment[age_to]')).val(formCreateSegmentAge_to);
                    $(document.getElementsByName('FormCreateSegment[income_from]')).val(formCreateSegmentIncome_from);
                    $(document.getElementsByName('FormCreateSegment[income_to]')).val(formCreateSegmentIncome_to);
                    $(document.getElementsByName('FormCreateSegment[quantity_from]')).val(formCreateSegmentQuantity_from);
                    $(document.getElementsByName('FormCreateSegment[quantity_to]')).val(formCreateSegmentQuantity_to);
                    $(document.getElementsByName('FormCreateSegment[market_volume_b2c]')).val(formCreateSegmentMarket_volume_b2c);

                    //Поля типа Select
                    $('#type-interaction').val(formCreateSegmentType).trigger('change.select2');
                    if (formCreateSegmentField_of_activity_b2c !== '') {
                        $('#listOfAreasOfActivityB2C').val(formCreateSegmentField_of_activity_b2c).trigger('change.select2').trigger('select2:select');

                        if (formCreateSegmentSort_of_activity_b2c !== '') {

                            var timerListOfActivitiesB2C = setInterval(function() {
                                var listOfActivitiesB2C = $('#listOfActivitiesB2C');
                                if ($(listOfActivitiesB2C).prop('disabled') === false) {
                                    clearInterval(timerListOfActivitiesB2C);
                                    $(listOfActivitiesB2C).val(formCreateSegmentSort_of_activity_b2c).trigger('change.select2').trigger('select2:select');
                                    if (formCreateSegmentSpecialization_of_activity_b2c === '') $(body).find('form#hypothesisCreateForm').trigger('change');
                                }
                            }, 1000);

                            if (formCreateSegmentSpecialization_of_activity_b2c !== '') {

                                var timerListOfSpecializationsB2C = setInterval(function() {
                                    var listOfSpecializationsB2C = $('#listOfSpecializationsB2C');
                                    if ($(listOfSpecializationsB2C).prop('disabled') === false) {
                                        clearInterval(timerListOfSpecializationsB2C);
                                        $(listOfSpecializationsB2C).val(formCreateSegmentSpecialization_of_activity_b2c).trigger('change.select2').trigger('select2:select');
                                        $(body).find('form#hypothesisCreateForm').trigger('change');
                                    }
                                }, 1000);
                            }
                        }
                    }

                    if (formCreateSegmentGender_consumer !== '') {
                        $(document.getElementsByName('FormCreateSegment[gender_consumer]')).val(formCreateSegmentGender_consumer).trigger('change.select2');
                        if (formCreateSegmentSort_of_activity_b2c === '' && formCreateSegmentSpecialization_of_activity_b2c === '' && formCreateSegmentEducation_of_consumer === '') {
                            $(body).find('form#hypothesisCreateForm').trigger('change');
                        }
                    }
                    if (formCreateSegmentEducation_of_consumer !== '') {
                        $(document.getElementsByName('FormCreateSegment[education_of_consumer]')).val(formCreateSegmentEducation_of_consumer).trigger('change.select2');
                        if (formCreateSegmentSort_of_activity_b2c === '' && formCreateSegmentSpecialization_of_activity_b2c === '') {
                            $(body).find('form#hypothesisCreateForm').trigger('change');
                        }
                    }
                }
            }
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

                $('.hypothesis_create_modal').modal('hide');
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
    var hypothesis_update_modal = $('.hypothesis_update_modal');
    $(body).append($(hypothesis_update_modal).first());

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $(hypothesis_update_modal).modal('show');
            $(hypothesis_update_modal).find('.modal-body').html(response.renderAjax);
        },
        error: function(){
            alert('Ошибка');
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
                alert('Ошибка');
            }
        });
    }

});