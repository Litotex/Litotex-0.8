<div id="navi_position">
        	<h1 id="menuItemTitle">{#acp_topMenu1_title#}</h1>
            <p><i id="menuItemDescription">{#acp_topMenu1_description#}</i></p>
        </div>
        <div id="navi_main">
        	<ul>
                    {foreach from=$navigationItems item=item}
                <li{if $item.ID == 4} style="border-right: 0px;"{/if}>
                    <a name="{$item.ID}" title="{$item.title}" rel="{$item.description}" href="index.php?topMenu=1" class="topNavigation {if $item.ID == 1}navi_main_active{else}navi_main_show{/if}">
                	{if $item.ID == 1}<div class="navi_main_aktiv"></div>{/if}
                	<div>
                        <div class="navi_main_icon"><img src="{$CORE_IMG_URL}{$item.icon}" alt="" width="32" height="32" /></div>
                        <div class="navi_main_text">{$item.title.0}</div>
                    </div>
                </a>
                </li>
                    {/foreach}
            </ul>
        </div>