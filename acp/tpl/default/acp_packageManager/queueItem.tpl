<h4><input type="checkbox" name="update[]" value="{$item.ID}" onchange="checkbox_checkItems(document.getElementsByName('update[]'), $('#checkctrl'))"{if $item.critupdate} checked="checked"{/if} />{$item.name}</h4>
<p><a href="#" onclick="if ($('#changelog{$item.ID}').is(':visible')) $('#changelog{$item.ID}').hide('slow'); else $('#changelog{$item.ID}').show('slow'); return true;">{#acp_packageManager_changelog#} &nbsp;&nbsp;&nbsp;҈</a></p>
<div style="display: none;" id="changelog{$item.ID}">
<ul>
{foreach from=$item.changelog item=changelog}
{if $changelog.new < 2}
    <li>{if $changelog.crit}<b>!</b>{/if}{$changelog.date}
        <ul><li>{$changelog.text}</li></ul>
    </li>
{/if}
{/foreach}
</ul>
</div>