
<table>
	<colgroup>
		<col style="width:200px;" />
		<col style="width:200px;" />
		<col style="width:200px;" />
		<col style="width:200px;" />
		<col style="width:auto;" />
	</colgroup>
	<tr>
		<th>
			{#LN_OPTION_TITEL_1#}
		</th>
		<th>
			{#LN_OPTION_TITEL_2#}
		</th>
		<th>
			{#LN_OPTION_TITEL_3#}
		</th>
		<th>
			{#LN_OPTION_TITEL_4#}
		</th>
		<th>
			{#LN_OPTION_TITEL_5#}
		</th>		
	</tr>
{foreach from=$aOptions item=oTempOptions}
	<tr>
		<td>
			{$oTempOptions.package}
		</td>
		<td>
			{$oTempOptions.key}
		</td>
		<td>
			{$oTempOptions.value}
		</td>		
		<td>
			{$oTempOptions.default}
		</td>		

		<td>
			<a style="cursor: pointer;" onclick="editOption('{$oTempOptions.package}', {$oTempOptions.ID}); return false;">
				<img src="{$IMG_URL}edit.png" title="{#LN_OPTION_EDIT#}" alt="{#LN_OPTION_EDIT#}"/>
			</a>
			
		</td>
	<tr>
{/foreach}

</table>