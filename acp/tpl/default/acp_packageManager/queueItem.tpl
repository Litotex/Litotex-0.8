<h4{if $item.error} style="color:red"{/if}><input type="checkbox" name="update[]" value="{$item.ID}" onchange="checkbox_checkItems(document.getElementsByName('update[]'), $('#checkctrl'))"{if not $item.error} checked="checked"{/if} />{$item.name}{if $item.error} <u>{#acp_packageManager_packageConflict#}</u>{/if}</h4>
<p><a href="#" onclick="if ($('#settings{$item.ID}').is(':visible')) $('#settings{$item.ID}').hide('slow'); else $('#settings{$item.ID}').show('slow'); return false;">{#acp_packageManager_installSettings#} &nbsp;&nbsp;&nbsp;҈</a></p>
<div style="display: none;" id="settings{$item.ID}">
    <h5>Dateien</h5>
<ul>
    <li>Checkboxen aktivieren um Dateien auszulassen</li>
    <li><b>{#acp_packageManager_tplFiles#}</b></li>
{foreach from=$item.tplFilesChecked item=file}
    <li><input type="checkbox" name="fileBlacklist[]" value="{$item.name};tpl;{$file.1}"{if $file.0 == -1} checked="checked"{/if} />{$file.1} {if $file.0 == 0}Datei nicht installiert{else}{if $file.0 == -1}<span style="color:red">Datei wurde lokal verändert</span>{else}Datei im Originalzustand{/if} <a href="index.php?package=acp_diff&oldFile={urlencode($file.3)}&newFile={urlencode($file.2)}&noTemplate=true" target="_blank">Vergleichen</a>{/if}</li>
{/foreach}
    <li><b>{#acp_packageManager_packageFiles#}</b></li>
{foreach from=$item.packageFilesChecked item=file}
    <li><input type="checkbox" name="fileBlacklist[]" value="{$item.name};package;{$file.1}"{if $file.0 == -1} checked="checked"{/if} />{$file.1} {if $file.0 == 0}Datei nicht installiert{else}{if $file.0 == -1}<span style="color:red">Datei wurde lokal verändert</span>{else}Datei im Originalzustand{/if} <a href="index.php?package=acp_diff&oldFile={urlencode($file.3)}&newFile={urlencode($file.2)}&noTemplate=true" target="_blank">Vergleichen</a>{/if}</li>
{/foreach}
    <li><b>{#acp_packageManager_dbQuerys#}</b></li>
{foreach from=$item.queryList item=query}
    <li><input type="checkbox" name="queryBlacklist[]" value="{$item.name};{md5($query)}" />{nl2br($query)}</li>
{/foreach}
</ul>
</div>