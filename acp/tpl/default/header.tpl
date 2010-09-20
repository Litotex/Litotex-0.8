<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<title>Litotex ACP</title>
{foreach from=$CSS_FILES item=CSS_FILE}
<link rel="stylesheet" type="text/css" href="{$CSS_FILE}">
{/foreach}

</head>
<body>

<div id="wrapper">
  <div id="header">
    	<div id="header_navi">
		<div id="navi_header_left">Willkommen auf der Administrationsoberfl&auml;che von Litotex</div>
		<div id="navi_header_right"><img src="{$CORE_IMG_URL}clock.png" class="clock" /> {$smarty.now|date_format:"%A, %e %B, %Y"} {$smarty.now|date_format:"%H:%M:%S"}</div>
        
    </div>
  </div>
<div>
<div id="navi">
{displayTplModification position=acp_navi}
</div>
<table border="0">
  <tr>
    <td>
