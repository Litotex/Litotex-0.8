{if $iAssociateType == 1 OR $iAssociateType == 2}
<form class="permissions" action="" method="post">
	<input type="hidden" name="associateType" value="{$iAssociateType}" />
	<input type="hidden" name="associateID" value="{$iAssociateID}" />
	<table>
		<colgroup>
			<col class="col_function"/>
			<col class="col_access_allowed"/>
			<col class="col_access_denied"/>
			<col class="col_access_denied_complete"/>
		</colgroup>
	</table>
	{foreach from=$aPermissionArray item=aPermissions key=sPackageName}
		<h2>{$sPackageName}</h2>
		<table>
			<colgroup>
				<col class="col_function"/>
				<col class="col_access_allowed"/>
				<col class="col_access_denied"/>
				<col class="col_access_denied_complete"/>
			</colgroup>
			<tr class="tr_header">
				<th>{#permissions_function#}</th>
				<th>{#permissions_access_allowed#}</th>
				<th>{#permissions_access_denied#}</th>
				<th>{#permissions_access_denied_complete#}</th>
			</tr>
		{foreach from=$aPermissions item=aPermission}
			<tr class="tr_permissions">
				<td>
					{$aPermission.function} <i>( {if $aPermission.type == 1} {#permissions_access_action#} {else} {#permissions_access_hook#} {/if} )</i>
				</td>
				<td>
					<input type="radio" name="permissions[{$aPermission.ID}]" value="1" {if $oPermission->getPermissionLevel($aPermission.ID) == 1} checked="checked" {/if}/>
				</td>
				<td>
					<input type="radio" name="permissions[{$aPermission.ID}]" value="0" {if $oPermission->getPermissionLevel($aPermission.ID) == 0} checked="checked" {/if}/>
				</td>
				<td>
					<input type="radio" name="permissions[{$aPermission.ID}]" value="-1" {if $oPermission->getPermissionLevel($aPermission.ID) == -1} checked="checked" {/if}/>
				</td>
			</tr>
		{/foreach}
		</table>
	{/foreach}
	<br/>
	<br/>

	<button class="acp_permissions_save_btn" onclick="savePermissions(this); return false;">{#permissions_save_button#}</button>
</form>
{/if}