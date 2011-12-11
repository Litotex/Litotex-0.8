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
			{#buildings_name#}
		</th>
		<th>
			{#buildings_race#}
		</th>
		<th>
			{#buildings_type#}
		</th>
		<th>
			{#buildings_status#}
		</th>
		<th>
			{#buildings_action#}
		</th>
	</tr>
{foreach item=building from=$buildings}
	<tr>
		<td>
			{$building->getName()}
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
				<img src="{$IMG_URL}user_edit.png" title="{#users_edit#}" alt="{#users_edit#}"/>
			</a>
			<a style="cursor: pointer;" onclick="accessUser('{$oTempUser->getUserName()}', {$oTempUser->getUserID()}); return false;">
				<img src="{$IMG_URL}key.png" title="{#users_access#}" alt="{#users_access#}"/>
			</a>
			{if $oUser->getUserID() != $oTempUser->getUserID() && $oTempUser->getData('serverAdmin') != 1}
				{if $oTempUser->checkUserBanned()}
					<a style="cursor: pointer;" onclick="unbanUser({$oTempUser->getUserID()}); return false;">
						<img src="{$IMG_URL}user_go.png" title="{#users_unban#}" alt="{#users_unban#}"/>
					</a>
				{else}
					<a style="cursor: pointer;" onclick="banUser({$oTempUser->getUserID()}); return false;">
						<img src="{$IMG_URL}exclamation.png" title="{#users_ban#}" alt="{#users_ban#}"/>
					</a>
				{/if}
				<a style="cursor: pointer;" onclick="delUser({$oTempUser->getUserID()}); return false;">
					<img src="{$IMG_URL}user_delete.png" title="{#users_delete#}" alt="{#users_delete#}"/>
				</a>
			{/if}
		</td>
	<tr>
{/foreach}

</table>