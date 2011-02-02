{foreach from=$navigationItems item=parentNodes key=parentID}
    <div class="navi_top" id="navi_top{$parentID}">
    	<ul>
            {foreach from=$parentNodes item=item}
            <li><a href="index.php?package={$item.package}&action={$item.action}{if $item.tab}#ui-tabs-{$item.tab}{/if}" onmouseover="show('um_{$item.ID}')" onmouseout="out('um_{$item.ID}')" onclick="location.reload()">{$item.title}</a>
                <ul id="um_{$item.ID}">
                {foreach from=$item.sub item=subItem}
                    <li><a href="index.php?package={$subItem.package}&action={$subItem.action}{if $subItem.tab}#ui-tabs-{$subItem.tab}{/if}" onmouseover="show('um_{$item.ID}')"  onmouseout="out('um_{$item.ID}')" onclick="location.reload()">{$subItem.title}</a></li>
                {/foreach}
                </ul>
            </li>
            {/foreach}
        </ul>
    </div>
{/foreach}
