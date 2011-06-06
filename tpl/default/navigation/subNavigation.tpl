<ul>
{foreach from=$navigationItems item=item}
        <li>
            <a id="{$item.ID}" name="{$item.ID}" title="{$item.title}" rel="{$item.description}" href="{$item.link}"  {if $item.active}class="active"{else}{/if}>{$item.title}
            </a>
        </li>
    {/foreach}  
</ul>	
	