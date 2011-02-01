<!--
var oldTitle = '';
var oldDescription = '';
$(function() {
    var navButtons = '.navi_main_show';
    $(navButtons).hover(function() {
        $(this).stop().animate({'bottom':'-35px'}); oldTitle = $('#menuItemTitle')[0].innerHTML; oldDescription = $('#menuItemDescription')[0].innerHTML; $('#menuItemTitle')[0].innerHTML = $(this)[0].title; $('#menuItemDescription')[0].innerHTML = $(this)[0].rel;
        }, function () {
        $(this).stop().animate({'bottom':'0px'}); $('#menuItemTitle')[0].innerHTML = oldTitle; $('#menuItemDescription')[0].innerHTML = oldDescription;
    });
    $(navButtons).click(function() {

        return false;
    });
});
//-->