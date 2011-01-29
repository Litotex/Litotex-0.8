<script>
	var iGroupId = {$oGroup->getID()};
	litotexAccordion();
	var availableTags = [
			{foreach item=oUser from=$aUsers}
				"{$oUser->getUsername()}",
			{/foreach}
		];
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#groups_userlist_"+iGroupId ).bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 0,
			source: function( request, response ) {
				// delegate back to autocomplete, but extract the last term
				response( $.ui.autocomplete.filter(
					availableTags, extractLast( request.term ) ) );
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
			}
		});

</script>

<form action="index.php?package=acp_groups&action=save" method="post">
	<div class="accordion">
		<h3><a href="#">{#groups_groupinfos#}</a></h3>

		<div>

			<label>{#groups_name#}:</label>
			<input name="group[{$oGroup->getID()}][name]" value="{$oGroup->getData('name')}" />

		</div>

		<h3><a href="#">{#groups_users#}</a></h3>
		<div>
			<span class="userlist_description">{#groups_userlist_description#}</span>
			<textarea id="groups_userlist_{$oGroup->getID()}" style="width: 100%; height: 50px;">{$sUserList}</textarea>
		</div>

	</div>
	<button onclick="saveGroup(this, {$oGroup->getID()}); return false;">{#groups_save_btn#}</button>
</form>