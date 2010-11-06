{include file=$HEADER}
{#deleteSureText#}<br>
<a href="index.php?package=acp_projects&action=deleteRelease&releaseID={$releaseID}&projectID={$projectID}"><button>{#yes#}</button></a>
<a href="index.php?package=acp_projects&action=editProject&projectID={$projectID}"><button>{#no#}</button></a>
{include file=$FOOTER}