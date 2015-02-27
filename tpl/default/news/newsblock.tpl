<br>
<h3>{#LN_NEWS_OVER#}</h3>

{foreach from=$news item=item}
<div class="NewsPost">
			<div class="NewsPostHeader">
				<div class="NewsPostTitleDate">{$item->getFormatedDate()}</div>
				<div class="NewsPostKat">{$item->getCategoryName()}</a></div>
				<div class="NewsPostTitle"><a href="{make_link package=news action=showComments id=$item->getID()}" title="{#LN_NEWS_PERMALINK_TITEL#}{make_link package=news action=showComments id=$item->getID()}">{$item->getTitle()}</a></div>
				
			</div>
			<div class="NewsPostContent"><p>{$item->getText()}</p></div>

			<div class="NewsPostFooter">
				{if !$item->getAllowComments() == 0}
					<span class="NewsPostComments "><a href="{make_link package=news action=showComments id=$item->getID()}" title="{#LN_NEWS_COMMENT_TITEL#}">{$item->getCommentNum()} {#LN_NEWS_COMMENT#}</a></span>
				{else}
				<span class="NewsPostComments ">{#LN_NEWS_NO_COMMENTS_ALLOWED#}</span>
				{/if}
				
				
				
				</a>
			</div>
		</div>
{/foreach}
