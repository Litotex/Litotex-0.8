{include file=$HEADER}

<div id="acp_tplmods_container" class="acp_tplmods_container" style="display: none;">

	<div class="actions">
		<a href="#" onclick="openNewWindow(); return false;">
			{#tplmods_open_new_window#}
		</a> | 
		<a href="#" onclick="showTplModBox(); return false;">
			{#tplmods_insert_new_mod#}
		</a>
	</div>
	
	<div id="acp_tplmods_elementbox" class="acp_tplmods_elementbox" style="display:none;">
		{foreach item=oElement from=$aElements}
			<div id="tpl[{$oElement->position}]_{$oElement->ID}" class="tplmods_draggable connectedDropable ui-widget-content">
				<p>
					{#tplmods_class#}: {$oElement->class}<br/>
					{#tplmods_function#}: {$oElement->class}</p>
			</div>
		{/foreach}
	</div>

</div>

<script>
	startTplMod();
</script>

{include file=$FOOTER}