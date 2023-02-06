//Установка Simple ScrollBar
const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));

var body = $('body');

$(body).on('click', '.one-wish_list_ready', function (){
    $(this).parent('.parent-wish_list_ready').find('.one-wish_list_ready-data').toggle('display');
    if ($(this).css('border-radius') === '12px') {
        $(this).css({
            'border-radius': '12px 12px 0 0',
            'margin-bottom': '0',
            'background': '#7F9FC5'
        })
    } else {
        $(this).css({
            'border-radius': '12px',
            'margin-bottom': '5px',
            'background': '#707F99'
        })
    }
});

$(body).on('click', '.change-requirement-actual', function (e){

    var url = $(this).attr('href');
    var parent = $(this).parent()

    $.ajax({
        url: url,
        method: 'POST',
        cache: false,
        success: function(response){
            if (response.success) {
                $(parent).find('.isActual').html(response.result);
            }
        }
    });

    e.preventDefault();
    return false;
})