<!--
var oldTitle = '';
var oldDescription = '';
$(function() {
    var navButtons = '.topNavigation';
    var navButtonsActive = '.navi_main_active';
    $(navButtons).hover(function() {
        $(this).stop().animate({'bottom':'-35px'});
        oldTitle = $('#menuItemTitle')[0].innerHTML;
        oldDescription = $('#menuItemDescription')[0].innerHTML;
        $('#menuItemTitle')[0].innerHTML = $(this)[0].title;
        $('#menuItemDescription')[0].innerHTML = $(this)[0].rel;
    }, function () {
        $(this).stop().animate({'bottom':'0px'});
        $('#menuItemTitle')[0].innerHTML = oldTitle;
        $('#menuItemDescription')[0].innerHTML = oldDescription;
    });
    $(navButtons).click(function() {
        $(navButtonsActive).each(function(){
            $(this).switchClass('navi_main_active', 'navi_main_show', 0);
        });
        $(this).stop().animate({'bottom':'0px'}, 0);
        $(this).switchClass('navi_main_show', 'navi_main_active', 0);
        oldTitle = $('#menuItemTitle')[0].innerHTML = $(this)[0].title;
        oldDescription= $('#menuItemDescription')[0].innerHTML = $(this)[0].rel;
        $('.navi_top').each(function(){
            $(this).fadeOut();
        });
        $('#navi_top'+$(this)[0].name).fadeIn();
        return false;
    });
});
//-->