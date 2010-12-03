{include file=$HEADER}
<h1>{#users_searchUser#}</h1>
<form action="index.php" method="get">
<input type="text" name="q" />
<input type="hidden" name="package" value="acp_users">
<input type="hidden" name="action" value="searchUser">
<input type="submit" value="{#users_search#}" />
</form>
<h4>{#users_results#}</h4>
{foreach from=$users item=user}
<p><a href="?package=acp_users&action=editUser&ID={$user->getUserID()}">{$user->getData('username')}</a></p>
{/foreach}
{include file=$FOOTER}