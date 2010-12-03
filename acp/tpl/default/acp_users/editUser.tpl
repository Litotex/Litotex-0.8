{include file=$HEADER}
<h1>{#users_editUser#} - {$user->getData('username')}</h1>
<form action="index.php?package=acp_users&action=editUser">
{$user->getData('email')}
</form>
{include file=$FOOTER}