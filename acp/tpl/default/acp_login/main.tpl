{include file=$HEADER}
<form class="cmxform" id="LoginForm" action="?package=acp_login&action=loginSubmit" method="post">
<fieldset class="ui-widget ui-widget-content ui-corner-all">
	<legend class="ui-widget ui-widget-header ui-corner-all">{#login_login#}</legend>

	<p>
	<label for="username">{#login_username#}</label>
	<input type="text" name="username"  class="required ui-widget-content" minlength="2" />
</p>
<p>
	<label for="username">{#login_password#}</label>
	<input type="password" name="password"  class="required ui-widget-content" minlength="2"  /></p>
<p>
<input id="button" type="submit" value="{#login_submit#}" /></p>
</fieldset>
</form>
{include file=$FOOTER}