
var body = $('body');

//При нажатии на кнопку редактировать(Шаг 1)
//показываем форму редактирования и скрываем вид просмотра
$(body).on('click', '#show_form_update_data', function(){
    $('.form-view-data-confirm').hide();
    $('.form-update-data-confirm').show();
});

//При нажатии на кнопку просмотр(Шаг 1)
//скрываем форму редактирования и показываем вид просмотра
$(body).on('click', '#show_form_view_data', function(){
    $('.form-update-data-confirm').hide();
    $('.form-view-data-confirm').show();
});


//Редактирование исходных даннных подтверждения (Шаг 1)
$(body).on('beforeSubmit', '#update_data_confirm', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (response.success) {

                //Обновление данных в режиме просмотра (Шаг 1)
                $('#step_one').html(response.ajax_data_confirm);// Закончил здесь - делать файл вида и метод контроллера

                //Вызов события клика на кнопку просмотра
                //для перехода в режим просмотра (Шаг 1)
                $('.form-update-data-confirm').hide();
                $('.form-view-data-confirm').show();

            }
        }, error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Показываем и скрываем форму добавления вопроса
//при нажатии на кнопку добавить вопрос (Шаг 2)
$(body).on('click', '#buttonAddQuestion', function(e){

    //Вырезаем и вставляем форму добавления вопроса (Шаг 2)
    var form_newQuestion_panel = $('.form-newQuestion-panel');
    $(form_newQuestion_panel).append($('.form-newQuestion').first());
    $(form_newQuestion_panel).toggle();

    e.preventDefault();
    return false;
});


//Передаем выбранное значение из select в поле ввода
$(body).on('select2:select', '#add_new_question_confirm', function(){
    $('#add_text_question_confirm').val($(this).val());
    $(this).val('');
});


//Создание нового вопроса (Шаг 2)
$(body).on('beforeSubmit', '#addNewQuestion', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            //Обновляем список вопросов на странице
            $('#QuestionsTable-container').html(response.ajax_questions_confirm);

            //Обновляем список вопросов для добавления (Шаг 2)
            var queryQuestions = response.queryQuestions;
            var addNewQuestionForm = $('#addNewQuestion');
            $(addNewQuestionForm).find('select').html('');
            $(addNewQuestionForm).find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
            $.each(queryQuestions, function(index, value) {
                $(addNewQuestionForm).find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
            });

            //Очищием форму добавления вопроса
            $(addNewQuestionForm)[0].reset();
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Удаление вопроса для анкеты (Шаг 2)
$(body).on('click', '.delete-question-confirm-problem', function(e){

    var url = $(this).attr('href');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            //Обновляем список вопросов на странице
            $('#QuestionsTable-container').html(response.ajax_questions_confirm);

            //Обновляем список вопросов для добавления (Шаг 2)
            var queryQuestions = response.queryQuestions;
            var addNewQuestionForm = $('#addNewQuestion');
            $(addNewQuestionForm).find('select').html('');
            $(addNewQuestionForm).find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
            $.each(queryQuestions, function(index, value) {
                $(addNewQuestionForm).find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
            });

        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//события для select2 https://select2.org/programmatic-control/events
//Открытие и закрытие списка вопросов для добавления в анкету
$(body).on('click', '#button_add_text_question_confirm', function(e){

    if(!$(this).hasClass('openDropDownList')){

        $('#add_new_question_confirm').select2('open');
        $(this).addClass('openDropDownList');
        $(this).css('border-width', '0');
        $(this).find('.triangle-bottom').css('transform', 'rotate(180deg)');

        var position_button = $('#button_add_text_question_confirm').offset().top;
        var position_select = $('.select2-container--krajee .select2-dropdown').offset().top;

        if (position_button < position_select) {
            $('#add_text_question_confirm').css({'border-bottom-width': '0', 'border-radius': '12px 12px 0 0'});
        } else {
            $('#add_text_question_confirm').css({'border-top-width': '0', 'border-radius': '0 0 12px 12px'});
        }

    }else {

        $('#add_new_question_confirm').select2('close');
        $(this).removeClass('openDropDownList');
        $(this).css('border-width', '0 0 0 1px');
        $(this).find('.triangle-bottom').css('transform', 'rotate(0deg)');
        $('#add_text_question_confirm').css({'border-width': '1px', 'border-radius': '12px'});
    }

    e.preventDefault();
    return false;
});

//Проверяем позицию кнопки и select при скролле страницы и задаем стили для поля ввода
$(window).on('scroll', function() {

    var button = $('#button_add_text_question_confirm');
    var select = $('.select2-container--krajee .select2-dropdown');

    if($(button).length > 0 && $(select).length > 0) {

        var position_button = $(button).offset().top;
        var position_select = $(select).offset().top;

        if (position_button < position_select) {

            $('#add_text_question_confirm').css({
                'border-top-width': '1px',
                'border-bottom-width': '0',
                'border-radius': '12px 12px 0 0',
            });
        } else {

            $('#add_text_question_confirm').css({
                'border-bottom-width': '1px',
                'border-top-width': '0',
                'border-radius': '0 0 12px 12px',
            });
        }
    }
});

// Отслеживаем клик вне поля Select
$(document).mouseup(function (e){ // событие клика по веб-документу

    var search = $('.select2-container--krajee .select2-search--dropdown .select2-search__field'); // поле поиска в select
    var button = $('#button_add_text_question_confirm'); // кнопка открытия и закрытия списка select

    if (!search.is(e.target) && !button.is(e.target) // если клик был не полю поиска и не по кнопке
        && search.has(e.target).length === 0 && button.has(e.target).length === 0) { // и не их по его дочерним элементам

        $('#add_new_question_confirm').select2('close'); // скрываем список select
        $(button).removeClass('openDropDownList'); // убираем класс открытового списка у кнопки открытия и закрытия списка select

        $(button).css('border-width', '0 0 0 1px'); // возвращаем стили кнопке
        $(this).find('.triangle-bottom').css('transform', 'rotate(0deg)'); // возвращаем стили кнопке
        $('#add_text_question_confirm').css({'border-width': '1px', 'border-radius': '12px'}); // возвращаем стили для поля ввода
    }
});


//Если задано, что count_respond < count_positive, то count_respond = count_positive
$(body).on('change', 'input#confirm_count_respond', function () {
    var value1 = $('input#confirm_count_positive').val();
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
$(body).on('change', 'input#confirm_count_positive', function () {
    var value1 = $(this).val();
    var value2 = $('input#confirm_count_respond').val();
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