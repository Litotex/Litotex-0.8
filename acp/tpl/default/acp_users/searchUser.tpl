{include file=$HEADER}
<h1>{#users_searchUser#}</h1>
<form action="?action=searchUser" method="get">
<input type="text" name="q" />
<input type="submit" name="search" value="{#users_search#}" />
</form>
{include file=$FOOTER}