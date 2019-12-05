//Добавить сегмент

$(document).ready(function() {
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
});