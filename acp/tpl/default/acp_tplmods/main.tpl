{include file=$HEADER}
<script>
	startDragable();
</script>
<div class="acp_tplmods_container">
	<div class="acp_tplmods_framebox">
		<iframe src="{$LITO_FRONTEND_URL}index.php?package=core_acp&action=tplModSort">
			
		</iframe>
	</div>
	<div class="acp_tplmods_elementbox">
		{foreach item=oElement from=$aElements}
			<div  class="ui-widget-content tplmods_draggable">
				<p>
					{#tplmods_class#}: {$oElement->class}<br/>
					{#tplmods_function#}: {$oElement->class}</p>
			</div>
		{/foreach}
	</div>
</div>
<div style="clear: both;"></div>

{include file=$FOOTER}
