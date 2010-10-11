{include file=$HEADER}
<h2>{#login_login#}</h2>
<form action="?package=acp_login&action=loginSubmit" method="post">
<p>{#login_username#}<input type="text" name="username" /></p>
<p>{#login_password#}<input type="password" name="password" /></p>
<p><input type="submit" value="{#login_submit#}" /></p>
</form>
{include file=$FOOTER}