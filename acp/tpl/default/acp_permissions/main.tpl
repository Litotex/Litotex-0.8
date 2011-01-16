{include file=$HEADER}
<h1>
{if $iAssociateType == 2}
	{#permissions_title_group#}
{elseif $iAssociateType == 1}
	{#permissions_title_user#}
{else}
	{#permissions_error_no_user#}
{/if}
</h1>
{if $iAssociateType == 1 OR $iAssociateType == 2}
<form action="index.php?package=acp_permissions&action=save" method="post">
	<input type="hidden" name="associateType" value="{$iAssociateType}" />
	<input type="hidden" name="associateID" value="{$iAssociateID}" />
	<table>
		<tr>
			<th>{#permissions_packet#}</th>
			<th>{#permissions_function#}</th>
			<th>{#permissions_access_allowed#}</th>
			<th>{#permissions_access_denied#}</th>
			<th>{#permissions_access_denied_complete#}</th>
		</tr>
	{foreach from=$oPermission->getAvailablePermissions() item=aPermission}
		<tr>
			<td>
				{$aPermission.package}
			</td>
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
<input type="submit" value="{#permissions_save_button#}" />
</form>
{/if}
{include file=$FOOTER}