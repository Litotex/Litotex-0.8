{include file=$HEADER}
<h2>News - Kommentare zu {$newsItem->getTitle()}</h2>
<div class="inhalt_box1">


<p>{$newsItem->getText()}</p>
<p align="right">By {$newsItem->getWriterName()}</p>
<h2>Kommentare</h2>
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