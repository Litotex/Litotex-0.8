<link rel="stylesheet" type="text/css" href="{$CSS_LOGIN_FILE}">




<div class="rbroundbox">
	<div class="rbtop"><div></div></div>
		<div class="rbcontent">
			<h2>Login </h2>
			 <form action="index.php?package=login&action=loginsubmit" name="frm_login" method="post">
					<p><em>Benutzername</em>
					<input class="textinput" name="username" type="text" value="" maxlength="255" /></p>
					<p><em>Kennwort</em>
					<input class="textinput" name="password" type="password" value="" maxlength="255" />
					</p>
					<p> <a href="index.php?package=login&action=forgott">Passwort vergessen?</a> | <a href="#" onClick="document.frm_login.submit();">Login</a></p>
				 </form>
	 </div><!-- /rbcontent -->
	<div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->

