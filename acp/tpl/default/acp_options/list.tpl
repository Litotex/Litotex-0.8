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
            {#LN_OPTION_packageName#}
        </th>
        <th>
            {#LN_OPTION_key#}
        </th>
        <th>
            {#LN_OPTION_value#}
        </th>
        <th>
            {#LN_OPTION_defaultValue#}
        </th>
        <th>
            {#LN_OPTION_action#}
        </th>		
    </tr>
{foreach from=$aOptions item=oTempOptions}
    <tr>
        <td>
            {$oTempOptions.packageName}
        </td>
        <td>
            {$oTempOptions.key}
        </td>
        <td>
            {$oTempOptions.value}
        </td>		
        <td>
            {$oTempOptions.defaultValue}
        </td>		
        <td>
            <a style="cursor: pointer;" onclick="editOption('{$oTempOptions.package}', {$oTempOptions.ID}); return false;">
                <img src="{$IMG_URL}edit.png" title="{#LN_OPTION_EDIT#}" alt="{#LN_OPTION_EDIT#}"/>
            </a>
        </td>
    <tr>
{/foreach}
</table>