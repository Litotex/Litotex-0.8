<ul id="dropmenueTop">
	{foreach from=$acpNavigationNodes item=acpNavigationNode}
	<li>{$acpNavigationNode.title} <a href="?package={$acpNavigationNode.package}&action={$acpNavigationNode.action}">Go</a>{if $acpNavigationNode.children} 
	<ul class="dropmenueDown">
		{foreach from=$acpNavigationNode.children item=child}
		<li><a href="?package={$child.package}&action={$child.action}">{$child.title}</a></li>
		{/foreach}
	</ul>
	{/if}</li>
	{/foreach}
</ul>
{literal}
<script type="text/javascript">
<!--
function initializeNavigation(root){
	i = 0;
	items = root.getElementsByTagName('ul');
	for(i = 0; i < items.length; i++){
		items[i].oldHeight = items[i].clientHeight;
		hideNavigation(items[i]);
		items[i].parentNode.dropItem = items[i];
		$(items[i].parentNode).click(
				function(){
					if(this.dropItem.style['visibility'] == 'hidden')
						extendNavigation(this.dropItem);
					else
						hideNavigation(this.dropItem);
				}
		);
	}
}
function hideNavigation(item){
	item.style['visibility'] = 'hidden';
	item.style['height'] = '0px';
}
function extendNavigation(item){
	item.style['height'] = item.oldHeight+'px';
	item.style['visibility'] = 'visible';
}
initializeNavigation(document.getElementById('dropmenueTop'));
//-->
</script>
{/literal}
