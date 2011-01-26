<div id="successDiv" style="display:none;"></div>
<div id="errorDiv" style="display:none;"></div>
	
<form action="index.php?package=acp_users&action=save" method="post">

	<label>{#users_username#}:</label> 
	<input name="user[{$oUser->getUserID()}][username]" value="{$oUser->getData('username')}" /> 
	<br/>
	
	<label>{#users_password#}:</label> 
	<input name="user[{$oUser->getUserID()}][password]" value="" /> 
	<br/>
	
	<label>{#users_email#}:</label> 
	<input name="user[{$oUser->getUserID()}][email]" value="{$oUser->getData('email')}" /> 
	<br/>
	
	<label>{#users_serveradmin#}:</label> 
	<input type="hidden" name="user[{$oUser->getUserID()}][serverAdmin]" value="0" /> 
	<input type="checkbox" name="user[{$oUser->getUserID()}][serverAdmin]" value="1" {if $oUser->getData('serverAdmin') == 1} checked="checked" {/if} /> 
	<br/>

	{foreach item=oField from=$aFields}
		<label>{$oField->key}:</label>
		{if $oField->type == 'input'}
			<input name="userfield[{$oUser->getUserID()}][{$oField->ID}]" value="{$oUser->getUserFieldData($oField->ID)}" />
		{else if $oField->type == 'textarea'}
			<textarea name="userfield[{$oUser->getUserID()}][{$oField->ID}]">{$oUser->getUserFieldData($oField->ID)}</textarea>
		{else if $oField->type == 'checkbox'}
			<input type="hidden" name="userfield[{$oUser->getUserID()}][{$oField->ID}]" value="0" />
			<input type="checkbox" name="userfield[{$oUser->getUserID()}][{$oField->ID}]" value="1" {if {$oUser->getUserFieldData($oField->ID)} == 1} checked="checked" {/if} />
		{/if}
		<br/>
	{/foreach}

	<br/>
	<button onclick="saveUser(this); return false;">{#users_save_btn#}</button>

</form>