
<table>
	<colgroup>
		<col style="width:500px;" />
		<col style="width:200px;" />
		<col style="width:200px;" />
		<col style="width:100px;" />
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
			{#LN_NEWS_TITEL_4#}
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
			{$oTempOptions.date}
		</td>
		
		<td>
			
			{if $oTempOptions.allow_comments == 1}
				<a style="cursor: pointer;" onclick="set_nocomments({$oTempOptions.ID}); return false;">
					<img src="{$IMG_URL}checked.png" title="{#LN_OPTION_COMMENTS#}" alt="{#LN_OPTION_COMMENTS#}"/>
				</a>
			{else}
				<a style="cursor: pointer;" onclick="set_comments({$oTempOptions.ID}); return false;">
					<img src="{$IMG_URL}stop.png" title="{#LN_OPTION_NOCOMMENTS#}" alt="{#LN_OPTION_NOCOMMENTS#}"/>
				</a>
			{/if}
			{$oTempOptions.commentNum}
		</td>		

		<td>
			<a style="cursor: pointer;" onclick="editNews('{$oTempOptions.title}', {$oTempOptions.ID}); return false;"><img src="{$IMG_URL}edit.png" title="{#LN_NEWS_EDIT#}" alt="{#LN_NEWS_EDIT#}"/></a>
			{if $oTempOptions.active == 1}
				<a style="cursor: pointer;" onclick="set_deaktive({$oTempOptions.ID}); return false;">
					<img src="{$IMG_URL}stop.png" title="{#LN_OPTION_DEAKTVIE#}" alt="{#LN_OPTION_DEAKTVIE#}"/>
				</a>
			{else}
				<a style="cursor: pointer;" onclick="set_aktive({$oTempOptions.ID}); return false;">
					<img src="{$IMG_URL}checked.png" title="{#LN_OPTION_AKTVIE#}" alt="{#LN_OPTION_AKTVIE#}"/>
				</a>
			{/if}
			<a style="cursor: pointer;" onclick="delNews({$oTempOptions.ID}); return false;"><img src="{$IMG_URL}delete.png" title="{#LN_OPTION_DELETE#}" alt="{#LN_OPTION_DELETE#}"/></a>		

			</td>
	<tr>
{/foreach}

</table>