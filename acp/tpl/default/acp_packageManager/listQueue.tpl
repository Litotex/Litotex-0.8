{include file=$HEADER}
<h1>{#acp_packageManager_updateManager#}</h1>
<h3>{#acp_packageManager_queueList#}</h3>
<p>{sprintf(#acp_packageManager_queueCount#, count($installQueue))}</p>
<p>{#acp_packageManager_queueNoProblems#}</p>
<p>
<form action="index.php?package=acp_packageManager&action=processUpdates" method="post">
<input type="submit" value="{#acp_packageManager_processQueue#}" />
{foreach from=$installQueue item=item}
{include file=$TPL_DIR|cat:"queueItem.tpl"}
{/foreach}
{literal}
<input type="checkbox" id="checkctrl" onchange="if(this.checked){checkboxes_checkAll(document.getElementsByName('update[]'));}else{checkboxes_uncheckAll(document.getElementsByName('update[]'));}" />
{/literal}
{#acp_packageManager_markAll#}
<input type="submit" value="{#acp_packageManager_processQueue#}" />
</form>
{include file=$FOOTER}