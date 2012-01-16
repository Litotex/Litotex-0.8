{include file=$HEADER}
<h2>{$newsItem->getTitle()}</h2>
<div class="inhalt_box1">
<p>{$newsItem->getText()}</p>
<p align="right">{#LN_NEWS_FROM_AUTHOR#} {$newsItem->getAuthorName()} {#LN_NEWS_FROM_AUTHOR_DATE#} {$newsItem->getFormatedDate()}</p>
<h2>{#LN_NEWS_COMMENT_TITLE#}</h2>
<ul class="NewsCommentList">
	{foreach from=$comments item=comment}
		<li class="NewsComment">
			<div class="author">
				<div class="avatar">
					<img alt='news_anonym.png' src='{$comment->getImageURL()}' height='60' width='60' />			
				</div>
				<div class="name">
					{$comment->getAuthorName()}
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
{if $comment_guest == 1}

	<textarea id="author" style="width: 50%; height: 20px;" name="author"></textarea>
	<label for="author"><small>{#LN_NEWS_COMMENT_AUTHOR_NAME#}</small></label>

	<textarea id="author_mail" style="width: 50%; height: 20px;" name="author_mail"></textarea>
	<label for="author_mail"><small>{#LN_NEWS_COMMENT_AUTHOR_MAIL#}</small></label>

	<textarea id="author_web" style="width: 50%; height: 20px;" name="author_web"></textarea>
	<label for="author_web"><small>{#LN_NEWS_COMMENT_AUTHOR_WEB#}</small></label>
{/if}

<textarea id="kommentar" class="ui-autocomplete-input" style="width:95%; height: 120px;" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>

<input id="button" type="submit" value="{#LN_NEWS_COMMENT_SEND#}" /></p>

{include file=$FOOTER}