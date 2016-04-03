$.fn.scale = function (x) {
    if (!$(this).filter(':visible').length && x != 1)return $(this);
    if (!$(this).parent().hasClass('scaleContainer')) {
        $(this).wrap($('<div class="scaleContainer">').css('position', 'relative'));
        $(this).data({
            'originalWidth': $(this).width(),
            'originalHeight': $(this).height()
        });
    }
    $(this).css({
        'zoom': x,
        '-ms-zoom': x,
        '-webkit-zoom': x,
        '-moz-transform': 'scale(x,x)',
        '-moz-transform-origin': 'left center'
    });
    if (x == 1)
        $(this).unwrap().css('position', 'static'); else
        $(this).parent()
            .width($(this).data('originalWidth') * x)
            .height($(this).data('originalHeight') * x);
    return $(this);
};