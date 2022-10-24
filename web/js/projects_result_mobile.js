var body = $('body');

$(document).ready(function() {
    // Устанавливаем высоту линии проекта
    var blockLastSegment = $(body).find('.block-segment:last');
    var blockProjectsResultMobile = $(body).find('.projectsResultMobile');
    var blockProjectName = $(body).find('.block-project-name');
    var heightLineProject = blockLastSegment.offset().top - blockProjectsResultMobile.offset().top - blockProjectName.css('height').split('px')[0] + 42;
    $(body).find('.line-project').css('height', heightLineProject);

    $(body).find('.line-segment').each(function (s, elemLineSegment) {
        // Устанавливаем высоту линий сегментов
        if ($('.line-segment').hasClass('line-segment-' + s)) {
            var blockCurrentSegment = $('.block-segment-' + s);
            var blockLastProblem = $('.block-problem.segment-number-' + s + ':last');
            var heightLineSegment = blockLastProblem.offset().top - blockCurrentSegment.offset().top - blockCurrentSegment.css('height').split('px')[0] + 37;
            $('.line-segment.line-segment-' + s).css({
                'top': blockCurrentSegment.css('height'),
                'height': heightLineSegment
            });

            $('.line-problem').each(function (p, elemLineProblem) {
                // Устанавливаем высоту линий ГПС
                if ($('.line-problem').hasClass('line-problem-' + s + '-' + p)) {
                    var blockCurrentProblem = $('.block-problem-' + s + '-' + p);
                    var blockLastGcp = $('.block-gcp.problem-number-' + s + '-' + p + ':last');
                    var heightLineProblem = blockLastGcp.offset().top - blockCurrentProblem.offset().top - blockCurrentProblem.css('height').split('px')[0] + 38;

                    var minusTopLineProblem;
                    if (Number(blockCurrentProblem.css('height').split('px')[0]) > 70)
                        minusTopLineProblem = 40;
                    else
                        minusTopLineProblem = 55;

                    $('.line-problem.line-problem-' + s + '-' + p).css({
                        'top': blockCurrentProblem.offset().top - minusTopLineProblem,
                        'height': heightLineProblem
                    });

                    $('.line-gcp').each(function (g, elemLineGcp) {
                        // Устанавливаем высоту линий ГЦП
                        if ($('.line-gcp').hasClass('line-gcp-' + s + '-' + p + '-' + g)) {
                            var blockCurrentGcp = $('.block-gcp-' + s + '-' + p + '-' + g);
                            var blockLastMvp = $('.block-mvp.gcp-number-' + s + '-' + p + '-' + g + ':last');
                            var heightLineGcp = blockLastMvp.offset().top - blockCurrentGcp.offset().top - blockCurrentGcp.css('height').split('px')[0] + 38;

                            var minusTopLineGcp;
                            if (Number(blockCurrentGcp.css('height').split('px')[0]) > 70)
                                minusTopLineGcp = 40;
                            else
                                minusTopLineGcp = 55;

                            $('.line-gcp.line-gcp-' + s + '-' + p + '-' + g).css({
                                'top': blockCurrentGcp.offset().top - minusTopLineGcp,
                                'height': heightLineGcp
                            });
                        }
                    })
                }
            })
        }
    })
});