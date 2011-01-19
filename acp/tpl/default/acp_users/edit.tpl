<div id="successDiv" style="display:none;"></div>
<div id="errorDiv" style="display:none;"></div>
	
<form action="index.php?package=acp_users&action=save" method="post" id="saveUser">

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

	<br/>
	<button onclick="saveUser(); return false;">{#users_save_btn#}</button>

</form>