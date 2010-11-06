{include file=$HEADER}
<form action="index.php?package=acp_projects&action=createProjectSave" method="POST">
    <table>
        <tr>
            <td width="100">{#projectName#}</td>
            <td><input type="text" name="name" /></td>
        </tr>
        <tr>
            <td>{#projectOwner#}</td>
            <td><input type="text" name="owner" /></td>
        </tr>
        <tr>
            <td>{#projectDescription#}</td>
            <td><textarea name="description" rows="10" cols="40"></textarea></td>
        </tr>
    </table>
    <input type="submit" value="{#save#}" />
</form>
{include file=$FOOTER}