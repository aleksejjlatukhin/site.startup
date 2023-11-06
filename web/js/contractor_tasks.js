//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');
var contractor_id = window.location.search.split('=')[1];

$(document).ready(function() {

    // Получаем задания для исполнителя по проектам
    var allHypothesis = $(body).find('.allHypothesis').children('.hypothesis')
    if (allHypothesis.length > 0) {
        $.each(allHypothesis, function(){
            var projectId = $(this).attr('id').split('hypothesis-')[1]
            $.ajax({
                url: '/tasks/get-tasks-by-params?contractorId='+contractor_id+'&projectId='+projectId,
                method: 'POST',
                cache: false,
                success: function(response){
                    console.log(response)
                    $('#hypothesis-' + projectId).find('.hereAddProjectTasks').html(response.renderAjax);
                }
            })
        })
    }
});

// При клике по строке с названием проекта
// Показываем и скрываем коммуникации по проекту
$(body).on('click', '.container-one_hypothesis', function () {
    var block_data_project = $(this).parent().find('.hereAddProjectTasks');

    if ($(block_data_project).is(':hidden')){
        $(this).parent().find('.container-one_hypothesis').css({
            'background' : '#7F9FC5',
            'border-radius' : '12px 12px 0px 0px',
        });
        $(this).find('.informationAboutAction').html('Закрыть задания по проекту');
    }
    if ($(block_data_project).is(':visible')) {
        $(this).parent().find('.container-one_hypothesis').css({
            'background' : '#707F99',
            'border-radius' : '12px',
        });
        $(this).find('.informationAboutAction').html('Посмотреть задания по проекту');
    }

    $(block_data_project).toggle('display');
})