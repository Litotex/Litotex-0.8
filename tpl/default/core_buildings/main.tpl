{include file=$HEADER}
<table>
{foreach from=$buildings item=building}
<tr><td>{$building[1]->getName()} aktuelle Stufe: {$building[0]}</td></tr>
{/foreach}
</table>
{include file=$FOOTER}