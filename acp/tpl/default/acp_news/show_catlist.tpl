
<table>
	<colgroup>
		<col style="width:200px;" />
		<col style="width:500px;" />

		<col style="width:auto;" />
	</colgroup>
	<tr>
		<th>
			{#LN_NEWS_TITEL_1#}
		</th>
		<th>
			{#LN_NEWS_TITEL_2#}
		</th>
		
		<th>
			{#LN_NEWS_TITEL_5#}
		</th>		
	</tr>
{foreach from=$aOptions item=oTempOptions}
	<tr>
		<td>
			{$oTempOptions.title}
		</td>
		<td>
			{$oTempOptions.description}
		</td>
		

		<td>
			<a style="cursor: pointer;" onclick="editNews_cat('{$oTempOptions.title}', {$oTempOptions.ID}); return false;"><img src="{$IMG_URL}edit.png" title="{#LN_NEWS_EDIT#}" alt="{#LN_NEWS_EDIT#}"/></a>
			<a style="cursor: pointer;" onclick="delNews_cat({$oTempOptions.ID}); return false;"><img src="{$IMG_URL}delete.png" title="{#LN_OPTION_DELETE#}" alt="{#LN_OPTION_DELETE#}"/></a>		

			</td>
	<tr>
{/foreach}

</table>