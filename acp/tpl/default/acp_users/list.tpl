
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
{foreach item=oTempUser from=$aUsers}
	<tr>
		<td>
			{$oTempUser->getUserName()}
		</td>
		<td>
			{$oTempUser->getCreateDate()}
		</td>
		<td>
			{$oTempUser->getLastActive()}
		</td>
		<td>
			{$oTempUser->getStatus()}
		</td>
		<td>
			<a style="cursor: pointer;" onclick="editUser('{$oTempUser->getUserName()}', {$oTempUser->getUserID()}); return false;">
				{#users_edit#}
			</a> |
			<a style="cursor: pointer;" onclick="accessUser('{$oTempUser->getUserName()}', {$oTempUser->getUserID()}); return false;">
				{#users_access#}
			</a>
			{if $oUser->getUserID() != $oTempUser->getUserID() && $oTempUser->getData('serverAdmin') != 1}
				 |
				{if $oTempUser->checkUserBanned()}
					<a style="cursor: pointer;" onclick="unbanUser({$oTempUser->getUserID()}); return false;">
						{#users_unban#} |
					</a>
				{else}
					<a style="cursor: pointer;" onclick="banUser({$oTempUser->getUserID()}); return false;">
						{#users_ban#} |
					</a>
				{/if}
				<a style="cursor: pointer;" onclick="delUser({$oTempUser->getUserID()}); return false;">
					{#users_delete#}
				</a>
			{/if}
		</td>
	<tr>
{/foreach}

</table>