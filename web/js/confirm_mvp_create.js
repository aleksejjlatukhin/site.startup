//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

//Форма создания модели подтверждения
$('#new_confirm_mvp').on('beforeSubmit', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (response.success) {

                window.location.href = '/confirm-mvp/add-questions?id=' + response.id;
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Если задано, что count_respond < count_positive, то count_respond = count_positive
$("input#confirm_count_respond").change(function () {
    var value1 = $("input#confirm_count_positive").val();
    var value2 = $(this).val();
    var valueMax = 100;
    var valueMin = 1;

    if (parseInt(value2) < parseInt(value1)){
        value2 = value1;
        $(this).val(value2);
    }
    if (parseInt(value2) > parseInt(valueMax)){
        value2 = valueMax;
        $(this).val(value2);
    }
    if (parseInt(value2) < parseInt(valueMin)){
        value2 = valueMin;
        $(this).val(value2);
    }
});

//Если задано, что count_positive > count_respond, то count_positive = count_respond
$("input#confirm_count_positive").change(function () {
    var value1 = $(this).val();
    var value2 = $("input#confirm_count_respond").val();
    var valueMax = 100;
    var valueMin = 1;

    if (parseInt(value1) > parseInt(value2)){
        value1 = value2;
        $(this).val(value1);
    }
    if (parseInt(value1) > parseInt(valueMax)){
        value1 = valueMax;
        $(this).val(value1);
    }
    if (parseInt(value1) < parseInt(valueMin)){
        value1 = valueMin;
        $(this).val(value1);
    }
});


var body = $('body');
var modal_next_step_error = $('#next_step_error');
var information_add_new_responds_modal = $('#information-add-new-responds');

//Показываем модальное окно - запрет перехода на следующий шаг
$(body).on('click', '.show_modal_next_step_error', function (e) {

    $(body).append($(modal_next_step_error).first());
    $(modal_next_step_error).modal('show');

    e.preventDefault();
    return false;
});

//Показываем модальное окно - информация о месте добавления новых респондентов
$(body).on('click', '.show_modal_information_add_new_responds', function (e) {

    $(body).append($(information_add_new_responds_modal).first());
    $(information_add_new_responds_modal).modal('show');

    e.preventDefault();
    return false;
});