{include file=$HEADER}
<h1>{sprintf(#acp_packageManager_packageInstallationOf#, $installItem.name)}</h1>
<p><a href="index.php?package=acp_packageManager&action=processUpdateQueue">{#acp_packageManager_continueInstallation#}</a>
{foreach from=$installer->getLog() item=logItem}
<p>{$logItem}</p>
{/foreach}
{include file=$FOOTER}