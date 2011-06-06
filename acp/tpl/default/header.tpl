{generateTplModification position=acpTopNavi}
{generateTplModification position=acpSubNavi}
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
<script type="text/javascript" src="{$CORE_JS_URL}jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}jquery-ui-1.8.6.custom.min.js"></script>
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
<span style="float: right;"><h1>{$smarty.now|date_format:#acp_dateFormTop#}</h1></span>
</div>
<div id="wrapper">
	<div id="header">
    	<div id="logo"></div>
        {displayTplModification position=acpTopNavi}
    </div>
    {displayTplModification position=acpSubNavi}
    <div id="main">
