{include file=$HEADER}
<h1>{sprintf(#acp_packageManager_packageInstallationOf#, $installItem.name)}</h1>
{foreach from=$installer->getLog() item=logItem}
<p>{$logItem}</p>
{/foreach}
{include file=$FOOTER}