{include file=$HEADER}
<div class="rbroundbox">
	<div class="rbtop"><div></div></div>
		<div class="rbcontent">
			<h2>Login </h2>
			 <form action="index.php?package=login&amp;action=loginsubmit" name="frm_login" method="post">
					<p>{#LN_LOGIN_USERNAME#}
					<input class="textinput" name="username" type="text" value="" maxlength="255" ></p>
					<p>{#LN_LOGIN_PASSWORD#}
					<input class="textinput" name="password" type="password" value="" maxlength="255" >
					</p>
					<p> <a href="index.php?package=login&amp;action=forget">{#LN_LOGIN_FORGET_LINKNAME#}</a> | <a href="#" onClick="document.frm_login.submit();">{#LN_LOGIN_LINKNAME#}</a></p>
				 </form>
	 </div><!-- /rbcontent -->
	<div class="rbbot"><div></div></div>
</div><!-- /rbroundbox -->
{include file=$FOOTER}