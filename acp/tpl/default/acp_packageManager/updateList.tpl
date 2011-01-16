{include file=$HEADER}
<h1>{#acp_packageManager_updateManager#}</h1>
<a href="?package=acp_packageManager&action=updateRemoteList">{#acp_packageManager_updatePackageList#}</a>
<h3>{#acp_packageManager_critUpdate#}</h3>
<form action="index.php?package=acp_packageManager&action=processUpdates" method="post">
{foreach from=$updates item=update}
{if $update.critupdate}
{include file=$TPL_DIR|cat:"updateItem.tpl"}
{/if}
{/foreach}
<h3>{#acp_packageManager_update#}</h3>
{foreach from=$updates item=update}
{if not $update.critupdate}
{include file=$TPL_DIR|cat:"updateItem.tpl"}
{/if}
{/foreach}
{literal}
<input type="checkbox" id="checkctrl" onchange="if(this.checked){checkboxes_checkAll(document.getElementsByName('update'));}else{checkboxes_uncheckAll(document.getElementsByName('update'));}" />
{/literal}
{#acp_packageManager_markAll#}
<input type="submit" value="{#acp_packageManager_processUpdate#}" />
</form>
{include file=$FOOTER}