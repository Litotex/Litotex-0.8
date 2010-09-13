
<h2>{#titleTplSwitcher#}</h2>
<form method="post" action="?package=tplSwitcher&action=save">
<select name="tpl">
{foreach from=$tpls item=tpl}
<option value="{$tpl.0}"{if $tpl.1} selected="selected"{/if}>{$tpl.0}</option>
{/foreach}
</select>
<br />
<input type="submit" />
</form>
