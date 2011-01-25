{generateTplModification position=acpNavi}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Litotex ACP v. 0.2.0</title>
<link href="{$CORE_CSS_URL}jquery-ui-1.8.6.custom.css" rel="stylesheet" type="text/css" />
<link href="{$CORE_CSS_URL}formate_reset.css" rel="stylesheet" type="text/css" />
<link href="{$CORE_CSS_URL}formate.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 8]>
<link href="{$CORE_CSS_URL}formate_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src='{$CORE_JS_URL}menu.js'></script>
<script type="text/javascript" src="{$CORE_JS_URL}jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}jquery-ui-1.8.6.custom.min.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}navi_main.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}box_large.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}litotex.js"></script>

{foreach from=$CSS_FILES item=CSS_FILE}
<link rel="stylesheet" type="text/css" href="{$CSS_FILE}">
{/foreach}
{foreach from=$JS_FILES item=JS_FILE}
<script type="text/javascript" src="{$JS_FILE}"></script>
{/foreach}
</head>

<body>
<div id="hg_body_info">
{if package::$user}<span><h1>{#acp_onlineAs#} {package::$user->getUserName()} | <a href="../index.php?package=login&action=logout">{#acp_logout#}</a> | <a href="http://update.freebg.de">{#acp_redirectUpdateServer#}</a> | <a href="..">{#acp_redirectFrontend#}</a></h1></span>
{else}
<span><h1>{#acp_notLoggedIn#} | <a href="http://update.freebg.de">{#acp_redirectUpdateServer#}</a> | <a href="..">{#acp_redirectFrontend#}</a></h1></span>
{/if}
<span style="float: right;"><h1>Zum {$smarty.now|date_format:#acp_dateFormTop#}</h1></span>
</div>
<div id="wrapper">
	<div id="header">
    	<div id="logo"></div>
        <div id="navi_position">
        	<h1>{#acp_topMenu1_title#}</h1>
            <p><i>{#acp_topMenu1_description#}</i></p>
        </div>
        <div id="navi_main">
        	<ul>
            	<li style="border-right: 0px;">
                <a href="index.php?topMenu=1" class="navi_main_aktiv">
                	<div class="navi_main_aktiv"></div>
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}home.png" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{#acp_topMenu1_char#}</div>
                    </div>
                </a>
                </li>
                <li style="border-right: 0px;">
                <a href="index.php?topMenu=2" class="navi_main_show">
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}process.png" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{#acp_topMenu2_char#}</div>
                    </div>
                </a>
                </li>
                <li style="border-right: 0px;">
                <a href="index.php?topMenu=3" class="navi_main_show">
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}chart.png" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{#acp_topMenu3_char#}</div>
                    </div>
                </a>
                </li>
                <li style="border-right: 0px;">
                <a href="index.php?topMenu=4" class="navi_main_show">
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}user.png" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{#acp_topMenu4_char#}</div>
                    </div>
                </a>
                </li>
                <li>
                <a href="index.php?topMenu=5" class="navi_main_show">
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}add.png" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{#acp_topMenu5_char#}</div>
                    </div>
                </a>
                </li>
            </ul>
        </div>
    </div>
    <div id="navi_top">
    	<ul>
        	<li><a href="#" onmouseover="show('um_0')" onmouseout="out()">Einstellungen</a>
            	<!-- 1. Untermenü -->
                <ul id="um_0">
                    <li><a href="#" onmouseover="show('um_0')"  onmouseout="out()">Print</a></li>
                    <li><a href="#" onmouseover="show('um_0')"  onmouseout="out()">Non-Print</a></li>
                    <li><a href="#" onmouseover="show('um_0')"  onmouseout="out()">Bilder-Service</a></li>
                </ul>
            </li>
            <li><a href="#" onmouseover="show('um_1')" onmouseout="out()">User</a>
            	<!-- 1. Untermenü -->
                <ul id="um_1">
                    <li><a href="#" onmouseover="show('um_1')"  onmouseout="out()">Print</a></li>
                    <li><a href="#" onmouseover="show('um_1')"  onmouseout="out()">Non-Print</a></li>
                    <li><a href="#" onmouseover="show('um_1')"  onmouseout="out()">Bilder-Service</a></li>
                </ul>
            </li>
            <li><a href="#">Banner</a></li>
            <li></li>
        </ul>
    </div>
    <div id="main">
