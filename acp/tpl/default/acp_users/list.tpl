
<table>
	<colgroup>
		<col style="width:auto;" />
		<col style="width:100px;" />
		<col style="width:100px;" />
		<col style="width:100px;" />
		<col style="width:200px;" />
	</colgroup>
	<tr>
		<th>
			{#users_user#}
		</th>
		<th>
			{#users_created#}
		</th>
		<th>
			{#users_last_active#}
		</th>
		<th>
			{#users_status#}
		</th>
		<th>
			{#users_action#}
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
			...gebannt...aktiv...
		</td>
		<td>
			{#users_edit#} | {#users_ban#} | {#users_delete#}
		</td>
	<tr>
{/foreach}

</table>