<fieldset class="ui-widget ui-widget-content ui-corner-all">
<legend class="ui-widget ui-widget-header ui-corner-all">{#titleTplSwitcher#}</legend>
	<form method="post" action="?package=tplSwitcher&amp;action=save">
	<select name="tpl">
	{foreach from=$tpls item=tpl}
	<option value="{$tpl.0}"{if $tpl.1} selected="selected"{/if}>{$tpl.0}</option>
	{/foreach}
	</select>
	<br>
	<button class="submit_s" type="submit">{#LN_BUTTON_TPL_SWITCH#}</button>
	</form>
</fieldset>
<br>
