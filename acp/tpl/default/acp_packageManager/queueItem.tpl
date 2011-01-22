<h4><input type="checkbox" name="update[]" value="{$item.ID}" onchange="checkbox_checkItems(document.getElementsByName('update[]'), $('#checkctrl'))"{if $item.critupdate} checked="checked"{/if} />{$item.name}</h4>
<p><a href="#" onclick="if ($('#settings{$item.ID}').is(':visible')) $('#settings{$item.ID}').hide('slow'); else $('#settings{$item.ID}').show('slow'); return true;">{#acp_packageManager_installSettings#} &nbsp;&nbsp;&nbsp;҈</a></p>
<div style="display: none;" id="settings{$item.ID}">
    <h5>Dateien</h5>
<ul>
    <li><b>{#acp_packageManager_tplFiles#}</b></li>
{foreach from=$item.packageFiles.tpl item=file}
    <li>{$file}</li>
{/foreach}
    <li><b>{#acp_packageManager_packageFiles#}</b></li>
{foreach from=$item.packageFiles.package item=file}
    <li>{$file}</li>
{/foreach}
</ul>
    <h5>Datenbank</h5>
</div>