<h4><input type="checkbox" name="update[]" value="{$update.ID}" onchange="checkbox_checkItems(document.getElementsByName('update[]'), $('#checkctrl'))"{if $update.critupdate} checked="checked"{/if} />{$update.name}</h4>
<p><a href="#" onclick="if ($('#changelog{$update.ID}').is(':visible')) $('#changelog{$update.ID}').hide('slow'); else $('#changelog{$update.ID}').show('slow'); return true;">{#acp_packageManager_changelog#} &nbsp;&nbsp;&nbsp;҈</a></p>
<div style="display: none;" id="changelog{$update.ID}">
<ul>
{foreach from=$update.changelog item=changelog}
{if $changelog.new < 2}
    <li>{if $changelog.crit}<b>!</b>{/if}{$changelog.date}
        <ul><li>{$changelog.text}</li></ul>
    </li>
{/if}
{/foreach}
</ul>
</div>