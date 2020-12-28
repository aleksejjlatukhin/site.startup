
$(document).ready(function() {

    var id = (window.location.search).split('?id=')[1];
    var url = '/respond/get-query-responds?id=' + id + '&page=1';

    //Загружаем данные респондентов (Шаг 3)
    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $('.content_responds_ajax').html(response.ajax_data_responds);
        },
        error: function(){
            alert('Ошибка');
        }
    });

});

var body = $('body');

//Постраничная навигация
$(body).on('click', '.pagination-responds-confirm .responds-confirm-pagin-list li a', function(e){

    var url = $(this).attr('href');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $('.content_responds_ajax').html(response.ajax_data_responds);
            $('html, body').animate({scrollTop: 0});
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


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
$(body).on('beforeSubmit', '#update_data_interview', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');
    var id = (window.location.search).split('?id=')[1];

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (response.success) {

                //Обновление данных в режиме просмотра (Шаг 1)
                $('#step_one').html(response.ajax_data_confirm);

                //Вызов события клика на кнопку просмотра
                //для перхода в режим просмотра (Шаг 1)
                $('.form-update-data-confirm').hide();
                $('.form-view-data-confirm').show();

                //Загружаем данные респондентов (Шаг 3)
                $.ajax({

                    url: '/respond/get-query-responds?id=' + id + '&page=1',
                    method: 'POST',
                    cache: false,
                    success: function(response){

                        $('.content_responds_ajax').html(response.ajax_data_responds);
                    },
                    error: function(){
                        alert('Ошибка');
                    }
                });
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

            //Обновляем список вопросов для добавления
            var queryQuestions = response.queryQuestions;
            var addNewQuestionForm = $('#addNewQuestion');
            $(addNewQuestionForm).find('select').html('');
            $(addNewQuestionForm).find('select').prepend('<\option style=\"font - weight:700;\" value=\"\">Выберите вариант из списка готовых вопросов<\/option>');
            $.each(queryQuestions, function(index, value) {
                $(addNewQuestionForm).find('select').append('<\option value=\"' + value.title + '\">' + value.title + '<\/option>');
            });

            //Скрываем и очищием форму добавления вопроса
            $(addNewQuestionForm)[0].reset();
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Получить форму редактирования вопроса для интервью (Шаг 2)
$(body).on('click', '.showQuestionUpdateForm', function (e) {

    var url = $(this).attr('href');
    var id = url.split('id=')[1];

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            //Обновляем список вопросов на странице
            $('#QuestionsTable-container').html(response.ajax_questions_confirm);

            //Добавляем форму редактирования для выбранного вопроса
            $('.string_question-' + id).html(response.renderAjax);

            //Устанавливаем курсор в поле формы
            var input = $('#update_text_question_confirm');
            var inputVal = input.val();
            input.val('').focus().val(inputVal);
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Отмена редактирования вопроса для интервью (Шаг 2)
$(body).on('click', '.submit_update_question_cancel', function (e) {

    var url = $(this).attr('href');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            //Обновляем список вопросов на странице
            $('#QuestionsTable-container').html(response.ajax_questions_confirm);
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Редактирование вопроса для интервью (Шаг 2)
$(body).on('beforeSubmit', '#updateQuestionForm', function(e){

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

            //Обновляем список вопросов для добавления
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


//Удаление вопроса для интервью (Шаг 2)
$(body).on('click', '.delete-question-confirm-segment', function(e){

    var url = $(this).attr('href');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            //Обновляем список вопросов на странице
            $('#QuestionsTable-container').html(response.ajax_questions_confirm);

            //Обновляем список вопросов для добавления
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


//При нажатии на кнопку Добавить респондента
$(body).on('click', '#showRespondCreateForm', function(e){

    var id = location.href.split('=')[1];
    var url = '/respond/get-data-create-form?id=' + id;

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $('#respondCreate_modal .modal-body').html(response.renderAjax);
            $('#respondCreate_modal').modal('show');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//При создании нового респондента из формы
$(body).on('beforeSubmit', '#new_respond_form', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');
    var error_respond_modal = $('#error_respond_modal');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (!response.limit_count_respond) {

                if (!response.error) {

                    //Загружаем данные респондентов (Шаг 3)
                    $.ajax({

                        url: '/respond/get-query-responds?id=' + response.interview_id + '&page=' + response.page,
                        method: 'POST',
                        cache: false,
                        success: function(response){

                            $('.content_responds_ajax').html(response.ajax_data_responds);
                            $('html, body').animate({scrollTop: $(document).height() - $(window).height()}, 600);
                        },
                        error: function(){
                            alert('Ошибка');
                        }
                    });

                    //Закрываем окно создания нового респондента
                    $('#respondCreate_modal').modal('hide');
                    //Обновление данных подтверждения (Шаг 1)
                    $('#step_one').html(response.ajax_data_confirm);

                } else {

                    //Показываем окно с информацией
                    $(error_respond_modal).find('.modal-header h3').html('Внимание');
                    $(error_respond_modal).find('.modal-body h4').html('Респондент с таким именем уже есть.<br>Имя респондента должно быть уникальным.');
                    $(error_respond_modal).modal('show');
                }

            }else {

                //Закрываем окно создания нового респондента
                $('#respondCreate_modal').modal('hide');
                //Показываем окно с информацией
                $(error_respond_modal).find('.modal-header h3').html('Создание нового респондента заблокировано');
                $(error_respond_modal).find('.modal-body h4').html('Действует ограничение. Вы не можете добавить больше существующего количества респондентов.');
                $(error_respond_modal).modal('show');
            }

        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Получение формы редактирования данных репондента
$(body).on('click', '.showRespondUpdateForm', function(e){

    var id = $(this).attr('id').split('-')[1];
    var url = '/respond/get-data-update-form?id=' + id;

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $('#respond_update_modal .modal-body').html(response.renderAjax);
            $('#respond_update_modal').modal('show');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Сохранении данных из формы редактирование дынных респондента
$(body).on('beforeSubmit', '#formUpdateRespond', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('action');
    var page = $('li.pagination_active_page a').html();
    if (!!!page) page = 1; //Проверка на то, что переменная как определена undefined и является ложью
    var error_respond_modal = $('#error_respond_modal');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (!response.error) {

                //Загружаем данные респондентов (Шаг 3)
                $.ajax({

                    url: '/respond/get-query-responds?id=' + response.interview_id + '&page=' + page,
                    method: 'POST',
                    cache: false,
                    success: function(response){

                        $('.content_responds_ajax').html(response.ajax_data_responds);
                    },
                    error: function(){
                        alert('Ошибка');
                    }
                });

                //Закрываем окно редактирования
                $('#respond_update_modal').modal('hide');

            } else {

                //Показываем окно с информацией
                $(error_respond_modal).find('.modal-header h3').html('Внимание');
                $(error_respond_modal).find('.modal-body h4').html('Респондент с таким именем уже есть.<br>Имя респондента должно быть уникальным.');
                $(error_respond_modal).modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Получение формы создания интервью для респондента
$(body).on('click', '.showDescInterviewCreateForm', function(e){

    var url_1 = $(this).attr('href');
    var id = $(this).attr('id').split('-')[1];
    var url_2 = '/desc-interview/get-data-create-form?id=' + id;
    var error_respond_modal = $('#error_respond_modal');

    $.ajax({

        url: url_1,
        method: 'POST',
        cache: false,
        success: function(response){
            if (!response.error) {

                //Показываем окно создания интервью
                $.ajax({
                    url: url_2,
                    method: 'POST',
                    cache: false,
                    success: function(response){

                        $('#create_descInterview_modal .modal-body').html(response.renderAjax);
                        $('#create_descInterview_modal').modal('show');
                    },
                    error: function(){
                        alert('Ошибка');
                    }
                });

            } else {

                //Показываем окно с информацией
                $(error_respond_modal).find('.modal-header h3').html('Информация');
                $(error_respond_modal).find('.modal-body h4').html('Для перехода к созданию интервью, необходимо заполнить вводные данные по всем заданным респондентам.');
                $(error_respond_modal).modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//После выбора файла в форме создания интервью выводим его имя на экран
$(body).on('change', '#formCreateDescInterview input[type=file]',function(){

    var filename = $(this).val().split('\\').pop();
    $('.title_file').html(filename);
});


//Сохранении данных из формы при создании интервью
$(body).on('beforeSubmit', '#formCreateDescInterview', function(e){

    var data = new FormData(this);
    var url = $(this).attr('action');
    var page = $('li.pagination_active_page a').html();
    if (!!!page) page = 1; //Проверка на то, что переменная как определена undefined и является ложью

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function(response){

            //Загружаем данные респондентов (Шаг 3)
            $.ajax({

                url: '/respond/get-query-responds?id=' + response.interview_id + '&page=' + page,
                method: 'POST',
                cache: false,
                success: function(response){

                    $('.content_responds_ajax').html(response.ajax_data_responds);
                },
                error: function(){
                    alert('Ошибка');
                }
            });

            //Закрываем модальное окно с формой
            $('#create_descInterview_modal').modal('hide');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Получение формы редактирования интервью респондента
$(body).on('click', '.showDescInterviewUpdateForm', function(e){

    var id = $(this).attr('id').split('-')[1];
    var url = '/desc-interview/get-data-update-form?id=' + id;

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response){

            $('#update_descInterview_modal .modal-body').html(response.renderAjax);
            $('#update_descInterview_modal').modal('show');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//После выбора файла в форме редактирования интервью выводим его имя на экран
$(body).on('change', '#formUpdateDescInterview input[type=file]',function(){

    var filename = $(this).val().split('\\').pop();
    $('.file_name_update_form').html(filename);
});


//Редактирование интервью при сохранении данных из формы
$(body).on('beforeSubmit', '#formUpdateDescInterview', function(e){

    var data = new FormData(this);
    var url = $(this).attr('action');
    var page = $('li.pagination_active_page a').html();
    if (!!!page) page = 1; //Проверка на то, что переменная как определена undefined и является ложью

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function(response){

            //Загружаем данные респондентов (Шаг 3)
            $.ajax({

                url: '/respond/get-query-responds?id=' + response.interview_id + '&page=' + page,
                method: 'POST',
                cache: false,
                success: function(response){

                    $('.content_responds_ajax').html(response.ajax_data_responds);
                },
                error: function(){
                    alert('Ошибка');
                }
            });

            //Закрываем модальное окно с формой
            $('#update_descInterview_modal').modal('hide');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Удаление файла из формы редактирования интервью
$(body).on('click', '#link_delete_file', function(e){

    var url = $(this).attr('href');

    $.ajax({

        type:'POST',
        cache: false,
        url: url,
        success: function() {

            $('.interview_file_update').hide();
            $('.link-delete').hide();
            $('.title_name_update_form').hide();
            $('.feed-exp').show();
        },
        error: function() {
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


//Вызов модального окна удаления респондента
$(body).on('click', '.showDeleteRespondModal', function(e){

    var id = $(this).attr('id').split('-')[1];
    var url = '/respond/get-data-model?id=' + id;

    $.ajax({

        type:'POST',
        cache: false,
        url: url,
        success: function(response){

            var delete_respond_modal = $('#delete-respond-modal');
            $(delete_respond_modal).find('.modal-body h4').html('Вы уверены, что хотите удалить все данные<br>о респонденте «' + response.name + '»?');
            $(delete_respond_modal).find('.modal-footer #confirm-delete-respond').attr('href', '/respond/delete?id=' + response.id);
            $(delete_respond_modal).modal('show');
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// CONFIRM RESPOND DELETE
$(body).on('click', '#confirm-delete-respond', function(e) {

    var url = $(this).attr('href');
    var count_respond = $('.block_all_responds').children('.container-one_respond').length;
    var page = $('li.pagination_active_page a').html();
    if (!!!page){
        page = 1;
    } else if (Number(count_respond) === 1){
        page = Number(page) - 1;
    }
    var error_respond_modal = $('#error_respond_modal');

    $.ajax({

        url: url,
        method: 'POST',
        cache: false,
        success: function(response) {

            if (!response.success) {

                if (response.zero_value_responds) {

                    //Закрываем окно подтверждения
                    $('#delete-respond-modal').modal('hide');
                    //Показываем окно с ошибкой
                    $(error_respond_modal).find('.modal-header h3').html('Удаление респондента отклонено');
                    $(error_respond_modal).find('.modal-body h4').html('Удаление последнего респондента запрещено.');
                    $(error_respond_modal).modal('show');
                }
                else if (response.number_less_than_allowed) {

                    //Закрываем окно подтверждения
                    $('#delete-respond-modal').modal('hide');
                    //Показываем окно с ошибкой
                    $(error_respond_modal).find('.modal-header h3').html('Удаление респондента отклонено');
                    $(error_respond_modal).find('.modal-body h4').html('Общее количество респондентов не должно быть меньше необходимого количества респондентов соответствующих сегменту.');
                    $(error_respond_modal).modal('show');
                }
            }
            else if (response.success) {

                //Загружаем данные респондентов (Шаг 3)
                $.ajax({

                    url: '/respond/get-query-responds?id=' + response.interview_id + '&page=' + page,
                    method: 'POST',
                    cache: false,
                    success: function(response){

                        $('.content_responds_ajax').html(response.ajax_data_responds);
                    },
                    error: function(){
                        alert('Ошибка');
                    }
                });

                //Закрываем окно подтверждения
                $('#delete-respond-modal').modal('hide');
                //Обновление данных подтверждения (Шаг 1)
                $('#step_one').html(response.ajax_data_confirm);
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// CANCEL RESPOND DELETE
$(body).on('click', '#cancel-delete-respond', function(e) {

    //Закрываем окно подтверждения
    $('#delete-respond-modal').modal('hide');

    e.preventDefault();
    return false;
});


//Переход к генерации ГПС по кнопке Далее
$(body).on('click', '#button_MovingNextStage', function(e){

    var data = $(this).serialize();
    var url = $(this).attr('href');
    var id = url.split('=')[1];
    var error_respond_modal = $('#error_respond_modal');

    $.ajax({

        url: url,
        method: 'POST',
        data: data,
        cache: false,
        success: function(response){

            if (!response.error) {

                if (!response.not_completed_descInterviews) {

                    if (response.exist_confirm === 1) {
                        window.location.href = '/generation-problem/index?id=' + id;
                    }
                    else if (response.exist_confirm === null) {
                        window.location.href = '/interview/exist-confirm?id=' + id;
                    }
                    else if (response.exist_confirm === 0) {
                        window.location.href = '/interview/exist-confirm?id=' + id;
                    }

                } else {

                    //Показываем окно с информацией
                    $(error_respond_modal).find('.modal-header h3').html('Информация');
                    $(error_respond_modal).find('.modal-body h4').html('Для продолжения Вам необходимо провести интервью со всеми заданными респондентами.');
                    $(error_respond_modal).modal('show');
                }

            } else {

                //Показываем окно выбора
                $('#not_exist-confirm-modal').modal('show');
            }
        },
        error: function(){
            alert('Ошибка');
        }
    });

    e.preventDefault();
    return false;
});


// Отмена завершения неудачного подтверждения сегмента
$(body).on('click', '#cancel-not_exist-confirm', function(e) {

    //Закрываем окно
    $('#not_exist-confirm-modal').modal('hide');

    e.preventDefault();
    return false;
});