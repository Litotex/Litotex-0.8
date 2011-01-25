
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
			{$oUser->getCreateDate()}
		</td>
		<td>
			{$oUser->getLastActive()}
		</td>
		<td>
			{$oUser->getStatus()}
		</td>
		<td>
			<a style="cursor: pointer;" onclick="editUser('{$oUser->getUserName()}', {$oUser->getUserID()}); return false;">
				{#users_edit#}
			</a> |
			<a style="cursor: pointer;" onclick="accessUser('{$oUser->getUserName()}', {$oUser->getUserID()}); return false;">
				{#users_access#}
			</a> |
			{if $oUser->checkUserBanned()}
				<a style="cursor: pointer;" onclick="unbanUser({$oUser->getUserID()}); return false;">
					{#users_unban#} |
				</a>
			{else}
				<a style="cursor: pointer;" onclick="banUser({$oUser->getUserID()}); return false;">
					{#users_ban#} |
				</a>
			{/if}
			<a style="cursor: pointer;" onclick="delUser({$oUser->getUserID()}); return false;">
				{#users_delete#}
			</a>
		</td>
	<tr>
{/foreach}

</table>