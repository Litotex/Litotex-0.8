<h4><input type="checkbox" name="update[]" value="{$update.ID}" onchange="checkbox_checkItems(document.getElementsByName('update[]'), $('#checkctrl'))" />{$update.name}</h4>
<a href="#" onclick="if ($('#changelog{$update.ID}').is(':visible')) $('#changelog{$update.ID}').hide('slow'); else $('#changelog{$update.ID}').show('slow');">{#acp_packageManager_changelog#} &nbsp;&nbsp;&nbsp;҈</a>
<div style="display: none;" id="changelog{$update.ID}">
<ul>
{foreach from=$update.changelog item=changelog}
{if $changelog.new}
    <li>{if $changelog.crit}<b>!</b>{/if}{$changelog.date}
        <ul><li>{$changelog.text}</li></ul>
    </li>
{/if}
{/foreach}
</ul>
</div>