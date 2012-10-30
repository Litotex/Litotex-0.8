$(document).ready(function() {
    $(function() {
        var navButtons = '.topNavigation';
        var navButtonsActive = '.navi_main_active';
        
        $(navButtons).hover(
            function () {
                if($(this).attr('class')=="topNavigation navi_main_show") {
                    $("div#navi_main_"+$(this).attr('id')).stop().animate({'margin-top':'35px'});
                }
                
                Title=$('div#acp_topMenu'+$(this).attr('id')+"_title").html();
                Description=$('div#acp_topMenu'+$(this).attr('id')+"_description").html();
                
                $('h1#menuItemTitle').html(Title);
                $('div#menuItemDescription').html(Description);
            },
            function () {
                if($(this).attr('class')=="topNavigation navi_main_show") {
                    $("div#navi_main_"+$(this).attr('id')).stop().animate({'margin-top':'0px'});
                }
                
                Title=$('div#acp_topMenu'+$('div#navSelected').html()+"_title").html();
                Description=$('div#acp_topMenu'+$('div#navSelected').html()+"_description").html();
                
                $('h1#menuItemTitle').html(Title);
                $('div#menuItemDescription').html(Description);
            }
        );
        
        $(navButtons).click(function() {
            $('div#navSelected').html($(this).attr('id'));
            
            if($(this).attr('class')=="topNavigation navi_main_show") {
                $(navButtonsActive).each(function(){
                    $(this).switchClass('navi_main_active', 'navi_main_show');
                });
                $("div#navi_main_"+$(this).attr('id')).stop().animate({'margin-top':'0px'});
                $(this).switchClass('navi_main_show', 'navi_main_active');
            }
            
            $('.navi_top').each(function(){
                $(this).fadeOut(0);
            });
            $('#navi_top'+$(this)[0].name).fadeIn();
            return false;
        });
    });
});
var current;
var tOut;
function show(item){
    if(!document.getElementById(item))
        return;
    //cover('um_'+current);
    clearTimeout(tOut);
    if(document.getElementById){
        document.getElementById(item).style.display="block";
    }

}

function cover(item){
    if(!document.getElementById(item))
        return;
    document.getElementById(item).style.display="none";
}
	
function out(item){
    if(!document.getElementById(item))
        return;
    tOut=setTimeout("cover('"+item+"')",200);
}
function redirect(url) {
    $.get("index.php?package=acp_main&action=main_redirect", function(data) {
        $('div#get_result').html(data);
    });
}

/*
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
        //alert(oldTitle + " " + oldDescription);
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
            $(this).fadeOut(0);
        });
        $('#navi_top'+$(this)[0].name).fadeIn();
        return false;
    });
});
*/