{include file=$HEADER}
<h3>{#LN_SCREENSHOT_ACP#}</h3><br>
 {literal} 
	<script type="text/javascript">
		$(function() {
			$('#gallery a').lightBox({fixedNavigation:true});
		});
    </script>
{/literal} 
<div id="gallery">
	<ul>
		{foreach from=$ImageItems item=item}
			<li>
				<a href="{$item.normal}" title="{$item.name}"><img src="{$item.thumb}"/></a>
			</li>
		{/foreach}
	</ul>
 </div><!-- / gallery -->
{include file=$FOOTER}
 