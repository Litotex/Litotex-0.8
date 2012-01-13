{include file=$HEADER}
<h2>{$newsItem->getTitle()}</h2>
<div class="inhalt_box1">
<p>{$newsItem->getText()}</p>
<p align="right">{#LN_NEWS_FROM_AUTHOR#} {$newsItem->getWriterName()} {#LN_NEWS_FROM_AUTHOR_DATE#} {$newsItem->getFormatedDate()}</p>
<h2>{#LN_NEWS_COMMENT_TITLE#}</h2>
<ul class="NewsCommentList">
	{foreach from=$comments item=comment}
		<li class="NewsComment">
			<div class="author">
				<div class="avatar">
					<img alt='news_anonym.png' src='{$comment->getImageURL()}' height='60' width='60' />			
				</div>
				<div class="name">
					{$comment->getWriterName()}
				</div>
			</div>				
			<div class="messageBox">
				<div class="date">{$comment->getFormatedDate()}</div>
				<div class="content">			
						<p>{$comment->getTitle()}</p>
						<p>{$comment->getText()}</p>
				</div>			
			</div>
		</li>
	{/foreach}
</ul>

</div>
{include file=$FOOTER}