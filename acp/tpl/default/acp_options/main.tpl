{include file=$HEADER}
<div id="options">
    <h2>{#LN_OPTIONS_OPTIONS#}</h2>
    <table>
        <tr>
            <th width="20%">{#LN_OPTIONS_PACKAGE#}</th>
            <th width="20%">{#LN_OPTIONS_KEY#}</th>
            <th width="20%">{#LN_OPTIONS_VALUE#}</th>
            <th width="20%">{#LN_OPTIONS_DEFAULT#}</th>
            <th width="20%">{#LN_OPTIONS_ACTIONS#}</th>
        </tr>
        {foreach item=option from=$options}
        <tr>
            <td>{$option.package}</td>
            <td>{$option.key}</td>
            <td class="optionValue">{$option.value}</td>
            <td>{$option.default}</td>
            <td>
                <a href="?package=acp_options&action=edit&optionID={$option.optionID}" class="optionsEditOption" optionID="{$option.optionID}"><img src="{$IMG_URL}edit.png" alt="{#LN_OPTIONS_EDIT#}" /></a>
                <a href="" class="optionsSaveOption" optionID="{$option.optionID}" style="display:none;"><img src="{$IMG_URL}save.png" alt="{#LN_OPTIONS_SAVE#}" /></a>
            </td>
        </tr>
        {/foreach}
    </table>
</div>
<script type="text/javascript">
LN_default_will_be_used = "{#LN_OPTIONS_DEFAULT_WILL_BE_USED#}";
</script>
{include file=$FOOTER}
