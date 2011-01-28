<script>
	litotexAccordion();
</script>
{if $iAssociateType == 1 OR $iAssociateType == 2}
<form class="permissions" action="" method="post">
	<input type="hidden" name="associateType" value="{$iAssociateType}" />
	<input type="hidden" name="associateID" value="{$iAssociateID}" />
	<div class="accordion">
		{foreach from=$aPermissionArray item=aPermissions key=sPackageName}
			<h2>{$sPackageName}</h2>
			<div>
				{foreach from=$aPermissions item=aPermission}
				<div class="permission_content_box">
						{$aPermission.function} <i>( {if $aPermission.type == 1} {#permissions_access_action#} {else} {#permissions_access_hook#} {/if} )</i>
						<div class="permission_button_box">
							<div class="permission_button">
								<input type="radio" name="permissions[{$aPermission.ID}]" value="1" {if $oPermission->getPermissionLevel($aPermission.ID) == 1} checked="checked" {/if}/>
								Ja
							</div>
							<div class="permission_button">
								<input type="radio" name="permissions[{$aPermission.ID}]" value="0" {if $oPermission->getPermissionLevel($aPermission.ID) == 0} checked="checked" {/if}/>
								Nein
							</div>
							<div class="permission_button">
								<input type="radio" name="permissions[{$aPermission.ID}]" value="-1" {if $oPermission->getPermissionLevel($aPermission.ID) == -1} checked="checked" {/if}/>
								Nie
							</div>
						</div>
					</div>
			   {/foreach}
			</div>
		{/foreach}
	</div>
	<button class="acp_permissions_save_btn" onclick="savePermissions(this); return false;">{#permissions_save_button#}</button>
</form>
{/if}