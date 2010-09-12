{include file=$HEADER}
<h2>News - Kommentare zu {$newsItem->getTitle()}</h2>
<div class="inhalt_box1">

<table width="100%">
<tr>
<td>{$newsItem->getTitle()}</td>
<td>{$newsItem->getFormatedDate()}</td>
</tr>
</table>
<p>{$newsItem->getText()}</p>
<p>{$newsItem->getCommentNum()} <a href="{make_link package=news action=showComments id=$newsItem->getID()}">Kommentare</a></p>
<p align="right">By {$newsItem->getWriterName()}</p>
<h2>Kommentare</h2>
{foreach from=$comments item=comment}
<table width="100%">
<tr>
<td>{$comment->getTitle()}</td>
<td>{$comment->getFormatedDate()}</td>
</tr>
</table>
<p>{$comment->getText()}</p>
<p align="right">By {$comment->getWriterName()}</p>
{/foreach}
</div>
{include file=$FOOTER}