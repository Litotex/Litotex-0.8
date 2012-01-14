{include file=$HEADER}
<div id="options">
    <h2>{#LN_OPTIONS_OPTION_EDIT#}</h2>
    <form action="?package=acp_options&action=editSubmit" method="POST">
        <input type="hidden" name="optionID" value="{$option.optionID}" />
        <p>
            <label>{#LN_OPTIONS_PACKAGE#}</label>
            <span>{$option.package}</span>
        </p>
        <p>
            <label>{#LN_OPTIONS_KEY#}</label>
            <span>{$option.key}</span>
        </p>
        <p>
            <label for="optionValue">{#LN_OPTIONS_VALUE#}</label>
            <span><input id="optionValue" type="text" name="value" value="{$option.value}" placeholder="{#LN_OPTIONS_DEFAULT_WILL_BE_USED#}" /></span>
        </p>
        <p>
            <label for="optionDefault">{#LN_OPTIONS_DEFAULT#}</label>
            <span><input id="optionDefault" type="text" value="{$option.default}" disabled /></span>
        </p>
        <p>
            <input type="submit" value="{#LN_OPTIONS_SAVE#}" />
        </p>
    </form>
</div>
{include file=$FOOTER}