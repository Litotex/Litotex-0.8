{include file=$HEADER}
<form action="index.php?package=acp_projects&action=editProjectSave&projectID={$projectID}" method="POST">
    <table>
        <tr>
            <td width="100">{#projectName#}</td>
            <td><input type="text" name="name" value="{$projectName}" /></td>
        </tr>
        <tr>
            <td>{#projectOwner#}</td>
            <td><input type="text" name="owner" value="{$projectOwner}" /></td>
        </tr>
        <tr>
            <td>{#projectDescription#}</td>
            <td><textarea name="description" rows="10" cols="40">{$projectDescription}</textarea></td>
        </tr>
    </table>
    <input type="submit" value="{#save#}" />
</form>
<br>
<table>
    <tr>
        <th>{#releaseVersion#}</th>
        <th>{#releasePlatform#}</th>
        <th>{#releaseUploader#}</th>
        <th>{#releaseDate#}</th>
        <th>{#releaseDownloads#}</th>
        <th>{#actions#}</th>
    </tr>
    {foreach from=$releases item=val}
    <tr>
        <td>{$val.version}</td>
        <td>{$val.platform}</td>
        <td>{$val.uploader}</td>
        <td>{$val.time}</td>
        <td>{$val.downloads}</td>
        <td><a href="index.php?package=acp_projects&action=deleteReleaseNotSure&releaseID={$val.id}&projectID={$projectID}"><img src="tpl/default/acp_projects/img/delete_big.png" height="16px" style="margin-bottom: -3px;" /> {#delete#}</a>
    </tr>
    {/foreach}
</table>
<a href="index.php?package=acp_projects&action=uploadRelease&projectID={$projectID}"><img src="tpl/default/acp_projects/img/add_big.png" height="16px" style="margin-bottom: -3px;" /> {#releaseUpload#}</a><br>
{include file=$FOOTER}