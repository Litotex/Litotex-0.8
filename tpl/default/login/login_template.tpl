
<form class="cmxform" id="loginForm" method="post" action="index.php?package=login&amp;action=loginsubmit">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_LOGIN_TITLE#}</legend>

			<label>{#LN_LOGIN_USERNAME#}</label>
			<input id="username" name="username" class="required ui-widget-content" minlength="2" >


			<label for="password">{#LN_LOGIN_PASSWORD#}</label>
			<input type="password" id="password" name="password" class="required ui-widget-content" minlength="2" >

		<p>
			<button class="submit" type="submit">{#LN_LOGIN_BUTTON_TITLE#}</button>
		</p>
		<p> <a href="index.php?package=login&amp;action=forget">{#LN_LOGIN_FORGET_LINKNAME#}</a></p>
	</fieldset>
</form>

