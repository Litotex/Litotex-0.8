<textarea name="{$cfgElementName}" id="{$cfgElementName}" rows="{$cfgElementSettings.height}" cols="{$cfgElementSettings.width}"></textarea>
{if $cfgElementSettings.maxLength > 0}
<div id="{$cfgElementName}TextCounter">0/{$cfgElementSettings.maxLength}</div>
{literal}
<script type="text/javascript">
<!--
	element = document.getElementById("{/literal}{$cfgElementName}{literal}");
	$(element).keyup(function(){
		//{/literal}
		countTextSigns(this, '{$cfgElementName}', {$cfgElementSettings.maxLength});
		//{literal}
	});
	$(document).ready(function(){
		//{/literal}
		registerSubmitFunction(countTextSignsSubmit, new Array(element, '{$cfgElementName}', {$cfgElementSettings.maxLength}, "{#E_tooMuchSigns#}"));
		//{literal}
	});
//-->
</script>
{/literal}
{/if}
{if $cfgElementSettings.allowedChars}
<div id="{$cfgElementName}AllowedChars">{#acp_usersAllowedChars#}{foreach from=$cfgElementSettings.allowedChars item=char} "{$char}" {/foreach}</div>
{literal}
<script type="text/javascript">
<!--
	element = document.getElementById("{/literal}{$cfgElementName}{literal}");
	$(element).keyup(function(){
		//{/literal}
		checkAllowedSigns(this, "{$cfgElementName}", '{foreach from=$cfgElementSettings.allowedChars item=char}\\{$char}{/foreach}');
		//{literal}
	});
	$(document).ready(function(){
		//{/literal}
		registerSubmitFunction(checkAllowedSignsSubmit, new Array(element, '{$cfgElementName}', '{foreach from=$cfgElementSettings.allowedChars item=char}\\{$char}{/foreach}', "{#E_notAllowedSigns#}"));
		//{literal}
	});
//-->
</script>
{/literal}
{/if}