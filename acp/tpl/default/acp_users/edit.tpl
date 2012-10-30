
<script>
	var iUserId = {$oUser->getUserID()};
	startGroupAssignments(iUserId);
	litotexAccordion();
</script>

<form action="index.php?package=acp_users&action=save" method="post">
	<div class="accordion">
		<h3><a href="#">{#users_userinfos#}</a></h3>

		<div>

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

			

		</div>

		<h3><a href="#">{#users_additionalfields#}</a></h3>
		<div>
			{foreach item=oField from=$aFields}
                                <label>{$oField->getKey()}:</label>{$oField->getHTML($oUser)}
				<br/>
			{/foreach}
		</div>
	
		<h3><a href="#">{#users_groups#}</a></h3>
		<div>

			<div class="users_groupblock">
				<span>zugewiesene Gruppen</span>
				<ul id="user_groups_{$oUser->getUserID()}" class='droptrue droptrue_{$oUser->getUserID()}'>
					{foreach item=oGroup from=$aUserGroups}
						<li class="ui-state-default" id="group_{$oGroup->getID()}">{$oGroup->getName()}</li>
					{/foreach}
				</ul>
			</div>
			<div class="users_groupblock">
				<span>mögliche Gruppen</span>
				<ul id="all_groups_{$oUser->getUserID()}" class='droptrue droptrue_{$oUser->getUserID()}'>
					{foreach item=oGroup from=$aGroups}
						<li class="ui-state-default" id="group_{$oGroup->getID()}">{$oGroup->getName()}</li>
					{/foreach}
				</ul>
			</div>

		</div>

	</div>
	<button onclick="saveUser(this, {$oUser->getUserID()}); return false;">{#users_save_btn#}</button>
</form>