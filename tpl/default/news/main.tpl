{include file=$HEADER}
<h2>{#LN_NEWS_OVER#}</h2>
<p>Seite x von xx</p>
          <div class="inhalt_box1">
<table width="100%"><tr>
<td><b>Kategorien</td>
            {foreach from=$categories item=category}
            <td><a href="{make_link package=news action=showCategory id=$category->getID()}">{$category}</a></td>
{/foreach}
</tr></table>
{foreach from=$news item=newsItem}
<table width="100%">
<tr>
<td>{$newsItem->getTitle()}</td>
<td>{$newsItem->getFormatedDate()}</td>
</tr>
</table>
<p>{$newsItem->getText()}</p>
<p>{$newsItem->getCommentNum()} <a href="{make_link package=news action=showComments id=$newsItem->getID()}">Kommentare</a></p>
<p align="right">By {$newsItem->getAuthorName()}</p>
{/foreach}
         </div>
{include file=$FOOTER}