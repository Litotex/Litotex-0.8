
<table>
	<tr>
		<th>
			Benutzer
		</th>
		<th>
			dabei seit
		</th>
		<th>
			letzter Login
		</th>
		<th>
			Aktion
		</th>
	</tr>
{foreach item=oUser from=$aUsers}
	<tr>
		<td>
			{$oUser->getUserName()}
		</td>
		<td>
			{$oUser->getLastActive()}
		</td>
		<td>
			{$oUser->getCreateDate()}
		</td>
		<td>
			{#users_edit#} | {#users_ban#} | {#users_delete#}
		</td>
	<tr>
{/foreach}

</table>