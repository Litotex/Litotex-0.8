{include file=$HEADER}
<h2><img src="tpl/default/acp_projects/img/info_big.png" style="margin-bottom: -9px;" /> {#projects#}</h2>
<div style="padding-left: 38px;">
    <span style="width: 200px;">{#projectCount#}</span>
    <b><span>{$projectCount}</span></b>
    <span style="width: 200px;">{#releaseCount#}</span>
    <b><span>{$releaseCount}</span></b>
</div>
<br>

<table>
    <tr>
        <th>{#projectID#}</th>
        <th>{#projectName#}</th>
        <th>{#projectOwner#}</th>
        <th>{#projectDownloads#}</th>
        <th>{#actions#}</th>
    </tr>
    {foreach from=$projects item=val}
    <tr>
        <td>{$val.id}</td>
        <td>{$val.name}</td>
        <td>{$val.owner}</td>
        <td>{$val.downloads}</td>
        <td>
            <!--<a href="index.php?package=acp_projects&action=uploadRelease&projectID={$val.id}"><img src="tpl/default/acp_projects/img/add_big.png" height="16px" style="margin-bottom: -3px;" /> {#releaseUpload#}</a><br>-->
            <a href="index.php?package=acp_projects&action=editProject&projectID={$val.id}"><img src="tpl/default/acp_projects/img/edit_big.png" height="16px" style="margin-bottom: -3px;" /> {#edit#}</a> 
            <a href="index.php?package=acp_projects&action=deleteProjectNotSure&projectID={$val.id}"><img src="tpl/default/acp_projects/img/delete_big.png" height="16px" style="margin-bottom: -3px;" /> {#delete#}</a>
        </td>
    </tr>
    {/foreach}
</table>
<br>
<a href="index.php?package=acp_projects&action=createProject"><img src="tpl/default/acp_projects/img/add_big.png" height="16px" style="margin-bottom: -3px;" /> {#createProject#}</a>
{include file=$FOOTER}