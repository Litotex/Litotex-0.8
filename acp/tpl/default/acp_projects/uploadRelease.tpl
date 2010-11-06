{include file=$HEADER}
<form action="index.php?package=acp_projects&action=uploadReleaseSave&projectID={$projectID}" method="POST" enctype="multipart/form-data">
    <table>
        <tr>
            <td width="100">{#releaseVersion#}</td>
            <td><input type="text" name="version" /></td>
        </tr>
        <tr>
            <td>{#releasePlatform#}</td>
            <td><input type="text" name="platform" /></td>
        </tr>
        <tr>
            <td>{#releaseChangelog#}</td>
            <td><textarea rows="10" cols="40" name="changelog"></textarea></td>
        </tr>
        <tr>
            <td>{#releasePackage#}</td>
            <td><input type="file" name="package" /><td>
        </tr>
    </table>
<input type="submit" value="{#save#}" />
</form>
{include file=$FOOTER}