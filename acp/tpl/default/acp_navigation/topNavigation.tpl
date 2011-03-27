<div id="navi_position">
    <h1 id="menuItemTitle">
        {foreach from=$navigationItems item=item}
            {if $item.active}
                {$item.title}
            {/if}
        {/foreach}
    </h1>
    <div id="menuItemDescription">
        {foreach from=$navigationItems item=item}
            {if $item.active}
                {$item.description}
            {/if}
        {/foreach}
    </div>
</div>
<div id="navi_main">
    <ul>
    {foreach from=$navigationItems item=item}
        <li{if $item.ID == 4} style="border-right: 0px;"{/if}>
            <a id="{$item.ID}" name="{$item.ID}" title="{$item.title}" rel="{$item.description}" href="index.php?topMenu=1" class="topNavigation {if $item.active}navi_main_active{else}navi_main_show{/if}">
            <div id="navi_main_{$item.ID}">
               <div class="navi_main_icon"><img src="{$CORE_IMG_URL}{$item.icon}" alt="" width="32" height="32" /></div>
                <div class="navi_main_text">{$item.title.0}</div>
            </div>
            </a>
        </li>
    {/foreach}
    </ul>
</div>
<div id="legend" style="bottom:0px; position:absolute;">
<div id="get_result"></div>
<div id="navSelected"></div>
{foreach from=$navigationItems item=item}
    <div id="acp_topMenu{$item.ID}_title" style="display:none;">{$item.title}</div>
    <div id="acp_topMenu{$item.ID}_description" style="display:none;">{$item.description}</div>
{/foreach}
</div>