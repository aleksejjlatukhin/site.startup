//Добавить сегмент

$(document).ready(function() {
    $('.open_fast').click(function () {
        $('.popap_fast').addClass('active');
        //$('.bg_popap').fadeIn();
    });

    // $('.bg_popap').click(function(){
    //     $('.popap_fast').removeClass('active');
    //     $('.bg_popap').fadeOut();
    // });

    $('.cross-out').click(function () {
        $('.popap_fast').removeClass('active');
        //$('.bg_popap').fadeOut();
    });
});