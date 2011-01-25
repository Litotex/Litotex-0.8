{if !$noTemplate}{include file=$HEADER}{else}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Litotex ACP v. 0.2.0</title>
<link href="{$CORE_CSS_URL}formate_reset.css" rel="stylesheet" type="text/css" />
<link href="{$CORE_CSS_URL}formate.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 8]>
<link href="{$CORE_CSS_URL}formate_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src='{$CORE_JS_URL}menu.js'></script>
<script type="text/javascript" src="{$CORE_JS_URL}jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}navi_main.js"></script>
<script type="text/javascript" src="{$CORE_JS_URL}box_large.js"></script>

{foreach from=$CSS_FILES item=CSS_FILE}
<link rel="stylesheet" type="text/css" href="{$CSS_FILE}">
{/foreach}
{foreach from=$JS_FILES item=JS_FILE}
<script type="text/javascript" src="{$JS_FILE}"></script>
{/foreach}
</head>

<body>
{/if}
{$diff}
{if !$noTemplate}{include file=$FOOTER}
{else}
</body>
{/if}