
$(document).ready(function(){

    $('header,section').animate({'opacity':1}, 500, function() {
        $('#global-loading').animate({'opacity':0}, 100, function() {
            $('#global-loading').hide();
        })
    })
});

$('header #slider').bxSlider({
    'mode':'fade',
    'auto':true,
    'controls':false,
    'pager':false,
    'randomStart':true,
    'wrapperClass':'headerSliderWrapper'
});
