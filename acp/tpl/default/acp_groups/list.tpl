
<table>
	<colgroup>
		<col style="width:auto;" />
		<col style="width:200px;" />
	</colgroup>
	<tr>
		<th>
			{#groups_name#}
		</th>
		<th>
			{#groups_action#}
		</th>
	</tr>
{foreach item=oGroup from=$aGroups}
	<tr>
		<td>
			{$oGroup->getName()}
		</td>
		<td>
			<a style="cursor: pointer;" onclick="editGroup('{$oGroup->getName()}', {$oGroup->getID()}); return false;">
				<img src="{$IMG_URL}pencil.png" title="{#groups_edit#}" alt="{#groups_edit#}"/>
			</a>
			<a style="cursor: pointer;" onclick="accessGroup('{$oGroup->getName()}', {$oGroup->getID()}); return false;">
				<img src="{$IMG_URL}key.png" title="{#groups_access#}" alt="{#groups_access#}"/>
			</a>
			{if $oGroup->getData('default') != 1}
				<a style="cursor: pointer;" onclick="delGroup({$oGroup->getID()}); return false;">
					<img src="{$IMG_URL}cross.png" title="{#groups_delete#}" alt="{#groups_delete#}"/>
				</a>
			{/if}
		</td>
	<tr>
{/foreach}

</table>