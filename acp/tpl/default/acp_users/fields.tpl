<style>
.column { width: 400px; float: left; padding-bottom: 100px; }
.portlet { margin: 0 1em 1em 0; }
.portlet-header { margin: 0.3em; padding-bottom: 4px; padding-left: 0.2em; }
.portlet-header .ui-icon { float: right; }
.portlet-content { padding: 0.4em; }
.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
.ui-sortable-placeholder * { visibility: hidden; }
</style>
<script>
$(function() {
	$( ".column" ).sortable({
		connectWith: ".column",
		update: function(event, ui){
			var sParam = $('#userFields').sortable('serialize');
			$.ajax({
				type: "POST",
				url: "index.php?package=acp_users&action=sortFields",
				data: sParam
			 });
		}
	});

	$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
		.find( ".portlet-header" )
			.addClass( "ui-widget-header ui-corner-all" )
			.end()
		.find( ".portlet-content" );

	$( ".portlet-header .ui-icon" ).click(function() {
		$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
		$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
	});

	$( ".column" ).disableSelection();
});
</script>


<div class="column" id="userFields">

	{foreach item=oField from=$aFields}
		<div class="portlet" id="userField_{$oField->getID()}">
			<div class="portlet-header">
				<div style="float: left;">
					{$oField->getKey()} ({$oField->getTypeName()})
				</div>
				<div style="float: right; margin-top: 5px; cursor: pointer;">
					<a onclick="deleteUserField({$oField->getID()}, '{#users_field_confirm_delete#}'); return false;">
						<img src="{$IMG_URL}cross.png" alt="{#users_field_delete#}" title="{#users_field_delete#}"/>
					</a>
				</div>
				<div style="clear: both;"></div>
			</div>
		<!--	<div class="portlet-content">
			
			</div> -->
		</div>
	{/foreach}

</div>


<div>
	<div style="width: 150px; float: left;">Name: </div><input value="" id="new_field_key" /><br/>
	<div style="width: 150px; float: left;">Sichtbar: </div><input type="checkbox" value="1" id="new_field_display" /><br/>
	<div style="width: 150px; float: left;">Editierbar: </div><input type="checkbox" value="1" id="new_field_editable" /><br/>
	<div style="width: 150px; float: left;">Optional: </div><input type="checkbox" value="1" id="new_field_optional" /><br/>
	<br/>
        {foreach from=$fieldTypes item=type}
	<button style="width: 200px; text-align: center;" onclick="addUserField('{$type.0}'); return false;">{$type.1}</button><br/>
	{/foreach}
</div>
<div style="clear: both;"></div>