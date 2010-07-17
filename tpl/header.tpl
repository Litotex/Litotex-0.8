<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>{$TITLE}</title>
{foreach from=$CSS_FILES item=CSS_FILE}
<link rel="stylesheet" type="text/css" href="{$CSS_FILE}">
{/foreach}
{foreach from=$JS_FILES item=JS_FILE}
<script type="text/javascript" src="{$JS_FILE}"></script>
{/foreach}
</head>
<body>
<div id="wrapper">
<div id="header">
   	<div id="date"> Monday, 26 April, 2010 20:37:10 | <a href="#">Impressum</a> | <a href="icon_support"></a> <a href="#">Logout</a> | <a href="#"> </a></div>
<div id="logo">
<table border="0">
  <tr>
    <td><a href="index.php"></a></td>
    <td id="werbung"></td>
  </tr>
</table>
</div>
<div id="login">
      <form action="http://localhost/Litotex/modules/login/login.php?action=submit" name="frm_login" method="post">
        <p><em>Benutzername</em>

      <input class="textinput" name="username" type="text" value="" maxlength="255" /></p>
     <p><em>Kennwort</em>
      <input class="textinput" name="password" type="password" value="" maxlength="255" />
     </p>
     <p> <a href="http://localhost/Litotex/modules/register/register.php?action=forgott">Passwort vergessen?</a> | <a href="#" onClick="document.frm_login.submit();">Login</a></p>
     </form>

    </div>

<div id="navi">
  <table width="auto" border="0">

					<tr>

													<td style="padding-left: 10px;" id="icon_general"><a href="http://localhost/Litotex/index.php"><img src="http://localhost/Litotex/images/standard/navigation/icon/general.png" border="0" /><div class="navi_link">Startseite</div></a></td>
													<td style="padding-left: 10px;" id="icon_message"><a href="index.php?package=news"><img src="http://localhost/Litotex/images/standard/navigation/icon/general.png" border="0" /><div class="navi_link">News</div></a></td>

													<td style="padding-left: 10px;" id="icon_setting"><a href="http://localhost/Litotex/modules/screenshot/screenshot.php"><img src="http://localhost/Litotex/images/standard/navigation/icon/general.png" border="0" /><div class="navi_link">Screenshots</div></a></td>
													<td style="padding-left: 10px;" id="icon_support"><a href="http://localhost/Litotex/modules/register/register.php"><img src="http://localhost/Litotex/images/standard/navigation/icon/general.png" border="0" /><div class="navi_link">Registrieren</div></a></td>
											</tr>
	</table>
</div>
<div class="subnavi">
<div id="navi_1">

</div>
</div>
<div class="subnavi">

<div id="navi_2">

</div>
</div>

<div id="content">

<div class="box1">
  <div class="ro_box1">
    <div class="lo_box1">
      <div class="ru_box1">
        <div class="lu_box1">