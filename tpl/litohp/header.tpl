<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{generateTplModification position=left}
{generateTplModification position=right}
{generateTplModification position=content}
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$TITLE}</title>

{foreach from=$CSS_FILES item=CSS_FILE}
<link rel="stylesheet" type="text/css" href="{$CSS_FILE}">
{/foreach}
{foreach from=$JS_FILES item=JS_FILE}
<script type="text/javascript" src="{$JS_FILE}"></script>
{/foreach}

<link href="{$CORE_CSS_URL}formate_reset.css" rel="stylesheet" type="text/css" />
<link href="{$CORE_CSS_URL}formate.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{$CORE_CSS_URL}slides.css" type="text/css" media="screen" />
<link rel="stylesheet" href="{$CORE_JS_URL}prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" />

<!-- JAVASCRIPT GOES HERE -->
<script type='text/javascript' src='{$CORE_JS_URL}jquery.js'></script>
<script type="text/javascript" src="{$CORE_JS_URL}prettyPhoto/js/jquery.prettyPhoto.js"></script>
<script type='text/javascript' src='{$CORE_JS_URL}jquery.aviaSlider.js'></script>
<script type='text/javascript' src='{$CORE_JS_URL}custom.js'></script>

{literal}
<!-- Example jQuery code (JavaScript)  -->
<script type="text/javascript">
<!--
jQuery(document).ready(function() {
	$("a#ToogleSidebarLogin").click().toggle(function() {
		$('#sidebar_login').animate({
			height: 'show',
			opacity: 'show'
		}, 'slow');
	}, function() {
		$('#sidebar_login').animate({
			height: 'hide',
			opacity: 'hide'
		}, 'slow');
	});
	$("a#ToogleSidebarSwitch").click().toggle(function() {
		$('#sidebar_switch').animate({
			height: 'show',
			opacity: 'show'
		}, 'slow');
	}, function() {
		$('#sidebar_switch').animate({
			height: 'hide',
			opacity: 'hide'
		}, 'slow');
	});
});
-->
</script>


<script type="text/javascript">
<!-- Example jQuery code (JavaScript) ICON ZOOM  -->

$(document).ready(function() {

	//move the image in pixel
	var move = -15;

	//zoom percentage, 1.2 =120%
	var zoom = 1.1;

	//On mouse over those thumbnail
	$('.navi_icon_zoom').hover(function() {

		//Set the width and height according to the zoom percentage
		width = $('.navi_icon_zoom').width() * zoom;
		height = $('.navi_icon_zoom').height() * zoom;

		//Move and zoom the image
		$(this).find('img').stop(false,true).animate({'width':width, 'height':height, 'top':move, 'left':move}, {duration:200});

		//Display the caption
		$(this).find('div.caption').stop(false,true).fadeIn(200);
	},
	function() {
		//Reset the image
		$(this).find('img').stop(false,true).animate({'width':$('.navi_icon_zoom').width(), 'height':$('.navi_icon_zoom').height(), 'top':'0', 'left':'0'}, {duration:100});

		//Hide the caption
		$(this).find('div.caption').stop(false,true).fadeOut(200);
	});

});

</script>
{/literal}
</head>

<body>

<div id="wrapper">
	<div id="sidebar_login">LOGIN</div>
    <div id="sidebar_switch">SWITCH</div>
	<div id="ci"></div>
    <div id="logo"></div>
    <div id="navi_top">
    	<ul>
        	<li><a href="#" class="active">HOME</a></li>
            <li><a href="#">TOUR</a></li>
            <li><a href="#">HELP</a></li>
            <li><a href="#">SIGNUP</a></li>
            <li><a href="#">BLOG</a></li>
            <li><a href="#">ABOUT</a></li>
        </ul>
    </div>
    <div id="navi_icon">
    	<ul>
        	<li class="navi_icon_zoom"><a href="#"><img src="{$CORE_IMG_URL}icons/suche.jpg" height="32" width="32" alt="Suche" /></a></li>
            <li class="navi_icon_zoom"><a href="#" id="ToogleSidebarLogin"><img src="{$CORE_IMG_URL}icons/account.jpg" height="32" width="32" alt="Account" /></a></li>
            <li class="navi_icon_zoom"><a href="#" id="ToogleSidebarSwitch"><img src="{$CORE_IMG_URL}icons/switch.jpg" height="32" width="32" alt="Switch" /></a></li>
        </ul>
    </div>
    <div class="both"></div>
    <div id="box_info">
    	<div id="box_info_bilder">
        	<ul class='aviaslider' id="frontpage-slider">
        		<li><img src="{$CORE_IMG_URL}slides/1.jpg" alt="" />
                	<div class="box_info_text">
        				<h1>Litotex. clever content managing.</h1>
            			<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore. Dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor.</p>
            			<div id="presentation"><a href="#">Zur Präsentation</a></div>
        			</div>
                </li>
        		<li><img src="{$CORE_IMG_URL}slides/2.jpg" alt="" /></li>
        		<li><img src="{$CORE_IMG_URL}slides/3.jpg" alt="" /></li>
			</ul>
        </div>
        <div class="both"></div>
    </div>
    <div id="box_slogan">
    	<p>Sie wollen Litotex mit Ihrem eigenen Stempel versehen? Nutzen Sie die Möglichkeit zum Erwerb eines Branding Free License!</p>
    </div>
    <div id="main">
    	